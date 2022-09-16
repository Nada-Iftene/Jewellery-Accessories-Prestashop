<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;
use CrazyElements\Widget_Base;
use CrazyElements\Includes\Widgets\Traits\Product_Trait;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Add_To_Cart extends Widget_Base {

	use Product_Trait;

	/**
	 * Get widget name.
	 *
	 * Retrieve accordion widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'add_to_cart';
	}
	/**
	 * Get widget title.
	 *
	 * Retrieve accordion widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return PrestaHelper::__( 'Add to cart', 'elementor' );
	}
	/**
	 * Get widget icon.
	 *
	 * Retrieve accordion widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		$hook = \Tools::getValue('hook');
		if($hook == 'prdlayouts'){
			return 'ceicon-product-add-to-cart-widget';
		}else{
			return 'ceicon-add-to-cart-widget';
		}
	}
	public function get_categories() {
		$hook = \Tools::getValue('hook');
		if($hook == 'prdlayouts'){
			return array( 'products_layout' );
		}else{
			return array( 'products_free' );
		}
	}
	/**
	 * Register accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
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
			'ids',
			array(
				'label'     => PrestaHelper::__( 'Select products', 'elementor' ),
				'type'      => Controls_Manager::AUTOCOMPLETE,
				'item_type' => 'product',
				'multiple'  => false,
				'description' => PrestaHelper::__( 'Not Selecting A Product Will Show a Sinngle Product Randomly', 'elementor' ),
				'separator' => 'after'
			)
		);
		$this->add_control(
			'action',
			array(
				'label'   => PrestaHelper::__( 'Button Action', 'elecounter' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'link' => PrestaHelper::__( 'Add To Cart Link', 'elecounter' ),
					'detail' => PrestaHelper::__( 'Product Detail Link', 'elecounter' ),
					'ajax' => PrestaHelper::__( 'Ajax Add To Cart (Pro)', 'elecounter' )
				),
				'default' => 'link',
			)
		);

		$this->add_control(
			'ajax_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => PrestaHelper::__( 'Note: Ajax Action Only Work for Product Single Page', 'elementor' ),
				'content_classes' => 'elementor-panel-danger elementor-panel-alert-danger',
				'condition' => array(
					'action' => 'ajax',
				),
			]
		);
		
		$this->add_control(
			'btn_text_onoff',
			array(
				'label'   => PrestaHelper::__( 'Button Text On/Off', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'     => PrestaHelper::__( 'Button Text', 'elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => 'Add to cart',
				'condition' => array(
					'btn_text_onoff' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'btn_icon_onoff',
			array(
				'label'   => PrestaHelper::__( 'Button Icon On/Off', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
				'separator' => 'before'
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'       => PrestaHelper::__( 'Icon', 'elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'condition'   => array(
					'btn_icon_onoff' => array( 'yes' ),
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
				'separator' => 'before'
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => PrestaHelper::__( 'Button Style', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,

			)
		);
		$this->add_control(
			'button_color',
			array(
				'label'     => PrestaHelper::__( 'Button Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart'      => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'button_hover_color',
			array(
				'label'     => PrestaHelper::__( 'Button Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart:hover'      => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'button_bg_color',
			array(
				'label'     => PrestaHelper::__( 'Button BG Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart'      => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'button_hover_bg_color',
			array(
				'label'     => PrestaHelper::__( 'Button Hover BG Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart:hover'      => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => PrestaHelper::__( 'Button Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .btn.btn-primary.add-to-cart',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => PrestaHelper::__( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .btn.btn-primary.add-to-cart',
			)
		);
		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn.btn-primary.add-to-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		
		$settings       = $this->get_settings_for_display();
		$action = $settings['action'];
		$btn_text_onoff = $settings['btn_text_onoff'];
		$btn_text       = $settings['btn_text'];
		$btn_icon_onoff = $settings['btn_icon_onoff'];
	
		// common vars
		$out_put = '';
		$this->current_context = \Context::getContext();
		$id_lang = $this->current_context->language->id;
		$controller_name = \Tools::getValue('controller');

		// visivility check
		$front   = true;
		if (!in_array($this->current_context->controller->controller_type, array('front', 'modulefront'))) {
			$front = false;
		}

		$ids    = $settings['ids'];
		$str    = $this->render_autocomplete_result($ids);
		
		if($str == ''){
			echo "No Products Selected";
			return;
		}
		
		$final_link = '';
		$link         = new \Link();
		if($action == 'link'){
			$query_id = '';
			if ($str != '') {
				$query_id = ' AND p.`id_product` IN( ' . $str . ')';
			}


			$query = $this->build_query($query_id, $id_lang, $front);
			$results = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $query );

			$products = \Product::getProductsProperties( $id_lang, $results );
			if ( ! $products ) {
				return false;
			}
			$final_link = $link->getAddToCartURL( $products[0]['id_product'], $products[0]['id_product_attribute'] );	
		}else{
			$final_link = $link->getProductLink((int) $str, null, null, null, (int) $id_lang);
		}
		
		
		?>
		<div class="add crazy-product-add-to-cart">
			<a href="<?php echo $final_link; ?>" class="btn btn-primary add-to-cart">
				<?php
					if ( $btn_icon_onoff ) :
						if ( empty( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
							$settings['icon'] = 'material-icons shopping-cart';
						}
						if ( ! empty( $settings['icon'] ) ) {
							$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
							$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
						}
						$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
						$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
						if ( $is_new || $migrated ) :
							Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
						else :
					?>
						<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
				<?php
						endif;
					endif;
					if ( $btn_text_onoff ) {
						echo $btn_text;
					}
					?>
			</a>
		</div>
		<?php 
	}
}