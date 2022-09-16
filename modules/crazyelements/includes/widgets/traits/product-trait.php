<?php
namespace CrazyElements\Includes\Widgets\Traits;

use CrazyElements\Scheme_Typography; 
use CrazyElements\PrestaHelper; 
use CrazyElements\Widget_Base;
use CrazyElements\Controls_Manager;
use CrazyElements\Group_Control_Background;
use CrazyElements\Group_Control_Border;
use CrazyElements\Group_Control_Box_Shadow;
use CrazyElements\Group_Control_Text_Shadow;
use CrazyElements\Group_Control_Typography;
use CrazyElements\Icons_Manager;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

trait Product_Trait {

	private $current_context;
	private $section_title;
	private $layout;
	private $ids;
	private $classic_skin;
	private $column_width;
	private $ed_short_desc;
	private $ed_desc;
	private $ed_manufacture;
	private $ed_supplier;
	private $ed_catagories;
	private $quantity_spin;
	private $ed_dis_amount;
	private $ed_dis_percent;
	private $from_cat_addon;

    public function general_controls($control, $multiple, $random = false, $layouts = true, $columns = true){

		if($layouts){
			$this->add_control(
				'layout',
				array(
					'label'   => PrestaHelper::__('Select Style', 'elementor'),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'default'   => PrestaHelper::__('Default', 'elementor'),
						'style_one' => PrestaHelper::__('Style One', 'elementor'),
						'style_two' => PrestaHelper::__('Style Two', 'elementor'),
						'style_three' => PrestaHelper::__('Style Three', 'elementor'),
						'style_four' => PrestaHelper::__('Style Four (PRO)', 'elementor'),
						'style_five' => PrestaHelper::__('Style Five (PRO)', 'elementor'),
					),
					'default' => 'default',
				)
			);
	
			$this->add_control(
				'classic_skin',
				array(
					'label'      => PrestaHelper::__( 'Skin', 'elementor' ),
					'type'       => Controls_Manager::SELECT,
					'options'    => array(
						'skin_one'   => PrestaHelper::__( 'One', 'elementor' ),
						'skin_two'   => PrestaHelper::__( 'Two', 'elementor' ),
						'skin_three' => PrestaHelper::__( 'Three', 'elementor' ),
					),
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
					'default'    => 'skin_one',
				)
			);
		}
       
		if($columns){
			$this->add_control(
				'column_width',
				array(
					'label'      => PrestaHelper::__( 'Column', 'elementor' ),
					'type'       => Controls_Manager::SELECT,
					'options'    => array(
						'col-lg-2 col-md-6 col-sm-12 col-xs-12'  => PrestaHelper::__( 'Six', 'elementor' ),
						'col-lg-3 col-md-6 col-sm-12 col-xs-12'  => PrestaHelper::__( 'Four', 'elementor' ),
						'col-lg-4 col-md-6 col-sm-12 col-xs-12'  => PrestaHelper::__( 'Three', 'elementor' ),
						'col-lg-6 col-md-6 col-sm-12 col-xs-12'  => PrestaHelper::__( 'Two', 'elementor' ),
						'col-lg-12 col-md-12 col-sm-12 col-xs-12' => PrestaHelper::__( 'One', 'elementor' ),
					),
					'conditions' => array(
						'relation' => 'or',
						'terms'    => array(
							array(
								'name'     => 'layout',
								'operator' => '==',
								'value'    => 'style_one',
							),
							array(
								'name'     => 'layout',
								'operator' => '==',
								'value'    => 'style_two',
							),
							array(
								'name'     => 'layout',
								'operator' => '==',
								'value'    => 'style_three',
							),
							array(
								'name'     => 'layout',
								'operator' => '==',
								'value'    => 'style_four',
							),
							array(
								'name'     => 'layout',
								'operator' => '==',
								'value'    => 'style_five',
							),
						),
					),
					'default'    => 'col-lg-4',
				)
			);
		}
		
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

		if(isset($control) && !empty($control)){
			$this->add_control($control[0],$control[1]);
		}
    
