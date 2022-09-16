<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * An abstract class for creating new controls in the panel that return
 * more than a single value. Each value of the multi-value control will
 * be returned as an item in a `key => value` array.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Control_Base_Multiple extends Base_Data_Control {

	/**
	 * Get multiple control default value.
	 *
	 * Retrieve the default value of the multiple control. Used to return the default
	 * values while initializing the multiple control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [];
	}

	/**
	 * Get multiple control value.
	 *
	 * Retrieve the value of the multiple control from a specific Controls_Stack settings.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $control  Control
	 * @param array $settings Settings
	 *
	 * @return mixed Control values.
	 */
	public function get_value( $control, $settings ) {
		$value = parent::get_value( $control, $settings );

		if ( empty( $control['default'] ) ) {
			$control['default'] = [];
		}

		if ( ! is_array( $value ) ) {
			$value = [];
		}

		$control['default'] = array_merge(
			$this->get_default_value(),
			$control['default']
		);

		return array_merge(
			$control['default'],
			$value
		);
	}

	public function get_style_value( $css_property, $control_value, array $control_data ) {
		return $control_value[ strtolower( $css_property ) ];
	}
}
