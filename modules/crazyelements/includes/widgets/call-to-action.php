<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Call_To_Action extends Widget_Base {

	public function get_name() {
		return 'call_to_action';
	}

	public function get_title() {
		return PrestaHelper::__( 'Call To Action', 'elementor' );
	}

	public function get_icon() {
		return 'ceicon-posts-ticker';
	}

	public function get_categories() {
		return array( 'crazy_addons_free' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_image',
			array(
				'label' => PrestaHelper::__( 'CTA Content', 'elementor' ),
			)
		);
		$this->add_control(
			'main_image',
			array(
				'label'   => PrestaHelper::__( 'Main Image', 'elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);
		$this->add_control(
			'image_style',
			array(
				'label'   => PrestaHelper::__( 'Image Type', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => array(
					'cover'   => PrestaHelper::__( 'Cover', 'elementor' ),
					'classic' => PrestaHelper::__( 'Classic', 'elementor' ),
				),
			)
		);
		$this->add_control(
			'select_img_icon',
			array(
				'label'   => PrestaHelper::__( 'Select Content', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon_select',
				'options' => array(
					'icon_select'  => PrestaHelper::__( 'Icon', 'elementor' ),
					'image_select' => PrestaHelper::__( 'Image', 'elementor' ),
				),
			)
		);
			$this->add_control(
				'icon_cont',
				array(
					'label'            => PrestaHelper::__( 'Icon', 'elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'condition'        => array(
						'select_img_icon' => 'icon_select',
					),
				)
			);
			$this->add_control(
				'image_cont',
				array(
					'label'     => PrestaHelper::__( 'Image', 'elementor' ),
					'type'      => Controls_Manager::MEDIA,
					'dynamic'   => array(
						'active' => true,
					),
					'default'   => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'condition' => array(
						'select_img_icon' => 'image_select',
					),
				)
			);
		$this->add_control(
			'title',
			array(
				'label'   => PrestaHelper::__( 'Title', 'elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => PrestaHelper::__( 'Call To Action', 'elementor' ),
			)
		);
		$this->add_control(
			'short_desc',
			array(
				'label'   => PrestaHelper::__( 'Short Description', 'elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => PrestaHelper::__( 'Call to action description', 'elementor' ),
			)
		);
		$this->add_control(
			'button_title',
			array(
				'label'   => PrestaHelper::__( 'Button Ttile', 'elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => PrestaHelper::__( 'Call to action', 'elementor' ),
			)
		);
		$this->add_control(
			'button_url',
			array(
				'label' => PrestaHelper::__( 'Button URL', 'elementor' ),
				'type'  => Controls_Manager::URL,
			)
		);
		$this->add_control(
			'button_icon',
			array(
				'label'            => PrestaHelper::__( 'Button Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			)
		);
		$this->add_control(
			'icon_align',
			array(
				'label'     => PrestaHelper::__( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => PrestaHelper::__( 'Before', 'elementor' ),
					'right' => PrestaHelper::__( 'After', 'elementor' ),
				),
				'condition' => array(
					'button_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'icon_indent_let',
			array(
				'label'     => PrestaHelper::__( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta-content a i' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'button_icon[value]!' => '',
					'icon_align'          => 'left',
				),
			)
		);
		$this->add_control(
			'icon_indent_right',
			array(
				'label'     => PrestaHelper::__( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta-content a i' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'button_icon[value]!' => '',
					'icon_align'          => 'right',
				),
			)
		);
		$this->add_responsive_control(
			'alignment',
			array(
				'label'        => PrestaHelper::__( 'Alignment', 'elecounter' ),
				'type'         => Controls_Manager::CHOOSE,
				'devices'      => array( 'desktop', 'tablet', 'mobile' ),
				'options'      => array(
					'left'    => array(
						'title' => PrestaHelper::__( 'Left', 'elecounter' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => PrestaHelper::__( 'Center', 'elecounter' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => PrestaHelper::__( 'Right', 'elecounter' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => PrestaHelper::__( 'Justify', 'elecounter' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'prefix_class' => 'alignment%s',
				'default'      => 'center',

			)
		);

		$this->add_control(
			'cta_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To add Padding, margin, border <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_hover_effects',
			array(
				'label' => PrestaHelper::__( 'Hover Effects', 'elementor' ),
			// 'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_hover_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => PrestaHelper::__( 'Content', 'elementor' ),
				'separator' => 'before',
				'condition' => array(
					'image_style' => 'cover',
				),
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'     => PrestaHelper::__( 'Hover Animation', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''                  => 'None',
					'enter-zoom-in'     => 'Zoom In',
					'enter-zoom-out'    => 'Zoom Out',
					'grow'              => 'Grow',
					'shrink'            => 'Shrink',
					'pro_feat'            => 'Get Pro and Have More Animation Options',
				),
				'default'   => 'grow',
				'condition' => array(
					'image_style' => 'cover',
				),
			)
		);

		/*
		 *
		 * Add class 'crazy-animated-content' to widget when assigned content animation
		 *
		 */

		$this->add_control(
			'animation_class',
			array(
				'label'        => 'Animation',
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'animated-content',
				'prefix_class' => 'crazy-',
				'condition'    => array(
					'content_animation!' => '',
				),
			)
		);

		$this->add_control(
			'content_animation_duration',
			array(
				'label'       => PrestaHelper::__( 'Animation Duration', 'elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default'     => array(
					'size' => 1000,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .crazy-content-item' => 'transition-duration: {{SIZE}}ms',
					'{{WRAPPER}}.crazy-cta--sequenced-animation .crazy-content-item:nth-child(2)' => 'transition-delay: calc( {{SIZE}}ms / 3 )',
					'{{WRAPPER}}.crazy-cta--sequenced-animation .crazy-content-item:nth-child(3)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 2 )',
					'{{WRAPPER}}.crazy-cta--sequenced-animation .crazy-content-item:nth-child(4)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 3 )',
				),
				'condition'   => array(
					'content_animation!' => '',
					'image_style'        => 'cover',
				),
			)
		);

		$this->add_control(
			'sequenced_animation',
			array(
				'label'        => PrestaHelper::__( 'Sequenced Animation', 'elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => PrestaHelper::__( 'On', 'elementor' ),
				'label_off'    => PrestaHelper::__( 'Off', 'elementor' ),
				'return_value' => 'crazy-cta--sequenced-animation',
				'prefix_class' => '',
				'condition'    => array(
					'content_animation!' => '',
					'image_style'        => 'cover',
				),
			)
		);

		$this->add_control(
			'background_hover_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => PrestaHelper::__( 'Image', 'elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'transformation',
			array(
				'label'        => PrestaHelper::__( 'Hover Animation', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					''           => 'None',
					'zoom-in'    => 'Zoom In',
					'zoom-out'   => 'Zoom Out',
					'pro_feat'            => 'Get Pro and Have More Animation Options',
				),
				'default'      => 'zoom-in',
				'prefix_class' => 'crazy-bg-transform crazy-bg-transform-',
			)
		);

		$this->add_control(
			'bg_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To Use Overlay Feature <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_icon',
			array(
				'label' => PrestaHelper::__( 'CTA Icon', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cta_icon_color',
			array(
				'label'     => PrestaHelper::__( 'Icon Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content i' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_icon_hover_color',
			array(
				'label'     => PrestaHelper::__( 'CTA Hover Icon Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta:hover .crazy-cta-content i' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ctaicon_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To Add Typography, Margin, Padding, Border <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements and add border radius.', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_title',
			array(
				'label' => PrestaHelper::__( 'CTA Title', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cta_title_color',
			array(
				'label'     => PrestaHelper::__( 'Title Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content h3' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_title_hover_color',
			array(
				'label'     => PrestaHelper::__( 'CTA Hover Title Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta:hover .crazy-cta-content h3' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ctatitle_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To Add Typography, Margin, Padding <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements and add border radius.', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_content',
			array(
				'label' => PrestaHelper::__( 'CTA Short Desc', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cta_desc_color',
			array(
				'label'     => PrestaHelper::__( 'Short Desc Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content .short-desc' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_desc_hover_color',
			array(
				'label'     => PrestaHelper::__( 'CTA Hover Short Desc Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta:hover .crazy-cta-content .short-desc' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ctadesc_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To Add Typography, Margin, Padding <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements and add border radius.', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_button',
			array(
				'label' => PrestaHelper::__( 'CTA Button', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'cta_btn_color',
			array(
				'label'     => PrestaHelper::__( 'Button Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_btn_bg_color',
			array(
				'label'     => PrestaHelper::__( 'Button BG Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content a' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_btn_hover_color',
			array(
				'label'     => PrestaHelper::__( 'Button Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content a:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_btn_bg_hover_color',
			array(
				'label'     => PrestaHelper::__( 'Button BG Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .crazy-cta-content a:hover' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_hover_btn_color',
			array(
				'label'     => PrestaHelper::__( 'CTA Hover Button Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta:hover .crazy-cta-content a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'cta_hover_btn_bg_color',
			array(
				'label'     => PrestaHelper::__( 'CTA Hover Button BG Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .crazy-cta:hover .crazy-cta-content a' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'btcta_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To add button border, typography<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_cta_image',
			array(
				'label'     => PrestaHelper::__( 'CTA Image', 'elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'image_style' => 'classic',
				),
			)
		);
		$this->add_control(
			'ctaimg_pro_alert',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'To add Image padding, margin, border <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">Click Here and Get The PRO</a> version of Crazy Elements', 'elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$settings     = $this->get_settings_for_display();
		$main_image   = $settings['main_image']['url'];
		$image_style  = $settings['image_style'];
		$title        = $settings['title'];
		$short_desc   = $settings['short_desc'];
		$button_icon  = $settings['button_icon'];
		$icon_align   = $settings['icon_align'];
		$button_title = $settings['button_title'];
		$button_url   = $settings['button_url']['url'];
		if ( ! empty( $button_url ) ) {
			$this->add_render_attribute( 'button_url', 'href', $button_url );
			if ( $settings['button_url']['is_external'] ) {
				$this->add_render_attribute( 'button_url', 'target', '_blank' );
			}

			if ( ! empty( $settings['button_url']['nofollow'] ) ) {
				$this->add_render_attribute( 'button_url', 'rel', 'nofollow' );
			}
		}
		$content_animation = $settings['content_animation'];
		$animation_class   = '';
		if ( ! empty( $content_animation ) && 'cover' == $image_style ) {
			$animation_class = 'crazy-animated-item--' . $content_animation;

			$this->add_render_attribute( 'title', 'class', $animation_class );
			$this->add_render_attribute( 'graphic_element', 'class', $animation_class );
			$this->add_render_attribute( 'description', 'class', $animation_class );
		}
		$image_cont      = $settings['image_cont']['url'];
		$icon_cont       = $settings['icon_cont'];
		$select_img_icon = $settings['select_img_icon'];
		// var_dump($icon_cont);
		?>
		<?php if ( $image_style == 'cover' ) { ?>   
		<div class="crazy-cta">
			<img src="<?php echo $main_image; ?>" alt=""> 
			<div class="crazy-cta-bg-overlay"></div>
			<div class="crazy-cta-table">
				<div class="crazy-cta-table-cell crazy-cta-content">
			
						<?php if ( $select_img_icon == 'image_select' ) { ?>
							<img class="crazy-content-item <?php echo $animation_class; ?>" src="<?php echo $image_cont; ?>" />
						<?php } elseif ( $select_img_icon == 'icon_select' ) { ?>
							<i class="<?php echo $icon_cont['value']; ?> crazy-content-item <?php echo $animation_class; ?>"></i>
						<?php } ?>
					
					<h3 class="crazy-content-item <?php echo $animation_class; ?>"><?php echo $title; ?></h3>
					<div class="short-desc crazy-content-item <?php echo $animation_class; ?>"><?php echo $short_desc; ?></div>
					<?php if ( $button_title && $button_url ) { ?>
					<a <?php echo $this->get_render_attribute_string( 'button_url' ); ?> class="crazy-content-item <?php echo $animation_class; ?>">
						<?php if ( $button_icon && $icon_align == 'left' ) { ?>
					<i class="<?php echo $button_icon['value']; ?>"></i>
					<?php } ?>
						<?php echo $button_title; ?>
						<?php if ( $button_icon && $icon_align == 'right' ) { ?>
					<i class="<?php echo $button_icon['value']; ?>"></i>
					<?php } ?>
					</a>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } elseif ( $image_style == 'classic' ) { ?>
			<div class="crazy-cta">
				<div class="crazy-cta-bg">
					<img src="<?php echo $main_image; ?>" alt=""> 
					<div class="crazy-cta-bg-overlay"></div>
				</div>
				<div class="crazy-cta-content">
					<h3><?php echo $title; ?></h3>
					<div class="short-desc"><?php echo $short_desc; ?></div>
					<?php if ( $button_title && $button_url ) { ?>
					<a <?php echo $this->get_render_attribute_string( 'button_url' ); ?>>
						<?php if ( $button_icon && $icon_align == 'left' ) { ?>
					<i class="<?php echo $button_icon['value']; ?>"></i>
					<?php } ?>
						<?php echo $button_title; ?>
						<?php if ( $button_icon && $icon_align == 'right' ) { ?>
					<i class="<?php echo $button_icon['value']; ?>"></i>
					<?php } ?>
					</a>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		<?php
	}
}
