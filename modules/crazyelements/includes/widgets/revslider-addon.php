<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}


class Widget_Revslider_Addon extends Widget_Base {
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
		return 'revsliderprestashop_sixaddons';
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
		return PrestaHelper::__( 'Revolution Slider 6 Sliders', 'elementor' );
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
		return 'ceicon-revslider_widget';
	}
	public function get_categories() {
		return array( 'crazy_addons_free' );
	}

	public function get_revsliders() {
		if ( \Module::isInstalled( 'revsliderprestashop' ) && \Module::isEnabled( 'revsliderprestashop' ) ) {

			$db         = \Db::getInstance();
			$getsliderq = 'SELECT * FROM ' . _DB_PREFIX_ . 'revslider_sliders';
			$sliders    = $db->executeS( $getsliderq );

			$slider_lists = array();

			if ( isset( $sliders ) && is_array( $sliders ) ) {
				foreach ( $sliders as $slider ) {
					if ( $slider['type'] != 'template' ) {
						$slider_lists[ $slider['alias'] ] = $slider['title'];
					}
				}
			}

			return $slider_lists;
		}
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
				'label' => PrestaHelper::__( 'Modules', 'elementor' ),
			)
		);
		$this->add_control(
			'select_slider',
			array(
				'label'   => PrestaHelper::__( 'Select Slider', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_revsliders(),
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

		if ( \Module::isInstalled( 'revsliderprestashop' ) && \Module::isEnabled( 'revsliderprestashop' ) ) {
			if ( class_exists( 'RevSliderFront' ) ) {
				$settings      = $this->get_settings_for_display();
				$select_slider = $settings['select_slider'];

				$rev_slider_front = new \RevSliderFront();
				\RevLoader::loadAllAddons();
				$content_sliders = '';

				ob_start();
				\RevLoader::do_action( 'wp_head' );
				\RevLoader::do_action( 'wp_enqueue_scripts' );
				\RevLoader::rev_front_print_styles();

				\RevLoader::rev_front_print_head_scripts();

				\RevLoader::do_action( 'revslider_slider_init_by_data_post', array() );
				$output = new \RevSliderOutput();

				$output->add_slider_to_stage( $select_slider );
				\RevLoader::do_action( 'wp_footer' );
				\RevLoader::rev_front_print_footer_scripts();

				$content_sliders = ob_get_contents();

				ob_get_clean();
				echo $content_sliders;
			}
		}else{
			echo "<a href='https://classydevs.com/slider-revolution-prestashop/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=revslider&utm_term=revslider&utm_content=revslider' target='_blank'>Get Revolution Slider PrestaShop to Use This Addon to Show Awesome Slider.</a>";
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