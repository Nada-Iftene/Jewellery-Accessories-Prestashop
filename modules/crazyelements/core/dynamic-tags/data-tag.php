<?php
namespace CrazyElements\Core\DynamicTags;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 * @abstract
 */
abstract class Data_Tag extends Base_Tag {

	/**
	 * @since 1.0.0
	 * @access protected
	 * @abstract
	 *
	 * @param array $options
	 */
	abstract protected function get_value( array $options = [] );

	/**
	 * @since 1.0.0
	 * @access public
	 */
	final public function get_content_type() {
		return 'plain';
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function get_content( array $options = [] ) {
		return $this->get_value( $options );
	}
}
