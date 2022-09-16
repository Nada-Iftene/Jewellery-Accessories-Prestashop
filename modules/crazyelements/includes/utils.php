<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Utils {

	public static function is_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX ;
	}

	public static function is_script_debug() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
	}
    
	public static function get_edit_link( $post_id = 0 ) {
		_deprecated_function( __METHOD__, '2.6.0', 'Plugin::$instance->documents->get( $post_id )->get_edit_url()' );
		if ( ! $post_id ) {
			$post_id = PrestaHelper::$id_content_global;//get_the_ID();
		}
		$edit_link = '';
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$edit_link = $document->get_edit_url();
		}
		$edit_link = apply_filters_deprecated( 'elementor/utils/get_edit_link', [ $edit_link, $post_id ], '2.0.0', 'elementor/document/urls/edit' );
		return $edit_link;
	}

	public static function get_pro_link( $link ) {
        return 'put link';
		static $theme_name = false;
		if ( ! $theme_name ) {
			$theme_obj = wp_get_theme();
			if ( $theme_obj->parent() ) {
				$theme_name = $theme_obj->parent()->get( 'Name' );
			} else {
				$theme_name = $theme_obj->get( 'Name' );
			}
			$theme_name = sanitize_key( $theme_name );
		}
		$link = add_query_arg( 'utm_term', $theme_name, $link );
		return $link;
	}

	public static function get_preview_url( $post_id ) {
		_deprecated_function( __METHOD__, '2.0.0', 'Plugin::$instance->documents->get( $post_id )->get_preview_url()' );
		$url = Plugin::$instance->documents->get( $post_id )->get_preview_url();
		$url = apply_filters_deprecated( 'elementor/utils/preview_url', [ $url, $post_id ], '2.0.0', 'elementor/document/urls/preview' );
		return $url;
	}

	public static function get_wp_preview_url( $post_id ) {
		_deprecated_function( __METHOD__, '2.0.0', 'Plugin::$instance->documents->get( $post_id )->get_wp_preview_url()' );
		$wp_preview_url = Plugin::$instance->documents->get( $post_id )->get_wp_preview_url();
		$wp_preview_url = apply_filters_deprecated( 'elementor/utils/wp_preview_url', [ $wp_preview_url, $post_id ], '2.0.0', 'elementor/document/urls/wp_preview' );
		return $wp_preview_url;
	}

	public static function replace_urls( $from, $to ) {
		$from = trim( $from );
		$to = trim( $to );
		if ( $from === $to ) {
			throw new \Exception( PrestaHelper::__( 'The `from` and `to` URL\'s must be different', 'elementor' ) );
		}
		$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
		if ( ! $is_valid_urls ) {
			throw new \Exception( PrestaHelper::__( 'The `from` and `to` URL\'s must be valid URL\'s', 'elementor' ) );
		}
		global $wpdb;
		$rows_affected = $wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
			"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" ); // meta_value LIKE '[%' are json formatted
		if ( false === $rows_affected ) {
			throw new \Exception( PrestaHelper::__( 'An error occurred', 'elementor' ) );
		}
		Plugin::$instance->files_manager->clear_cache();
		return sprintf(
			/* translators: %d: Number of rows */
			_n( '%d row affected.', '%d rows affected.', $rows_affected, 'elementor' ),
			$rows_affected
		);
	}

	public static function get_exit_to_dashboard_url( $post_id ) {
		_deprecated_function( __METHOD__, '2.0.0', 'Plugin::$instance->documents->get( $post_id )->get_exit_to_dashboard_url()' );
		return Plugin::$instance->documents->get( $post_id )->get_exit_to_dashboard_url();
	}

	public static function is_post_support( $post_id = 0 ) {
		$post_type = get_post_type( $post_id );
		$is_supported = self::is_post_type_support( $post_type );
		$is_supported = PrestaHelper::apply_filters( 'elementor/utils/is_post_type_support', $is_supported, $post_id, $post_type );
		$is_supported = PrestaHelper::apply_filters( 'elementor/utils/is_post_support', $is_supported, $post_id, $post_type );
		return $is_supported;
	}

	public static function is_post_type_support( $post_type ) {
		return true;
	}

	public static function get_placeholder_image_src() {
		$placeholder_image = CRAZY_ASSETS_URL . 'images/placeholder.png';
		$placeholder_image = PrestaHelper::apply_filters( 'elementor/utils/get_placeholder_image_src', $placeholder_image );
		return $placeholder_image;
	}

	public static function generate_random_string() {
		return dechex( rand() );
	}

	public static function do_not_cache() {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		if ( ! defined( 'DONOTCACHEDB' ) ) {
			define( 'DONOTCACHEDB', true );
		}
		if ( ! defined( 'DONOTMINIFY' ) ) {
			define( 'DONOTMINIFY', true );
		}
		if ( ! defined( 'DONOTCDN' ) ) {
			define( 'DONOTCDN', true );
		}
		if ( ! defined( 'DONOTCACHCEOBJECT' ) ) {
			define( 'DONOTCACHCEOBJECT', true );
		}
		nocache_headers();
	}

	public static function get_timezone_string() {
		$current_offset = (float) PrestaHelper::get_option( 'gmt_offset' );
		$timezone_string = PrestaHelper::get_option( 'timezone_string' );
		// Create a UTC+- zone if no timezone string exists.
		if ( empty( $timezone_string ) ) {
			if ( $current_offset < 0 ) {
				$timezone_string = 'UTC' . $current_offset;
			} else {
				$timezone_string = 'UTC+' . $current_offset;
			}
		}
		return $timezone_string;
	}

	public static function get_last_edited( $post_id ) {
		_deprecated_function( __METHOD__, '2.0.0', 'Plugin::$instance->documents->get( $post_id )->get_last_edited()' );
		$document = Plugin::$instance->documents->get( $post_id );
		return $document->get_last_edited();
	}

	public static function get_create_new_post_url( $post_type = 'page' ) {
		$new_post_url = add_query_arg( [
			'action' => 'elementor_new_post',
			'post_type' => $post_type,
		], PrestaHelper::admin_url( 'edit.php' ) );
		$new_post_url = add_query_arg( '_wpnonce', wp_create_nonce( 'elementor_action_new_post' ), $new_post_url );
		return $new_post_url;
	}

	
	public static function get_post_autosave( $post_id, $type ) {
		$context = \Context::getContext();
        $shop_id = $context->shop->id;
        $id_lang = \Tools::getValue('id_lang',$context->language->id);
        $table_name = _DB_PREFIX_ . 'crazy_revision';
        $results = \Db::getInstance()->executeS("SELECT * FROM $table_name  WHERE   id_lang =$id_lang AND id_shop =  $shop_id AND id_post=$post_id AND type='". $type."' order by id_crazy_revision  DESC LIMIT 1" );
        if (empty($results)) { 
        	$revision = false;
		}else{
            $revision = $results;
        }
		return $revision;
	}
	public static function is_cpt_custom_templates_supported() {
		require_once ABSPATH . '/wp-admin/includes/theme.php';
		return method_exists( wp_get_theme(), 'get_post_templates' );
	}
	public static function array_inject( $array, $key, $insert ) {
		$length = array_search( $key, array_keys( $array ), true ) + 1;
		return array_slice( $array, 0, $length, true ) +
			$insert +
			array_slice( $array, $length, null, true );
	}

	public static function render_html_attributes( array $attributes ) {
		$rendered_attributes = [];
		foreach ( $attributes as $attribute_key => $attribute_values ) {
			if ( is_array( $attribute_values ) ) {
				$attribute_values = implode( ' ', $attribute_values );
			}
			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, PrestaHelper::esc_attr( $attribute_values ) );
		}
		return implode( ' ', $rendered_attributes );
	}

	public static function get_meta_viewport( $context = '' ) {
		$meta_tag = '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />';
		return PrestaHelper::apply_filters( 'elementor/template/viewport_tag', $meta_tag, $context );
	}


	public static function print_js_config( $handle, $js_var, $config ) {
		foreach ( (array) $config as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}
			$config[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}
		$config = json_encode( $config );
		if ( PrestaHelper::get_option( 'elementor_editor_break_lines' ) ) {
			$config = str_replace( '}},"', '}},' . PHP_EOL . '"', $config );
		}
		$script_data = 'var ' . $js_var . ' = ' . $config . ';';
		echo "<script type='text/javascript'>" . $script_data."</script>";
	}
}