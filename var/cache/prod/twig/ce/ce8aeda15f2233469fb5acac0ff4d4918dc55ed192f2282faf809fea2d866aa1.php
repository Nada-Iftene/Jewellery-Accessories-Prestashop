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

/* @PrestaShop/Admin/Improve/Design/Cms/index.html.twig */
class __TwigTemplate_bda1c3910701ee41cdd4609722e691b38e01144ed519c052f7fe86b4ff26cc2b extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'cms_page_category_breadcrumb' => [$this, 'block_cms_page_category_breadcrumb'],
            'cms_category_grid' => [$this, 'block_cms_category_grid'],
            'cms_grid' => [$this, 'block_cms_grid'],
            'javascripts' => [$this, 'block_javascripts'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 26
        return "@PrestaShop/Admin/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 28
        $context["layoutHeaderToolbarBtn"] = ["add_cms_category" => ["href" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_cms_pages_category_create", ["id_cms_category" => $this->getAttribute($this->getAttribute($this->getAttribute(        // line 30
($context["app"] ?? null), "request", []), "query", []), "get", [0 => "id_cms_category"], "method")]), "desc" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Add new page category", [], "Admin.Design.Help"), "icon" => "add_circle_outline"], "add_cms_page" => ["href" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_cms_pages_create", ["id_cms_category" => $this->getAttribute($this->getAttribute($this->getAttribute(        // line 35
($context["app"] ?? null), "request", []), "query", []), "get", [0 => "id_cms_category"], "method")]), "desc" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Add new page", [], "Admin.Design.Help"), "icon" => "add_circle_outline"]];
        // line 26
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/layout.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 26);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 42
    public function block_content($context, array $blocks = [])
    {
        // line 43
        echo "
  <div class=\"row\">
    <div class=\"col-sm\">
      ";
        // line 46
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Cms/Blocks/showcase_card.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 46)->display($context);
        // line 47
        echo "    </div>
  </div>

  ";
        // line 50
        $this->displayBlock('cms_page_category_breadcrumb', $context, $blocks);
        // line 53
        echo "
  ";
        // line 54
        $this->displayBlock('cms_category_grid', $context, $blocks);
        // line 68
        echo "
  ";
        // line 69
        $this->displayBlock('cms_grid', $context, $blocks);
    }

    // line 50
    public function block_cms_page_category_breadcrumb($context, array $blocks = [])
    {
        // line 51
        echo "    ";
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Cms/Blocks/breadcrumb.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 51)->display($context);
        // line 52
        echo "  ";
    }

    // line 54
    public function block_cms_category_grid($context, array $blocks = [])
    {
        // line 55
        echo "    <div class=\"row\">
      <div class=\"col\">
        ";
        // line 57
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 57, "1957854519")->display(twig_array_merge($context, ["grid" => ($context["cmsCategoryGrid"] ?? null)]));
        // line 65
        echo "      </div>
    </div>
  ";
    }

    // line 69
    public function block_cms_grid($context, array $blocks = [])
    {
        // line 70
        echo "    <div class=\"row\">
      <div class=\"col\">
        ";
        // line 72
        $this->loadTemplate("@PrestaShop/Admin/Common/Grid/grid_panel.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 72)->display(twig_array_merge($context, ["grid" => ($context["cmsGrid"] ?? null)]));
        // line 73
        echo "      </div>
    </div>
  ";
    }

    // line 78
    public function block_javascripts($context, array $blocks = [])
    {
        // line 79
        echo "  ";
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "
  <script src=\"";
        // line 80
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("themes/new-theme/public/cms_page.bundle.js"), "html", null, true);
        echo "\"></script>
  <script src=\"";
        // line 81
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("themes/default/js/bundle/pagination.js"), "html", null, true);
        echo "\"></script>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  129 => 81,  125 => 80,  120 => 79,  117 => 78,  111 => 73,  109 => 72,  105 => 70,  102 => 69,  96 => 65,  94 => 57,  90 => 55,  87 => 54,  83 => 52,  80 => 51,  77 => 50,  73 => 69,  70 => 68,  68 => 54,  65 => 53,  63 => 50,  58 => 47,  56 => 46,  51 => 43,  48 => 42,  43 => 26,  41 => 35,  40 => 30,  39 => 28,  33 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Cms\\index.html.twig");
    }
}


/* @PrestaShop/Admin/Improve/Design/Cms/index.html.twig */
class __TwigTemplate_bda1c3910701ee41cdd4609722e691b38e01144ed519c052f7fe86b4ff26cc2b___1957854519 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'grid_panel_footer' => [$this, 'block_grid_panel_footer'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 57
        return "@PrestaShop/Admin/Common/Grid/grid_panel.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Common/Grid/grid_panel.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 57);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 59
    public function block_grid_panel_footer($context, array $blocks = [])
    {
        // line 60
        echo "              ";
        if (($this->getAttribute($this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", []), "query", []), "get", [0 => "id_cms_category"], "method") && ($this->getAttribute($this->getAttribute($this->getAttribute(($context["app"] ?? null), "request", []), "query", []), "get", [0 => "id_cms_category"], "method") != $this->getAttribute(($context["cmsPageView"] ?? null), "root_category_id", [])))) {
            // line 61
            echo "                ";
            $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Cms/Blocks/listing_panel_footer.html.twig", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", 61)->display($context);
            // line 62
            echo "              ";
        }
        // line 63
        echo "            ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  201 => 63,  198 => 62,  195 => 61,  192 => 60,  189 => 59,  179 => 57,  129 => 81,  125 => 80,  120 => 79,  117 => 78,  111 => 73,  109 => 72,  105 => 70,  102 => 69,  96 => 65,  94 => 57,  90 => 55,  87 => 54,  83 => 52,  80 => 51,  77 => 50,  73 => 69,  70 => 68,  68 => 54,  65 => 53,  63 => 50,  58 => 47,  56 => 46,  51 => 43,  48 => 42,  43 => 26,  41 => 35,  40 => 30,  39 => 28,  33 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Cms/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Cms\\index.html.twig");
    }
}
