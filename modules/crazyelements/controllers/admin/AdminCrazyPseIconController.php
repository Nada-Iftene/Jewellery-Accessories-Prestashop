<?php
require_once (dirname(__FILE__) . '/../../classes/PseIcon.php');

require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';

use CrazyElements\PrestaHelper;

class AdminCrazyPseIconController extends AdminController {
    public $dirpaths = array();
    public $json_file_name = '';
    public $svg_file_name = '';
    public $new_json = array();
    public $custom_icon_upload_font_name = '';
    public $text_file_name = 'fontarray.txt';
    public $new_json_file_name = 'fontarray.json';
    public $folder_name = '';
    public $first_icon_name = '';
    public $allowedExts = array("zip");

    public function __construct() { 
        $this->table = 'crazy_options';  // give the table name which we have create,
        $this->className = 'PseIcon'; // class name of our object model
        $this->lang = false;
        $this->identifier = "id_options";
        $this->deleted = false;
        $this->bootstrap = true;
        $this->module = 'crazyelements';
        $this->_filter = 'and option_name="custom_icon_upload_fonts"';
        parent::__construct(); 
        $this->fields_options = array(
            'icon' => array(
                'title' => $this->l('Icons Manager'),
                'icon' => 'icon-cogs',
                'fields' => array(
                    'crazy_new_icon' => array(
                        'title' => $this->l('Add Icomoon Icon'),
                        'desc' => $this->l('Enter your old URL.'),
                        'type' => 'file',
                        'name'=>'crazy_new_icon'
                    ),
                ),
                'submit' => array('title' => $this->l('Add Icon'))
            ),
        );
        parent::__construct();
        $this->actions = array('delete');
    }


    public function renderList() {
        $html = "";
        $html.='<div class="panel col-lg-12"> <div class="panel-heading"> Icons Preview<span class="badge"></span></div>
        <div class="font-prev-wrapper" style="text-align: center;">
        <h2>This is how it looks in the PRO version. <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_icon&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_icon&utm_term=crazyfree_icon&utm_content=crazyfree" target="_blank">Get PRO</a></h2><br>
        <div class="row fontgroup" style="justify-content: center;">
        <img src=" ' . CRAZY_ASSETS_URL . 'images/pro_preview/icon_manager_pro.png" width="1200">
        </div><a  href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_icon&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_icon&utm_term=crazyfree_icon&utm_content=crazyfree_icon?utm_source=crazyfree_icon&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_icon&utm_term=crazyfree_icon&utm_content=crazyfree" target="_blank"> <img src=" ' . CRAZY_ASSETS_URL . 'images/price_compare.png" width="1200"></a></div></div>';
        $htmlfinal= parent::renderList() . $html;
        return $htmlfinal."&nbsp";
    }

    public function initContent() {
        if (Tools::isSubmit('submitOptionscrazy_options') ) {
            $this->errors[] = 'You are using the FREE version of Crazy Elements. Get the PRO version from <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_icon&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_icon&utm_term=crazyfree_icon&utm_content=crazyfree" target="_blank">ClassyDevs</a> to use this amazing feature.';
        }
        parent::initContent();
    }
}