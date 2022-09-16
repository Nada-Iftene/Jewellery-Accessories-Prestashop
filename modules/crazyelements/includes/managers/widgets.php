<?php
namespace CrazyElements;

use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Utils\Exceptions;
use CrazyElements\Core\Base\Document;

use CrazyElements\PrestaHelper;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Widgets_Manager {
	/**
	 * Widget types.
	 *
	 * Holds the list of all the widget types.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @var Widget_Base[]
	 */
	private $_widget_types          = null;
	private $build_widgets_filename = array();
	private $revmod                 = 'rev_slider_prestashop';

	/**
	 * @since  1.0.0
	 * @access private
	 */
	private function init_widgets() {
		$this->build_widgets_filename = array(
			'common',
			'heading',
			'image',
			'text-editor',
			'video',
			'menu-anchor',
			'button',
			'featured-products',
			'product',
			'products',
			'divider',
			'spacer',
			'products_carousal',
			'image-box',
			'countdown',
			'google-maps',
			'icon',
			'icon-box',
			'star-rating',
			'image-gallery',
			'bestseller-products',
			'image-carousel',
			'icon-list',
			'counter',
			'progress',
			'suppliers',
			'testimonial',
			'tabs',
			'product-category',
			'accordion',
			'supplier',
			'toggle',
			'social-icons',
			'text-path',
			'alert',
			'special-products',
			'new-products',
			'audio',
			'manufacturers',
			'html',
			'modules',
			'ajax-search',
			'productscategory_slider',
			'category-tree',
			'image-slider',
			'manufacturer',
			'call-to-action',
			'newsletter-subscribe',
			'image-hotspot',
			'animate-text',
			'main-menu',
			'add-to-cart',
			'revslider-addon',
			'shortcode',
		);

		$extra_addons = array(
			'modules',
			'bestseller-products',
			'category-tree',
			'image-slider',
			'manufacturer',
			'new-products',
			'product-category',
			'products',
			'special-products',
			'supplier',
			'products_carousal',
			'ajax-search',
			'newsletter-subscribe',
			'image-hotspot',
			'animate-text',
			'main-menu',
			'shortcode',
			'productscategory_slider'
		);

		$this->_widget_types = array();

		foreach ( $this->build_widgets_filename as $widget_filename ) {

			include_once CRAZY_PATH . 'includes/widgets/' . $widget_filename . '.php';

			if ( in_array( $widget_filename, $extra_addons ) ) {
				$class_name = str_replace( '-', ' ', $widget_filename );
				$class_name = ucwords( $class_name );
				$class_name = str_replace( ' ', '', $class_name );
			} else {
				$class_name = str_replace( '-', '_', $widget_filename );
			}
			$class_name = __NAMESPACE__ . '\Widget_' . $class_name;

			$this->register_widget_type( new $class_name() );
		}
		PrestaHelper::get_lience_expired_date();

		\Hook::exec( 'actionCrazyBeforeInit' );

		/**
		 * After widgets registered.
		 *
		 * Fires after Elementor widgets are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Widgets_Manager $this The widgets manager.
		 */
		PrestaHelper::do_action( 'elementor/widgets/widgets_registered', $this );
	}

	private function check_not_free() {
		return false;
	}

	/**
	 * Register WordPress widgets.
	 *
	 * Add native WordPress widget to the list of registered widget types.
	 *
	 * Exclude the widgets that are in Elementor widgets black list. Theme and
	 * plugin authors can filter the black list.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function register_wp_widgets() {
		global $wp_widget_factory;

		// Skip Pojo widgets.
		$pojo_allowed_widgets = array(
			'Pojo_Widget_Recent_Posts',
			'Pojo_Widget_Posts_Group',
			'Pojo_Widget_Gallery',
			'Pojo_Widget_Recent_Galleries',
			'Pojo_Slideshow_Widget',
			'Pojo_Forms_Widget',
			'Pojo_Widget_News_Ticker',

			'Pojo_Widget_WC_Products',
			'Pojo_Widget_WC_Products_Category',
			'Pojo_Widget_WC_Product_Categories',
		);

		// Allow themes/plugins to filter out their widgets.
		$black_list = array();

		/**
		 * Elementor widgets black list.
		 *
		 * Filters the widgets black list that won't be displayed in the panel.
		 *
		 * @since 1.0.0
		 *
		 * @param array $black_list A black list of widgets. Default is an empty array.
		 */
		$black_list = PrestaHelper::apply_filters( 'elementor/widgets/black_list', $black_list );

		foreach ( $wp_widget_factory->widgets as $widget_class => $widget_obj ) {

			if ( in_array( $widget_class, $black_list ) ) {
				continue;
			}

			if ( $widget_obj instanceof \Pojo_Widget_Base && ! in_array( $widget_class, $pojo_allowed_widgets ) ) {
				continue;
			}

			$elementor_widget_class = __NAMESPACE__ . '\Widget_WordPress';

			$this->register_widget_type(
				new $elementor_widget_class(
					array(),
					array(
						'widget_name' => $widget_class,
					)
				)
			);
		}
	}

	/**
	 * Require files.
	 *
	 * Require Elementor widget base class.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function require_files() {
		include_once CRAZY_PATH . 'includes/base/widget-base.php';
	}

	/**
	 * Register widget type.
	 *
	 * Add a new widget type to the list of registered widget types.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param Widget_Base $widget Elementor widget.
	 *
	 * @return true True if the widget was registered.
	 */
	public function register_widget_type( Widget_Base $widget ) {

		if ( is_null( $this->_widget_types ) ) {
			$this->init_widgets();
		}

		if ( ! in_array( $widget, $this->_widget_types ) ) {
			$this->_widget_types[ $widget->get_name() ] = $widget;
		}

		// echo $ddaa;
		return true;
	}

	/**
	 * Unregister widget type.
	 *
	 * Removes widget type from the list of registered widget types.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $name Widget name.
	 *
	 * @return true True if the widget was unregistered, False otherwise.
	 */
	public function unregister_widget_type( $name ) {
		if ( ! isset( $this->_widget_types[ $name ] ) ) {
			return false;
		}

		unset( $this->_widget_types[ $name ] );

		return true;
	}

	private function get_not_free_widgets() {
		$indexstr = 'EHPXZ_`defghiklmnoq';
		$indexstr = str_split( $indexstr );
		$indexstr = array_flip( $indexstr );
		$indexarr = array();
		for ( $i = 65; $i < 110; $i++ ) {
			$index                 = $i - 60;
			$indexarr[ chr( $i ) ] = $index;
		}
		$indexarr = array_intersect_key( $indexarr, $indexstr );
		return $indexarr;
	}

	/**
	 * Get widget types.
	 *
	 * Retrieve the registered widget types list.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $widget_name Optional. Widget name. Default is null.
	 *
	 * @return Widget_Base|Widget_Base[]|null Registered widget types.
	 */
	public function get_widget_types( $widget_name = null ) {

		if ( is_null( $this->_widget_types ) ) {
			$this->init_widgets();
		}

		if ( null !== $widget_name ) {
			return isset( $this->_widget_types[ $widget_name ] ) ? $this->_widget_types[ $widget_name ] : null;
		}

		return $this->_widget_types;
	}

	/**
	 * Get widget types config.
	 *
	 * Retrieve all the registered widgets with config for each widgets.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Registered widget types with each widget config.
	 */
	public function get_widget_types_config() {
		$config       = array();
		$indexarr     = $this->get_not_free_widgets();
		
		$widget_types = $this->get_widget_types();
		foreach ( $widget_types as $widget_key => $widget ) {
			$widget_config = $widget->get_config();
			$ind           = array_search( $widget_key, array_keys( $widget_types ) );
			if ( in_array( $ind, $indexarr ) ) {
				if ( $this->check_not_free() == 'valid' ) {
					$widget_config['editable'] = true;
					$revmod_name               = explode( '_', $widget_key );
					$revname                   = str_replace( '_', '', $this->revmod );
					if ( $revmod_name[0] == $revname ) {

						if ( ! \Module::isInstalled( $revmod_name[0] ) && ! \Module::isEnabled( $revmod_name[0] ) ) {
							$widget_config['editable'] = false;
						}
					}
				}
			} else {
				$widget_config['editable'] = true;
				$revmod_name               = explode( '_', $widget_key );
				$revname                   = str_replace( '_', '', $this->revmod );
				if ( $revmod_name[0] == $revname ) {

					if ( ! \Module::isInstalled( $revmod_name[0] ) && ! \Module::isEnabled( $revmod_name[0] ) ) {
						 $widget_config['editable'] = false;
					}
				}
			}
			$config[ $widget_key ] = $widget_config;
		}
		return $config;
	}

	public function ajax_get_widget_types_controls_config( array $data ) {
		$config = array();

		foreach ( $this->get_widget_types() as $widget_key => $widget ) {
			if ( isset( $data['exclude'][ $widget_key ] ) ) {
				continue;
			}

			$config[ $widget_key ] = array(
				'controls'      => $widget->get_stack( false )['controls'],
				'tabs_controls' => $widget->get_tabs_controls(),
			);
		}

		return $config;
	}

	/**
	 * Ajax render widget.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @throws \Exception If current user don't have permissions to edit the post.
	 *
	 * @param array $request Ajax request.
	 *
	 * @return array {
	 *     Rendered widget.
	 *
	 * @type string $render The rendered HTML.
	 * }
	 */
	public function ajax_render_widget( $request ) {

		$document = array( 'id' => $request['editor_post_id'] );

		$editor       = Plugin::$instance->editor;
		$is_edit_mode = $editor->is_edit_mode();
		$editor->set_edit_mode( true );
		Plugin::$instance->documents->switch_to_document( $document );
		$render_html = Document::render_element( $request['data'] );
		$editor->set_edit_mode( $is_edit_mode );
		return array(
			'render' => $render_html,
		);
	}

	/**
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $request Ajax request.
	 *
	 * @return bool|string Rendered widget form.
	 */
	public function ajax_get_wp_widget_form( $request ) {
		if ( empty( $request['widget_type'] ) ) {
			return false;
		}

		if ( empty( $request['data'] ) ) {
			$request['data'] = array();
		}

		$element_data = array(
			'id'         => $request['id'],
			'elType'     => 'widget',
			'widgetType' => $request['widget_type'],
			'settings'   => $request['data'],
			'test2'      => 'test2',
		);

		/**
		 * @var $widget_obj Widget_WordPress
		 */
		$widget_obj = Plugin::$instance->elements_manager->create_element_instance( $element_data );

		if ( ! $widget_obj ) {
			return false;
		}

		return $widget_obj->get_form();
	}

	/**
	 * Render widgets content.
	 *
	 * Used to generate the widget templates on the editor using Underscore JS
	 * template, for all the registered widget types.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function render_widgets_content() {
		foreach ( $this->get_widget_types() as $widget ) {
			$widget->print_template();
		}
	}

	/**
	 * Get widgets frontend settings keys.
	 *
	 * Retrieve frontend controls settings keys for all the registered widget
	 * types.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Registered widget types with settings keys for each widget.
	 */
	public function get_widgets_frontend_settings_keys() {
		$keys = array();

		foreach ( $this->get_widget_types() as $widget_type_name => $widget_type ) {
			$widget_type_keys = $widget_type->get_frontend_settings_keys();

			if ( $widget_type_keys ) {
				$keys[ $widget_type_name ] = $widget_type_keys;
			}
		}

		return $keys;
	}

	/**
	 * Enqueue widgets scripts.
	 *
	 * Enqueue all the scripts defined as a dependency for each widget.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue_widgets_scripts() {
		foreach ( $this->get_widget_types() as $widget ) {
			$widget->enqueue_scripts();
		}
	}

	/**
	 * Retrieve inline editing configuration.
	 *
	 * Returns general inline editing configurations like toolbar types etc.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @return array {
	 *     Inline editing configuration.
	 *
	 * @type array $toolbar {
	 *         Toolbar types and the actions each toolbar includes.
	 *         Note: Wysiwyg controls uses the advanced toolbar, textarea controls
	 *         uses the basic toolbar and text controls has no toolbar.
	 *
	 * @type array $basic    Basic actions included in the edit tool.
	 * @type array $advanced Advanced actions included in the edit tool.
	 *     }
	 * }
	 */
	public function get_inline_editing_config() {
		$basic_tools = array(
			'bold',
			'underline',
			'italic',
		);

		$advanced_tools = array_merge(
			$basic_tools,
			array(
				'createlink',
				'unlink',
				'h1'   => array(
					'h1',
					'h2',
					'h3',
					'h4',
					'h5',
					'h6',
					'p',
					'blockquote',
					'pre',
				),
				'list' => array(
					'insertOrderedList',
					'insertUnorderedList',
				),
			)
		);

		return array(
			'toolbar' => array(
				'basic'    => $basic_tools,
				'advanced' => $advanced_tools,
			),
		);
	}

	/**
	 * Widgets manager constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->require_files();

		PrestaHelper::add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
	}

	/**
	 * Register ajax actions.
	 *
	 * Add new actions to handle data after an ajax requests returned.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param Ajax $ajax_manager
	 */
	public function register_ajax_actions( Ajax $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'render_widget', array( $this, 'ajax_render_widget' ) );
		$ajax_manager->register_ajax_action( 'editor_get_wp_widget_form', array( $this, 'ajax_get_wp_widget_form' ) );
		$ajax_manager->register_ajax_action( 'get_widgets_config', array( $this, 'ajax_get_widget_types_controls_config' ) );
	}
}