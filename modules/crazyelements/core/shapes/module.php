<?php

namespace CrazyElements\Core\Shapes;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

use CrazyElements\PrestaHelper; 

class Module extends \CrazyElements\Core\Base\Module {

	/**
	 * Return a translated user-friendly list of the available SVG shapes.
	 *
	 * @param bool $add_custom Determine if the output should include the `Custom` option.
	 *
	 * @return array List of paths.
	 */
	public static function get_paths( $add_custom = true ) {
		$paths = [
			'wave' => PrestaHelper::__( 'Wave', 'elementor' ),
			'arc' => PrestaHelper::__( 'Arc', 'elementor' ),
			'circle' => PrestaHelper::__( 'Circle', 'elementor' ),
			'line' => PrestaHelper::__( 'Line', 'elementor' ),
			'oval' => PrestaHelper::__( 'Oval', 'elementor' ),
			'spiral' => PrestaHelper::__( 'Spiral', 'elementor' ),
		];

		if ( $add_custom ) {
			$paths['custom'] = PrestaHelper::__( 'Custom', 'elementor' );
		}

		return $paths;
	}

	/**
	 * Get an SVG Path URL from the pre-defined ones.
	 *
	 * @param string $path - Path name.
	 *
	 * @return string
	 */
	public static function get_path_url( $path ) {
		return CRAZY_ASSETS_URL . 'svg-paths/' . $path . '.svg';
	}

	/**
	 * Get the module's associated widgets.
	 *
	 * @return string[]
	 */
	protected function get_widgets() {
		return [
			'TextPath',
		];
	}

	/**
	 * Retrieve the module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'shapes';
	}
}
