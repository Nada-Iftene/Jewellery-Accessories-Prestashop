<?php
namespace CrazyElements\Core\DocumentTypes;

use CrazyElements\Controls_Manager;
use CrazyElements\Core\Base\Document;
use CrazyElements\Group_Control_Background;
use CrazyElements\Plugin;
use CrazyElements\Settings;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\Utils;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

class Post extends Document {

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = '';
		$properties['support_wp_page_templates'] = true;

		return $properties;
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 * @static
	 */
	protected static function get_editor_panel_categories() {
		return Utils::array_inject(
			parent::get_editor_panel_categories(),
			'theme-elements',
			[
				'theme-elements-single' => [
					'title' => PrestaHelper::__( 'Single', 'elementor' ),
					'active' => false,
				],
			]
		);
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'post';
	}

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_title() {
		return PrestaHelper::__( 'Page', 'elementor' );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_css_wrapper_selector() {
		$id = PrestaHelper::$id_content_global;
		return 'body.elementor-editor-active';
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		parent::_register_controls();
		self::register_hide_title_control( $this );
		self::register_post_fields_control( $this );
		self::register_style_controls( $this );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_hide_title_control( $document ) {
		$page_title_selector = '';

		if ( ! $page_title_selector ) {
			$page_title_selector = '{{WRAPPER}} h1.entry-title';
		}
		$page_title_selector .= ', {{WRAPPER}} .elementor-page-title';
		$selector=PrestaHelper::get_option('page_title',"false");
		if($selector!='false'){
			$page_title_selector .= ',{{WRAPPER}} '.$selector;
		}
		$document->start_injection( [
			'of' => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );
		// $document->add_control(
		// 	'hide_title',
		// 	[
		// 		'label' => PrestaHelper::__( 'Hide Title', 'elementor' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'default'=>"block",
		// 		'return_value'=>"none",
		// 		'selectors_dictionary' => [
		// 			'on' => 'none',
		// 			'off' => 'block',
		// 		],
		// 		'selectors' => [
		// 			 $page_title_selector => 'display: {{VALUE}}',
		// 		],
		// 	]
		// );
		$document->add_control(
			'hide_title',
			[
				'label' => PrestaHelper::__( 'Hide Title', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => PrestaHelper::__( 'Default', 'elementor' ),
					'off' => PrestaHelper::__( 'Off', 'elementor' ),
					'on' => PrestaHelper::__( 'On', 'elementor' ),
					
				],
				'selectors_dictionary' => [
					'' => 'block',
					'off' => 'block',
					'on' => 'none',
				],
				'selectors' => [
					 $page_title_selector => 'display: {{VALUE}}',
				],
			]
		);
		$document->end_injection();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_style_controls( $document ) {
		$document->start_controls_section(
			'section_page_style',
			[
				'label' => PrestaHelper::__( 'Body Style', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$document->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'background',
				'fields_options' => [
					'image' => [
						// Currently isn't supported.
						'dynamic' => [
							'active' => false,
						],
					],
				],
			]
		);

		$document->add_responsive_control(
			'padding',
			[
				'label' => PrestaHelper::__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$document->end_controls_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $document );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @param Document $document
	 */
	public static function register_post_fields_control( $document ) {
		$document->start_injection( [
			'of' => 'post_status',
			'fallback' => [
				'of' => 'post_title',
			],
		] );
		$document->end_injection();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	public function __construct( array $data = [] ) {
		if ( $data ) {
			$template = 'default';
			if ( empty( $template ) ) {
				$template = 'default';
			}
			$data['settings']['template'] = $template;
		}
		parent::__construct( $data );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();
		$config['type'] = 'page';
		return $config;
	}
}
