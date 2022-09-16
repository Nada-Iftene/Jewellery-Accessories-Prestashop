<?php
namespace CrazyElements\Core\Base;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Module extends Base_Object {

	private $reflection;
	private $components = [];
	protected static $_instances = [];
	abstract public function get_name();

	public static function instance() {
		$class_name = static::class_name();

		if ( empty( static::$_instances[ $class_name ] ) ) {
			static::$_instances[ $class_name ] = new static();
		}

		return static::$_instances[ $class_name ];
	}

	public static function is_active() {
		return true;
	}

	public static function class_name() {
		return get_called_class();
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, PrestaHelper::esc_html__( 'Something went wrong.', 'elementor' ), '1.0.0' );
	}
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, PrestaHelper::esc_html__( 'Something went wrong.', 'elementor' ), '1.0.0' );
	}

	public function get_reflection() {
		if ( null === $this->reflection ) {
			$this->reflection = new \ReflectionClass( $this );
		}

		return $this->reflection;
	}


	public function add_component( $id, $instance ) {
		$this->components[ $id ] = $instance;
	}

	public function get_components() {
		return $this->components;
	}

	public function get_component( $id ) {
		if ( isset( $this->components[ $id ] ) ) {
			return $this->components[ $id ];
		}

		return false;
	}

	final protected function get_assets_url( $file_name, $file_extension, $relative_url = null, $add_min_suffix = 'default' ) {
		static $is_test_mode = null;

		if ( null === $is_test_mode ) {
			$is_test_mode = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'CRAZY_TESTS' ) && CRAZY_TESTS;
		}

		if ( ! $relative_url ) {
			$relative_url = $this->get_assets_relative_url() . $file_extension . '/';
		}

		$url = $this->get_assets_base_url() . $relative_url . $file_name;

		if ( 'default' === $add_min_suffix ) {
			$add_min_suffix = ! $is_test_mode;
		}

		if ( $add_min_suffix ) {
			$url .= '.min';
		}

		return $url . '.' . $file_extension;
	}

	
	final protected function get_js_assets_url( $file_name, $relative_url = null, $add_min_suffix = 'default' ) {
		return $this->get_assets_url( $file_name, 'js', $relative_url, $add_min_suffix );
	}

	final protected function get_css_assets_url( $file_name, $relative_url = null, $add_min_suffix = 'default', $add_direction_suffix = false ) {
		static $direction_suffix = null;

		if ( ! $direction_suffix ) {
			$direction_suffix = PrestaHelper::is_rtl() ? '-rtl' : '';
		}

		if ( $add_direction_suffix ) {
			$file_name .= $direction_suffix;
		}

		return $this->get_assets_url( $file_name, 'css', $relative_url, $add_min_suffix );
	}

	protected function get_assets_base_url() {
		return CRAZY_URL;
	}

	protected function get_assets_relative_url() {
		return 'assets/';
	}
}