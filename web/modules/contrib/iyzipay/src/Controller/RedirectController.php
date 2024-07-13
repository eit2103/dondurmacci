<?php

namespace Drupal\iyzipay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Iyzipay\Model\Locale;
use Iyzipay\Model\ThreedsPayment;
use Iyzipay\Options;
use Iyzipay\Request\CreateThreedsPaymentRequest;

/**
 * This is a controller for 3D secure payment.
 */
class RedirectController extends ControllerBase {

  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityTypeManager;
  protected $messenger;

  /**
   * Dependency injection for entity type manager.
   */
  public function __construct(EntityTypeManager $entityTypeManager, MessengerInterface $messenger) {
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
  }

  /**
   * Return the services for entity query and entity type manager.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger')
    );
  }

  /**
   * Get proper return status from the 3D secure page.
   */
  public function getStatus(Request $request) {

    // Get parameters returning back from the 3D Secure interface.
    $status = $request->request->get('status');
    $paymentId = $request->request->get('paymentId');
    $conversationData = $request->request->get('conversationData');
    $conversationId = $request->request->get('conversationId');
    $mdStatus = $request->request->get('mdStatus');

    $query = $this->entityTypeManager->getStorage('commerce_payment')->getQuery();
    $query->condition('order_id', $conversationId);
    $query->condition('payment_gateway', 'iyzipay');
    $payment_ids = $query->execute();

    $return_product = 0;
    $return_order   = 0;

    $message = $this->t("Your transaction has failed");
    $order_status = "failed";
    $message_status = "error";

    if (!empty($query) && $status == "success") {
      $payments = $this->entityTypeManager
        ->getStorage('commerce_payment')
        ->loadMultiple($payment_ids);

      foreach ($payments as $payment) {
        if ($payment->state->value == 'pending') {
          if ($mdStatus == 1) {
            $request = new CreateThreedsPaymentRequest();
            $request->setLocale(Locale::TR);
            $request->setConversationId($conversationId);
            $request->setPaymentId($paymentId);
            $request->setConversationData($conversationData);

            $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
            $configs = $payment_gateway_plugin->getConfiguration();

            $options = new Options();
            $options->setApiKey($configs['api_key']);
            $options->setSecretKey($configs['secret_key']);
            $options->setBaseUrl($configs['api_url']);

            $threedsPayment = ThreedsPayment::create($request, $options);
            // By sending the latest request to the gateway,
            // we should be making the payment.
            $status_3d = $threedsPayment->getStatus();

            if ($status_3d == "success") {
              $order_status = "completed";
              $message = $this->t("Your transaction is successful!");
              $message = "";
              $message_status = "success";
              $query = $this->entityTypeManager->getStorage('commerce_payment')->getQuery();
              $query->condition('order_id', $conversationId);

              if (\Drupal::currentUser()->isAnonymous()) {
                $query->accessCheck(FALSE);
              }
              $order_ids = $query->execute();

              $orders = $this->entityTypeManager
                ->getStorage('commerce_order')
                ->loadMultiple($order_ids);

              foreach ($orders as $order) {
                $order->setOrderNumber($conversationId);
                $order->unlock();
                $order->state = "completed";
                $order->cart = 0;
                $order->setPlacedTime(time());
                $order->setCompletedTime(time());
                $order->save();
                $return_order = $conversationId;
              }
            } else {
              $error_group = $threedsPayment->getErrorGroup();
              switch ($error_group) {
                case 'NOT_SUFFICIENT_FUNDS':
                  $message = $this->t('Insufficient funds');
                  break;
                case 'STOLEN_CARD':
                  $message = $this->t('Stolen card');
                  break;
                default:
                  $message = $threedsPayment->getErrorMessage();
                  break;
              }
              $this->messenger->addError($message);
            }
          }

          $payment->setRemoteId($paymentId);
          $payment->setState($order_status);
          $payment->save();
          $this->messenger->addMessage($message, $message_status);
        }
      }
    }

    if (!$return_order) {
      return new TrustedRedirectResponse('/');
    }
    else {
      return new TrustedRedirectResponse("/checkout/".$return_order."/complete");
    }
  }

}
