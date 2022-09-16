<?php

namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if (!defined('_PS_VERSION_')) {
	exit; // Exit if accessed directly.
}

use CrazyElements\Modules\DynamicTags\Module as TagsModule;

class Widget_MainMenu extends Widget_Base
{




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
	public function get_name()
	{
		return 'main_menu';
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
	public function get_title()
	{
		return PrestaHelper::__('Main Menu', 'customaddons');
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
	public function get_icon()
	{
		return 'ceicon-main-menu';
	}

	public function get_categories()
	{
		return array('crazy_addons_free');
	}

	/**
	 * Register accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function _register_controls()
	{

		// ---------------------------------------------------------------------------- General Content

		$this->start_controls_section(
			'general',
			array(
				'label' => PrestaHelper::__('General', 'customaddons'),
			)
		);

		$this->add_control(
			'logo_img',
			array(
				'label'     => PrestaHelper::__('Logo', 'customaddons'),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'brand_name',
			array(
				'label'     => PrestaHelper::__('Brand Name', 'customaddons'),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'logo_img[url]' => '',
				),
			)
		);

		$this->add_control(
			'brand_name_color',
			array(
				'label'     => PrestaHelper::__('Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy_menu .brand-name' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'brand_name!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'brand_name_typography',
				'label'     => PrestaHelper::__('Typography', 'customaddons'),
				'selector'  => '{{WRAPPER}} .brand-name',
				'condition' => array(
					'brand_name!' => '',
				),
			)
		);

		$this->add_control(
			'brand_link',
			array(
				'label'         => PrestaHelper::__('Link', 'customaddons'),
				'type'          => Controls_Manager::URL,
				'placeholder'   => PrestaHelper::__('https://your-link.com', 'customaddons'),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				)
			)
		);

		$this->add_responsive_control(
			'logo_image_width',
			array(
				'label'      => PrestaHelper::__('Size', 'customaddons'),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array('desktop', 'tablet', 'mobile'),
				'size_units' => array('px', '%'),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} .navbar-brand>img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => PrestaHelper::__('Typography', 'customaddons'),
				'selector' => '{{WRAPPER}} .crazy-menu a',
			)
		);

		// Tabs

		$this->start_controls_tabs('crazy_menu_color');
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => PrestaHelper::__('Normal', 'customaddons'),
			)
		);
		$this->add_control(
			'crazy_menu_color_normal',
			array(
				'label'     => PrestaHelper::__('Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu a , {{WRAPPER}} .crazy-menu i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'crazy_dropdown_bg_normal',
			array(
				'label'     => PrestaHelper::__('Background', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu li' => 'background: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => PrestaHelper::__('Hover', 'customaddons'),
			)
		);
		$this->add_control(
			'crazy_menu_color_hover',
			array(
				'label'     => PrestaHelper::__('Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu li:hover > a, {{WRAPPER}} .crazy-menu li:hover > a i' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'crazy_dropdown_bg_hover',
			array(
				'label'     => PrestaHelper::__('Background', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu>ul>li:hover' => 'background: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_button_active',
			array(
				'label' => PrestaHelper::__('Active', 'customaddons'),
			)
		);
		$this->add_control(
			'crazy_menu_color_active',
			array(
				'label'     => PrestaHelper::__('Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu .active a , {{WRAPPER}} .crazy-menu .active i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'crazy_dropdown_bg_active',
			array(
				'label'     => PrestaHelper::__('Background', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu li.active' => 'background: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Tabs

		$this->add_control(
			'menu_alignment',
			array(
				'label'   => PrestaHelper::__('Alignment', 'plugin-domain'),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'default' => array(
						'title' => PrestaHelper::__('Left', 'plugin-domain'),
						'icon'  => 'fa fa-align-left',
					),
					'right'   => array(
						'title' => PrestaHelper::__('Right', 'plugin-domain'),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'default',
				'toggle'  => true,
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------------------- Dropdown Content

		$this->start_controls_section(
			'dropdown_menu',
			array(
				'label' => PrestaHelper::__('Dropdown', 'customaddons'),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => PrestaHelper::__('Width', 'customaddons'),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array('desktop', 'tablet', 'mobile'),
				'size_units' => array('px', '%'),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 150,
				),
				'selectors'  => array(
					'{{WRAPPER}} .crazy-menu .dropdown ul' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs('crazy_menu_hover_color');
		$this->start_controls_tab(
			'menu_dropdown_normal',
			array(
				'label' => PrestaHelper::__('Normal', 'customaddons'),
			)
		);

		$this->add_control(
			'dropdown_menu_color',
			array(
				'label'     => PrestaHelper::__('Menu Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu .dropdown ul a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => PrestaHelper::__('Background', 'customaddons'),
				'types'    => array('classic', 'gradient', 'video'),
				'selector' => '{{WRAPPER}} .crazy-menu .dropdown ul',
			)
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'menu_dropdown_hover',
			array(
				'label' => PrestaHelper::__('Hover', 'customaddons'),
			)
		);

		$this->add_control(
			'dropdown_menu_color_hover',
			array(
				'label'     => PrestaHelper::__('Menu Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-menu .dropdown ul a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background_hover',
				'label'    => PrestaHelper::__('Background', 'customaddons'),
				'types'    => array('classic', 'gradient', 'video'),
				'selector' => '{{WRAPPER}} .crazy-menu .dropdown ul li a:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => PrestaHelper::__('Box Shadow', 'customaddons'),
				'selector' => '{{WRAPPER}} .crazy-menu .dropdown ul',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'dropdown_border',
				'label'     => PrestaHelper::__('Border', 'customaddons'),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .crazy-menu .dropdown ul li',
			)
		);

		$this->add_responsive_control(
			'dropdown_padding',
			array(
				'label'      => PrestaHelper::__('Padding', 'customaddons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%', 'em'),
				'devices'    => array('desktop', 'tablet', 'mobile'),
				'selectors'  => array(
					'{{WRAPPER}} .crazy-menu .dropdown ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'mainmenu',
			array(
				'label' => PrestaHelper::__('Main Menu', 'customaddons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'main_menu_bg',
			array(
				'label'     => PrestaHelper::__('Background', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy_menu' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'label'     => PrestaHelper::__('Border', 'customaddons'),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .crazy-menu>ul>li',
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => PrestaHelper::__('Padding', 'customaddons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%', 'em'),
				'devices'    => array('desktop', 'tablet', 'mobile'),
				'selectors'  => array(
					'{{WRAPPER}} .crazy-menu>ul>li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => PrestaHelper::__('Margin', 'customaddons'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%', 'em'),
				'devices'    => array('desktop', 'tablet', 'mobile'),
				'selectors'  => array(
					'{{WRAPPER}} .crazy-menu>ul>li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------------------- General Style

		$this->start_controls_section(
			'mobile_menu_style',
			array(
				'label' => PrestaHelper::__('Mobile Menu', 'customaddons'),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_icon',
			array(
				'label'   => PrestaHelper::__('Toggle Icon', 'customaddons'),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-bars',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'menu_toggle_color',
			array(
				'label'     => PrestaHelper::__('Color', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navbar-toggle span'      => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .navbar-toggle i::before' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'menu_toggle_bg',
			array(
				'label'     => PrestaHelper::__('Background', 'customaddons'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .navbar-toggle' => 'background: {{VALUE}}',
				),
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
	protected function render()
	{
		$settings    = $this->get_settings_for_display();
		$context     = \Context::getContext();
		$header_icon = $settings['header_icon']['value'];
		$brand_name  = $settings['brand_name'];
		$brand_link  = $settings['brand_link'];
		
		if ($brand_link['is_external']) {
			$target_value = 'target="_blank"';
		} else {
			$target_value = null;
		}
		if ($brand_link['nofollow']) {
			$rel_value = 'rel="nofollow"';
		} else {
			$rel_value = null;
		}
		$logo_img       = $settings['logo_img']['url'];
		$menu_toggle_id = rand(11, 99);
		if (\Module::isInstalled('ps_mainmenu') && \Module::isEnabled('ps_mainmenu')) {
			$mod_ins         = \Module::getInstanceByName('ps_mainmenu');
			$context         = \Context::getContext();
			$retro_hook_name = PrestaHelper::$hook_current;
			$context->smarty->assign(
				array(
					'menu' => $mod_ins->getWidgetVariables($retro_hook_name, array()),
				)
			);
			$style_src = CRAZY_URL . "assets/css/bootstrap.min.css";
			$output = $context->smarty->fetch(CRAZY_PATH . '/views/templates/front/crazy_mainmenu.tpl');
			if ($retro_hook_name == "DisplayTop" || $retro_hook_name == "DisplayFooter") {
				echo '<link rel="stylesheet" href="' . $style_src . '" type="text/css" />';
			} else {
				$context->controller->addCSS($style_src, 'all');
			}
?>
<nav class="crazy_menu">
    <div class="navbar-header 
			<?php
			if ($settings['menu_alignment']) {
				echo $settings['menu_alignment'];
			} else {
				echo 'default';
			};
			?>
		">
        <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target="#crazy_menu_list<?php echo $menu_toggle_id; ?>">
            <?php
						if ($header_icon) {
							echo '<i class="' . $header_icon . '"></i>';
						} else {
						?>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <?php } ?>
        </button>
        <?php
					if ($logo_img) {
					?>
        <a class="navbar-brand" <?php echo $target_value . $rel_value; ?> href="<?php echo $brand_link['url']; ?>"><img
                src="<?php echo $logo_img; ?>" loading="lazy"></a>
        <?php
					} else {
					?>
        <a class="brand-name navbar-brand" <?php echo $target_value . $rel_value; ?>
            href="<?php echo $brand_link['url']; ?>"><?php echo $brand_name; ?></a>
        <?php
					}
					?>
    </div>
    <div class="crazy-menu <?php echo $settings['menu_alignment']; ?> collapse navbar-collapse"
        id="crazy_menu_list<?php echo $menu_toggle_id; ?>">
        <?php echo $output; ?>
    </div>
</nav>
<?php

		} else {
			$results = '<div class="error-mainmenu">Please Install <b>Main menu</b> Plugin</div>';
			return $results;
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
	protected function _content_template()
	{
	}
}