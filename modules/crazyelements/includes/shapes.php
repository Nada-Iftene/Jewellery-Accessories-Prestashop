<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 
if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Shapes {

	const FILTER_EXCLUDE = 'exclude';
	const FILTER_INCLUDE = 'include';

	private static $shapes;

	public static function get_shapes( $shape = null ) {
		if ( null === self::$shapes ) {
			self::init_shapes();
		}
		if ( $shape ) {
			return isset( self::$shapes[ $shape ] ) ? self::$shapes[ $shape ] : null;
		}
		return self::$shapes;
	}

	public static function filter_shapes( $by, $filter = self::FILTER_INCLUDE ) {
		return array_filter(
			self::get_shapes(), function( $shape ) use ( $by, $filter ) {
				return self::FILTER_INCLUDE === $filter xor empty( $shape[ $by ] );
			}
		);
	}


	public static function get_shape_path( $shape, $is_negative = false ) {
		if ( isset( self::$shapes[ $shape ] ) && isset( self::$shapes[ $shape ]['path'] ) ) {
			return self::$shapes[ $shape ]['path'];
		}
		$file_name = $shape;
		if ( $is_negative ) {
			$file_name .= '-negative';
		}
		return CRAZY_PATH . 'assets/shapes/' . $file_name . '.svg';
	}

	private static function init_shapes() {
		$native_shapes = [
			'mountains' => [
				'title' => PrestaHelper::_x( 'Mountains', 'Shapes', 'elementor' ),
				'has_flip' => true,
			],
			'drops' => [
				'title' => PrestaHelper::_x( 'Drops', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
				'height_only' => true,
			],
			'clouds' => [
				'title' => PrestaHelper::_x( 'Clouds', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
				'height_only' => true,
			],
			'zigzag' => [
				'title' => PrestaHelper::_x( 'Zigzag', 'Shapes', 'elementor' ),
			],
			'pyramids' => [
				'title' => PrestaHelper::_x( 'Pyramids', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
			],
			'triangle' => [
				'title' => PrestaHelper::_x( 'Triangle', 'Shapes', 'elementor' ),
				'has_negative' => true,
			],
			'triangle-asymmetrical' => [
				'title' => PrestaHelper::_x( 'Triangle Asymmetrical', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
			],
			'tilt' => [
				'title' => PrestaHelper::_x( 'Tilt', 'Shapes', 'elementor' ),
				'has_flip' => true,
				'height_only' => true,
			],
			'opacity-tilt' => [
				'title' => PrestaHelper::_x( 'Tilt Opacity', 'Shapes', 'elementor' ),
				'has_flip' => true,
			],
			'opacity-fan' => [
				'title' => PrestaHelper::_x( 'Fan Opacity', 'Shapes', 'elementor' ),
			],
			'curve' => [
				'title' => PrestaHelper::_x( 'Curve', 'Shapes', 'elementor' ),
				'has_negative' => true,
			],
			'curve-asymmetrical' => [
				'title' => PrestaHelper::_x( 'Curve Asymmetrical', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
			],
			'waves' => [
				'title' => PrestaHelper::_x( 'Waves', 'Shapes', 'elementor' ),
				'has_negative' => true,
				'has_flip' => true,
			],
			'wave-brush' => [
				'title' => PrestaHelper::_x( 'Waves Brush', 'Shapes', 'elementor' ),
				'has_flip' => true,
			],
			'waves-pattern' => [
				'title' => PrestaHelper::_x( 'Waves Pattern', 'Shapes', 'elementor' ),
				'has_flip' => true,
			],
			'arrow' => [
				'title' => PrestaHelper::_x( 'Arrow', 'Shapes', 'elementor' ),
				'has_negative' => true,
			],
			'split' => [
				'title' => PrestaHelper::_x( 'Split', 'Shapes', 'elementor' ),
				'has_negative' => true,
			],
			'book' => [
				'title' => PrestaHelper::_x( 'Book', 'Shapes', 'elementor' ),
				'has_negative' => true,
			],
		];

		self::$shapes = array_merge( $native_shapes, self::get_additional_shapes() );
	}

	private static function get_additional_shapes() {
		static $additional_shapes = null;
		if ( null !== $additional_shapes ) {
			return $additional_shapes;
		}
		$additional_shapes = [];
		$additional_shapes = PrestaHelper::apply_filters( 'elementor/shapes/additional_shapes', $additional_shapes );
		return $additional_shapes;
	}

	public static function get_additional_shapes_for_config() {
		$additional_shapes = self::get_additional_shapes();
		if ( empty( $additional_shapes ) ) {
			return false;
		}
		$additional_shapes_config = [];
		foreach ( $additional_shapes as $shape_name => $shape_settings ) {
			if ( ! isset( $shape_settings['url'] ) ) {
				continue;
			}
			$additional_shapes_config[ $shape_name ] = $shape_settings['url'];
		}
		if ( empty( $additional_shapes_config ) ) {
			return false;
		}
		return $additional_shapes_config;
	}
}