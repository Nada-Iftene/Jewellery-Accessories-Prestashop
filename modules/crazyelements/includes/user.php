<?php
namespace CrazyElements;

use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class User {


	const ADMIN_NOTICES_KEY = 'elementor_admin_notices';
	const INTRODUCTION_KEY = 'elementor_introduction';
	const BETA_TESTER_META_KEY = 'elementor_beta_tester';
	const BETA_TESTER_API_URL = 'https://classydevs.com/docs/crazy-elements/';

	public static function init() {
		PrestaHelper::add_action( 'wp_ajax_elementor_set_admin_notice_viewed', [ __CLASS__, 'ajax_set_admin_notice_viewed' ] );
		PrestaHelper::add_action( 'admin_post_elementor_set_admin_notice_viewed', [ __CLASS__, 'ajax_set_admin_notice_viewed' ] );
		PrestaHelper::add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
	}

	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'introduction_viewed', [ __CLASS__, 'set_introduction_viewed' ] );
		$ajax->register_ajax_action( 'beta_tester_signup', [ __CLASS__, 'register_as_beta_tester' ] );
	}

	public static function is_current_user_can_edit( $post_id = 0 ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return false;
		}
		if ( 'trash' === get_post_status( $post_id ) ) {
			return false;
		}
		if ( ! self::is_current_user_can_edit_post_type( $post->post_type ) ) {
			return false;
		}
		$post_type_object = get_post_type_object( $post->post_type );
		if ( ! isset( $post_type_object->cap->edit_post ) ) {
			return false;
		}
		$edit_cap = $post_type_object->cap->edit_post;
		if ( ! current_user_can( $edit_cap, $post_id ) ) {
			return false;
		}
		if ( PrestaHelper::get_option( 'page_for_posts' ) === $post_id ) {
			return false;
		}
		return true;
	}

	
	public static function is_current_user_in_editing_black_list() {
		return true;
	}
	
	public static function is_current_user_can_edit_post_type( $post_type ) {
		return true;
	}

	private static function get_user_notices() {
		return get_user_meta( get_current_user_id(), self::ADMIN_NOTICES_KEY, true );
	}

	public static function is_user_notice_viewed( $notice_id ) {
		$notices = self::get_user_notices();
		if ( empty( $notices ) || empty( $notices[ $notice_id ] ) ) {
			return false;
		}
		return true;
	}

	public static function ajax_set_admin_notice_viewed() {
		if ( empty( $_REQUEST['notice_id'] ) ) {
			wp_die();
		}
		$notices = self::get_user_notices();
		if ( empty( $notices ) ) {
			$notices = [];
		}
		$notices[ $_REQUEST['notice_id'] ] = 'true';
		update_user_meta( get_current_user_id(), self::ADMIN_NOTICES_KEY, $notices );
		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( PrestaHelper::admin_url() );
			die;
		}
		wp_die();
	}

	public static function set_introduction_viewed( array $data ) {
		$user_introduction_meta = self::get_introduction_meta();
		$user_introduction_meta[ $data['introductionKey'] ] = true;
		update_user_meta( get_current_user_id(), self::INTRODUCTION_KEY, $user_introduction_meta );
	}

	public static function register_as_beta_tester( array $data ) {
		update_user_meta( get_current_user_id(), self::BETA_TESTER_META_KEY, true );
		wp_safe_remote_post(
			self::BETA_TESTER_API_URL,
			[
				'timeout' => 25,
				'body' => [
					'api_version' => CRAZY_VERSION,
					'site_lang' => get_bloginfo( 'language' ),
					'beta_tester_email' => $data['betaTesterEmail'],
				],
			]
		);
	}


	public static function get_introduction_meta() {
		$user_introduction_meta = get_user_meta( get_current_user_id(), self::INTRODUCTION_KEY, true );
		if ( ! $user_introduction_meta ) {
			$user_introduction_meta = [];
		}
		return $user_introduction_meta;
	}
}