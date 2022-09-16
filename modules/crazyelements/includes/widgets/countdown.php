<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Countdown extends Widget_Base {

	public function get_name() {
		return 'crazy_countdown';
	}

	public function get_title() {
		return PrestaHelper::__( 'Countdown', 'elecounter' );
	}

	public function get_icon() {
		return 'ceicon-countdown';
	}

	public function get_categories() {
		return array( 'crazy_addons_free' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'general',
			array(
				'label' => PrestaHelper::__( 'General', 'elecounter' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => PrestaHelper::__( 'Layout', 'elecounter' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => PrestaHelper::__( 'Layout One', 'elecounter' ),
					'2' => PrestaHelper::__( 'Layout Two', 'elecounter' ),
					'3' => PrestaHelper::__( 'Layout Three', 'elecounter' ),
					'4' => PrestaHelper::__( 'Layout Four (pro)', 'elecounter' ),
					'5' => PrestaHelper::__( 'Layout Five (pro)', 'elecounter' ),
					'6' => PrestaHelper::__( 'Layout Six (pro)', 'elecounter' ),
					'7' => PrestaHelper::__( 'Layout Seven (pro)', 'elecounter' ),
				),
				'default' => '1',
			)
		);

		$this->add_control(
			'completed_text',
			array(
				'label'       => PrestaHelper::__( 'Completed Text', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => PrestaHelper::__( 'Completed', 'elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_elements',
			array(
				'label'    => PrestaHelper::__( 'Show Elements', 'elecounter' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'options'  => array(
					'ce-days'    => PrestaHelper::__( 'Days', 'elecounter' ),
					'ce-hours'   => PrestaHelper::__( 'Hour', 'elecounter' ),
					'ce-minutes' => PrestaHelper::__( 'Minute', 'elecounter' ),
					'ce-seconds' => PrestaHelper::__( 'Second', 'elecounter' ),
				),
				'default'  => array( 'ce-hours', 'ce-minutes', 'ce-seconds' ),
			)
		);

		$this->add_control(
			'date_value',
			array(
				'label'          => PrestaHelper::__( 'Date', 'elecounter' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => array(
					'enableTime' => false,
				),
				'default'        => '2021-10-02',
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'        => PrestaHelper::__( 'Alignment', 'elecounter' ),
				'type'         => Controls_Manager::CHOOSE,
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '1',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '2',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '3',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '4',
						),
					),
				),
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
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style',
			array(
				'label' => PrestaHelper::__( 'Content', 'elecounter' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => PrestaHelper::__( 'Show Label', 'elecounter' ),
				'type'         => Controls_Manager::SWITCHER,
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '1',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '2',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '3',
						),
					),
				),
				'label_on'     => PrestaHelper::__( 'Show', 'elecounter' ),
				'label_off'    => PrestaHelper::__( 'Hide', 'elecounter' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'inline_display',
			array(
				'label'        => PrestaHelper::__( 'Item Inline', 'elecounter' ),
				'type'         => Controls_Manager::SWITCHER,
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '1',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '2',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '3',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '4',
						),
					),
				),
				'label_on'     => PrestaHelper::__( 'Yes', 'elecounter' ),
				'label_off'    => PrestaHelper::__( 'No', 'elecounter' ),
				'return_value' => 'inline-display',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'item_separator',
			array(
				'label'       => PrestaHelper::__( 'Separator', 'elecounter' ),
				'type'        => Controls_Manager::TEXT,
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '1',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '2',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '3',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '4',
						),
					),
				),
				'condition'   => array( 'inline_display' => 'inline-display' ),
				'placeholder' => PrestaHelper::__( 'Type your title here', 'elecounter' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counter_content_typography',
				'label'    => PrestaHelper::__( 'Counter Typography', 'elecounter' ),
				'selector' => '{{WRAPPER}} .ce-col',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'completed_typography',
				'label'    => PrestaHelper::__( 'Completed Typography', 'elecounter' ),
				'selector' => '{{WRAPPER}} .text',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'advanced',
			array(
				'label' => PrestaHelper::__( 'Advanced', 'elecounter' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elecounter' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .ce-col' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => PrestaHelper::__( 'Margin', 'elecounter' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .ce-col' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'color',
			array(
				'label' => PrestaHelper::__( 'Color', 'elecounter' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		
		$this->add_control(
			'title_color',
			array(
				'label'     => PrestaHelper::__( 'Global Color', 'elecounter' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ce-col' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'completed_color',
			array(
				'label'     => PrestaHelper::__( 'Completed Color', 'elecounter' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'fill_color_5',
			array(
				'label'      => PrestaHelper::__( 'Fill Color', 'elecounter' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '5',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '7',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ce-countdown--theme-2 .ce-fill' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'un_fill_color_5',
			array(
				'label'      => PrestaHelper::__( 'Empty Portions Color', 'elecounter' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '5',
						),
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => '7',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ce-countdown--theme-2 .ce-bar' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'days_bg_color_6',
			array(
				'label'     => PrestaHelper::__( 'Days Background Color', 'elecounter' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'layout' => '6' ),
				'selectors' => array(
					'{{WRAPPER}} .ce-countdown--theme-6 .ce-col .ce-days' => 'background: {{VALUE}}',
				),
			)
		);

		$this->ext_color_control( $this, 'Days Counter', 'ce-days', 'ce-days' );
		$this->ext_color_control( $this, 'Days Label', 'ce-days-label', 'ce-days' );
		$this->ext_color_control( $this, 'Hours Counter', 'ce-hours', 'ce-hours' );
		$this->ext_color_control( $this, 'Hours Label', 'ce-hours-label', 'ce-hours' );
		$this->ext_color_control( $this, 'Minutes Counter', 'ce-minutes', 'ce-minutes' );
		$this->ext_color_control( $this, 'Minutes Label', 'ce-minutes-label', 'ce-minutes' );
		$this->ext_color_control( $this, 'Seconds Counter', 'ce-seconds', 'ce-seconds' );
		$this->ext_color_control( $this, 'Seconds Label', 'ce-seconds-label', 'ce-seconds' );


		$this->end_controls_section();

	}
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$date_value     = $settings['date_value'];
		$layout         = $settings['layout'];
		$show_label     = $settings['show_label'];
		$item_separator = $settings['item_separator'];
		$inline_display = $settings['inline_display'];
		$alignment      = $settings['alignment'];
		$completed_text = $settings['completed_text'];
		$date           = explode( '-', $date_value );
		$random_id      = rand( 9999, 99999 );

		$then           = strtotime( $date_value );
		$now            = time();
		$somplete_class = '';
		if ( $now < $then ) {
			$somplete_class = 'hidden';
		}
		if ( $layout == 1 ) {
			$container_class = '-theme-1';
		} elseif ( $layout == 2 ) {
			$container_class = '-theme-3';
		} elseif ( $layout == 3 ) {
			$container_class = '-theme-5';
		}

		if ( $layout == 1 || $layout == 2 || $layout == 3 ) { ?>

<!-- Nothing to change here -->
<div class="counter-content ce-countdown123 ce-countdown-<?php echo $container_class . ' ' . $inline_display; ?>"
	id="ce-countdown">
	<div class="cont <?php echo $somplete_class; ?>">
		<div class="text">
			<h2><?php echo $completed_text; ?></h2>
		</div>
	</div>
			<?php
			foreach ( $settings['show_elements'] as $element ) {
				?>
	<div class="ce-col"><span class="<?php echo $element; ?>"></span>
				<?php if ( $show_label ) : ?>
		<span class="ce-col-label <?php echo $element; ?>-label"></span> <?php endif; ?>
				<?php
				if ( $item_separator ) :
					echo '<span class="item-sep">' . $item_separator . '&nbsp</span>';
	endif;
				?>
	</div>
				<?php
			}
			?>
	<input type="hidden" id="layoutstyle" value="1">
	<input type="hidden" id="dayid" value="<?php echo $date[2]; ?>">
	<input type="hidden" id="monthid" value="<?php echo $date[1]; ?>">
	<input type="hidden" id="yarid" value="<?php echo $date[0]; ?>">

</div>
			<?php
		} elseif ( $layout == 4 ) {
			echo 'This layout is available in Crazy Elements Pro!!!'
			?>
			<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank"><?php echo "Get Now!!!"; ?></a>
			<?php 
		} elseif ( $layout == 5 ) {
			echo 'This layout is available in Crazy Elements Pro!!!'
			?>
			<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank"><?php echo "Get Now!!!"; ?></a>
			<?php 
		} elseif ( $layout == 6 ) {
			echo 'This layout is available in Crazy Elements Pro!!!'
			?>
			<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank"><?php echo "Get Now!!!"; ?></a>
			<?php 
		} elseif ( $layout == 7 ) {
			echo 'This layout is available in Crazy Elements Pro!!!'
			?>
			<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank"><?php echo "Get Now!!!"; ?></a>
			<?php 
		}
	}

	protected function _content_template() {    }
}
