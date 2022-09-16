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

/* @PrestaShop/Admin/Improve/Design/Theme/index.html.twig */
class __TwigTemplate_9bfb68b07d150986373448e314658a996790b687b8ea83fd027f1ba9a2b533aa extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'content' => [$this, 'block_content'],
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
        $context["layoutTitle"] = (($this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Theme & Logo", [], "Admin.Navigation.Menu") . " > ") . $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Theme", [], "Admin.Design.Feature"));
        // line 29
        $context["layoutHeaderToolbarBtn"] = ["add" => ["href" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_themes_import"), "desc" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Add new theme", [], "Admin.Design.Feature"), "icon" => "add"], "export" => ["href" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_themes_export_current"), "desc" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Export current theme", [], "Admin.Design.Feature"), "icon" => "cloud_download"]];
        // line 26
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/layout.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 26);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 42
    public function block_content($context, array $blocks = [])
    {
        // line 43
        echo "  <div id=\"themes-logo-page\">
    <div class=\"row\">
      <div class=\"col\">

        ";
        // line 47
        if ( !($context["isSingleShopContext"] ?? null)) {
            // line 48
            echo "          <div class=\"alert alert-info\">
            <p class=\"alert-text\">
              ";
            // line 50
            echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("You must select a shop from the above list if you wish to choose a theme.", [], "Admin.Design.Help"), "html", null, true);
            echo "
            </p>
          </div>
        ";
        }
        // line 54
        echo "
        ";
        // line 55
        if (($context["isInstalledRtlLanguage"] ?? null)) {
            // line 56
            echo "          ";
            $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/rtl_configuration.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 56)->display($context);
            // line 57
            echo "        ";
        }
        // line 58
        echo "
        ";
        // line 59
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["shopLogosForm"] ?? null), 'form_start', ["action" => $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_themes_upload_logos")]);
        echo "
          ";
        // line 60
        if ((( !($context["isInstalledRtlLanguage"] ?? null) && ($context["isSingleShopContext"] ?? null)) && ($context["isMultiShopFeatureUsed"] ?? null))) {
            // line 61
            echo "            ";
            $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/multishop_switch.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 61)->display($context);
            // line 62
            echo "          ";
        }
        // line 63
        echo "          <div class=\"card\">
            <div class=\"card-header\">
              ";
        // line 65
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Logo", [], "Admin.Global"), "html", null, true);
        echo "
            </div>
            <div class=\"card-body logo-configuration-card-body\">
              ";
        // line 68
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/logo_configuration.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 68)->display($context);
        // line 69
        echo "            </div>
            <div class=\"card-footer\">
              <button class=\"btn btn-primary float-right\">
                ";
        // line 72
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Save", [], "Admin.Actions"), "html", null, true);
        echo "
              </button>
              <div class=\"clearfix\">&nbsp;</div>
            </div>
          </div>
          ";
        // line 77
        echo $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(($context["shopLogosForm"] ?? null), 'rest');
        echo "
        ";
        // line 78
        echo         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock(($context["shopLogosForm"] ?? null), 'form_end');
        echo "

        <div class=\"card\">
          <div class=\"card-header\">
            ";
        // line 82
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("My theme for %name% shop", ["%name%" => ($context["shopName"] ?? null)], "Admin.Design.Feature"), "html", null, true);
        echo "
          </div>
          <div class=\"card-body row\">

            ";
        // line 86
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 86, "1970052837")->display(twig_array_merge($context, ["themeName" => $this->getAttribute(        // line 87
($context["currentlyUsedTheme"] ?? null), "get", [0 => "display_name"], "method"), "themeVersion" => $this->getAttribute(        // line 88
($context["currentlyUsedTheme"] ?? null), "get", [0 => "version"], "method"), "themeAuthor" => $this->getAttribute(        // line 89
($context["currentlyUsedTheme"] ?? null), "get", [0 => "author.name"], "method"), "themeAuthorUrl" => $this->getAttribute(        // line 90
($context["theme"] ?? null), "get", [0 => "author.url"], "method"), "isActive" => true]));
        // line 103
        echo "
            ";
        // line 104
        if ( !twig_test_empty(($context["notUsedThemes"] ?? null))) {
            // line 105
            echo "              ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["notUsedThemes"] ?? null));
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
            foreach ($context['_seq'] as $context["_key"] => $context["theme"]) {
                // line 106
                echo "                ";
                $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 106, "743268612")->display(twig_array_merge($context, ["themeName" => $this->getAttribute(                // line 107
$context["theme"], "get", [0 => "display_name"], "method"), "themeVersion" => $this->getAttribute(                // line 108
$context["theme"], "get", [0 => "version"], "method"), "themeAuthor" => $this->getAttribute(                // line 109
$context["theme"], "get", [0 => "author.name"], "method"), "themeAuthorUrl" => $this->getAttribute(                // line 110
$context["theme"], "get", [0 => "author.url"], "method"), "isActive" => false]));
                // line 136
                echo "              ";
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['theme'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 137
            echo "
              ";
            // line 138
            $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/use_theme_modal.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 138)->display($context);
            // line 139
            echo "              ";
            $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/delete_theme_modal.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 139)->display($context);
            // line 140
            echo "            ";
        }
        // line 141
        echo "
            ";
        // line 142
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 142, "2045926335")->display($context);
        // line 158
        echo "
            ";
        // line 159
        $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/layouts_configuration.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 159)->display($context);
        // line 160
        echo "          </div>
        </div>
      </div>
    </div>
  </div>