        if($multiple){
            $this->add_control(
                'per_page',
                array(
                    'label'   => PrestaHelper::__( 'Per Page', 'elementor' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 8,
                    'description' => PrestaHelper::__( 'This Works if No Product is Selected.', 'elementor' ),
                    'separator' => 'before'
                )
            );

			$orderby_fields = array(
				'label'   => PrestaHelper::__( 'Order by', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'id_product'   => PrestaHelper::__( 'Product Id', 'elementor' ),
					'price'        => PrestaHelper::__( 'Price', 'elementor' ),
					'date_add'     => PrestaHelper::__( 'Published Date', 'elementor' ),
					'name'         => PrestaHelper::__( 'Product Name', 'elementor' ),
					'position'     => PrestaHelper::__( 'Position', 'elementor' )
				),
				'default' => 'id_product',
			);

			$order_fields = array(
                    'label'   => PrestaHelper::__( 'Order', 'elementor' ),
                    'type'    => Controls_Manager::SELECT,
                    'options' => array(
                        'DESC' => PrestaHelper::__( 'DESC', 'elementor' ),
                        'ASC'  => PrestaHelper::__( 'ASC', 'elementor' ),
                    ),
                    'default' => 'ASC',
                );

			if($random){
				$this->add_control(
					$random,
					array(
						'label'   => PrestaHelper::__( 'Random?', 'elementor' ),
						'type'    => Controls_Manager::SWITCHER,
						'dynamic' => array(
							'active' => true,
						),
						'default' => false,
					)
				);	
				$condition = array(
					'conditions' => array(
						'terms'    => array(
							array(
								'name'     => $random,
								'operator' => '!=',
								'value'    => 'yes',
							),
						),
					)
				);
				$orderby_fields = array_merge($orderby_fields, $condition);
				$order_fields   = array_merge($order_fields, $condition);
			}
    
            $this->add_control(
                'orderby',
                $orderby_fields
            );
    
            $this->add_control(
                'order',
				$order_fields
            );
        }
    }

