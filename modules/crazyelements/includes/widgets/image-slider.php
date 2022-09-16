<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class Widget_ImageSlider extends Widget_Base {



	public function get_name() {
		return 'image_slider';
	}

	public function get_title() {
		return PrestaHelper::__( 'Image Slider', 'elementor' );
	}

	public function get_icon() {
		return 'ceicon-slider-widget';
	}

	public function get_categories() {
		return array( 'crazy_addons' );
	}


	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => PrestaHelper::__( 'General', 'elementor' ),
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

	protected function _content_template() {
	}
}