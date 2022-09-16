<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Accordion extends Widget_Base {

	public function get_name() {
		return 'accordion';
	}

	public function get_title() {
		return PrestaHelper::__( 'Accordion', 'elementor' );
	}

	public function get_icon() {
		return 'ceicon-accordion';
	}

	public function get_keywords() {
		return array( 'accordion', 'tabs', 'toggle' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => PrestaHelper::__( 'Accordion', 'elementor' ),
			)
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'tab_title',
			array(
				'label'       => PrestaHelper::__( 'Title & Description', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => PrestaHelper::__( 'Accordion Title', 'elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'tab_content',
			array(
				'label'      => PrestaHelper::__( 'Content', 'elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => PrestaHelper::__( 'Accordion Content', 'elementor' ),
				'show_label' => false,
			)
		);
		$this->add_control(
			'tabs',
			array(
				'label'       => PrestaHelper::__( 'Accordion Items', 'elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'tab_title'   => PrestaHelper::__( 'Accordion #1', 'elementor' ),
						'tab_content' => PrestaHelper::__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
					),
					array(
						'tab_title'   => PrestaHelper::__( 'Accordion #2', 'elementor' ),
						'tab_content' => PrestaHelper::__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
					),
				),
				'title_field' => '{{{ tab_title }}}',
			)
		);
		$this->add_control(
			'view',
			array(
				'label'   => PrestaHelper::__( 'View', 'elementor' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);
		$this->add_control(
			'selected_icon',
			array(
				'label'            => PrestaHelper::__( 'Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'separator'        => 'before',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
			)
		);
		$this->add_control(
			'selected_active_icon',
			array(
				'label'            => PrestaHelper::__( 'Active Icon', 'elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_active',
				'default'          => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'selected_icon[value]!' => '',
				),
			)
		);
		$this->add_control(
			'title_html_tag',
			array(
				'label'     => PrestaHelper::__( 'Title HTML Tag', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
				'default'   => 'div',
				'separator' => 'before',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => PrestaHelper::__( 'Accordion', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		); 
		$this->add_control(
			'border_width',
			array(
				'label'     => PrestaHelper::__( 'Border Width', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item .elementor-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item .elementor-tab-title.elementor-active' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'border_color',
			array(
				'label'     => PrestaHelper::__( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item .elementor-tab-content' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-item .elementor-tab-title.elementor-active' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle_style_title',
			array(
				'label' => PrestaHelper::__( 'Title', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'title_background',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
			)
		);
		$this->add_control(
			'tab_active_color',
			array(
				'label'     => PrestaHelper::__( 'Active Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);
		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'text_stroke',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-title',
			]
		);
		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle_style_icon',
			array(
				'label'     => PrestaHelper::__( 'Icon', 'elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'selected_icon[value]!' => '',
				),
			)
		);
		$this->add_control(
			'icon_align',
			array(
				'label'       => PrestaHelper::__( 'Alignment', 'elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'  => array(
						'title' => PrestaHelper::__( 'Start', 'elementor' ),
						'icon'  => 'ceicon-h-align-left',
					),
					'right' => array(
						'title' => PrestaHelper::__( 'End', 'elementor' ),
						'icon'  => 'ceicon-h-align-right',
					),
				),
				'default'     => PrestaHelper::is_rtl() ? 'right' : 'left',
				'toggle'      => false,
				'label_block' => false,
			)
		);
		$this->add_control(
			'icon_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title .elementor-accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'icon_active_color',
			array(
				'label'     => PrestaHelper::__( 'Active Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-accordion .elementor-tab-title.elementor-active .elementor-accordion-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'icon_space',
			array(
				'label'     => PrestaHelper::__( 'Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-icon.elementor-accordion-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion .elementor-accordion-icon.elementor-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle_style_content',
			array(
				'label' => PrestaHelper::__( 'Content', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'content_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-content',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			)
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_shadow',
				'selector' => '{{WRAPPER}} .elementor-accordion .elementor-tab-content',
			]
		);
		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-accordion .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			$settings['icon']        = 'fa fa-plus';
			$settings['icon_active'] = 'fa fa-minus';
			$settings['icon_align']  = $this->get_settings( 'icon_align' );
		}

		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		$has_icon = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );
		$id_int   = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="elementor-accordion" role="tablist">
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;
				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );
				$this->add_render_attribute(
					$tab_title_setting_key,
					array(
						'id'            => 'elementor-tab-title-' . $id_int . $tab_count,
						'class'         => array( 'elementor-tab-title' ),
						'data-tab'      => $tab_count,
						'role'          => 'tab',
						'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
					)
				);
				$this->add_render_attribute(
					$tab_content_setting_key,
					array(
						'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
						'class'           => array( 'elementor-tab-content', 'elementor-clearfix' ),
						'data-tab'        => $tab_count,
						'role'            => 'tabpanel',
						'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
					)
				);
				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
				?>
				<div class="elementor-accordion-item">
					<<?php echo $settings['title_html_tag']; ?> <?php echo $this->get_render_attribute_string( $tab_title_setting_key ); ?>>
						<?php if ( $has_icon ) : ?>
							<span class="elementor-accordion-icon elementor-accordion-icon-<?php echo PrestaHelper::esc_attr( $settings['icon_align'] ); ?>" aria-hidden="true">
							<?php
							if ( $is_new || $migrated ) {
								?>
								<span class="elementor-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
								<span class="elementor-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
							<?php } else { ?>
								<i class="elementor-accordion-icon-closed <?php echo PrestaHelper::esc_attr( $settings['icon'] ); ?>"></i>
								<i class="elementor-accordion-icon-opened <?php echo PrestaHelper::esc_attr( $settings['icon_active'] ); ?>"></i>
							<?php } ?>
							</span>
						<?php endif; ?>
						<a href=""><?php echo $item['tab_title']; ?></a>
					</<?php echo $settings['title_html_tag']; ?>>
					<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>><?php echo $item['tab_content']; ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="elementor-accordion" role="tablist">
			<#
			if ( settings.tabs ) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 ),
					iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, {}, 'i' , 'object' ),
					iconActiveHTML = elementor.helpers.renderIcon( view, settings.selected_active_icon, {}, 'i' , 'object' ),
					migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

				_.each( settings.tabs, function( item, index ) {
					var tabCount = index + 1,
						tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
						tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

					view.addRenderAttribute( tabTitleKey, {
						'id': 'elementor-tab-title-' + tabindex + tabCount,
						'class': [ 'elementor-tab-title' ],
						'tabindex': tabindex + tabCount,
						'data-tab': tabCount,
						'role': 'tab',
						'aria-controls': 'elementor-tab-content-' + tabindex + tabCount
					} );

					view.addRenderAttribute( tabContentKey, {
						'id': 'elementor-tab-content-' + tabindex + tabCount,
						'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab': tabCount,
						'role': 'tabpanel',
						'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
					} );

					view.addInlineEditingAttributes( tabContentKey, 'advanced' );
					#>
					<div class="elementor-accordion-item">
						<{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
							<# if ( settings.icon || settings.selected_icon ) { #>
							<span class="elementor-accordion-icon elementor-accordion-icon-{{ settings.icon_align }}" aria-hidden="true">
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
									<span class="elementor-accordion-icon-closed">{{{ iconHTML.value }}}</span>
									<span class="elementor-accordion-icon-opened">{{{ iconActiveHTML.value }}}</span>
								<# } else { #>
									<i class="elementor-accordion-icon-closed {{ settings.icon }}"></i>
									<i class="elementor-accordion-icon-opened {{ settings.icon_active }}"></i>
								<# } #>
							</span>
							<# } #>
							<a href="">{{{ item.tab_title }}}</a>
						</{{{ settings.title_html_tag }}}>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					</div>
					<#
				} );
			} #>
		</div>
		<?php
	}
}