<?php
namespace CrazyElements\Core\Debug;

use CrazyElements\Core\Debug\Classes\Inspection_Base;
use CrazyElements\Core\Debug\Classes\Theme_Missing;
use CrazyElements\Core\Debug\Classes\Htaccess;
use CrazyElements\PrestaHelper;
class Loading_Inspection_Manager {

	public static $_instance = null;

	public static function instance() {
		if ( null === self::$_instance ) {
			self::$_instance = new Loading_Inspection_Manager();
		}
		return self::$_instance;
	}

	/** @var Inspection_Base[] */
	private $inspections = [];

	public function register_inspections() {
		$this->inspections['theme-missing'] = new Theme_Missing();
		$this->inspections['htaccess'] = new Htaccess();
	}

	/**
	 * @param Inspection_Base $inspection
	 */
	public function register_inspection( $inspection ) {
		$this->inspections[ $inspection->get_name() ] = $inspection;
	}

	public function run_inspections() {


	    $default_message = 'We\'re sorry, but something went wrong. Click on \'Learn more\' and follow each of the steps to quickly solve it. Or Contact Support';

	    $debug_message = PrestaHelper::getCurrentError($default_message);
		$debug_data = [
			'message' => PrestaHelper::__( $debug_message, 'elementor' ),
			'header' => PrestaHelper::__( 'The preview could not be loaded', 'elementor' ),
			'doc_url' => 'https://classydevs.com/docs/crazy-elements/',
		];
		foreach ( $this->inspections as $inspection ) {
			if ( ! $inspection->run() ) {
				$debug_data = [
					'message' => $inspection->get_message(),
					'header' => $inspection->get_header_message(),
					'doc_url' => $inspection->get_help_doc_url(),
					'error' => true,
				];
				break;
			}
		}

		return $debug_data;
	}
}
