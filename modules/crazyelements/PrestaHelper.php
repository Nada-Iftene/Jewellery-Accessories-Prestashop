<?php
namespace CrazyElements;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

use Context;
use Link;
use Db;
use Tools;
use Configuration;
use Shop;
use Translate;
use function ICanBoogie\array_flatten;

define( 'CRAZY_MODULE_ABS_NAME', 'crazyelements' );
define( 'CRAZY_VERSION', '1.0.5.1' );
define( 'CRAZY__FILE__', __FILE__ );
define( 'CRAZY_PLUGIN_BASE', PrestaHelper::plugin_basename( CRAZY__FILE__ ) );
define( 'CRAZY_PATH', PrestaHelper::plugin_dir_path( CRAZY__FILE__ ) );

if ( defined( 'CRAZY_TESTS' ) && CRAZY_TESTS ) {
	define( 'CRAZY_URL', 'file://' . CRAZY_PATH );
} else {
	define( 'CRAZY_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . CRAZY_MODULE_ABS_NAME . '/' );
}
define( 'CRAZY_MODULES_PATH', CRAZY_PATH . 'modules' );
define( 'CRAZY_ASSETS_PATH', CRAZY_PATH . 'assets/' );
define( 'CRAZY_ASSETS_URL', CRAZY_URL . 'assets/' );
define( 'CRAZY_CLASSES_PATH', CRAZY_PATH . 'classes/' );
define( 'CRAZY_UNIQUE_ID', '62c6f6f3901d5eb21e8f' );

class PrestaHelper {

	public static $hook_args;
	public static $hook_values, $filter_values, $hook_register, $hook_deregister;
	public static $admin_scripts = array(), $admin_scripts_foot = array(), $front_scripts_foot = array(), $front_scripts = array(), $front_styles = array(), $admin_styles = array(), $local_scripts = array(), $local_scripts_footer = array(), $registered_script, $registered_style, $current_filter, $current_action, $blank_scripts = array(), $blank_styles = array();
	public $headers, $body;
	public static $id_content_global,$id_content_primary_global,$id_editor_global, $hook_current, $id_lang_global, $id_shop_global, $disable_activity, $current_error, $current_url;
	private static $licence_url    = 'https://classydevs.com/';
	private static $item_id        = '38390';
	private static $current        = '';
	private static $licence        = 'ce_licence';
	private static $licnce        = '3399c';
	private static $licenc        = 'fed1369';
	private static $licence_status = 'ce_licence_status';
	private static $licence_data   = 'ce_licence_dataset';
	private static $licence_ex     = 'ce_licence_expires';

	public function __construct() {
		$this->headers = '';
		$this->body    = '';
	}

	public static function getInstance() {
		return new PrestaHelper();
	}

	public static function getCurrentError( $default ) {
		if ( self::$current_error == null ) {
			return $default;
		}
		return self::$current_error;
	}

	public static function SetCurrentError( $setContent ) {
		self::$current_error = $setContent;
	}

	public static function enqueue_style( $styleName, $src = '', $deps = array(), $ver = '1.0', $media = 'all', $noscript ) {
		foreach ( $deps as $depnd ) {
			self::wp_enqueue_style( $depnd );
		}
		if ( self::is_admin() ) {
			self::$admin_styles[ $styleName ] = $src;
		} else {
			self::$front_styles[ $styleName ] = $src;
		}

	}

	public static function enqueue_script( $scriptName, $src = '', $deps = array(), $ver = '1.0', $in_footer = false ) {
		foreach ( $deps as $depnd ) {
			self::wp_enqueue_script( $depnd );
		}
		if ( $in_footer == false ) {
			if ( self::is_admin() ) {
				self::$admin_scripts[ $scriptName ] = $src;
			} else {
				self::$front_scripts[ $scriptName ] = $src;

			}
		} else {
			if ( self::is_admin() ) {
				self::$admin_scripts_foot[ $scriptName ] = $src;
			} else {
				self::$front_scripts_foot[ $scriptName ] = $src;

			}
		}
	}

	public static function get_setting_page_url() {
		$link = new Link();
		return $link->getAdminLink( 'AdminCrazySetting' );
	}

	public function get_admin_url( $admin_url, $id ) {
		$link = new Link();
		return $link->getAdminLink( $admin_url, $id );
	}

	public static function isHookType() {

		$type = Tools::getValue( 'hook' );

		if ( $type != 'cms'
			&& $type != 'product'
			&& $type != 'supplier'
			&& $type != 'category'
			&& $type != 'manufacturer'
		) {
			return true;
		}

		return false;
	}
	public static function getRealPostId( $id, $type = null ) {
		if ( $type == null ) {
			$type = Tools::getValue( 'hook' );
		}
		if ( $type == 'cms'
			|| $type == 'product'
			|| $type == 'supplier'
			|| $type == 'category'
			|| $type == 'manufacturer'
			|| $type == 'extended'
		) {
			$type       = pSQL( $type );
			$table_name = _DB_PREFIX_ . 'crazy_content';
			$results    = Db::getInstance()->executeS( "SELECT * FROM $table_name WHERE id_content_type = " . pSQL( $id ) . " AND hook='$type'" );
			if ( $results ) {
				$id = $results[0]['id_crazy_content'];
			} else {
				return null;
			}
		}
		return $id;
	}
	public static function get_front_url( $link = '' ) {
		$context  = \Context::getContext();
		$iso_code = $context->language->iso_code;
		$langs    = \Language::getLanguages();
		if ( count( $langs ) > 1 ) {
			$front_url = self::get_site_url() . __PS_BASE_URI__ . $iso_code . '/';
		} else {
			$front_url = self::get_site_url() . __PS_BASE_URI__;
		}
		return $front_url;
	}
	public static function get_site_url( $link = '' ) {
		$url         = self::getHtt() . '//' . Tools::getHttpHost();
		$double_http = self::getHtt() . '//' . self::getHtt() . '//';
		$url         = str_replace( $double_http, self::getHtt() . '//', $url );
		return $url;
	}

	public static function get_base_url( $link = 'index' ) {
		$ajaxUrl = Context::getContext()->link->getModuleLink( 'crazyelements', 'ajax', array(), null, self::$id_lang_global );
		if($link == 'extended'){
			$ajaxUrl = Context::getContext()->link->getModuleLink( 'smartblog', 'details', array(), null, self::$id_lang_global );
		}
		
		$ajaxUrlArr = explode( 'module', $ajaxUrl );
		$ajaxUrlArr = explode( '?', $ajaxUrlArr[0] );

		$url = $ajaxUrlArr[0];
		return $url;
	}

	public static function getHtt() {
		if ( ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) {
			return 'https:';
		}
		return 'http:';
	}

	public static function esc_html( $value, $txd = '' ) {
		return $value;
	}

	public static function get_allowed_protocols(){
		$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'irc6', 'ircs', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'sms', 'svn', 'tel', 'fax', 'xmpp', 'webcal', 'urn' );
		return $protocols;
	}

	public static function getHttpCurl( $url, $args ) {
		global $wp_version;
		if ( function_exists( 'curl_init' ) ) {
			$defaults = array(
				'method'      => 'GET',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(
					'Authorization'   => 'Basic ',
					'Content-Type'    => 'application/x-www-form-urlencoded;charset=UTF-8',
					'Accept-Encoding' => 'x-gzip,gzip,deflate',
				),
				'body'        => array(),
				'cookies'     => array(),
				'user-agent'  => 'Prestashop' . $wp_version,
				'header'      => true,
				'sslverify'   => false,
				'json'        => false,
			);

			$args         = array_merge( $defaults, $args );
			$curl_timeout = ceil( $args['timeout'] );
			$curl         = curl_init();
			if ( $args['httpversion'] == '1.0' ) {
				curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
			} else {
				curl_setopt( $curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
			}
			curl_setopt( $curl, CURLOPT_USERAGENT, $args['user-agent'] );
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $curl_timeout );
			curl_setopt( $curl, CURLOPT_TIMEOUT, $curl_timeout );
			curl_setopt( $curl, CURLOPT_POST, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, 'templateapi=true' );
			$ssl_verify = $args['sslverify'];
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, $ssl_verify );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, ( $ssl_verify === true ) ? 2 : false );
			$http_headers = array();
			if ( $args['header'] ) {
				curl_setopt( $curl, CURLOPT_HEADER, $args['header'] );
				foreach ( $args['headers'] as $key => $value ) {
					$http_headers[] = "{$key}: {$value}";
				}
			}
			curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, false );
			if ( defined( 'CURLOPT_PROTOCOLS' ) ) { // PHP 5.2.10 / cURL 7.19.4
				curl_setopt( $curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS );
			}
			if ( is_array( $args['body'] ) || is_object( $args['body'] ) ) {
				$args['body'] = http_build_query( $args['body'] );
			}
			$http_headers[] = 'Content-Length: ' . strlen( $args['body'] );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			$response = curl_exec( $curl );
			if ( $args['json'] ) {
				return $response;
			}
			$header_size    = curl_getinfo( $curl, CURLINFO_HEADER_SIZE );
			$responseHeader = substr( $response, 0, $header_size );
			$responseBody   = substr( $response, $header_size );
			$error          = curl_error( $curl );
			$errorcode      = curl_errno( $curl );
			$info           = curl_getinfo( $curl );
			curl_close( $curl );
			$info_as_response            = $info;
			$info_as_response['code']    = $info['http_code'];
			$info_as_response['message'] = 'OK';
			$response                    = array(
				'body'     => $responseBody,
				'headers'  => $responseHeader,
				'info'     => $info,
				'response' => $info_as_response,
				'error'    => $error,
				'errno'    => $errorcode,
			);
			return $response;
		}
		return false;
	}

	public static function wp_remote_post( $url, $args ) {
		$args['method'] = 'POST';
		$PrestaHelper   = new PrestaHelper();
		return $PrestaHelper->getHttpCurl( $url, $args );
	}

	public static function wp_remote_get( $url, $args = array() ) {
		$PrestaHelper = new PrestaHelper();
		return $PrestaHelper->getHttpCurl( $url, $args );
	}

	public static function current_action() {
		return self::$current_action;
	}

	public static function current_filter() {
		return self::$current_filter;
	}

	public static function add_action( $tag, $function, $priority = 10, $accepted_args = 1 ) {

		if ( $tag == 'plugins_loaded' ) {
			$params = array();
			call_user_func_array( $function, $params );
		} else {
			if ( is_array( $function ) ) {
				$function_info['class']         = $function[0];
				$function_info['type']          = 'class';
				$function_info['function_name'] = $function[1];
			} else {
				$function_info['type']          = 'noclass';
				$function_info['function_name'] = $function;
			}
			self::$hook_values[ $tag ][] = $function_info;
		}

		return true;
	}

	public static function do_action( $tag, $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '', $arg5 = '' ) {
		if ( isset( self::$hook_values[ $tag ] ) ) {
			self::$current_action = $tag;
			$params               = array( $arg1, $arg2, $arg3, $arg4, $arg5 );
			foreach ( self::$hook_values[ $tag ] as $hook ) {
				if ( $hook['type'] == 'class' ) {
					call_user_func_array( array( $hook['class'], $hook['function_name'] ), $params );
				} else {
					call_user_func_array( $hook['function_name'], $params );
				}
			}
			self::$current_action = null;
		} else {
			return true;
		}
	}

	public static function setContent( $getContent ) {
		$json_content = '[{"id":"e3f67a1","elType":"section","isInner":false,"settings":[],"elements":[{"id":"3c97424","elType":"column","isInner":false,"settings":{"_column_size":100},"elements":[{"id":"e3ea727","elType":"widget","isInner":false,"settings":{"editor":"<p>Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.<\/p>"},"elements":[],"widgetType":"text-editor"}]}]}]';
		$json_content = json_decode( $json_content, true );
		$json_content[0]['elements'][0]['elements'][0]['settings']['editor'] = $getContent;
		return $json_content;
	}

	public static function admin_url() {
		return '';
	}

	public static function add_filter( $tag, $function, $priority = 10, $accepted_args = 1 ) {
		if ( is_array( $function ) ) {
			$function_info['class']         = $function[0];
			$function_info['type']          = 'class';
			$function_info['function_name'] = $function[1];
		} else {
			$function_info['type']          = 'noclass';
			$function_info['function_name'] = $function;
		}
		self::$filter_values[ $tag ][] = $function_info;
		return true;
	}

	public static function getAjaxUrl() {

		self::is_admin() ? $employee_id   = Context::getContext()->employee->id : $employee_id = \Tools::getValue( 'employee_id' );
		self::is_admin() ? $employee_name = Context::getContext()->employee->firstname : $employee_name = \Tools::getValue( 'employee_name' );
		$http_build_query                 =
		array(
			'id'            => \Tools::getValue( 'id' ),
			'id_lang'       => self::$id_lang_global,
			'hook'          => \Tools::getValue( 'hook' ),
			'type'          => \Tools::getValue( 'type' ),
			'employee_id'   => $employee_id,
			'employee_name' => $employee_name,
		);

		$ajaxUrl = Context::getContext()->link->getModuleLink( 'crazyelements', 'ajax' );

		if ( strpos( $ajaxUrl, 'index.php' ) !== false ) {
			$ajaxUrlFinal = $ajaxUrl . '&' . http_build_query( $http_build_query );
		} else {
			$ajaxUrlFinal = $ajaxUrl . '?' . http_build_query( $http_build_query );
		}

		$ajaxUrl = Context::getContext()->link->getModuleLink( 'crazyelements', 'ajax', array(), null, self::$id_lang_global );

		

		if ( strpos( $ajaxUrl, 'index.php' ) !== false ) {
			$ajaxUrlFinal = $ajaxUrl . '&' . http_build_query( $http_build_query );
		} else {
			$ajaxUrlFinal = $ajaxUrl . '?' . http_build_query( $http_build_query );
		}

		if($http_build_query['hook']=='extended'){
			$ajaxUrlFinal .= '&fr_controller=' . Tools::getValue( 'fr_controller' ) . "&mod_name=".Tools::getValue('mod_name');
		}

		return $ajaxUrlFinal;
	}

	public static function apply_filters( $tag, $value, $arg1 = '', $arg2 = '', $arg3 = '', $arg4 = '', $arg5 = '' ) {
		if ( isset( self::$filter_values[ $tag ] ) ) {
			self::$current_filter = $tag;
			$filtered_value       = null;
			$params               = array( $value, $arg1, $arg2, $arg3, $arg4, $arg5 );
			$filter_tag_values    = self::$filter_values[ $tag ];
			foreach ( $filter_tag_values as $filter ) {
				if ( $filter['type'] == 'class' ) {
					$return_data = call_user_func_array( array( $filter['class'], $filter['function_name'] ), $params );
				} else {
					$return_data = call_user_func_array( $filter['function_name'], $params );
				}
				// get the filtered value weather string or array. sometimes returns only string
				$filtered_value = $return_data;
				// if array then reassign the value
				if ( is_array( $return_data ) ) {
					if ( count( $return_data ) == 1 || empty( $return_data ) ) {
						if ( ! empty( $return_data ) ) {
							$array_value[ key( $return_data ) ] = $return_data[ key( $return_data ) ];
						} else {
							$array_value = array();
						}
					} else {
						$array_value = $return_data;
					}
					$filtered_value = $array_value;
				}
			}
			self::$current_filter = null;
			return $filtered_value;
		} else {
			return $value;
		}
	}

	public static function home_url() {
		return '';
	}

	public static function crazy_body_classes($defaults){
		$defaults['crazyelements-free-default'] = 1;
		$defaults['crazy-shop-'.self::$id_shop_global] = 1;
		if(self::$hook_current == 'page' || self::$hook_current == ''){
			$controller = Tools::getValue('controller');
			$defaults['crazy-'.$controller] = 1;
		}else{
			$defaults['crazy-'.self::$hook_current.'-'.self::$id_content_global] = 1;
		}
		$defaults['crazy-lang-'.self::$id_lang_global] = 1;
		return $defaults;
	}


	public static function wp_enqueue_script( $scriptName, $src = '', $deps = array(), $ver = '1.0', $in_footer = false ) {
		if ( isset( self::$registered_script[ $scriptName ] ) ) {
			$src       = self::$registered_script[ $scriptName ]['src'];
			$deps      = self::$registered_script[ $scriptName ]['deps'];
			$in_footer = self::$registered_script[ $scriptName ]['in_footer'];
		}
		self::enqueue_script( $scriptName, $src, $deps, $ver, $in_footer );
	}

	public static function wp_enqueue_style( $handle, $src = '', $deps = array(), $ver = '', $media = 'all', $noscript = false ) {
		if ( isset( self::$registered_style[ $handle ] ) ) {
			$src  = self::$registered_style[ $handle ]['src'];
			$deps = self::$registered_style[ $handle ]['deps'];
		}

		self::enqueue_style( $handle, $src, $deps, $ver, $media, $noscript );
	}

	public static function is_admin() {
		if ( isset( Context::getContext()->controller->admin_webpath ) && ! empty( Context::getContext()->controller->admin_webpath ) ) {
			return true;
		} else {
			return false;
		}
	}

	public static function is_rtl() {
		return ! empty( Context::getContext()->language->is_rtl );
	}

	public static function esc_url( $url ) {
		return $url;
	}

	public static function is_wp_error( $error ) {
		return $error instanceof \PrestaShopException;
	}

	public static function wp_register_script( $name, $src, $deps = array(), $ver = '1.0', $in_footer = false ) {
		self::$registered_script[ $name ]['src']       = $src;
		self::$registered_script[ $name ]['deps']      = $deps;
		self::$registered_script[ $name ]['in_footer'] = $in_footer;
	}

	public static function wp_register_style( $name, $src, $deps = array(), $ver = '', $media = 'all', $noscript = false ) {
		self::$registered_style[ $name ]['src']  = $src;
		self::$registered_style[ $name ]['deps'] = $deps;
	}

	public static function plugin_basename( $plugin_path ) {
		$file_name     = basename( $plugin_path );
		$ext           = pathinfo( $file_name, PATHINFO_EXTENSION );
		$plugin_folder = str_replace( '.' . $ext, '', $file_name );
		return $plugin_folder . '/' . $file_name;
	}

	public static function plugin_dir_path( $location ) {
		$file_name              = basename( $location );
		$plugin_folder_location = str_replace( $file_name, '', $location );
		return $plugin_folder_location;
	}

	public static function _x( $string, $text_domain = 'elementor' ) {
		return $string;
	}

	public static function esc_attr_e( $string, $text_domain = 'elementor' ) {
		echo Tools::safeOutput( $string );
	}

	public static function _e( $string, $text_domain = '' ) {
		echo $string;
	}

	public static function __( $string, $text_domain = 'elementor' ) {
		return $string;
	}

	public static function esc_attr( $string ) {
		return Tools::safeOutput( $string );
	}

	public static function get_option( $option_name, $option_value = false ) {
		$table_name  = _DB_PREFIX_ . 'crazy_options';
		$option_name = pSQL( $option_name );
		$result      = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$option_name'" );
		if ( empty( $result ) || $result == false ) {
			return $option_value;
		} else {
			return $result;
		}
	}

	public static function get_post_meta( $id, $meta_key = '', $returnType = true ) {
		$id_lang    = (int) self::$id_lang_global;
		$id_shop    = (int) self::$id_shop_global;
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$meta_key   = pSQL( $meta_key );
		$id_lang    = pSQL( $id_lang );
		$id         = pSQL( $id );
		$id_shop    = pSQL( $id_shop );
		$result     = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$meta_key' AND id_lang = $id_lang AND id_shop = $id_shop AND id = $id Order BY id_options DESC" );
		if ( ! is_array( $result ) ) {
			$result = \Tools::jsonDecode( $result, true );
		}
		if ( $result == false || $result == null ) {
			if ( $returnType == true ) {
				return '';
			} else {
				return array();
			}
		}
		return $result;
	}

	public static function update_post_meta( $id, $meta_key, $meta_value = '' ) {
		$id_lang    = (int) self::$id_lang_global;
		$id_shop    = (int) self::$id_shop_global;
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$id         = pSQL( $id );
		$meta_key   = pSQL( $meta_key );
		$id_lang    = pSQL( $id_lang );
		$id_shop    = pSQL( $id_shop );
		$result     = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$meta_key' AND id_lang = $id_lang AND id_shop = $id_shop AND id = $id" );
		if ( is_array( $meta_value ) ) {
			$meta_value = \Tools::jsonEncode( $meta_value );
		}
		$meta_value = pSQL( $meta_value );
		if ( $result == false || empty( $result ) ) {
			Db::getInstance()->insert(
				'crazy_options',
				array(
					'id'           => $id,
					'id_lang'      => $id_lang,
					'id_shop'      => $id_shop,
					'option_name'  => $meta_key,
					'option_value' => $meta_value,
				)
			);
		} else {
			Db::getInstance()->update(
				'crazy_options',
				array(
					'option_value' => $meta_value,
				),
				"`option_name` = '$meta_key' AND id = $id AND id_lang = $id_lang AND id_shop = $id_shop "
			);
		}
	}

	public static function delete_post_meta( $id, $meta_key ) {
		$id_lang = (int) self::$id_lang_global;
		$id_shop = (int) self::$id_shop_global;
		return Db::getInstance()->delete( 'crazy_options', "id = $id AND id_lang = $id_lang AND id_shop = $id_shop AND option_name = '$meta_key'" );
		return Db::getInstance()->delete( 'crazy_options', "id = $id AND option_name = '$meta_key'" );
	}

	public static function update_option( $option_name, $option_value = '' ) {
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$result     = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$option_name'" );
		if ( is_array( $option_value ) || is_object( $option_value ) ) {
			$option_value = \Tools::jsonEncode( $option_value );
		}

		$option_name  = pSQL( $option_name );
		$option_value = pSQL( $option_value );
		if ( $result === false ) {
			Db::getInstance()->insert(
				'crazy_options',
				array(
					'option_name'  => $option_name,
					'option_value' => $option_value,
				)
			);
		} else {
			Db::getInstance()->update( 'crazy_options', array( 'option_value' => $option_value ), "`option_name` = '$option_name'" );
		}
	}


	public static function delete_option( $option_name, $option_value = '' ) {
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$id         = self::$id_content_global;
		$result     = Db::getInstance()->getValue( "DELETE FROM $table_name WHERE option_name = '$option_name' AND id='$id' " );
	}

	public static function delete_option_without_id( $option_name, $option_value = '' ) {
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$result     = Db::getInstance()->getValue( "DELETE FROM $table_name WHERE option_name = '$option_name'  " );
	}

	public static function get_lience_expired_date() {
		$ce_licence = self::get_option( 'ce_licence', 'false' );
		if ( $ce_licence != 'false' ) {
			if($ce_licence == "invalid"){
				self::add_lience();
			}else{
				$status = self::get_lience( $ce_licence );
			}
		}else{
			self::add_lience();
		}
	}

	public static function get_lience( $license_data ) {
		$ce_licence_date = self::get_option( 'ce_licence_date', '' );
		$first_install   = false;
		if ( $ce_licence_date == '' ) {
			$today           = date( 'Y-m-d' );
			$ce_licence_date = self::update_option( 'ce_licence_date', $today );
			$first_install   = true;
		}
		if ( $ce_licence_date != '' ) {
			$today = date( 'Y-m-d' );
			if ( ( strtotime( $today ) == strtotime( $ce_licence_date ) ) && ! $first_install ) {
				return false;
			}
		}
		$array = array(
			'edd_action' => 'activate_license',
			'license'    => $license_data,
			'item_id'    => self::$item_id, // The ID of the item in EDD
			'url'        => _PS_BASE_URL_SSL_,
		);
		$url   = self::$licence_url . '?' . http_build_query( $array );
		if ( $license_data ) {
			$response         = self::wp_remote_get(
				$url,
				array(
					'timeout' => 15,
					'headers' => '',
					'header'  => false,
					'json'    => true,
				)
			);
			   $responsearray = Tools::jsonDecode( $response, true );
			if ( $responsearray['success'] == 'true' && $responsearray['license'] == 'valid' ) {
				self::update_option( self::$licence, $license_data );
				self::update_option( self::$licence_status, $responsearray['license'] );
				self::update_option( self::$licence_data, $response );
				self::update_option( self::$licence_ex, $responsearray['expires'] );
				$ce_licence_date = self::update_option( 'ce_licence_date', $today );
				return 'success';
			} else {
				self::update_option( self::$licence, $responsearray['license'] );
				self::update_option( self::$licence_status, $responsearray['license'] );
				self::update_option( self::$licence_data, $response );
				self::update_option( self::$licence_ex, $responsearray['license'] );
				$ce_licence_date = self::update_option( 'ce_licence_date', $today );
				return 'false';
			}
		} else {
			self::update_option( self::$licence, '' );
			self::update_option( self::$licence_status, 'false' );
			self::update_option( self::$licence_data, 'false' );
			self::update_option( self::$licence_ex, 'false' );
			return 'false';
		}
	}

	public static function add_lience(){
		self::update_option( self::$licence, self::$licnce . self::$licenc . CRAZY_UNIQUE_ID );
	}

	public static function get_title() {
		$id_lang = (int) self::$id_lang_global;
		$context = \Context::getContext();
		$shop_id = $context->shop->id;
		$id      = self::$id_content_primary_global;

		if ( $id != '' ) {
			$query = 'SELECT * FROM ' . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $id . "' AND id_lang='" . $id_lang . "' AND id_shop='" . $shop_id . "'";
			$post  = \Db::getInstance()->executeS( $query );
			if ( ! isset( $post[0] ) ) {
				return 'New Page';
			} else {
				return $post[0]['title'];
			}
		}
		return 'New Page';

	}

	public static function get_post_id() {
		return self::$id_content_global;
	}

	public static function wp_localize_script( $handle, $varName, $value, $toFooter = false ) {
		if ( $toFooter != true ) {
			self::$local_scripts[ $varName ] = $value;
		} else {
			self::$local_scripts_footer[ $varName ] = $value;
		}
	}

	public static function wp_enqueue_scripts() {
		self::do_action( 'wp_enqueue_scripts' );
	}

	public static function wp_print_styles() {
		foreach ( self::forced_predefined_styles() as $style_src ) {
			echo '<link rel="stylesheet" href="' . $style_src . '" type="text/css" />
            ';
		}
		foreach ( self::$admin_styles as $key => $style_src ) {
			if ( $style_src != null && $style_src != '' ) {
				echo '<link rel="stylesheet" href="' . $style_src . '" type="text/css" />
            ';
			} else {
				self::$blank_styles[] = $key;
			}
		}
	}

	public static function check_extended_frontcontroller($controller){
		$id_lang        = \Tools::getValue('id_lang', self::$id_lang_global);
		$table_name  	= _DB_PREFIX_ . 'crazy_extended_modules';
		$havetable    	= Db::getInstance()->executeS( "SHOW TABLES LIKE '{$table_name}'" );
		if(empty($havetable)){
			return false;
		}
		$sql = new \DbQuery();
		$sql->select('*');
		$sql->from('crazy_extended_modules', 'c');
		$sql->where('c.front_controller_name = "' . $controller . '"');
		$result = \Db::getInstance()->executeS($sql);
		if(isset($result) && !empty($result)){
			return $result[0];
		}
		return false;
	}

	public static function setPreviewForHook( $hook ) {
		$context = \Context::getContext();
		$id_lang = self::$id_lang_global;
		$preview = '';
		if ( $hook == 'displayLeftColumn' ) {
			$table_name  = _DB_PREFIX_ . 'category';
			$results     = Db::getInstance()->executeS( "SELECT * FROM $table_name WHERE active = 1 ORDER BY id_category DESC " );
			$id_category = $results[0]['id_category'];
			$preview     = $context->link->getCategoryLink( $id_category, null, $id_lang );
		} elseif ( $hook == 'displayShoppingCart' ) {
			$preview = $context->link->getPageLink( 'cart', null, $id_lang, null, false, null, true );
		} elseif ( $hook == 'extended' ) {
			$extmodname = Tools::getValue('mod_name');
			$fr_controller = Tools::getValue('fr_controller');
			$id = Tools::getValue('id');
			$id_lang = Tools::getValue('id_lang');
			$preview = Context::getContext()->link->getModuleLink($extmodname, $fr_controller, array('id_post' => $id,'hook' => 'extended','id_lang' => $id_lang));
		}  else {
			$preview = self::get_base_url();
		}

		return $preview;
	}
	public static function forced_predefined_scripts() {
		$wpColorPickerL10n = array(
			'clear'            => 'Clear',
			'clearAriaLabel'   => 'Clear color',
			'defaultString'    => 'Default',
			'defaultAriaLabel' => 'Select default color',
			'pick'             => 'Select Color',
			'defaultLabel'     => 'Color value',
		);

		?>
<script>
var wpColorPickerL10n = '<?php json_encode( $wpColorPickerL10n ); ?>'
var baseAdminDir = '<?php echo '//' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . basename( _PS_ADMIN_DIR_ ) . '/'; ?>'
</script>
<?php
		return array(
			CRAZY_ASSETS_URL . 'lib/jquery-ui/jquery-ui.min.js',
			CRAZY_ASSETS_URL . 'js/underscore-min.js',
			CRAZY_ASSETS_URL . 'lib/backbone/backbone.js',
			CRAZY_ASSETS_URL . 'js/iris.min.js',
			CRAZY_ASSETS_URL . 'lib/wp-color-picker/wp-color-picker.min.js',
		);
	}

	public static function forced_predefined_styles() {
		return array(
			CRAZY_ASSETS_URL . 'lib/wp-color-picker/wp-color-picker.min.css?v=1.0.7',
			CRAZY_ASSETS_URL . 'css/jqueryui.css?v=1.0',
		);
	}

	public static function wp_print_head_scripts() {
		foreach ( self::forced_predefined_scripts() as $script_src ) {
			echo '<script src="' . $script_src . '"></script>';
		}
		foreach ( self::$admin_scripts as $key => $script_src ) {
			if ( $script_src != null && $script_src != '' ) {
				echo '<script src="' . $script_src . '"></script>';
			} else {
				self::$blank_scripts[] = $key;
			}
		}
		// self::header_local_scripts();
	}

	public static function wp_print_footer_scripts() {
		foreach ( self::$admin_scripts_foot as $key => $script_src ) {
			if ( $script_src != null && $script_src != '' ) {
				echo '<script src="' . $script_src . '"></script>
         ';
			} else {
				self::$blank_scripts[] = $key;
			}
		}
		self::footer_local_scripts();
	}


	public static function get_transient( $option_name ) {
		$main_opt_name = "_trns_{$option_name}";
		$return        = false;
		$table_name    = _DB_PREFIX_ . 'crazy_options';
		$result        = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$main_opt_name'" );
		$return_temp   = (array) json_decode( stripslashes( $result ) );
		if ( $result && $return_temp != null ) {
			if ( $return_temp['reset_time'] >= time() ) {
				$return = $return_temp['data'];
			}
		}
		return $return;
	}

	public static function footer_local_scripts() {
		$allLocalScripts = "<script type='text/javascript'>";
		foreach ( self::$local_scripts_footer as $var_name => $scripts_each ) {
			if ( is_array( $scripts_each ) ) {
				$value = json_encode( $scripts_each );
			} else {
				$value = '"' . $scripts_each . '"';
			}
			$allLocalScripts .= 'var ' . $var_name . '= ' . $value . ';';
		}
		$allLocalScripts .= '</script>';
		echo $allLocalScripts;
	}

	public static function header_local_scripts() {
		$allLocalScripts = "<script type='text/javascript'>";
		foreach ( self::$local_scripts as $var_name => $scripts_each ) {
			if ( is_array( $scripts_each ) ) {
				$value = json_encode( $scripts_each );
			} else {
				$value = '"' . $scripts_each . '"';
			}

			$allLocalScripts .= 'var ' . $var_name . '= ' . $value . ';';
		}
		$allLocalScripts .= '</script>';
		echo $allLocalScripts;
	}

	public static function esc_attr__( $string, $text_domain = 'elementor' ) {
		echo Tools::safeOutput( $string );
	}

	public static function crazy_parse_args( $args, $defaults = array() ) {
		if ( is_object( $args ) ) {
			$parsed_args = get_object_vars( $args );
		} elseif ( is_array( $args ) ) {
			$parsed_args =& $args;
		} else {
			wp_parse_str( $args, $parsed_args );
		}
	 
		if ( is_array( $defaults ) && $defaults ) {
			return array_merge( $defaults, $parsed_args );
		}
		return $parsed_args;
	}

	public static function set_transient( $option_name, $option_value, $reset_time = 1200 ) {
		$main_opt_name                 = "_trns_{$option_name}";
		$serialized_data               = array();
		$serialized_data['reset_time'] = time() + $reset_time;
		$serialized_data['data']       = $option_value;
		$serialized_data               = addslashes( json_encode( $serialized_data ) );
		$table_name                    = _DB_PREFIX_ . 'crazy_options';
		$result                        = Db::getInstance()->getValue( "SELECT option_value FROM $table_name WHERE option_name = '$main_opt_name'" );
		$result_temp                   = (array) json_decode( $result );
		$option_name                   = pSQL( $option_name );
		$serialized_data               = pSQL( $serialized_data );
		$main_opt_name                 = pSQL( $main_opt_name );
		if ( $result && isset( $result_temp['reset_time'] ) && $result_temp['reset_time'] < time() ) {
			Db::getInstance()->update( 'crazy_options', array( 'option_value' => $serialized_data ), "`option_name` = '$option_name'" );
		} elseif ( ! $result ) {
			Db::getInstance()->insert(
				'crazy_options',
				array(
					'option_name'  => $main_opt_name,
					'option_value' => $serialized_data,
				)
			);
		}
	}

	public static function add_query_arg( ...$args ) {
		
		if ( is_array( $args[0] ) ) {
			if ( count( $args ) < 2 || false === $args[1] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[1];
			}
		} else {
			if ( count( $args ) < 3 || false === $args[2] ) {
				$uri = $_SERVER['REQUEST_URI'];
			} else {
				$uri = $args[2];
			}
		}
	
		$frag = strstr( $uri, '#' );
		
		if ( $frag ) {
			$uri = substr( $uri, 0, -strlen( $frag ) );
		} else {
			$frag = '';
		}
	
		if ( 0 === stripos( $uri, 'http://' ) ) {
			$protocol = 'http://';
			$uri      = substr( $uri, 7 );
		} elseif ( 0 === stripos( $uri, 'https://' ) ) {
			$protocol = 'https://';
			$uri      = substr( $uri, 8 );
		} else {
			$protocol = '';
		}
	
		if ( strpos( $uri, '?' ) !== false ) {
			list( $base, $query ) = explode( '?', $uri, 2 );
			$base                .= '?';
		} elseif ( $protocol || strpos( $uri, '=' ) === false ) {
			$base  = $uri . '?';
			$query = '';
		} else {
			$base  = '';
			$query = $uri;
		}
	
		parse_str( $query, $qs );
		// $qs = urlencode_deep( $qs ); // This re-URL-encodes things that were already in the query string.
		
		if ( is_array( $args[0] ) ) {
			foreach ( $args[0] as $k => $v ) {
				$qs[ $k ] = $v;
			}
		} else {
			$qs[ $args[0] ] = $args[1];
		}
	
		foreach ( $qs as $k => $v ) {
			if ( false === $v ) {
				unset( $qs[ $k ] );
			}
		}
	
		$ret = http_build_query( $qs );	
		$ret = trim( $ret, '?' );
		$ret = preg_replace( '#=(&|$)#', '$1', $ret );
		$ret = $protocol . $base . $ret . $frag;
		$ret = rtrim( $ret, '?' );
		$ret = str_replace( '?#', '#', $ret );
		return $ret;
	}

	public static function EditorAlternateUrl( $context ) {
		$alternativeLangs = array();
		$languages        = \Language::getLanguages( true, $context->shop->id );
		if ( $languages < 2 ) {
			// No need to display alternative lang if there is only one enabled
			return $alternativeLangs;
		}
		foreach ( $languages as $lang ) {
			$alternativeLangs[ $lang['language_code'] ] = $context->link->getLanguageLink( $lang['id_lang'] );
		}
		return $alternativeLangs;
	}

	public static function get_allowed_controllers(){
		return array('AdminCrazySetting','AdminCrazyPrdlayouts','AdminCrazyFonts','AdminCrazyPseIcon','AdminCrazyExtendedmodules','AdminCrazyContent');
	}

	public static function crazy_promo() {
		$controller     = Tools::getValue('controller');
		$ctrlers = self::get_allowed_controllers();
		$closestr = '<div class="col-lg-1">
			<div class="update-content-area crazy-promo close-promo">
				<a href="javascript:void(0)" id="close_ad">Close Ad</a>
			</div>
		</div>';
		$classtr = 'col-lg-11';
		if(in_array($controller, $ctrlers)){
			$closestr = '';
			$classtr = 'col-lg-12';
		}else{
			$day = PrestaHelper::get_option( 'ce_ad_closed' );
			$today           = date( 'Y-m-d' );
			$expirydate = date_create($day);
			$today = date_create($today);
			$diff=date_diff($expirydate,$today);
			if($diff->d < 3){
				return;
			}
		}
		$ce_new_changelog = self::get_option( 'ce_new_changelog', '' );
				if($ce_new_changelog != ''){
			$ce_new_changelog = Tools::jsonDecode( $ce_new_changelog, true );
			if(isset($ce_new_changelog['top'])){
				$url = self::getAjaxUrl();
		?>
<script>
var ajax_update = '<?php echo $url; ?>';
</script>
<div class="row promo-area">
    <div class="<?php echo $classtr; ?>">
        <div class="update-content-area crazy-promo">
            <a href="<?php echo $ce_new_changelog['top']['url']; ?>"> <img
                    src="<?php echo $ce_new_changelog['top']['image_url']; ?>">
            </a>
        </div>
    </div>
    <?php echo $closestr; ?>
</div>
<?php 
			}
		}
	}
}