";
    }

    // line 167
    public function block_javascripts($context, array $blocks = [])
    {
        // line 168
        echo "  ";
        $this->displayParentBlock("javascripts", $context, $blocks);
        echo "

  <script src=\"";
        // line 170
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("themes/new-theme/public/themes.bundle.js"), "html", null, true);
        echo "\"></script>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  225 => 170,  219 => 168,  216 => 167,  207 => 160,  205 => 159,  202 => 158,  200 => 142,  197 => 141,  194 => 140,  191 => 139,  189 => 138,  186 => 137,  172 => 136,  170 => 110,  169 => 109,  168 => 108,  167 => 107,  165 => 106,  147 => 105,  145 => 104,  142 => 103,  140 => 90,  139 => 89,  138 => 88,  137 => 87,  136 => 86,  129 => 82,  122 => 78,  118 => 77,  110 => 72,  105 => 69,  103 => 68,  97 => 65,  93 => 63,  90 => 62,  87 => 61,  85 => 60,  81 => 59,  78 => 58,  75 => 57,  72 => 56,  70 => 55,  67 => 54,  60 => 50,  56 => 48,  54 => 47,  48 => 43,  45 => 42,  40 => 26,  38 => 29,  36 => 28,  30 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\index.html.twig");
    }
}


/* @PrestaShop/Admin/Improve/Design/Theme/index.html.twig */
class __TwigTemplate_9bfb68b07d150986373448e314658a996790b687b8ea83fd027f1ba9a2b533aa___1970052837 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'image' => [$this, 'block_image'],
            'button_container' => [$this, 'block_button_container'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 86
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 86);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 93
    public function block_image($context, array $blocks = [])
    {
        // line 94
        echo "                <img src=\"";
        echo twig_escape_filter($this->env, ($context["baseShopUrl"] ?? null), "html", null, true);
        echo twig_escape_filter($this->env, $this->getAttribute(($context["currentlyUsedTheme"] ?? null), "get", [0 => "preview"], "method"), "html", null, true);
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["currentlyUsedTheme"] ?? null), "get", [0 => "display_name"], "method"), "html", null, true);
        echo "\">
              ";
    }

    // line 96
    public function block_button_container($context, array $blocks = [])
    {
        // line 97
        echo "                <button class=\"btn action-button\">
                  <i class=\"material-icons icon-current-theme\">done</i>
                  ";
        // line 99
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("My current theme", [], "Admin.Design.Feature"), "html", null, true);
        echo "
                </button>
              ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  306 => 99,  302 => 97,  299 => 96,  289 => 94,  286 => 93,  276 => 86,  225 => 170,  219 => 168,  216 => 167,  207 => 160,  205 => 159,  202 => 158,  200 => 142,  197 => 141,  194 => 140,  191 => 139,  189 => 138,  186 => 137,  172 => 136,  170 => 110,  169 => 109,  168 => 108,  167 => 107,  165 => 106,  147 => 105,  145 => 104,  142 => 103,  140 => 90,  139 => 89,  138 => 88,  137 => 87,  136 => 86,  129 => 82,  122 => 78,  118 => 77,  110 => 72,  105 => 69,  103 => 68,  97 => 65,  93 => 63,  90 => 62,  87 => 61,  85 => 60,  81 => 59,  78 => 58,  75 => 57,  72 => 56,  70 => 55,  67 => 54,  60 => 50,  56 => 48,  54 => 47,  48 => 43,  45 => 42,  40 => 26,  38 => 29,  36 => 28,  30 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\index.html.twig");
    }
}


