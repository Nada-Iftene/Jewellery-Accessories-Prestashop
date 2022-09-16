<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Text_Editor extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve text editor widget name.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'text-editor';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve text editor widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return PrestaHelper::__( 'Text Editor', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve text editor widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ceicon-text';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the text editor widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'text', 'editor' );
	}

	/**
	 * Register text editor widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_editor',
			array(
				'label' => PrestaHelper::__( 'Text Editor', 'elementor' ),
			)
		);

		$this->add_control(
			'editor',
			array(
				'label'   => '',
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array(
					'active' => true,
				),
				'default' => PrestaHelper::__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
			)
		);

		$this->add_control(
			'drop_cap',
			array(
				'label'              => PrestaHelper::__( 'Drop Cap', 'elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => PrestaHelper::__( 'Off', 'elementor' ),
				'label_on'           => PrestaHelper::__( 'On', 'elementor' ),
				'prefix_class'       => 'elementor-drop-cap-',
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => PrestaHelper::__( 'Text Editor', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => PrestaHelper::__( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => PrestaHelper::__( 'Left', 'elementor' ),
						'icon'  => 'ceicon-text-align-left',
					),
					'center'  => array(
						'title' => PrestaHelper::__( 'Center', 'elementor' ),
						'icon'  => 'ceicon-text-align-center',
					),
					'right'   => array(
						'title' => PrestaHelper::__( 'Right', 'elementor' ),
						'icon'  => 'ceicon-text-align-right',
					),
					'justify' => array(
						'title' => PrestaHelper::__( 'Justified', 'elementor' ),
						'icon'  => 'ceicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-text-editor' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => PrestaHelper::__( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}};',
					'{{WRAPPER}} p' => 'color: {{VALUE}};',
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
				'name'   => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			)
		);

		$text_columns     = range( 1, 10 );
		$text_columns     = array_combine( $text_columns, $text_columns );
		$text_columns[''] = PrestaHelper::__( 'Default', 'elementor' );

		$this->add_responsive_control(
			'text_columns',
			array(
				'label'     => PrestaHelper::__( 'Columns', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'options'   => $text_columns,
				'selectors' => array(
					'{{WRAPPER}} .elementor-text-editor' => 'columns: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => PrestaHelper::__( 'Columns Gap', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'vw' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
					'%'  => array(
						'max'  => 10,
						'step' => 0.1,
					),
					'vw' => array(
						'max'  => 10,
						'step' => 0.1,
					),
					'em' => array(
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-text-editor' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_drop_cap',
			array(
				'label'     => PrestaHelper::__( 'Drop Cap', 'elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'drop_cap' => 'yes',
				),
			)
		);

		$this->add_control(
			'drop_cap_view',
			array(
				'label'        => PrestaHelper::__( 'View', 'elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'default' => PrestaHelper::__( 'Default', 'elementor' ),
					'stacked' => PrestaHelper::__( 'Stacked', 'elementor' ),
					'framed'  => PrestaHelper::__( 'Framed', 'elementor' ),
				),
				'default'      => 'default',
				'prefix_class' => 'elementor-drop-cap-view-',
				'condition'    => array(
					'drop_cap' => 'yes',
				),
			)
		);

		$this->add_control(
			'drop_cap_primary_color',
			array(
				'label'     => PrestaHelper::__( 'Primary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap, {{WRAPPER}}.elementor-drop-cap-view-default .elementor-drop-cap' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				),
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'condition' => array(
					'drop_cap' => 'yes',
				),
			)
		);

		$this->add_control(
			'drop_cap_secondary_color',
			array(
				'label'     => PrestaHelper::__( 'Secondary Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'drop_cap_view!' => 'default',
				),
			)
		);

		$this->add_control(
			'drop_cap_size',
			array(
				'label'     => PrestaHelper::__( 'Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-drop-cap' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'drop_cap_view!' => 'default',
				),
			)
		);

		$this->add_control(
			'drop_cap_space',
			array(
				'label'     => PrestaHelper::__( 'Space', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-drop-cap' => 'margin-right: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .elementor-drop-cap' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'drop_cap_border_radius',
			array(
				'label'      => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-drop-cap' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'drop_cap_border_width',
			array(
				'label'     => PrestaHelper::__( 'Border Width', 'elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .elementor-drop-cap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'drop_cap_view' => 'framed',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'drop_cap_typography',
				'selector'  => '{{WRAPPER}} .elementor-drop-cap-letter',
				'exclude'   => array(
					'letter_spacing',
				),
				'condition' => array(
					'drop_cap' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render text editor widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0
	 * @access protected
	 */
	protected function render() {
		$editor_content = $this->get_settings_for_display( 'editor' );

		// $editor_content = $this->parse_text_editor( $editor_content );

		$this->add_render_attribute( 'editor', 'class', array( 'elementor-text-editor', 'elementor-clearfix' ) );

		$this->add_inline_editing_attributes( 'editor', 'advanced' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'editor' ); ?>><?php echo $editor_content; ?></div>
		<?php
	}

	/**
	 * Render text editor widget as plain content.
	 *
	 * Override the default behavior by printing the content without rendering it.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode
		echo $this->get_settings( 'editor' );
	}

	/**
	 * Render text editor widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'editor', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ] );

		view.addInlineEditingAttributes( 'editor', 'advanced' );
		#>
		<div {{{ view.getRenderAttributeString( 'editor' ) }}}>{{{ settings.editor }}}</div>
		<?php
	}
}