<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @PrestaShop/Admin/Improve/Design/Cms/Blocks/breadcrumb.html.twig */
class __TwigTemplate_78afca1a1dc3dbf30c5480cf319a3a40eb2c886223d44ee98a6671a7d2c3278d extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 25
        echo "
";
        // line 26
        if ( !twig_test_empty($this->getAttribute(($context["cmsPageView"] ?? null), "breadcrumb_tree", []))) {
            // line 27
            echo "  <div class=\"row\">
    <div class=\"col\">
      <div class=\"card\">
        <div class=\"card-block\">
          <nav>
            <ol class=\"breadcrumb\">
              ";
            // line 33
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["cmsPageView"] ?? null), "breadcrumb_tree", []));
            foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                // line 34
                echo "                <li class=\"breadcrumb-item\">
                  ";
                // line 35
                $context["cmsPageRouteParameters"] = [];
                // line 36
                echo "
                  ";
                // line 37
                if (($this->getAttribute($this->getAttribute($context["category"], "cmsPageCategoryId", []), "value", []) != $this->getAttribute(($context["cmsPageView"] ?? null), "root_category_id", []))) {
                    // line 38
                    echo "                    ";
                    $context["cmsPageRouteParameters"] = ["id_cms_category" => $this->getAttribute($this->getAttribute($context["category"], "cmsPageCategoryId", []), "value", [])];
                    // line 39
                    echo "                  ";
                }
                // line 40
                echo "
                  <a href=\"";
                // line 41
                echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_cms_pages_index", ($context["cmsPageRouteParameters"] ?? null)), "html", null, true);
                echo "\">
                    ";
                // line 42
                echo twig_escape_filter($this->env, $this->getAttribute($context["category"], "name", []), "html", null, true);
                echo "
                  </a>
                </li>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
";
        }
        // line 53
        echo "
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Cms/Blocks/breadcrumb.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 53,  80 => 46,  70 => 42,  66 => 41,  63 => 40,  60 => 39,  57 => 38,  55 => 37,  52 => 36,  50 => 35,  47 => 34,  43 => 33,  35 => 27,  33 => 26,  30 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Cms/Blocks/breadcrumb.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Cms\\Blocks\\breadcrumb.html.twig");
    }
}
