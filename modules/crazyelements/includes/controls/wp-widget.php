<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * A base control for creating WordPress widget control. Displays native
 * WordPress widgets. This a private control for internal use.
 *
 * @since 1.0.0
 */
class Control_WP_Widget extends Base_Data_Control {

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'wp_widget';
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [];
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<form action="" method="post">
			<div class="wp-widget-form-loading">Loading..</div>
		</form>
		<?php
	}
}
