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

/* @PrestaShop/Admin/Improve/Design/Cms/Blocks/showcase_card.html.twig */
class __TwigTemplate_7905362670676cc860d28ed5d64e62e694ba9f45d5733f0ef770933c7a0fef14 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'cms_pages_showcase_card' => [$this, 'block_cms_pages_showcase_card'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 25
        echo "
";
        // line 26
        $this->displayBlock('cms_pages_showcase_card', $context, $blocks);
    }

    public function block_cms_pages_showcase_card($context, array $blocks = [])
    {
        // line 27
        echo "  ";
        if ( !($context["showcaseCardIsClosed"] ?? null)) {
            // line 28
            echo "    <div id=\"cms-pages-showcase-card\" class=\"showcase-card card\">
      <div class=\"showcase-card__left shape-one helper-card__cms-pages-background img-rtl\">
        <img src=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("themes/new-theme/public/helper-card/content@3x.png"), "html", null, true);
            echo "\" class=\"img-fluid\">
      </div>
      <div class=\"showcase-card__right\">
        <h2>";
            // line 33
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Create meaningful content", [], "Admin.Design.Help"), "html", null, true);
            echo "</h2>
        <p>";
            // line 34
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Because it is not just selling products but also creating a universe, build pages to tell stories and catch your visitors’ interest, to turn them into loyal customers.", [], "Admin.Design.Help"), "html", null, true);
            echo "</p>
        <a class=\"btn btn-outline-secondary\" href=\"";
            // line 35
            echo twig_escape_filter($this->env, ($context["helperDocLink"] ?? null), "html", null, true);
            echo "\" target=\"_blank\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Learn more", [], "Admin.Actions"), "html", null, true);
            echo "</a>
      </div>
      <i class=\"showcase-card__close material-icons js-remove-helper-block\" data-card-name=\"";
            // line 37
            echo twig_escape_filter($this->env, ($context["cmsPageShowcaseCardName"] ?? null), "html", null, true);
            echo "\" data-close-url=\"";
            echo $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_close_showcase_card");
            echo "\">close</i>
    </div>
  ";
        }
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Cms/Blocks/showcase_card.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  68 => 37,  61 => 35,  57 => 34,  53 => 33,  47 => 30,  43 => 28,  40 => 27,  34 => 26,  31 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Cms/Blocks/showcase_card.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Cms\\Blocks\\showcase_card.html.twig");
    }
}