/* @PrestaShop/Admin/Improve/Design/Theme/index.html.twig */
class __TwigTemplate_9bfb68b07d150986373448e314658a996790b687b8ea83fd027f1ba9a2b533aa___743268612 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'image' => [$this, 'block_image'],
            'button_container' => [$this, 'block_button_container'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 106
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_card.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 106);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 113
    public function block_image($context, array $blocks = [])
    {
        // line 114
        echo "                    <img src=\"";
        echo twig_escape_filter($this->env, ($context["baseShopUrl"] ?? null), "html", null, true);
        echo twig_escape_filter($this->env, $this->getAttribute(($context["theme"] ?? null), "get", [0 => "preview"], "method"), "html", null, true);
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["theme"] ?? null), "get", [0 => "display_name"], "method"), "html", null, true);
        echo "\">
                  ";
    }

    // line 116
    public function block_button_container($context, array $blocks = [])
    {
        // line 117
        echo "                    <form action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_themes_enable", ["themeName" => $this->getAttribute(($context["theme"] ?? null), "name", [])]), "html", null, true);
        echo "\" method=\"post\" class=\"d-inline\">
                      <input type=\"hidden\" name=\"token\" value=\"";
        // line 118
        echo twig_escape_filter($this->env, $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("enable-theme"), "html", null, true);
        echo "\" />
                      <button type=\"button\" class=\"btn action-button js-display-use-theme-modal\" ";
        // line 119
        echo (( !($context["isSingleShopContext"] ?? null)) ? ("disabled") : (""));
        echo ">
                        <i class=\"material-icons\">
                          present_to_all
                        </i>
                        <span>";
        // line 123
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Use this theme", [], "Admin.Design.Feature"), "html", null, true);
        echo "</span>
                      </button>
                    </form>
                    <form action=\"";
        // line 126
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\RoutingExtension')->getPath("admin_themes_delete", ["themeName" => $this->getAttribute(($context["theme"] ?? null), "name", [])]), "html", null, true);
        echo "\" method=\"post\" class=\"d-inline\">
                      <input type=\"hidden\" name=\"token\" value=\"";
        // line 127
        echo twig_escape_filter($this->env, $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderCsrfToken("delete-theme"), "html", null, true);
        echo "\" />
                      <button type=\"button\" class=\"btn delete-button js-display-delete-theme-modal\">
                        <i class=\"material-icons\">
                          delete
                        </i>
                      </button>
                    </form>
                  ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  410 => 127,  406 => 126,  400 => 123,  393 => 119,  389 => 118,  384 => 117,  381 => 116,  371 => 114,  368 => 113,  358 => 106,  306 => 99,  302 => 97,  299 => 96,  289 => 94,  286 => 93,  276 => 86,  225 => 170,  219 => 168,  216 => 167,  207 => 160,  205 => 159,  202 => 158,  200 => 142,  197 => 141,  194 => 140,  191 => 139,  189 => 138,  186 => 137,  172 => 136,  170 => 110,  169 => 109,  168 => 108,  167 => 107,  165 => 106,  147 => 105,  145 => 104,  142 => 103,  140 => 90,  139 => 89,  138 => 88,  137 => 87,  136 => 86,  129 => 82,  122 => 78,  118 => 77,  110 => 72,  105 => 69,  103 => 68,  97 => 65,  93 => 63,  90 => 62,  87 => 61,  85 => 60,  81 => 59,  78 => 58,  75 => 57,  72 => 56,  70 => 55,  67 => 54,  60 => 50,  56 => 48,  54 => 47,  48 => 43,  45 => 42,  40 => 26,  38 => 29,  36 => 28,  30 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\index.html.twig");
    }
}