	public function register_feature_controls(){

		$this->add_control(
			'ed_short_desc',
			array(
				'label'   => PrestaHelper::__( 'Short Description', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);
		$this->add_control(
			'ed_desc',
			array(
				'label'   => PrestaHelper::__( 'Description', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);
		$this->add_control(
			'ed_manufacture',
			array(
				'label'   => PrestaHelper::__( 'Manufacture', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);
		$this->add_control(
			'ed_supplier',
			array(
				'label'   => PrestaHelper::__( 'Supplier', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);
		$this->add_control(
			'ed_catagories',
			array(
				'label'   => PrestaHelper::__( 'Catagories', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'quantity_spin',
			array(
				'label'   => PrestaHelper::__( 'Quantity Selector', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => false,
			)
		);
		

		$this->add_control(
			'ed_dis_percent',
			array(
				'label'   => PrestaHelper::__( 'Show Discount Percentage', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);

		$this->add_control(
			'ed_dis_amount',
			array(
				'label'   => PrestaHelper::__( 'Show Discount Amount', 'elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'dynamic' => array(
					'active' => true,
				),
				'default' => 'yes',
			)
		);	
	}

    public function register_section_style( $content_style = true ){

        $this->add_control(
			'heading_style',
			[
				'label' => PrestaHelper::__( 'Heading Style', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
			'header_color',
			array(
				'label'     => PrestaHelper::__( 'Heading Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .title_block' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_header_typography',
				'label'    => PrestaHelper::__( 'Heading Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .title_block',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'section_header_shadow',
				'label'    => PrestaHelper::__( 'Heading Shadow', 'elementor' ),
				'selector' => '{{WRAPPER}} .title_block',
			]
		);

		$this->add_control(
            'alignment_a',
            [
                'label' => PrestaHelper::__('Heading Alignment', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => PrestaHelper::__('Left', 'elementor'),
                        'icon' => 'ceicon-text-align-left',
                    ],
                    'center' => [
                        'title' => PrestaHelper::__('Center', 'elementor'),
                        'icon' => 'ceicon-text-align-center',
                    ],
                    'right' => [
                        'title' => PrestaHelper::__('Right', 'elementor'),
                        'icon' => 'ceicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .title_block' => 'text-align: {{VALUE}}',
                ],
                'separator' => 'after'
            ]
        );

		if($content_style){
			$this->add_control(
				'products_style',
				[
					'label' => PrestaHelper::__( 'Content Style', 'elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
	
			$this->add_control(
				'alignment_content',
				[
					'label' => PrestaHelper::__('Alignment', 'elementor'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => PrestaHelper::__('Left', 'elementor'),
							'icon' => 'ceicon-text-align-left',
						],
						'center' => [
							'title' => PrestaHelper::__('Center', 'elementor'),
							'icon' => 'ceicon-text-align-center',
						],
						'flex-end' => [
							'title' => PrestaHelper::__('Right', 'elementor'),
							'icon' => 'ceicon-text-align-right',
						],
					],
					'default' => 'flex-start',
					'selectors' => [
						'{{WRAPPER}} .product-miniature' => 'justify-content: {{VALUE}}',
						'{{WRAPPER}} .product-miniature .products' => 'justify-content: {{VALUE}}',
						'{{WRAPPER}} .ce_pr .products .ce_pr_row' => 'justify-content: {{VALUE}}',
					],
				]
			);
		}
    }

    public function register_card_style(){
        $this->add_control(
			'product_inner_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_inner ,{{WRAPPER}} .ce_pr.skin_two .ce_pr_row .product_desc, {{WRAPPER}} .ce_pr.skin_three .ce_pr_row .product_desc' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-grid-wrapper .products .product .product-miniature .product-description' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-carousel-wrapper .products .product .product-miniature .product-description' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ce_pr .products .ce_pr_row .cr-pr-inner' => 'background: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_inner_shadow',
				'selector' => '{{WRAPPER}} .product_inner, {{WRAPPER}} .product-grid-wrapper .products .thumbnail-container , {{WRAPPER}} .product-grid-wrapper .products .thumbnail-container img, {{WRAPPER}} .product-grid-wrapper .products .product .product-description,
				{{WRAPPER}} .product-carousel-wrapper .products .product .thumbnail-container img, {{WRAPPER}} .product-carousel-wrapper  .products .product .product-description, {{WRAPPER}} .ce_pr .products .ce_pr_row .cr-pr-inner',
			)
		);
		$this->add_responsive_control(
			'product_inner_radius',
			array(
				'label'      => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .product_inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail-container img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-grid-wrapper .products .product-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-carousel-wrapper .products .product-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ce_pr .products .ce_pr_row .cr-pr-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
    }
	public function register_highlighted_section_style(){
		$this->add_control(
			'highlighted_section_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-miniature .highlighted-informations a' => 'color: {{VALUE}};'
				),
			)
		);
		$this->add_control(
			'highlighted_section_hover_color',
			array(
				'label'     => PrestaHelper::__( 'Quickview Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-miniature .highlighted-informations .quick-view:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'highlighted_section_typography',
				'label'    => PrestaHelper::__( 'Quickview Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .highlighted-informations .quick-view',
			)
		);
		$this->add_control(
			'highlighted_section_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-miniature .highlighted-informations' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-miniature .variant-links' => 'background: {{VALUE}};',
				),
			)
		);
	}

	public function register_action_style(){

		$this->add_control(
			'addto_cart_style',
			[
				'label' => PrestaHelper::__( 'Add To Cart', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'addto_cart_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn, ',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'addto_cart_btn_icon',
			array(
				'label'     => PrestaHelper::__( 'Icon Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_control(
			'addto_cart_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'addto_cart_color_hover',
			array(
				'label'     => PrestaHelper::__( 'Color Hover', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn:hover' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'addto_cart_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn' => 'background: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'addto_cart_bg_hover',
			array(
				'label'     => PrestaHelper::__( 'Background Hover', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .add_to_cart .add_to_cart_btn:hover' => 'background: {{VALUE}};'
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'q_view_style',
			[
				'label' => PrestaHelper::__( 'Quick View', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'q_view__typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .cr-pr-inner .thumbnail .quick-view',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'q_view_btn_icon',
			array(
				'label'     => PrestaHelper::__( 'Icon Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .quick-view i' => 'font-size: {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_control(
			'q_view_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .quick-view' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'q_view_color_hover',
			array(
				'label'     => PrestaHelper::__( 'Color Hover', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .thumbnail .quick-view:hover' => 'color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'q_view_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .product-quantity .quick-view' => 'background-color: {{VALUE}};'
				),
			)
		);

		$this->add_control(
			'q_view_bg_hover',
			array(
				'label'     => PrestaHelper::__( 'Background Hover', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cr-pr-inner .product-quantity .quick-view:hover' => 'background-color: {{VALUE}};'
				),
				'separator' => 'after',
			)
		);

	}

	public function register_image_styles(){

		$this->add_control(
			'product_image_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-miniature .thumbnail-container .product-thumbnail' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail' => 'background: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-image .images-container .product-cover img' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'img_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elecounter' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .product-miniature .thumbnail-container .product-thumbnail img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-image .images-container .product-cover img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'label'    => PrestaHelper::__( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .product-miniature .thumbnail-container .product-thumbnail img, {{WRAPPER}} .product-grid-wrapper .products .thumbnail img, {{WRAPPER}} .crazy-single-product-image .images-container .product-cover img, {{WRAPPER}} .crazy-single-product-image .product-images .thumb-container img',
			)
		);
		$this->add_responsive_control(
			'img_border_radius',
			array(
				'label'      => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .product-miniature .thumbnail-container .product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-grid-wrapper .products .thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-image .images-container .product-cover img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	public function register_title_styles(){
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'Title',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .name, {{WRAPPER}} .product-description .product-title a, {{WRAPPER}} .lower-content a .product-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label'    => PrestaHelper::__( 'Title Shadow', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .name, {{WRAPPER}} .product-description .product-title a, {{WRAPPER}} .lower-content a .product-title',
			]
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .name' => 'color: {{VALUE}};',
					'{{WRAPPER}} .product-description .product-title a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .lower-content a .product-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .product_inner_style5 .border-one::before, {{WRAPPER}} .product_inner_style5 .border-one::after, {{WRAPPER}} .product_inner_style5 .border-two::before, {{WRAPPER}} .product_inner_style5 .border-two::after' => 'background-color: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'name_hover_color',
			array(
				'label'     => PrestaHelper::__( 'Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .name:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .product-description .product-title a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .lower-content a .product-title:hover' => 'color: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label' => PrestaHelper::__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => PrestaHelper::__( 'Left', 'elementor' ),
						'icon' => 'ceicon-text-align-left',
					],
					'center' => [
						'title' => PrestaHelper::__( 'Center', 'elementor' ),
						'icon' => 'ceicon-text-align-center',
					],
					'right' => [
						'title' => PrestaHelper::__( 'Right', 'elementor' ),
						'icon' => 'ceicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product-description .product-title' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .ce_pr .product_desc a .name' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .ce_pr .product_inner_style4 .lower-content' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .lower-content a .product-title' => 'text-align: {{VALUE}}',
				],
				'frontend_available' => true,
			]
		);
    }

	public function register_flag_style( $position = 'absolute' ){

		if($position == 'absolute'){
			$this->add_responsive_control(
				'_flag_offset_x',
				[
					'label' => PrestaHelper::__( 'Offset Horizontal', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'default' => [
						'size' => '0',
					],
					'size_units' => [ 'px', '%', 'vw', 'vh' ],
					'selectors' => [
						'{{WRAPPER}} .product-miniature .product-flags' => 'left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .crazy-single-product-badge .product-flags' => 'left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .crazy-single-product-image .product-flags' => 'left: {{SIZE}}{{UNIT}}',
					],
					'separator' => 'before',
				]
			);
	
			$this->add_responsive_control(
				'_flag_offset_y',
				[
					'label' => PrestaHelper::__( 'Offset Vertical', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -1000,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -200,
							'max' => 200,
						],
						'vh' => [
							'min' => -200,
							'max' => 200,
						],
						'vw' => [
							'min' => -200,
							'max' => 200,
						],
					],
					'size_units' => [ 'px', '%', 'vh', 'vw' ],
					'default' => [
						'size' => '0',
					],
					'selectors' => [
						'{{WRAPPER}} .product-miniature .product-flags' => 'top: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .crazy-single-product-badge .product-flags' => 'top: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .crazy-single-product-image .product-flags' => 'top: {{SIZE}}{{UNIT}}',
					],
					'separator' => 'after'
				]
			);
		}else{
			$this->add_control(
				'product_flag_align',
				[
					'label' => PrestaHelper::__('Alignment', 'elementor'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'start' => [
							'title' => PrestaHelper::__('Left', 'elementor'),
							'icon' => 'ceicon-text-align-left',
						],
						'center' => [
							'title' => PrestaHelper::__('Center', 'elementor'),
							'icon' => 'ceicon-text-align-center',
						],
						'end' => [
							'title' => PrestaHelper::__('Right', 'elementor'),
							'icon' => 'ceicon-text-align-right',
						],
					],
					'default' => 'start',
					'selectors' => [
						'{{WRAPPER}} .crazy-single-product-badge--stacked .product-flags' => 'align-items: {{VALUE}}',
						'{{WRAPPER}} .crazy-single-product-badge--inline .product-flags' => 'justify-content: {{VALUE}}',
					],
				]
			);
		}

		$this->add_control(
			'product_flag_text_align',
			[
				'label' => PrestaHelper::__('Fag Text Alignment', 'elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => PrestaHelper::__('Left', 'elementor'),
						'icon' => 'ceicon-text-align-left',
					],
					'center' => [
						'title' => PrestaHelper::__('Center', 'elementor'),
						'icon' => 'ceicon-text-align-center',
					],
					'right' => [
						'title' => PrestaHelper::__('Right', 'elementor'),
						'icon' => 'ceicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .product-miniature .product-flags li' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags li' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .crazy-single-product-image .product-flags li' => 'text-align: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);
		
		$this->add_responsive_control(
			'flag_width',
			[
				'label' => PrestaHelper::__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 200,
					],
					'vh' => [
						'min' => 0,
						'max' => 200,
					],
					'vw' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw' ],
				'selectors' => [
					'{{WRAPPER}} .product-miniature .product-flags li' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags li' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crazy-single-product-image .product-flags li' => 'width: {{SIZE}}{{UNIT}}',
				],
				
			]
		);

		$this->add_responsive_control(
			'gap_between',
			[
				'label' => PrestaHelper::__( 'Gap Between', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 200,
					],
					'vh' => [
						'min' => 0,
						'max' => 200,
					],
					'vw' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw' ],
				'default' => [
					'size' => '10',
				],
				'selectors' => [
					'{{WRAPPER}} .product-miniature .product-flags' => 'gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags' => 'gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .crazy-single-product-image .product-flags' => 'gap: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'after'
			]
		);

		$this->start_controls_tabs( 'product_flag_style' );

		$this->start_controls_tab(
			'discount_style_tab',
			array(
				'label' => PrestaHelper::__( 'Discount', 'elementor' ),
			)
		);

		$this->flag_controls('discount', 'discount');

		$this->end_controls_tab();

		$this->start_controls_tab(
			'new_flag_style_tab',
			array(
				'label' => PrestaHelper::__( 'New', 'elementor' ),
			)
		);

		$this->flag_controls('new', 'new');
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'pack_flag_style_tab',
			array(
				'label' => PrestaHelper::__( 'Pack', 'elementor' ),
			)
		);

		$this->flag_controls('pack', 'pack');
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'out_flag_style_tab',
			array(
				'label' => PrestaHelper::__( 'Out', 'elementor' ),
			)
		);

		$this->flag_controls('out', 'out');
		
		$this->end_controls_tab();

		$this->end_controls_tabs();

	}

	public function register_add_cart_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'atc_btn_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn,{{WRAPPER}} .product_inner .add_to_cart .avail_msg, {{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'atc_btn_icon',
			array(
				'label'     => PrestaHelper::__( 'Icon Size', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .product_inner .add_to_cart i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_control(
			'atc_btn_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'atc_btn_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn' => 'background: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'add_cart_border',
				'label'    => PrestaHelper::__( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn',
				'selector' => '{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn',
			)
		);

		$this->add_responsive_control(
			'add_cart_radius',
			[
				'label' => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_cart_shadow',
				'selector' => '{{WRAPPER}} .product_inner .add_to_cart .add_to_cart_btn, {{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart .add_to_cart_btn',
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'add_cart_align',
			[
				'label' => PrestaHelper::__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => PrestaHelper::__( 'Left', 'elementor' ),
						'icon' => 'ceicon-text-align-left',
					],
					'center' => [
						'title' => PrestaHelper::__( 'Center', 'elementor' ),
						'icon' => 'ceicon-text-align-center',
					],
					'right' => [
						'title' => PrestaHelper::__( 'Right', 'elementor' ),
						'icon' => 'ceicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product_inner .add_to_cart' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .product_inner_style5 .lower-content .add_to_cart' => 'justify-content: {{VALUE}}'
				],
				'frontend_available' => true,
			]
		);
	}

	public function register_short_desc_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'short_desc_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .description_short',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'short_desc_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .description_short' => 'color: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);
	}

	public function register_description_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .description',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .description' => 'color: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);
	}

	public function register_manufecturer_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'manufacturer_name',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .texonom .manufacturer_name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);
		$this->add_control(
			'manufacturer_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .manufacturer_name' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'manufacturer_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .manufacturer_name' => 'background: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);
	}

	public function register_supplier_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'supplier_name',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .texonom .supplier_name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);
		$this->add_control(
			'supplier_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .supplier_name' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'supplier_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .supplier_name' => 'background: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);
	}

	public function register_category_style(){
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_name',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .texonom .category_name',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);
		$this->add_control(
			'Category_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .category_name' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'Category_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .texonom .category_name' => 'background: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);
	}

	public function register_price_style($price_name = 'price_name', $price_color = 'price_color'){

		$this->add_responsive_control(
			'price_radius',
			array(
				'label'      => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'selectors'  => array(
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product_info .regular_price, .products .product_info .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product_desc .product_info .has_discount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product_desc .product_info .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-price .product-discount .regular-price, {{WRAPPER}} .crazy-single-product-price .product-price .current-price .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_align',
			[
				'label' => PrestaHelper::__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => PrestaHelper::__( 'Left', 'elementor' ),
						'icon' => 'ceicon-text-align-left',
					],
					'center' => [
						'title' => PrestaHelper::__( 'Center', 'elementor' ),
						'icon' => 'ceicon-text-align-center',
					],
					'right' => [
						'title' => PrestaHelper::__( 'Right', 'elementor' ),
						'icon' => 'ceicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .cr-pr-inner .product_desc .product_info' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .ce_pr .product_inner_style4 .lower-content .product_info' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .crazy-single-product-price' => 'justify-content: {{VALUE}}',
				],
				'frontend_available' => true,
				'separator' => 'after',
			]
		);

	
		$this->add_control(
			'price_style',
			[
				'label' => PrestaHelper::__( 'Price Style', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $price_name,
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .product_info p, {{WRAPPER}} .products .product-miniature .product-price-and-shipping .price, {{WRAPPER}} .products .product_info .price, {{WRAPPER}} .crazy-single-product-price .product-price .current-price .price, {{WRAPPER}} .ce_pr .products .lower-content .product_info .regular_price',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_responsive_control(
			'price_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .product_desc .product_info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product_info .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-price .product-price .current-price .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ce_pr .products .lower-content .product_info .regular_price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);
		$this->add_control(
			$price_color,
			array(
				'label'     => PrestaHelper::__( 'Price Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .product_info .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .products .product_info .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-price .product-price .current-price .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ce_pr .products .lower-content .product_info .regular_price' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'price_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .product_info .price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ce_pr .products .lower-content .product_info .price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .products .product_info .price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-price .product-price .current-price .price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .ce_pr .products .lower-content .product_info .regular_price' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'price_shadow',
				'selector' => '{{WRAPPER}} .products .product-miniature .product-price-and-shipping .price, {{WRAPPER}} .product_desc .product_info .price, {{WRAPPER}} .products .product_info .price, {{WRAPPER}} .crazy-single-product-price .product-price .current-price .price, {{WRAPPER}} .ce_pr .products .lower-content .product_info .regular_price',
			)
		);

		$this->add_control(
			'disc_price_style',
			[
				'label' => PrestaHelper::__( 'Discounted Price Style', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'disc_price_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_desc .product_info p, {{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price , {{WRAPPER}} .products .product_info .regular-price , {{WRAPPER}} .crazy-single-product-price .product-discount .regular-price',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_responsive_control(
			'disc_price_padding',
			array(
				'label'      => PrestaHelper::__( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .product_desc .product_info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .products .product_info .regular-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-price .product-discount .regular-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);

		$this->add_control(
			'discount_price_color',
			array(
				'label'     => PrestaHelper::__( 'Discounted Price Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .product_info .has_discount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .products .product_info .regular-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-price .product-discount .regular-price' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'discount_price_bg',
			array(
				'label'     => PrestaHelper::__( 'Discounted Price Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_desc .product_info .has_discount' => 'background: {{VALUE}};',
					'{{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .products .product_info .regular-price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-price .product-discount .regular-price' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'disc_price_shadow',
				'selector' => '{{WRAPPER}} .products .product-miniature .product-price-and-shipping .regular-price, {{WRAPPER}} .product_desc .product_info .has_discount, {{WRAPPER}} .products .product_info .regular-price, {{WRAPPER}} .crazy-single-product-price .product-discount .regular-price',
			)
		);
	}

	private function generate_controls_data($settings, $select_control_id = ''){
		$this->section_title        = $settings['title'];
		$this->layout       = $settings['layout'];
		if($select_control_id != ''){
			$this->ids          = $settings[$select_control_id];
			if(is_array($this->ids)){
				$this->ids = array_filter($this->ids);   
			}
		}
		$this->classic_skin = $settings['classic_skin'];
		$this->column_width = $settings['column_width'];
		$this->ed_short_desc  = $settings['ed_short_desc'];
		$this->ed_desc        = $settings['ed_desc'];
		$this->ed_manufacture = $settings['ed_manufacture'];
		$this->ed_supplier    = $settings['ed_supplier'];
		$this->ed_catagories  = $settings['ed_catagories'];
		$this->quantity_spin  = $settings['quantity_spin'];
		$this->ed_dis_amount  = $settings['ed_dis_amount'];
		$this->ed_dis_percent = $settings['ed_dis_percent'];
	}

	public function load_assets(){
		$this->current_context->controller->addCSS( CRAZY_PATH . 'assets/css/widgetonload/products_skin.css' );
		$this->current_context->controller->addCSS( _THEME_CSS_DIR_ . 'product.css' );
		$this->current_context->controller->addCSS( _THEME_CSS_DIR_ . 'product_list.css' );
		$this->current_context->controller->addCSS( _THEME_CSS_DIR_ . 'print.css', 'print' );
		$this->current_context->controller->addJqueryPlugin( array( 'fancybox', 'idTabs', 'scrollTo', 'serialScroll', 'bxslider' ) );
		$this->current_context->controller->addJqueryUI(array('ui.spinner'));
		$this->current_context->controller->addJS(
			array(
				_THEME_JS_DIR_ . 'tools.js',
			)
		);
	}

	public function build_query($where_query, $id_lang, $front = true, $orderby = ' RAND() ', $order='', $limit = 'LIMIT 1'){
		$sql = 'SELECT p.*, product_shop.*, pl.*, image_shop.`id_image`, il.`legend`, m.`name` AS manufacturer_name, s.`name` AS supplier_name
		FROM `' . _DB_PREFIX_ . 'product` p
		' . \Shop::addSqlAssociation( 'product', 'p' ) . '
		LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `' . _DB_PREFIX_ . 'supplier` s ON (s.`id_supplier` = p.`id_supplier`)
		LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)' .
		\Shop::addSqlAssociation( 'image', 'i', false, 'image_shop.cover=1' ) . '
		LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
		WHERE pl.`id_lang` = ' . (int) $id_lang .
		$where_query .
		( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
		' AND ((image_shop.id_image IS NOT NULL OR i.id_image IS NULL) OR (image_shop.id_image IS NULL AND i.cover=1))' .
		' AND product_shop.`active` = 1 ';
		if ( isset( $orderby ) && $orderby!= '' ) {
			$sql .= " ORDER BY {$orderby} {$order} ";
		}
		$sql .= $limit;
		return $sql;
	}

	public function get_category_by_id($id_lang, $id_category){
		if(!isset($id_category) || $id_category == ''){
			$id_category = $this->current_context->shop->getCategory();
		}
		return new \Category( $id_category, (int) $id_lang );
	}

	public function products_by_category($id_lang, $id_category, $orderby = '', $order='', $limit=8, $random_query = 'yes'){
		$category       = $this->get_category_by_id($id_lang, $id_category);
		$is_random = true;
		if($random_query != 'yes'){
			$is_random = false;
		}
		$products = $category->getProducts( (int) $id_lang, 1, $limit, $orderby, $order, false, true, (bool) $is_random,  $limit);
		return $products;
	}

	public function get_products_for_template($id_lang, $data){
		$product = \Product::getProductsProperties( $id_lang, $data );

		if ( ! $product ) {
			return false;
		}
		$assembler = new \ProductAssembler( $this->current_context );

		$presenterFactory     = new \ProductPresenterFactory( $this->current_context );
		$presentationSettings = $presenterFactory->getPresentationSettings();
		$presenter            = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
			new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
				$this->current_context->link
			),
			$this->current_context->link,
			new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
			new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
			$this->current_context->getTranslator()
		);

		$products_for_template = array();

		foreach ( $product as $rawProduct ) {
			$products_for_template[] = $presenter->present(
				$presentationSettings,
				$assembler->assembleProduct( $rawProduct ),
				$this->current_context->language
			);
		}

		return $products_for_template;
	}

	public function show_default_skin($data, $prefix, $display_type = 'grid'){
		$this->current_context->smarty->assign(
			array(
				'section_heading'      => $this->section_title,
				'crazy_products'       => $data,
				'elementprefix'        => $prefix,
				'theme_template_path'  => _PS_THEME_DIR_ . 'templates/catalog/_partials/miniatures/product.tpl',
			)
		);
		if($display_type == 'sidebar'){
			$template_file_name = CRAZY_PATH . 'views/templates/front/products_skin_default.tpl';
		}else{
			$template_file_name = CRAZY_PATH . 'views/templates/front/products_skin_default.tpl';
		}
		
		$out_put = $this->current_context->smarty->fetch($template_file_name);
		echo $out_put;
	}

	public function show_crazy_skins($data, $prefix){

		$layout = str_replace('style_','products_skin_',$this->layout) . '.tpl';

		$this->current_context->smarty->assign(
			array(
				'crazy_products'  => $data,
				'section_heading' => $this->section_title,
				'elementprefix'   => $prefix,
				'skin_class'      => $this->classic_skin,
				'ed_short_desc'   => $this->ed_short_desc,
				'ed_dis_amount'   => $this->ed_dis_amount,
				'ed_dis_percent'  => $this->ed_dis_percent,
				'ed_supplier'  => $this->ed_supplier,
				'column_width'    => $this->column_width,
				'ed_desc'         => $this->ed_desc,
				'ed_manufacture'  => $this->ed_manufacture,
				'ed_catagories'   => $this->ed_catagories,
				'quantity_spin'   => $this->quantity_spin,
				'from_cat_addon'  => $this->from_cat_addon
			)
		);
		$template_file_name = CRAZY_PATH . 'views/templates/front/products/'.$layout;
		$out_put            = $this->current_context->smarty->fetch($template_file_name);
		echo $out_put;
	}

	private function flag_controls($pref, $class){
		$label = ucfirst($pref);
		$this->add_control(
			$pref.'_flag_style',
			[
				'label' => PrestaHelper::__( $label .' Flag', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $pref.'_product_flag_typo',
				'label'    => PrestaHelper::__( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .product_inner .product_flag p, {{WRAPPER}} .product-miniature .product-flags li.'.$class. ', {{WRAPPER}} .crazy-single-product-badge .product-flags li.'.$class.', {{WRAPPER}} .crazy-single-product-image .product-flags li.'.$class,
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			$pref.'_product_flag_color',
			array(
				'label'     => PrestaHelper::__( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_inner .product_flag p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .product-miniature .product-flags li.'.$class => 'color: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags li.'.$class => 'color: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-image .product-flags li.'.$class => 'color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			$pref.'_product_flag_bg',
			array(
				'label'     => PrestaHelper::__( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product_inner .product_flag p' => 'background: {{VALUE}};',
					'{{WRAPPER}} .product-miniature .product-flags li.'.$class => 'background: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags li.'.$class => 'background: {{VALUE}};',
					'{{WRAPPER}} .crazy-single-product-image .product-flags li.'.$class => 'background: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			$pref.'_flag_radius',
			[
				'label' => PrestaHelper::__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_inner .product_flag p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-miniature .product-flags li.'.$class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-badge .product-flags li.'.$class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .crazy-single-product-image .product-flags li.'.$class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => $pref.'_flag_shadow',
				'selector' => '{{WRAPPER}} .product_inner .product_flag p, {{WRAPPER}} .product-miniature .product-flags li.'.$class.',{{WRAPPER}} .crazy-single-product-badge .product-flags li.'.$class.', {{WRAPPER}} .crazy-single-product-image .product-flags li.'.$class,
				'separator' => 'after',
			]
		);
	}

	public function getConfigurationVals(){
        $quantity_discount_price = \Configuration::get('PS_DISPLAY_DISCOUNT_PRICE');

        return [
            'display_taxes_label' => $this->getDisplayTaxesLabel(),
            'display_prices_tax_incl' => (bool) (new \TaxConfiguration())->includeTaxes(),
            'taxes_enabled' => (bool) \Configuration::get('PS_TAX'),
            'low_quantity_threshold' => (int) \Configuration::get('PS_LAST_QTIES'),
            'is_b2b' => (bool) \Configuration::get('PS_B2B_ENABLE'),
            'is_catalog' => (bool) \Configuration::isCatalogMode(),
            'show_prices' => (bool) \Configuration::showPrices(),
            'opt_in' => [
                'partner' => (bool) \Configuration::get('PS_CUSTOMER_OPTIN'),
            ],
            'quantity_discount' => [
                'type' => ($quantity_discount_price) ? 'price' : 'discount',
                'label' => ($quantity_discount_price)
                    ? $this->current_context->getTranslator()->trans('Unit price', [], 'Shop.Theme.Catalog')
                    : $this->current_context->getTranslator()->trans('Unit discount', [], 'Shop.Theme.Catalog'),
            ],
            'voucher_enabled' => (int) \CartRule::isFeatureActive(),
            'return_enabled' => (int) \Configuration::get('PS_ORDER_RETURN'),
            'number_of_days_for_return' => (int) \Configuration::get('PS_ORDER_RETURN_NB_DAYS'),
        ];
    }

    public function getDisplayTaxesLabel(){
        return (\Module::isEnabled('ps_legalcompliance') && (bool) \Configuration::get('AEUC_LABEL_TAX_INC_EXC')) || $this->current_context->country->display_tax_label;
    }
}
