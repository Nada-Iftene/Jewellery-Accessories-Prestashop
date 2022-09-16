<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 
if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Api {

	const LIBRARY_OPTION_KEY = 'elementor_remote_info_library';
	const FEED_OPTION_KEY = 'elementor_remote_info_feed_data';
	public static $api_info_url = 'https://template.classydevs.com/module/crazyelements/templateapi';
	private static $api_feedback_url = 'https://template.classydevs.com/v1/feedback/';
	private static $api_get_template_content_url = 'https://template.classydevs.com/module/crazyelements/templateapi?target=%d';
	private static function get_info_data( $force_update = false ) {
		$result = PrestaHelper::wp_remote_get(  self::$api_info_url, [
			'timeout' => 40,
		] );

		
		
		$info_data = json_decode( $result['body'], true );
		$lib=\Tools::jsonEncode($info_data['library']);
		if ( isset( $info_data['library'] ) ) {
		}
		return $lib;
	}

	
	public static function get_upgrade_notice() {
		$data = self::get_info_data();
		if ( empty( $data['upgrade_notice'] ) ) {
			return false;
		}
		return $data['upgrade_notice'];
	}

	public static function get_canary_deployment_info( $force = false ) {
		$data = self::get_info_data( $force );
		if ( empty( $data['canary_deployment'] ) ) {
			return false;
		}
		return $data['canary_deployment'];
	}

	
	public static function get_library_data( $force_update = false ) {
		$library_data = "";
		$ce_template_date = PrestaHelper::get_option( 'ce_template_date', '' );
		$today           = date( 'Y-m-d' );
		if ( strtotime( $today ) == strtotime( $ce_template_date )) {			
			$library_data = PrestaHelper::get_option( self::LIBRARY_OPTION_KEY );
			if($library_data=="" || $library_data=='null'){
				$library_data = self::get_info_data();
				PrestaHelper::update_option( self::LIBRARY_OPTION_KEY, $library_data);

			}
		}else{			
			$library_data = self::get_info_data();
			PrestaHelper::update_option( self::LIBRARY_OPTION_KEY, $library_data);
			PrestaHelper::update_option( 'ce_template_date', $today );
		}

		
		if ( empty( $library_data ) ) {
			return [];
		}
		return $library_data;
	}


	public static function get_feed_data( $force_update = false ) {
		self::get_info_data( $force_update );
		$feed = PrestaHelper::get_option( self::FEED_OPTION_KEY );
		if ( empty( $feed ) ) {
			return [];
		}
		return $feed;
	}

	
	public static function get_template_content( $template_id ) {
		$url = sprintf( self::$api_get_template_content_url, $template_id );
		$context = \Context::getContext();
        $iso_code= $context->language->iso_code;
		$body_args = [
			'api_version' => CRAZY_VERSION,
			'site_lang' => $iso_code,
		];

		$body_args = PrestaHelper::apply_filters( 'elementor/api/get_templates/body_args', $body_args );
		$response = PrestaHelper::wp_remote_get( $url, [
			'timeout' => 40,
			'body' => $body_args,
		] );

		if ( PrestaHelper::is_wp_error( $response ) ) {
			return $response;
		}
		$response_code = $response['info']['http_code'];
		if ( 200 !== $response_code ) {
			return new \WP_Error( 'response_code_error', sprintf( 'The request returned with a status code of %s.', $response_code ) );
		}
		$template_content = json_decode( $response['body'], true );
		if ( isset( $template_content['error'] ) ) {
			return false;
		}
		if ( empty( $template_content['data'] ) && empty( $template_content['content'] ) ) {
			return false;
		}
		if(!is_array($template_content['content'])){
			$template_content['content']=json_decode($template_content['content'],true);
		}
		return $template_content;
	}

	
	public static function send_feedback( $feedback_key, $feedback_text ) {
		return PrestaHelper::wp_remote_post( self::$api_feedback_url, [
			'timeout' => 30,
			'body' => [
				'api_version' => CRAZY_VERSION,
				'site_lang' => get_bloginfo( 'language' ),
				'feedback_key' => $feedback_key,
				'feedback' => $feedback_text,
			],
		] );
	}

	public static function ajax_reset_api_data() {
		check_ajax_referer( 'elementor_reset_library', '_nonce' );
		self::get_info_data( true );
		wp_send_json_success();
	}

	public static function init() {
		PrestaHelper::add_action( 'wp_ajax_elementor_reset_library', [ __CLASS__, 'ajax_reset_api_data' ] );
	}
}