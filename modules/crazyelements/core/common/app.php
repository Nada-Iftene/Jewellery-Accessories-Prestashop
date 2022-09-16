<?php
namespace CrazyElements\Core\Common;

use CrazyElements\Core\Base\App as BaseApp;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Common\Modules\Connect\Module as Connect;
use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class App extends BaseApp {

	private $templates = [];

	public function __construct() {
		$this->add_default_templates();

		PrestaHelper::add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'register_scripts' ] );
		PrestaHelper::add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
		PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
		PrestaHelper::add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'register_styles' ] );
		PrestaHelper::add_action( 'admin_enqueue_scripts', [ $this, 'register_styles' ] );
		PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ], 9 );
		PrestaHelper::add_action( 'elementor/editor/footer', [ $this, 'print_templates' ] );
		PrestaHelper::add_action( 'admin_footer', [ $this, 'print_templates' ] );
		PrestaHelper::add_action( 'wp_footer', [ $this, 'print_templates' ] );
	}

	public function init_components() {
		$this->add_component( 'ajax', new Ajax() );
		$this->add_component( 'connect', new Connect() );
	}

	public function get_name() {
		return 'common';
	}

	public function register_scripts() {
		PrestaHelper::wp_register_script(
			'elementor-common-modules',
			$this->get_js_assets_url( 'common-modules' ),
			[],
			CRAZY_VERSION,
			true
		);

		PrestaHelper::wp_register_script(
			'backbone-marionette',
			$this->get_js_assets_url( 'backbone.marionette', 'assets/lib/backbone/' ),
			[
				'backbone',
			],
			'2.4.5',
			true
		);

		PrestaHelper::wp_register_script(
			'backbone-radio',
			$this->get_js_assets_url( 'backbone.radio', 'assets/lib/backbone/' ),
			[
				'backbone',
			],
			'1.0.4',
			true
		);

		PrestaHelper::wp_register_script(
			'elementor-dialog',
			$this->get_js_assets_url( 'dialog', 'assets/lib/dialog/' ),
			[
				'jquery-ui-position',
			],
			'4.7.1',
			true
		);

		PrestaHelper::wp_enqueue_script(
			'elementor-common',
			$this->get_js_assets_url( 'common',null,false ),
			[
				'jquery',
				'jquery-ui-draggable',
				'backbone-marionette',
				'backbone-radio',
				'elementor-common-modules',
				'elementor-dialog',
			],
			CRAZY_VERSION,
			true
		);
        
		$this->print_config();
	}

	public function register_styles() {
		PrestaHelper::wp_register_style(
			'ce-icons',
			$this->get_css_assets_url( 'ce-icons', 'assets/lib/ceicons/css/' ),
			[],
			'5.3.0'
		);

		PrestaHelper::wp_enqueue_style(
			'elementor-common',
			$this->get_css_assets_url( 'common', null, 'default', true ),
			[
				'ce-icons',
			],
			CRAZY_VERSION
		);
	}


	public function add_template( $template, $type = 'path' ) {
		if ( 'path' === $type ) {
			ob_start();

			include $template;

			$template = ob_get_clean();
		}

		$this->templates[] = $template;
	}

	public function print_templates() {
		foreach ( $this->templates as $template ) {
			echo $template;
		}
	}

	protected function get_init_settings() {
 		return [
			'version' => CRAZY_VERSION,
			'isRTL' => PrestaHelper::is_rtl(),
			'activeModules' => array_keys( $this->get_components() ),
			'urls' => [
				'assets' => CRAZY_ASSETS_URL,
			],
		];
	}

	private function add_default_templates() {
		$default_templates = [
			'includes/editor-templates/library-layout.php',
		];

		foreach ( $default_templates as $template ) {
			$this->add_template( CRAZY_PATH . $template );
		}
	}
}