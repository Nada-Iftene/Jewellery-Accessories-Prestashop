<?php
require_once dirname( __FILE__ ) . '/../../classes/CrazyExtendedmodules.php';

require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';

use CrazyElements\PrestaHelper;
use CrazyElements\TemplateLibrary\Classes\Import_Images;

class AdminCrazyExtendedmodulesController extends ModuleAdminController {

	public $activeButton=true;
	public function __construct() {
		$this->table     = 'crazy_extended_modules';
		$this->className = 'CrazyExtendedmodules';
		$this->lang      = false;
		$this->deleted   = false;
		$this->bootstrap = true;
		$this->module    = 'crazyelements';
		$this->activeButton=PrestaHelper::get_option('ce_licence','false');

		parent::__construct();

		$this->fields_list  = array(
			'id_crazy_extended_modules' => array(
				'title'   => $this->l( 'Id' ),
				'width'   => 100,
				'type'    => 'text',
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'title'            => array(
				'title'   => $this->l( 'Title' ),
				'width'   => 440,
				'type'    => 'text',
				'lang'    => true,
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'module_name'            => array(
				'title'   => $this->l( 'Module Name' ),
				'width'   => 440,
				'type'    => 'text',
				'lang'    => true,
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'active'           => array(
				'title'   => $this->l( 'Status' ),
				'width'   => '70',
				'align'   => 'center',
				'active'  => 'status',
				'type'    => 'bool',
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
		);
		$this->bulk_actions = array(
			'delete' => array(
				'text'    => $this->l( 'Delete selected' ),
				'icon'    => 'icon-trash',
				'confirm' => $this->l( 'Delete selected items?' ),
			),
		);
		parent::__construct();
	}

	public function initContent() {
		if ( $this->display == 'list' ) {
			$this->display = '';
		}
		if ( isset( $this->display ) && method_exists( $this, 'render' . $this->display ) ) {
			$this->content .= $this->initPageHeaderToolbar();
			$this->content .= $this->{'render' . $this->display}();
			$this->context->smarty->assign(
				array(
					'content'                   => $this->content,
				)
			);
		} else {
			return parent::initContent();
		}
	}

	public function display() {
		parent::display();
	}

	public function renderForm() {
		
		
		return parent::renderForm();
	}

	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
	}

	public function renderList() {
		$html = "";
        $html.='<div class="panel col-lg-12"> <div class="panel-heading"> Extended Modules Preview<span class="badge"></span></div>
        <div class="font-prev-wrapper" style="text-align: center;">
        <h2>This is how it looks in the PRO version. <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_extended&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_extended&utm_term=crazyfree_extended&utm_content=crazyfree_extended?utm_source=crazyfree_extended&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_extended&utm_term=crazyfree_extended&utm_content=crazyfree" target="_blank">Get PRO</a></h2><br>
        <div class="row fontgroup" style="justify-content: center;">
        <img src=" ' . CRAZY_ASSETS_URL . 'images/pro_preview/extend_modules_pro.png" width="1200">
        </div><a  href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_extended&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_extended&utm_term=crazyfree_extended&utm_content=crazyfree_extended?utm_source=crazyfree_extended&utm_medium=crazyfree_module_extended&utm_campaign=crazyfree_extended&utm_term=crazyfree_extended&utm_content=crazyfree" target="_blank"> <img src=" ' . CRAZY_ASSETS_URL . 'images/price_compare.png" width="1200"></a></div></div>';
        $htmlfinal= parent::renderList() . $html;
        return $htmlfinal."&nbsp";
	}

	public function initToolbar() {
		return;
	}
}