<?php
namespace CrazyElements\Core;

use CrazyElements\Core\Base\Module;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Modules_Manager {

	/**
	 * Registered modules.
	 *
	 * Holds the list of all the registered modules.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	private $modules = [];

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$modules_namespace_prefix = $this->get_modules_namespace_prefix();

		foreach ( $this->get_modules_names() as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = $modules_namespace_prefix . '\\Modules\\' . $class_name . '\Module';

			/** @var Module $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string[] Modules names.
	 */
	public function get_modules_names() {
		return [
			'history',
			'library',
			'dynamic-tags',
			'page-templates',
			'wp-cli',
			'safe-mode',
		];
	}

	/**
	 * Get modules.
	 *
	 * Retrieve all the registered modules or a specific module.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $module_name Module name.
	 *
	 * @return null|Module|Module[] All the registered modules or a specific module.
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string Modules namespace prefix.
	 */
	protected function get_modules_namespace_prefix() {
		return 'Elementor';
	}
}