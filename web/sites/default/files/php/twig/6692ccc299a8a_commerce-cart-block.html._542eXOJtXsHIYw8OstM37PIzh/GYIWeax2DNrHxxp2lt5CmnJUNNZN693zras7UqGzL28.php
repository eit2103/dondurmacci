<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/contrib/commerce/modules/cart/templates/commerce-cart-block.html.twig */
class __TwigTemplate_98ccf63f3269eefa05da0d221969dc57 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 19
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 19, $this->source), "html", null, true);
        echo ">
  <div class=\"cart-block--summary\">
    <a class=\"cart-block--link__expand\" href=\"";
        // line 21
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 21, $this->source), "html", null, true);
        echo "\">
      <span class=\"cart-block--summary__icon\">";
        // line 22
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 22, $this->source), "html", null, true);
        echo "</span>
      <span class=\"cart-block--summary__count\">";
        // line 23
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["count_text"] ?? null), 23, $this->source), "html", null, true);
        echo "</span>
    </a>
  </div>
  ";
        // line 26
        if (($context["content"] ?? null)) {
            // line 27
            echo "  <div class=\"cart-block--contents\">
    <div class=\"cart-block--contents__inner\">
      <div class=\"cart-block--contents__items\">
        ";
            // line 30
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 30, $this->source), "html", null, true);
            echo "
      </div>
      <div class=\"cart-block--contents__links\">
        ";
            // line 33
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["links"] ?? null), 33, $this->source), "html", null, true);
            echo "
      </div>
    </div>
  </div>
  ";
        }
        // line 38
        echo "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "url", "icon", "count_text", "content", "links"]);    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "modules/contrib/commerce/modules/cart/templates/commerce-cart-block.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  80 => 38,  72 => 33,  66 => 30,  61 => 27,  59 => 26,  53 => 23,  49 => 22,  45 => 21,  39 => 19,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/contrib/commerce/modules/cart/templates/commerce-cart-block.html.twig", "/home/cej5vsm359bz/public_html/web/modules/contrib/commerce/modules/cart/templates/commerce-cart-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 26);
        static $filters = array("escape" => 19);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
