<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Core\Admin\Admin;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Common\App as CommonApp;
use CrazyElements\Core\Debug\Inspector;
use CrazyElements\Core\Documents_Manager;
use CrazyElements\Core\Editor\Editor;
use CrazyElements\Core\Files\Manager as Files_Manager;
use CrazyElements\Core\Files\Assets\Manager as Assets_Manager;
use CrazyElements\Core\Modules_Manager;
use CrazyElements\Core\Settings\Manager as Settings_Manager;
use CrazyElements\Core\Settings\Page\Manager as Page_Settings_Manager;
use CrazyElements\Core\Revisions\Revisions_Manager;
use CrazyElements\Core\DynamicTags\Manager as Dynamic_Tags_Manager;
use CrazyElements\Core\Logger\Manager as Log_Manager;
use CrazyElements\Core\Revisions;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}
require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';

class Plugin {

	public static $instance = null;
	public $db;
	public $ajax;
	public $controls_manager;
	public $documents;
	public $schemes_manager;
	public $elements_manager;
	public $widgets_manager;
	public $revisions_manager;
	public $maintenance_mode;
	public $page_settings_manager;
	public $dynamic_tags;
	public $Settings_Manager;
	public $settings;
	public $role_manager;
	public $admin;
	public $tools;
	public $preview;
	public $editor;
	public $frontend;
	public $heartbeat;
	public $system_info;
	public $templates_manager;
	public $skins_manager;
	public $files_manager;
	public $assets_manager;
	public $posts_css_manager;
	public $wordpress_widgets_manager;
	public $modules_manager;
	public $beta_testers;
	public $debugger;
	public $inspector;
	public $common;
	public $logger;
	public $upgrade;

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, PrestaHelper::esc_html__( 'Something went wrong.', 'elementor' ), '1.0.0' );
	}

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, PrestaHelper::esc_html__( 'Something went wrong.', 'elementor' ), '1.0.0' );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			PrestaHelper::do_action( 'elementor/loaded' );
		}
		return self::$instance;
	}

	public function loadElements( $id_crazy_content = null ) {
		$this->init_components();
		$frontend = new Frontend();
		$frontend->loadElementsFrontend( $id_crazy_content );
	}

	public function loadElementsForTemplate( $element_data ) {
		$this->init_components();
		$frontend = new Frontend();
		$frontend->loadElementsFrontendTemplate( $element_data );
	}
	
	public function initForEditor() {

		if ( is_null( $this->common ) ) {
			$this->common = new CommonApp();
		}
		$this->common->init_components();
		$this->init_components();
		PrestaHelper::do_action( 'elementor/init' );
		$this->editor->init();
	}
	public function initForAjax() {
		$this->on_rest_api_init();
		$this->init_components();
	}

	public function get_install_time() {
		$installed_time = PrestaHelper::get_option( '_elementor_installed_time' );
		if ( ! $installed_time ) {
			$installed_time = time();
			PrestaHelper::update_option( '_elementor_installed_time', $installed_time );
		}
		return $installed_time;
	}

	public function on_rest_api_init() {
		// On admin/frontend sometimes the rest API is initialized after the common is initialized.
		if ( ! $this->common ) {
			$this->init_common();
		}
	}

	private function init_components() {
		$this->Settings_Manager = new Settings_Manager();
		$this->Settings_Manager::run();
		$this->db               = new DB();
		$this->controls_manager = new Controls_Manager();
		$this->documents        = new Documents_Manager();
		$this->schemes_manager  = new Schemes_Manager();
		$this->elements_manager = new Elements_Manager();
		$this->widgets_manager  = new Widgets_Manager();
		$this->skins_manager    = new Skins_Manager();
		$this->files_manager    = new Files_Manager();
		$this->assets_manager   = new Assets_Manager();
		$this->icons_manager    = new Icons_Manager();
		$this->posts_css_manager = $this->files_manager;
		$this->tools   = new Tools();
		$this->editor  = new Editor();
		$this->preview = new Preview();
		$this->templates_manager = new TemplateLibrary\Manager();
		$this->dynamic_tags = new Dynamic_Tags_Manager();
		$this->revisions_manager = new Revisions_Manager();
	}

	public function load_elementor() {

	}

	public function init_common() {
		$this->common = new CommonApp();
		$this->common->init_components();
		$this->ajax = $this->common->get_component( 'ajax' );
	}
	
	private function add_cpt_support() {
		$cpt_support = PrestaHelper::get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
		foreach ( $cpt_support as $cpt_slug ) {
			add_post_type_support( $cpt_slug, 'elementor' );
		}
	}

	private function register_autoloader() {
		include CRAZY_PATH . '/includes/autoloader.php';
		Autoloader::run();
	}

	private function __construct() {
		$this->register_autoloader();
	}

	final public static function get_title() {
		return PrestaHelper::__( 'Elementor', 'elementor' );
	}
}