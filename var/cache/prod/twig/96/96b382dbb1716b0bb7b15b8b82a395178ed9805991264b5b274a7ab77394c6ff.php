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

/* @PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/logo_card.html.twig */
class __TwigTemplate_cb19c41c56872c1c6af7f02111733d4c6cae8855c0afa106e8f763416317e792 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'logo_card' => [$this, 'block_logo_card'],
            'card_title' => [$this, 'block_card_title'],
            'card_content' => [$this, 'block_card_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 25
        echo "
";
        // line 26
        $this->displayBlock('logo_card', $context, $blocks);
    }

    public function block_logo_card($context, array $blocks = [])
    {
        // line 27
        echo "  <div class=\"card logo-card\">
    <div class=\"card-body\">
      <div class=\"logo-card-title text-left\">
        ";
        // line 30
        $this->displayBlock('card_title', $context, $blocks);
        // line 32
        echo "      </div>
      ";
        // line 33
        $this->displayBlock('card_content', $context, $blocks);
        // line 35
        echo "    </div>
  </div>
";
    }

    // line 30
    public function block_card_title($context, array $blocks = [])
    {
        // line 31
        echo "        ";
    }

    // line 33
    public function block_card_content($context, array $blocks = [])
    {
        // line 34
        echo "      ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/logo_card.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  70 => 34,  67 => 33,  63 => 31,  60 => 30,  54 => 35,  52 => 33,  49 => 32,  47 => 30,  42 => 27,  36 => 26,  33 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/logo_card.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\Blocks\\Partials\\logo_card.html.twig");
    }
}
