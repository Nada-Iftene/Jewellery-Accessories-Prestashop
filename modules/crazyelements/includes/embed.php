<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Embed {

	private static $provider_match_masks = [
		'youtube' => '/^.*(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/',
		'vimeo' => '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
		'dailymotion' => '/^.*dailymotion.com\/(?:video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/',
	];

	private static $embed_patterns = [
		'youtube' => 'https://www.youtube{NO_COOKIE}.com/embed/{VIDEO_ID}?feature=oembed',
		'vimeo' => 'https://player.vimeo.com/video/{VIDEO_ID}#t={TIME}',
		'dailymotion' => 'https://dailymotion.com/embed/video/{VIDEO_ID}',
	];

	public static function get_video_properties( $video_url ) {
		foreach ( self::$provider_match_masks as $provider => $match_mask ) {
			preg_match( $match_mask, $video_url, $matches );
			if ( $matches ) {
				return [
					'provider' => $provider,
					'video_id' => $matches[1],
				];
			}
		}
		return null;
	}

	public static function get_embed_url( $video_url, array $embed_url_params = [], array $options = [] ) {
		$video_properties = self::get_video_properties( $video_url );

		if ( ! $video_properties ) {
			return null;
		}
		$embed_pattern = self::$embed_patterns[ $video_properties['provider'] ];
		$replacements = [
			'{VIDEO_ID}' => $video_properties['video_id'],
		];
		if ( 'youtube' === $video_properties['provider'] ) {
			$replacements['{NO_COOKIE}'] = ! empty( $options['privacy'] ) ? '-nocookie' : '';
		} elseif ( 'vimeo' === $video_properties['provider'] ) {
			$time_text = '';
			if ( ! empty( $options['start'] ) ) {
				$time_text = date( 'H\hi\ms\s', $options['start'] );
			}
			$replacements['{TIME}'] = $time_text;
		}
		$embed_pattern = str_replace( array_keys( $replacements ), $replacements, $embed_pattern );
		return self::pe_add_var( $embed_url_params, $embed_pattern );
	}


	public static function get_embed_html( $video_url, array $embed_url_params = [], array $options = [], array $frame_attributes = [] ) {
		$default_frame_attributes = [
			'class' => 'elementor-video-iframe',
			'allowfullscreen',
		];
		$video_embed_url = self::get_embed_url( $video_url, $embed_url_params, $options );
		if ( ! $video_embed_url ) {
			return null;
		}
		if ( ! $options['lazy_load'] ) {
			$default_frame_attributes['src'] = $video_embed_url;
		} else {
			$default_frame_attributes['data-lazy-load'] = $video_embed_url;
		}
		$frame_attributes = array_merge( $default_frame_attributes, $frame_attributes );
		$attributes_for_print = [];
		foreach ( $frame_attributes as $attribute_key => $attribute_value ) {
			$attribute_value = PrestaHelper::esc_attr( $attribute_value );
			if ( is_numeric( $attribute_key ) ) {
				$attributes_for_print[] = $attribute_value;
			} else {
				$attributes_for_print[] = sprintf( '%1$s="%2$s"', $attribute_key, $attribute_value );
			}
		}
		$attributes_for_print = implode( ' ', $attributes_for_print );
		$iframe_html = "<iframe $attributes_for_print></iframe>";
		return PrestaHelper::apply_filters( 'oembed_result', $iframe_html, $video_url, $frame_attributes );
	}

	public static function pe_add_var( ...$args ) {
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
	    if ( is_array( $args[0] ) ) {
	        foreach ( $args[0] as $k => $v ) {
	            $qs[ $k ] = $v;
	        }
	    } else {
	        $qs[ $args[0] ] = $args[1];
	    }
	    foreach ( $qs as $k => $v ) {
	        if ( $v === false ) {
	            unset( $qs[ $k ] );
	        }
	    }
	    $ret = http_build_query( $qs );
	    $ret = trim( $ret, '?' );
	    $ret = preg_replace( '#=(&|$)#', '$1', $ret );
	    $ret = $protocol . $base . $ret . $frag;
	    $ret = rtrim( $ret, '?' );
	    return $ret;
	}

}