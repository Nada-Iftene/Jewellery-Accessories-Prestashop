<?php
namespace CrazyElements\Core\Common\Modules\Connect\Apps;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

class Connect extends Common_App {

	/**
	 * @since 1.0
	 * @access protected
	 */
	protected function get_slug() {
		return 'connect';
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function render_admin_widget() {
		if ( $this->is_connected() ) {
			$remote_user = $this->get( 'user' );
			$title = sprintf( PrestaHelper::__( 'Connected to Elementor as %s', 'elementor' ), '<strong>' . $remote_user->email . '</strong>' ) . get_avatar( $remote_user->email, 20, '' );
			$label = PrestaHelper::__( 'Disconnect', 'elementor' );
			$url = $this->get_admin_url( 'disconnect' );
			$attr = '';
		} else {
			$title = PrestaHelper::__( 'Connect to CrazyElements', 'elementor' );
			$label = PrestaHelper::__( 'Connect', 'elementor' );
			$url = $this->get_admin_url( 'authorize' );
			$attr = 'class="elementor-connect-popup"';
		}
		echo '<h1>' . PrestaHelper::__( 'Connect', 'elementor' ) . '</h1>';
		echo sprintf( '%s <a %s href="%s">%s</a>', $title, $attr, PrestaHelper::esc_attr( $url ), PrestaHelper::esc_html( $label ) );
	}
	
}