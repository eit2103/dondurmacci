<?php

namespace Drupal\iyzipay\Plugin\Commerce\PaymentGateway;

use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_payment\CreditCard;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Drupal\commerce_payment\Exception\HardDeclineException;
use Drupal\commerce_payment\Exception\PaymentGatewayException;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OnsitePaymentGatewayBase;
use Drupal\commerce_price\Price;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\InstallmentInfo;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\Refund;
use Iyzipay\Model\ThreedsInitialize;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateRefundRequest;
use Iyzipay\Request\RetrieveInstallmentInfoRequest;
use Iyzipay\Request;

/**
 * Provides the On-site payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "iyzipay_onsite",
 *   label = "Iyzipay (On-site)",
 *   display_label = "Iyzipay",
 *   forms = {
 *     "add-payment-method" = "Drupal\iyzipay\PluginForm\IyzipayOnsite\IyzipayAddForm",
 *   },
 *   payment_method_types = {"full_credit_card"},
 *   credit_card_types = {
 *     "amex", "mastercard", "visa", "troy", "visa electron",
 *   },
 *   js_library = "iyzipay/card",
 * )
 */
class IyzipayOnsite extends OnsitePaymentGatewayBase implements IyzipayOnsiteInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'api_key' => '',
      'secret_key' => '',
      'api_url' => '',
      'currency_converter_api_key' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#default_value' => $this->configuration['api_key'],
      '#required' => TRUE,
    ];

    $form['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret key'),
      '#default_value' => $this->configuration['secret_key'],
      '#required' => TRUE,
    ];

    $form['api_url'] = [
      '#type' => 'url',
      '#title' => $this->t('API URL'),
      '#default_value' => $this->configuration['api_url'],
      '#required' => TRUE,
    ];

    $form['currency_converter_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('https://exchangerates.io API key'),
      '#default_value' => $this->configuration['currency_converter_api_key'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $this->configuration['api_key'] = $values['api_key'];
      $this->configuration['secret_key'] = $values['secret_key'];
      $this->configuration['api_url'] = $values['api_url'];
      $this->configuration['currency_converter_api_key'] = $values['currency_converter_api_key'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createPayment(PaymentInterface $payment, $capture = TRUE) {
    $this->assertPaymentState($payment, ['new']);
    $payment_method = $payment->getPaymentMethod();
    $this->assertPaymentMethod($payment_method);

    $response = $this->makePayment($payment);
    $message = $response->getErrorMessage();

    if ($response->getStatus() != 'success') {
      \Drupal::logger('iyzipay')->error($message);
      \Drupal::messenger()->addMessage($this->t('The payment has failed'), 'error');
      throw new PaymentGatewayException($message);
    }

    if (!empty($response->getErrorCode())) {
      \Drupal::messenger()->addMessage($this->t('The payment has failed'), 'error');
      throw new HardDeclineException($message);
    }

    $expires = 300;
    $next_state = 'authorization';
    if ($response->getStatus() == "success" && $capture) {
      $next_state = 'completed';
    }

    $payment->setExpiresTime($expires);
    $payment->setState($next_state);
    $remote_id = $response->getPaymentId();
    $payment->setRemoteId($remote_id);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function capturePayment(PaymentInterface $payment, Price $amount = NULL) {
    $this->assertPaymentState($payment, ['authorization']);
    // If not specified, capture the entire amount.
    $amount = $amount ?: $payment->getAmount();

    $response = $this->makePayment($payment);
    if ($response->getStatus() == "success") {
      $payment->setState('completed');
      $payment->setAmount($amount);
      $payment->save();
    }
  }

  /**
   * Prepare the request and send it to the gateway.
   */
  public function makePayment(PaymentInterface $payment, $capture = TRUE) {
    $order = $payment->getOrder();
    $payment_method = $payment->getPaymentMethod();

    $billing_address = $payment_method->getBillingProfile()->get('address')->first();
    $owner = $payment_method->getOwner();
    $buyer_id = sprintf('%05d', $owner->id());

    $options = new Options();
    $options->setApiKey($this->configuration['api_key']);
    $options->setSecretKey($this->configuration['secret_key']);
    $options->setBaseUrl($this->configuration['api_url']);

    $encryption_service = \Drupal::service('encryption');
    // See if the card is turkish or not.
    $binRequest = new RetrieveInstallmentInfoRequest();
    $binRequest->setLocale(Locale::TR);
    $binRequest->setConversationId($order->id());
    $binRequest->setBinNumber(substr($encryption_service->decrypt($payment_method->encrypted_full_card_number->value),0,6));
    $binRequest->setPrice($payment->getAmount()->getNumber());
    $installmentInfo = InstallmentInfo::retrieve($binRequest, $options);

    $price = $payment->getAmount()->getNumber();
    $currency_code = $payment->getAmount()->getCurrencyCode();
    $convert_to_try = FALSE;
    // If it's a turkish card, we have to convert the currency to TRY.
    if ($installmentInfo->getInstallmentDetails()[0]->getBankName() !== NULL && $currency_code !== "TRY") {
      $client = new Client();
      try {
        $res = $client->request('GET', 'http://api.exchangeratesapi.io/v1/latest?access_key=' . $this->configuration['currency_converter_api_key']);
        $raw_rate = json_decode($res->getBody());
        $try_currency_rate = $raw_rate->rates->TRY/$raw_rate->rates->USD;
        $currency_code = "TRY";
        $price = $try_currency_rate * $payment->getAmount()->getNumber();
        $convert_to_try = TRUE;
      }
      catch (RequestException $e) {
        return($this->t('Error'));
      }
    }

    $request = new CreatePaymentRequest();
    $request->setLocale(Locale::EN);
    $request->setConversationId($order->id());
    // $request->setPrice($price);
    $request->setPaidPrice($price);
    $request->setCurrency($currency_code);
    $request->setInstallment(1);
    $request->setBasketId($order->id());
    $request->setPaymentChannel(PaymentChannel::WEB);
    $request->setPaymentGroup(PaymentGroup::PRODUCT);

    $paymentCard = new PaymentCard();
    $paymentCard->setCardHolderName($encryption_service->decrypt($payment_method->encrypted_full_holder_name->value));
    $paymentCard->setCardNumber($encryption_service->decrypt($payment_method->encrypted_full_card_number->value));
    $paymentCard->setExpireMonth($encryption_service->decrypt($payment_method->encrypted_full_card_exp_month->value));
    $paymentCard->setExpireYear($encryption_service->decrypt($payment_method->encrypted_full_card_exp_year->value));
    $paymentCard->setCvc($encryption_service->decrypt($payment_method->encrypted_full_card_cvv->value));
    $paymentCard->setRegisterCard(0);
    $request->setPaymentCard($paymentCard);

    $buyer = new Buyer();
    $buyer->setId($owner->id());
    $buyer->setName($billing_address->getGivenName());
    $buyer->setSurname($billing_address->getFamilyName());
    $buyer->setEmail($owner->getEmail() ?? $order->getEmail());
    $buyer->setIdentityNumber($buyer_id);
    $buyer->setRegistrationAddress(substr($billing_address->getAddressLine1() . ' ' . $billing_address->getAddressLine2(), 0, 60));
    $buyer->setIp(\Drupal::request()->getClientIp());
    $buyer->setCity($billing_address->getLocality());
    $buyer->setCountry($billing_address->getCountryCode());
    $buyer->setZipCode($billing_address->getPostalCode());
    $request->setBuyer($buyer);

    $billingAddress = new Address();
    $billingAddress->setContactName($billing_address->getGivenName() . " " . $billing_address->getFamilyName());
    $billingAddress->setCity($billing_address->getLocality());
    $billingAddress->setCountry($billing_address->getCountryCode());
    $billingAddress->setAddress(substr($billing_address->getAddressLine1() . ' ' . $billing_address->getAddressLine2(), 0, 60));
    $billingAddress->setZipCode($billing_address->getPostalCode());
    $request->setBillingAddress($billingAddress);
    $request->setShippingAddress($billingAddress);

    $basketItems = [];
    $total_product_price  = 0;

    foreach ($order->getItems() as $order_item) {
      $product_variation = $order_item->getPurchasedEntity();
      $title = $product_variation->getTitle();
      $price = $order_item->getTotalPrice()->getNumber();

      if ($convert_to_try) {
        $price = $try_currency_rate * $price;
      }

      $sku = $product_variation->getSku();
      $basket_item = new BasketItem();
      $basket_item->setId($sku);
      $basket_item->setName($title);
      $basket_item->setCategory1("Course");
      $basket_item->setItemType(BasketItemType::VIRTUAL);
      $basket_item->setPrice($price);
      $basketItems[] = $basket_item;

      $total_product_price += $price;
    }

    if (count($basketItems) > 0) {
      $request->setBasketItems($basketItems);
    }

    $request->setPrice($total_product_price);

    // See if it's forcing 3Dsecure. Well I tried this,
    // it seems to be working mostly
    // but it does not create the order for some reason.
    if ($installmentInfo->getInstallmentDetails()[0]->getForce3ds() || true) {
      $host = \Drupal::request()->getSchemeAndHttpHost();
      $request->setCallbackUrl($host . "/iyzipay/3d_landing_page");
      $threedsInitialize = ThreedsInitialize::create($request, $options);
      $d_html = $threedsInitialize->gethtmlContent();

      $expires = 300;
      $payment->setExpiresTime($expires);
      $payment->setState('pending');
      $payment->save();

      print $d_html;exit;
    }

    $result = Payment::create($request, $options);

    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function voidPayment(PaymentInterface $payment) {
    $this->assertPaymentState($payment, ['authorization']);
    $remote_id = $payment->getRemoteId();
    $price = $payment->getAmount()->getNumber();
    $currency_code = $payment->getAmount()->getcurrencyCode();

    $options = new Options();
    $options->setApiKey($this->configuration['api_key']);
    $options->setSecretKey($this->configuration['secret_key']);
    $options->setBaseUrl($this->configuration['api_url']);

    $request = new CreateRefundRequest();
    $request->setPaymentTransactionId($remote_id);
    $request->setPrice($price);
    $request->setCurrency($currency_code);
    $refund = Refund::create($request, $options);

    if ($refund->getStatus() == "success") {
      $payment->setState('authorization_voided');
      $payment->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function refundPayment(PaymentInterface $payment, Price $amount = NULL) {
    $this->assertPaymentState($payment, ['completed', 'partially_refunded']);
    // If not specified, refund the entire amount.
    $amount = $amount ?: $payment->getAmount();
    $this->assertRefundAmount($payment, $amount);

    $remote_id = $payment->getRemoteId();

    $price = $payment->getAmount()->getNumber();
    $currency_code = $payment->getAmount()->getcurrencyCode();

    $options = new Options();
    $options->setApiKey($this->configuration['api_key']);
    $options->setSecretKey($this->configuration['secret_key']);
    $options->setBaseUrl($this->configuration['api_url']);

    $request = new CreateRefundRequest();
    $request->setPaymentTransactionId($remote_id);
    $request->setPrice($price);
    $request->setCurrency($currency_code);
    $response = Refund::create($request, $options);

    if ($response->getStatus() == 'success') {
      $old_refunded_amount = $payment->getRefundedAmount();
      $new_refunded_amount = $old_refunded_amount->add($amount);
      if ($new_refunded_amount->lessThan($payment->getAmount())) {
        $payment->setState('partially_refunded');
      }
      else {
        $payment->setState('refunded');
      }
      $payment->setRefundedAmount($new_refunded_amount);
      $payment->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function createPaymentMethod(PaymentMethodInterface $payment_method, array $payment_details) {
    $required_keys = [
      // The expected keys are payment gateway specific and usually match
      // the PaymentMethodAddForm form elements. They are expected to be valid.
      'type', 'number', 'expiration',
    ];
    foreach ($required_keys as $required_key) {
      if (empty($payment_details[$required_key])) {
        throw new \InvalidArgumentException(sprintf('$payment_details must contain the %s key.', $required_key));
      }
    }

    $payment_method->encrypted_full_holder_name = $payment_details['holder_name'];
    $payment_method->encrypted_full_card_type = $payment_details['type'];
    $payment_method->encrypted_full_card_number = $payment_details['number'];
    $payment_method->encrypted_full_card_exp_month = $payment_details['expiration']['month'];
    $payment_method->encrypted_full_card_exp_year = $payment_details['expiration']['year'];
    $payment_method->encrypted_full_card_cvv = $payment_details['security_code'];

    $expires = CreditCard::calculateExpirationTimestamp($payment_details['expiration']['month'], $payment_details['expiration']['year']);
    $payment_method->setReusable(FALSE);
    $payment_method->setExpiresTime($expires);
    $payment_method->save();
  }

  /**
   * {@inheritdoc}
   */
  public function deletePaymentMethod(PaymentMethodInterface $payment_method) {
    // Delete the remote record here, throw an exception if it fails.
    // See \Drupal\commerce_payment\Exception for the available exceptions.
    // Delete the local entity.
    $payment_method->delete();
  }

  /**
   * {@inheritdoc}
   */
  public function updatePaymentMethod(PaymentMethodInterface $payment_method) {

  }

}
