<?php
namespace CrazyElements;
use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;
if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Manufacturers extends Widget_Base {
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
		return 'menufacturers';
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
		return PrestaHelper::__( 'Manufacturers List', 'elementor' );
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
		return 'ceicon-manufacturers-widget';
	}
	public function get_categories() {
		return array( 'products_free' );
	}
	private function getPsImgSizesOption() {
		$db        = \Db::getInstance();
		$tablename = _DB_PREFIX_ . 'image_type';
		$sizes     = $db->executeS( "SELECT name FROM {$tablename} ORDER BY name ASC" );
		$options   = array( 'Default' => '' );
		if ( ! empty( $sizes ) ) {
			foreach ( $sizes as $size ) {
				$options[ $size['name'] ] = $size['name'];
			}
		}
		return $options;
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
		$this->add_control(
			'title',
			array(
				'label'       => PrestaHelper::__( 'Title', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);
		$this->add_control(
			'img_size',
			array(
				'label'   => PrestaHelper::__( 'Image Size', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->getPsImgSizesOption(),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'carousal_settings_man',
			array(
				'label' => PrestaHelper::__( 'Carousel Settings', 'elementor' ),
			)
		);

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => PrestaHelper::__( 'Slides to Show', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'' => PrestaHelper::__( 'Default', 'elementor' ),
				) + $slides_to_show,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'              => PrestaHelper::__( 'Slides to Scroll', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => PrestaHelper::__( 'Set how many slides are scrolled per swipe.', 'elementor' ),
				'options'            => array(
					'' => PrestaHelper::__( 'Default', 'elementor' ),
				) + $slides_to_show,
				'condition'          => array(
					'slides_to_show!' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => PrestaHelper::__( 'Navigation', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => PrestaHelper::__( 'Arrows and Dots', 'elementor' ),
					'arrows' => PrestaHelper::__( 'Arrows', 'elementor' ),
					'dots'   => PrestaHelper::__( 'Dots', 'elementor' ),
					'none'   => PrestaHelper::__( 'None', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => PrestaHelper::__( 'Pause on Hover', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => PrestaHelper::__( 'Yes', 'elementor' ),
					'no'  => PrestaHelper::__( 'No', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => PrestaHelper::__( 'Autoplay', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => PrestaHelper::__( 'Yes', 'elementor' ),
					'no'  => PrestaHelper::__( 'No', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => PrestaHelper::__( 'Autoplay Speed', 'elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'              => PrestaHelper::__( 'Infinite Loop', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => PrestaHelper::__( 'Yes', 'elementor' ),
					'no'  => PrestaHelper::__( 'No', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'              => PrestaHelper::__( 'Effect', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => PrestaHelper::__( 'Slide', 'elementor' ),
					'fade'  => PrestaHelper::__( 'Fade', 'elementor' ),
				),
				'condition'          => array(
					'slides_to_show' => '1',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed1',
			array(
				'label'              => PrestaHelper::__( 'Animation Speed', 'elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'              => PrestaHelper::__( 'Direction', 'elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'ltr',
				'options'            => array(
					'ltr' => PrestaHelper::__( 'Left', 'elementor' ),
					'rtl' => PrestaHelper::__( 'Right', 'elementor' ),
				),
				'frontend_available' => true,
			)
		);

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
		$settings      = $this->get_settings_for_display();
		$title         = $settings['title'];
		$man_img_size  = $settings['img_size'];
		$context       = \Context::getContext();
		$manufacturers = \Manufacturer::getManufacturers( false, $context->language->id, true );
		$context->smarty->assign(
			array(
				'title'         => $title,
				'manufacturers' => $manufacturers,
				'man_img_size'  => $man_img_size,
				'img_manu_dir'  => _PS_IMG_ . 'm/',
				'type'          => 'suppliers',
			)
		);

		$output = $context->smarty->fetch(
			CRAZY_PATH . 'views/templates/front/vc_product_manufacturers.tpl'
		);
		echo $output;
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