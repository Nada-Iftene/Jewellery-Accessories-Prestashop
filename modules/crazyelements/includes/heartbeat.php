<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Heartbeat {

	public function heartbeat_received( $response, $data ) {
		if ( isset( $data['elementor_post_lock']['post_ID'] ) ) {
			$post_id = $data['elementor_post_lock']['post_ID'];
			$locked_user = Plugin::$instance->editor->get_locked_user( $post_id );
			if ( ! $locked_user || ! empty( $data['elementor_force_post_lock'] ) ) {
				Plugin::$instance->editor->lock_post( $post_id );
			} else {
				$response['locked_user'] = $locked_user->display_name;
			}
			/** @var Core\Common\Modules\Ajax\Module $ajax */
			$ajax = Plugin::$instance->common->get_component( 'ajax' );
			$response['elementorNonce'] = $ajax->create_nonce();
		}
		return $response;
	}

	public function refresh_nonces( $response, $data ) {
		if ( isset( $data['elementor_post_lock']['post_ID'] ) ) {
			/** @var Core\Common\Modules\Ajax\Module $ajax */
			$ajax = Plugin::$instance->common->get_component( 'ajax' );
			$response['elementor-refresh-nonces'] = [
				'elementorNonce' => $ajax->create_nonce(),
				'heartbeatNonce' => wp_create_nonce( 'heartbeat-nonce' ),
			];
		}
		return $response;
	}

	public function __construct() {
		PrestaHelper::add_filter( 'heartbeat_received', [ $this, 'heartbeat_received' ], 10, 2 );
		PrestaHelper::add_filter( 'wp_refresh_nonces', [ $this, 'refresh_nonces' ], 30, 2 );
	}
}