<?php


/**
 * <ModuleName> => cheque
 * <FileName> => validation.php
 * Format expected: <ModuleName><FileName>ModuleFrontController
 */
require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
require_once _PS_MODULE_DIR_ . 'crazyelements/classes/CrazyContent.php';

use CrazyElements\PrestaHelper;
use CrazyElements\Plugin;
class CrazyElementsAjaxModuleFrontController extends ModuleFrontController {



	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
	}

	public function initContent() {
		parent::initContent();


		$this->assignVariables();

		Plugin::instance()->initForAjax();

		// Tools::get_value()
		if ( isset( $_POST['action'] ) ) {
			$action = $_POST['action'];
			PrestaHelper::do_action( 'wp_ajax_' . $action );
			// exit();
		} elseif ( isset( $_GET['action'] ) ) {
			$action = $_GET['action'];
			PrestaHelper::do_action( 'wp_ajax_' . $action );
		} else {

		}
		die( 'exit' );

	}

	public function assignVariables() {
		PrestaHelper::$hook_current              = Tools::getValue( 'hook' );
		PrestaHelper::$id_content_global         = Tools::getValue( 'id' );
		PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId( Tools::getValue( 'id' ) );
		PrestaHelper::$id_lang_global            = Tools::getValue( 'id_lang' );


		PrestaHelper::$id_shop_global            = $this->context->shop->id;
	}

}
