<?php
require_once (dirname(__FILE__) . '/../../classes/PseFonts.php');
require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';
use CrazyElements\PrestaHelper;

class AdminCrazyFontsController extends ModuleAdminController {
    public $folder_name = '';

public function __construct() { 
	$this->table = 'crazy_fonts';  // give the table name which we have create,
	$this->className = 'PseFonts'; // class name of our object model
    $this->lang = false;
    $this->deleted = false;
    $this->bootstrap = true;
    $this->module = 'crazyelements';
    parent::__construct(); 
    // now we will create the table to show
    $this->bulk_actions = array(
        'delete' => array(
            'text' => $this->l('Delete selected'),
            'confirm' => $this->l('Delete selected items?'),
            'icon' => 'icon-trash'
        )
    );
    $this->fields_list = array(
        'id_crazy_fonts' => array(
            'title' => $this->l('Id'),
            'width' => 100,
            'type' => 'text',
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'title' => array(
            'title' => $this->l('Title'),
            'width' => 440,
            'type' => 'text',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'font_weight' => array(
            'title' => $this->l('Font Weight'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'font_style' => array(
            'title' => $this->l('Font Style'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'ttf' => array(
            'title' => $this->l('TTF'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),

        'woff' => array(
            'title' => $this->l('Woff'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'woff2' => array(
            'title' => $this->l('Woff2'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'svg' => array(
            'title' => $this->l('SVG'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'eot' => array(
            'title' => $this->l('Eot'),
            'width' => 440,
            'type' => 'file',
            'lang' => true,
            'orderby' => false,
            'filter' => false,
            'search' => false
        ),
        'active' => array(
            'title' => $this->l('Status'),
            'width' => '70',
            'align' => 'center',
            'active' => 'status',
            'type' => 'bool',
            'orderby' => false,
            'filter' => false,
            'search' => false
        )
    );
    $this->bulk_actions = array(
        'delete' => array(
            'text' => $this->l('Delete selected'),
            'icon' => 'icon-trash',
            'confirm' => $this->l('Delete selected items?')
        )
    );
    parent::__construct();
 	 $this->actions = array('delete');
}

public function renderForm() { 

    $this->fields_form = array(
        'legend' => array(
            'title' => $this->l('Content Any Where'),
        ),
         'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Title'),
                'name' => 'title', 
                'lang' => false,
                'required' => true,
                'desc' => $this->l('Enter Your Title')
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('font_weight'),
                'name' => 'font_weight',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('Font Style'),
                'name' => 'font_style',
                'lang' => false,
            ), 
            array(
                'type' => 'file',
                'label' => $this->l('TTF FIle'),
                'name' => 'ttf_file',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('ttf'),
                'name' => 'ttf',
                'lang' => false,
                'readonly'=>true,
                'class'=>"psecustomfont customttf",
            ), 
            array(
                'type' => 'file',
                'label' => $this->l('Woff FIle'),
                'name' => 'woff_file',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('Woff'),
                'name' => 'woff',
                'lang' => false,
                'readonly'=>true,
                'class'=>"psecustomfont customwoff",
            ), 
            array(
                'type' => 'file',
                'label' => $this->l('Woff2 FIle'),
                'name' => 'woff2_file',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('Woff2'),
                'name' => 'woff2',
                'lang' => false,
                'readonly'=>true,
                'class'=>"psecustomfont customwoff2",
            ), 
            array(
                'type' => 'file',
                'label' => $this->l('SVG FIle'),
                'name' => 'svg_file',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('Svg'),
                'name' => 'svg',
                'lang' => false,
                'readonly'=>true,
                'class'=>"psecustomfont customsvg",
            ), 
            array(
                'type' => 'file',
                'label' => $this->l('Eot FIle'),
                'name' => 'eot_file',
                'lang' => false,
            ), 
            array(
                'type' => 'text',
                'label' => $this->l('Eot'),
                'name' => 'eot',
                'lang' => false,
                'readonly'=>true,
                'class'=>"psecustomfont customeot",
            ), 
            array(
                'type' => 'switch',
                'label' => $this->l('Status'),
                'name' => 'active',
                'required' => false,
                'class' => 't',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'active',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            )
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right',
        ),
    );

    return parent::renderForm();
 }


public function renderList() {
    $html='';
    $html.='<div class="panel col-lg-12"> <div class="panel-heading"> Fonts Preview<span class="badge"></span></div>
    <div class="font-prev-wrapper" style="text-align: center;">
    <h2>This is how it looks in the PRO version. <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree_fonts?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree" target="_blank">Get PRO</a></h2><br>
    <div class="row fontgroup" style="justify-content: center;">
    <img src=" ' . CRAZY_ASSETS_URL . 'images/pro_preview/font_manager_preview.png" width="1200">
    </div><a  href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree_fonts?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree" target="_blank"> <img src=" ' . CRAZY_ASSETS_URL . 'images/price_compare.png" width="1200"></a></div></div>';
    $htmlfinal= parent::renderList().$html;
    return $htmlfinal;
}

public function initToolbar()
{
    parent::initToolbar();
}

public function initContent()
    {
        PrestaHelper::get_lience_expired_date();
        $a="<div></div>".parent::initContent();
        return $a;
    }

    public function postProcess() {
        if (Tools::isSubmit('submitAddcrazy_fonts')) {
            $this->errors[] = 'You are using the FREE version of Crazy Elements. Get the PRO version from <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree_fonts?utm_source=crazyfree_fonts&utm_medium=crazyfree_fonts_module&utm_campaign=crazyfree_fonts&utm_term=crazyfree_fonts&utm_content=crazyfree" target="_blank">ClassyDevs</a> to use this amazing feature.';
        }
        parent::postProcess(true);
    }
}