<?php

require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';
// require _PS_MODULE_DIR_ . '/crazyelements/includes/autoloader.php';
// new Autoloader();

// include_once(dirname(__FILE__).'/../../../crazyelements/core/documents-manager.php');

// include(_PS_MODULE_DIR_.'crazyelements'.DIRECTORY_SEPARATOR.'core/documents-manager.php');


require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';

use CrazyElements\PrestaHelper;
use CrazyElements\Plugin;


/**
 * <ModuleName> => cheque
 * <FileName> => validation.php
 * Format expected: <ModuleName><FileName>ModuleFrontController
 */
class CrazyElementsDisplayModuleFrontController extends ModuleFrontController {

	public $ssl = true;
	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
	}

	public function initContent() {
		parent::initContent();
		$template_id       = Tools::getValue( 'elementor_library' );
		$query             = 'SELECT elements FROM ' . _DB_PREFIX_ . "crazy_library where id_crazy_library='$template_id'";
		$get_elements_data = Db::getInstance()->getValue( $query );

		$get_elements_data = json_decode( $get_elements_data, true );

			ob_start();
				Plugin::instance()->loadElementsForTemplate( $get_elements_data );
				$parsed_content = ob_get_contents();
			ob_end_clean();
			$this->context->smarty->assign(
				array(
					'parsed_content' => $parsed_content,

				)
			);
		  // Will use the file modules/cheque/views/templates/front/validation.tpl

		 $this->setTemplate( 'module:crazyelements/views/templates/front/frontcontentanywhere.tpl' );

	}

}
