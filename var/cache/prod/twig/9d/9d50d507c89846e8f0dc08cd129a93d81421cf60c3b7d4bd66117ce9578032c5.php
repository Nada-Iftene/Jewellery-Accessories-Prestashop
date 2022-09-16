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

/* @PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig */
class __TwigTemplate_efff161ef81a8c7b8a090f408e227339d33ccb2cbab9c5a7a96c2e4d29e89f21 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'image' => [$this, 'block_image'],
            'description' => [$this, 'block_description'],
            'button_container' => [$this, 'block_button_container'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 26
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card_container.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card_container.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig", 26);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 28
    public function block_content($context, array $blocks = [])
    {
        // line 29
        echo "  <div class=\"card theme-card theme-catalog-card\">

    <div class=\"text-center catalog-image\">
      ";
        // line 32
        $this->displayBlock('image', $context, $blocks);
        // line 34
        echo "    </div>

    <div class=\"text-center catalog-description\">
      ";
        // line 37
        $this->displayBlock('description', $context, $blocks);
        // line 39
        echo "    </div>

    <div class=\"actions-container active\">
      ";
        // line 42
        $this->displayBlock('button_container', $context, $blocks);
        // line 44
        echo "    </div>
  </div>
";
    }

    // line 32
    public function block_image($context, array $blocks = [])
    {
        // line 33
        echo "      ";
    }

    // line 37
    public function block_description($context, array $blocks = [])
    {
        // line 38
        echo "      ";
    }

    // line 42
    public function block_button_container($context, array $blocks = [])
    {
        // line 43
        echo "      ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 43,  86 => 42,  82 => 38,  79 => 37,  75 => 33,  72 => 32,  66 => 44,  64 => 42,  59 => 39,  57 => 37,  52 => 34,  50 => 32,  45 => 29,  42 => 28,  32 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\Blocks\\Partials\\theme_catalog_card.html.twig");
    }
}
