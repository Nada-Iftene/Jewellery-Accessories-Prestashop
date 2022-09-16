<?php

namespace CrazyElements\Core\Base;

use CrazyElements\Utils;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Base App
 *
 * Base app utility class that provides shared functionality of apps.
 *
 * @since 1.0
 */
abstract class App extends Module {

	/**
	 * Print config.
	 *
	 * Used to print the app and its components settings as a JavaScript object.
	 *
	 * @param string $handle Optional
	 *
	 * @since 1.0
	 * @since 1.0 added the `$handle` parameter
	 * @access protected
	 */
	final protected function print_config( $handle = null ) {
		$name = $this->get_name();

		$js_var = 'elementor' . str_replace( ' ', '', ucwords( str_replace( '-', ' ', $name ) ) ) . 'Config';

		$config = $this->get_settings() + $this->get_components_config();

		if ( ! $handle ) {
			$handle = 'elementor-' . $name;
		}

		Utils::print_js_config( $handle, $js_var, $config );
	}

	/**
	 * Get components config.
	 *
	 * Retrieves the app components settings.
	 *
	 * @since 1.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_components_config() {
		$settings = [];

		foreach ( $this->get_components() as $id => $instance ) {
			$settings[ $id ] = $instance->get_settings();
		}

		return $settings;
	}
}