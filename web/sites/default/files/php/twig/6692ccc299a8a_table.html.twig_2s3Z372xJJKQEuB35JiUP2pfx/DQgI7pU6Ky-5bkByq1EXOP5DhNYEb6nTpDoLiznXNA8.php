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

/* themes/contrib/bootstrap5/templates/form/table.html.twig */
class __TwigTemplate_a58efd53fb94fc5ebd6db4336d0fce30 extends Template
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
        // line 43
        echo "
<table";
        // line 44
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["table"], "method", false, false, true, 44), 44, $this->source), "html", null, true);
        echo ">
  
  ";
        // line 46
        if (($context["caption"] ?? null)) {
            // line 47
            echo "    <caption>";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["caption"] ?? null), 47, $this->source), "html", null, true);
            echo "</caption>
  ";
        }
        // line 49
        echo "
  ";
        // line 50
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["colgroups"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["colgroup"]) {
            // line 51
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, $context["colgroup"], "cols", [], "any", false, false, true, 51)) {
                // line 52
                echo "      <colgroup";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["colgroup"], "attributes", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
                echo ">
        ";
                // line 53
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["colgroup"], "cols", [], "any", false, false, true, 53));
                foreach ($context['_seq'] as $context["_key"] => $context["col"]) {
                    // line 54
                    echo "          <col";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["col"], "attributes", [], "any", false, false, true, 54), 54, $this->source), "html", null, true);
                    echo " />
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['col'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 56
                echo "      </colgroup>
    ";
            } else {
                // line 58
                echo "      <colgroup";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["colgroup"], "attributes", [], "any", false, false, true, 58), 58, $this->source), "html", null, true);
                echo " />
    ";
            }
            // line 60
            echo "  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['colgroup'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "
  ";
        // line 62
        if (($context["header"] ?? null)) {
            // line 63
            echo "    <thead>
      <tr>
        ";
            // line 65
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["header"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["cell"]) {
                // line 66
                echo "          ";
                // line 67
                $context["cell_classes"] = [((twig_get_attribute($this->env, $this->source,                 // line 68
$context["cell"], "active_table_sort", [], "any", false, false, true, 68)) ? ("is-active") : (""))];
                // line 71
                echo "          <";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["cell"], "attributes", [], "any", false, false, true, 71), "addClass", [($context["cell_classes"] ?? null)], "method", false, false, true, 71), 71, $this->source), "html", null, true);
                echo ">";
                // line 72
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "content", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
                // line 73
                echo "</";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
                echo ">
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cell'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 75
            echo "      </tr>
    </thead>
  ";
        }
        // line 78
        echo "
  ";
        // line 79
        if (($context["rows"] ?? null)) {
            // line 80
            echo "    <tbody>
      ";
            // line 81
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 82
                echo "        ";
                // line 83
                $context["row_classes"] = [(( !                // line 84
($context["no_striping"] ?? null)) ? (twig_cycle(["odd", "even"], $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 84), 84, $this->source))) : (""))];
                // line 87
                echo "        <tr";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 87), "addClass", [($context["row_classes"] ?? null)], "method", false, false, true, 87), 87, $this->source), "html", null, true);
                echo ">
          ";
                // line 88
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["row"], "cells", [], "any", false, false, true, 88));
                foreach ($context['_seq'] as $context["_key"] => $context["cell"]) {
                    // line 89
                    echo "            <";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 89), 89, $this->source), "html", null, true);
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "attributes", [], "any", false, false, true, 89), 89, $this->source), "html", null, true);
                    echo ">";
                    // line 90
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "content", [], "any", false, false, true, 90), 90, $this->source), "html", null, true);
                    // line 91
                    echo "</";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 91), 91, $this->source), "html", null, true);
                    echo ">
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cell'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 93
                echo "        </tr>
      ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 95
            echo "    </tbody>
  ";
        } elseif (        // line 96
($context["empty"] ?? null)) {
            // line 97
            echo "    <tbody>
      <tr class=\"odd\">
        <td colspan=\"";
            // line 99
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_columns"] ?? null), 99, $this->source), "html", null, true);
            echo "\" class=\"empty message\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty"] ?? null), 99, $this->source), "html", null, true);
            echo "</td>
      </tr>
    </tbody>
  ";
        }
        // line 103
        echo "  ";
        if (($context["footer"] ?? null)) {
            // line 104
            echo "    <tfoot>
      ";
            // line 105
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["footer"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 106
                echo "        <tr";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 106), 106, $this->source), "html", null, true);
                echo ">
          ";
                // line 107
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["row"], "cells", [], "any", false, false, true, 107));
                foreach ($context['_seq'] as $context["_key"] => $context["cell"]) {
                    // line 108
                    echo "            <";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 108), 108, $this->source), "html", null, true);
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "attributes", [], "any", false, false, true, 108), 108, $this->source), "html", null, true);
                    echo ">";
                    // line 109
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "content", [], "any", false, false, true, 109), 109, $this->source), "html", null, true);
                    // line 110
                    echo "</";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["cell"], "tag", [], "any", false, false, true, 110), 110, $this->source), "html", null, true);
                    echo ">
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cell'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 112
                echo "        </tr>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 114
            echo "    </tfoot>
  ";
        }
        // line 116
        echo "</table>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["attributes", "caption", "colgroups", "header", "rows", "no_striping", "loop", "empty", "header_columns", "footer"]);    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "themes/contrib/bootstrap5/templates/form/table.html.twig";
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
        return array (  272 => 116,  268 => 114,  261 => 112,  252 => 110,  250 => 109,  245 => 108,  241 => 107,  236 => 106,  232 => 105,  229 => 104,  226 => 103,  217 => 99,  213 => 97,  211 => 96,  208 => 95,  193 => 93,  184 => 91,  182 => 90,  177 => 89,  173 => 88,  168 => 87,  166 => 84,  165 => 83,  163 => 82,  146 => 81,  143 => 80,  141 => 79,  138 => 78,  133 => 75,  124 => 73,  122 => 72,  117 => 71,  115 => 68,  114 => 67,  112 => 66,  108 => 65,  104 => 63,  102 => 62,  99 => 61,  93 => 60,  87 => 58,  83 => 56,  74 => 54,  70 => 53,  65 => 52,  62 => 51,  58 => 50,  55 => 49,  49 => 47,  47 => 46,  42 => 44,  39 => 43,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/bootstrap5/templates/form/table.html.twig", "/home/cej5vsm359bz/public_html/web/themes/contrib/bootstrap5/templates/form/table.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 46, "for" => 50, "set" => 67);
        static $filters = array("escape" => 44);
        static $functions = array("cycle" => 84);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for', 'set'],
                ['escape'],
                ['cycle']
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
