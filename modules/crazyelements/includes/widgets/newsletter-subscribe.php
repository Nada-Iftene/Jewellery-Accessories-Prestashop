<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}


class Widget_NewsletterSubscribe extends Widget_Base {



	public function get_name() {
		return 'mail_champ';
	}

	public function get_title() {
		return PrestaHelper::__( 'Mailchimp', 'elementor' );
	}

	public function get_icon() {
		return 'ceicon-mailchimp';
	}

	public function get_categories() {
		return array( 'crazy_addons' );
	}

	private function mail_champ_list( $url, $request_type, $api_key, $data = array() ) {
		if ( $request_type == 'GET' ) {
			$url .= '?' . http_build_query( $data );
		}

		$curl    = curl_init( $url );
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic ' . base64_encode( 'user:' . $api_key ),
		);
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		// curl_setopt($curl, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $request_type );
		curl_setopt( $curl, CURLOPT_TIMEOUT, 50 );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		$res    = curl_exec( $curl );
		$err    = curl_errno( $curl );
		$errmsg = curl_error( $curl );
		$header = curl_getinfo( $curl );
		return $res;
	}

	private function list_id_mailchamp() {
		$api_key = PrestaHelper::get_option( 'mailchimp_data' );
		$data    = array(
			'fields' => 'lists',
			'count'  => 5,
		);
		$url     = 'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/';
		$someOne = $this->mail_champ_list( $url, 'GET', $api_key, $data );
		$result  = json_decode( $someOne );

		$mail_list_id_arry = array();
		if ( ! empty( $result->lists ) ) {
			foreach ( $result->lists as $list ) {
				$mail_list_id_arry[ $list->id ] = $list->name . ' ' . $list->stats->member_count;
			}
		}
		return $mail_list_id_arry;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'form',
			array(
				'label' => PrestaHelper::__( 'Form', 'elementor' ),
			)
		);
		$this->add_control(
			'pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( '<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements to Use This Awesome Addons.', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings       = $this->get_settings_for_display();
		echo "You are using the Free Version of Crazy Elements. <a href='https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree' target='_blank'>Get Pro to use this feature.</a>";
	}
}