/* @PrestaShop/Admin/Improve/Design/Theme/index.html.twig */
class __TwigTemplate_9bfb68b07d150986373448e314658a996790b687b8ea83fd027f1ba9a2b533aa___2045926335 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'image' => [$this, 'block_image'],
            'description' => [$this, 'block_description'],
            'button_container' => [$this, 'block_button_container'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 142
        return "@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@PrestaShop/Admin/Improve/Design/Theme/Blocks/Partials/theme_catalog_card.html.twig", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", 142);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 143
    public function block_image($context, array $blocks = [])
    {
        // line 144
        echo "                <img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\AssetExtension')->getAssetUrl("themes/new-theme/public/pages/themes/icon_themes.png"), "html", null, true);
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Visit the theme catalog", [], "Admin.Design.Feature"), "html", null, true);
        echo "\">
              ";
    }

    // line 147
    public function block_description($context, array $blocks = [])
    {
        // line 148
        echo "                ";
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Explore more than a thousand themes", [], "Admin.Design.Feature"), "html", null, true);
        echo "
              ";
    }

    // line 151
    public function block_button_container($context, array $blocks = [])
    {
        // line 152
        echo "                <a class=\"btn btn-primary\" href=\"";
        echo twig_escape_filter($this->env, ($context["themeCatalogUrl"] ?? null), "html", null, true);
        echo "\" target=\"_blank\">
                  ";
        // line 153
        echo twig_escape_filter($this->env, $this->env->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension')->trans("Visit the theme catalog", [], "Admin.Design.Feature"), "html", null, true);
        echo "
                </a>
              ";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  508 => 153,  503 => 152,  500 => 151,  493 => 148,  490 => 147,  481 => 144,  478 => 143,  468 => 142,  410 => 127,  406 => 126,  400 => 123,  393 => 119,  389 => 118,  384 => 117,  381 => 116,  371 => 114,  368 => 113,  358 => 106,  306 => 99,  302 => 97,  299 => 96,  289 => 94,  286 => 93,  276 => 86,  225 => 170,  219 => 168,  216 => 167,  207 => 160,  205 => 159,  202 => 158,  200 => 142,  197 => 141,  194 => 140,  191 => 139,  189 => 138,  186 => 137,  172 => 136,  170 => 110,  169 => 109,  168 => 108,  167 => 107,  165 => 106,  147 => 105,  145 => 104,  142 => 103,  140 => 90,  139 => 89,  138 => 88,  137 => 87,  136 => 86,  129 => 82,  122 => 78,  118 => 77,  110 => 72,  105 => 69,  103 => 68,  97 => 65,  93 => 63,  90 => 62,  87 => 61,  85 => 60,  81 => 59,  78 => 58,  75 => 57,  72 => 56,  70 => 55,  67 => 54,  60 => 50,  56 => 48,  54 => 47,  48 => 43,  45 => 42,  40 => 26,  38 => 29,  36 => 28,  30 => 26,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@PrestaShop/Admin/Improve/Design/Theme/index.html.twig", "C:\\xampp-v7\\htdocs\\prestashop\\src\\PrestaShopBundle\\Resources\\views\\Admin\\Improve\\Design\\Theme\\index.html.twig");
    }
}
