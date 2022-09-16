<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;
use CrazyElements\Includes\Widgets\Traits\Product_Trait;


if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Featured_Products extends Widget_Base {

	use Product_Trait;


	/**
	 * Get widget name.
	 *
	 * Retrieve accordion widget name.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'featured_products';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve accordion widget title.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return PrestaHelper::__( 'Featured Products', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve accordion widget icon.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ceicon-product-widget';
	}

	public function get_categories() {
		return array( 'products_free' );
	}

	/**
	 * Register accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => PrestaHelper::__( 'General', 'elementor' ),
			)
		);

		$this->general_controls(array(), true, 'random');
		
		$this->add_control(
			'display_type',
			array(
				'label'   => PrestaHelper::__( 'Display Type', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'grid'    => PrestaHelper::__( 'Grid View', 'elementor' ),
					'sidebar' => PrestaHelper::__( 'Sidebar View', 'elementor' ),
				),
				'default' => 'grid',
				'separator' => 'before',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'default',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'features',
			array(
				'label'     => PrestaHelper::__( 'Features', 'elementor' ),
				'condition' => array(
					'layout' => 'style_one',
				),
			)
		);

		$this->register_feature_controls();
		$this->end_controls_section();

		$this->start_controls_section(
			'product_section',
			array(
				'label'      => PrestaHelper::__( 'Product Section', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE
			)
		);

		$this->register_section_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'product_card',
			array(
				'label'      => PrestaHelper::__( 'Product Card', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE
			)
		);

		$this->register_card_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_product_image',
			array(
				'label' => PrestaHelper::__( 'Product Image', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			)
		);

		$this->register_image_styles();

		$this->end_controls_section();

		$this->start_controls_section(
			'highlighted_section_style',
			array(
				'label'      => PrestaHelper::__( 'Highlighted Section', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'default',
						)
					),
				),
			)
		);

		$this->register_highlighted_section_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'title_typo',
			array(
				'label'      => PrestaHelper::__( 'Title', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_title_styles();
		
		$this->end_controls_section();

		$this->start_controls_section(
			'product_flag',
			array(
				'label'      => PrestaHelper::__( 'Flag', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE
			)
		);

		$this->register_flag_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'atc_btn',
			array(
				'label'      => PrestaHelper::__( 'Cart Button', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
					),
				),
			)
		);

		$this->register_add_cart_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'short_desc_typo',
			array(
				'label'      => PrestaHelper::__( 'Short Description', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
						array(
							'name'     => 'ed_short_desc',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->register_short_desc_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'desc_typo',
			array(
				'label'      => PrestaHelper::__( 'Description', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
						array(
							'name'     => 'ed_desc',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->register_description_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'manufacturer_typo',
			array(
				'label'      => PrestaHelper::__( 'Manufacturer', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
						array(
							'name'     => 'ed_manufacture',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->register_manufecturer_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'Supplier_typo',
			array(
				'label'      => PrestaHelper::__( 'Supplier', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
						array(
							'name'     => 'ed_supplier',
							'operator' => '==',
							'value'    => 'yes',
						),

					),
				),
			)
		);
		$this->register_supplier_style();
		$this->end_controls_section();

		$this->start_controls_section(
			'category_typo',
			array(
				'label'      => PrestaHelper::__( 'Category', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'style_one',
						),
						array(
							'name'     => 'ed_catagories',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->register_category_style();

		$this->end_controls_section();

		$this->start_controls_section(
			'price_typo',
			array(
				'label'      => PrestaHelper::__( 'Price', 'elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_price_style();

		$this->end_controls_section();
	}

	/**
	 * Render accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function render() {

		if ( PrestaHelper::is_admin() ) {
			return;
		}

		// controls
		$settings       = $this->get_settings_for_display();
		$this->generate_controls_data($settings);
		$orderby       = $settings['orderby'];
		$order         = $settings['order'];
		$limit         = $settings['per_page'];
		$random_query  = $settings['random'];
		$display_type = $settings['display_type'];

		// common vars
		$cache_products = array();
		$this->current_context = \Context::getContext();
		$id_lang = $this->current_context->language->id;
		$id_shop     = $this->current_context->shop->id;

		// load assets
		$this->load_assets();

		// where query params
		$cache_products = $this->products_by_category($id_lang, '', $orderby, $order, $limit, $random_query);

		if ( ! $cache_products ) {
			return false;
		}

		// Query and get data for template
		$products_for_template = $this->get_products_for_template($id_lang, $cache_products);

		if ( $this->layout == 'default' ) {
			$this->show_default_skin($products_for_template, 'featured-products', $display_type);
		} else {
			$from_cat_addon = false;
			$this->show_crazy_skins($products_for_template, 'featured-products');
		}
	}

	/**
	 * Render accordion widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function _content_template() {
	}
}