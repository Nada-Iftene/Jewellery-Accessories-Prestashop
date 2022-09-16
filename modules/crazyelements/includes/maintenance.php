<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Maintenance {


	public static function activation( $network_wide ) {
		wp_clear_scheduled_hook( 'elementor/tracker/send_event' );
		wp_schedule_event( time(), 'daily', 'elementor/tracker/send_event' );
		flush_rewrite_rules();
		if ( is_multisite() && $network_wide ) {
			return;
		}
		PrestaHelper::set_transient( 'elementor_activation_redirect', true, CE_MINUTE_IN_SECONDS );
	}

	public static function uninstall() {
		wp_clear_scheduled_hook( 'elementor/tracker/send_event' );
	}

	public static function init() {
		register_activation_hook( CRAZY_PLUGIN_BASE, [ __CLASS__, 'activation' ] );
		register_uninstall_hook( CRAZY_PLUGIN_BASE, [ __CLASS__, 'uninstall' ] );
	}
}