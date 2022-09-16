<?php

namespace CrazyElements\Core\Editor;

require_once CRAZY_PATH . 'core/editor/editor_core.php';

use CrazyElements;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Debug\Loading_Inspection_Manager;
use CrazyElements\Core\Responsive\Responsive;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\Icons_Manager;
use CrazyElements\Plugin;
use CrazyElements\Schemes_Manager;
use CrazyElements\Settings;
use CrazyElements\Shapes;
use CrazyElements\TemplateLibrary\Source_Local;
use CrazyElements\Tools;
use CrazyElements\Link;
use CrazyElements\User;
use CrazyElements\Utils;
use CrazyElements\Core\Editor\Editor_core;
use CrazyElements\PrestaHelper;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CrazyElements editor.
 *
 * CrazyElements editor handler class is responsible for initializing CrazyElements
 * editor and register all the actions needed to display the editor.
 *
 * @since 1.0.0
 */
class Editor {


	/**
	 * The nonce key for CrazyElements editor.
	 */
	const EDITING_NONCE_KEY = 'elementor-editing';

	/**
	 * User capability required to access CrazyElements editor.
	 */
	const EDITING_CAPABILITY = 'edit_posts';

	/**
	 * Post ID.
	 *
	 * Holds the ID of the current post being edited.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var int Post ID.
	 */
	private $_post_id;

	/**
	 * Whether the edit mode is active.
	 *
	 * Used to determine whether we are in edit mode.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var bool Whether the edit mode is active.
	 */
	private $_is_edit_mode;

	/**
	 * @var Notice_Bar
	 */
	public $notice_bar;

	/**
	 * Init.
	 * Fired by `admin_action_elementor` action.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool $die Optional. Whether to die at the end. Default is `true`.
	 */
	public function initTest() {
		$this->print_editor_template();
	}
	public function init( $die = true ) {

		// added for prestashop
		$this->_is_edit_mode = true;
		// Handle `wp_head`

		PrestaHelper::add_action( 'wp_head', array( PrestaHelper::getInstance(), 'wp_enqueue_scripts' ), 1 );
		PrestaHelper::add_action( 'wp_head', array( PrestaHelper::getInstance(), 'wp_print_styles' ), 8 );
		PrestaHelper::add_action( 'wp_head', array( PrestaHelper::getInstance(), 'wp_print_head_scripts' ), 9 );
		PrestaHelper::add_action( 'wp_head', array( $this, 'editor_head_trigger' ), 30 );

		// Handle `wp_footer`
		PrestaHelper::add_action( 'wp_footer', array( $this, 'wp_footer' ) );

		PrestaHelper::add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999999 );
		PrestaHelper::add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 999999 );

		// Setup default heartbeat options

		PrestaHelper::add_filter(
			'heartbeat_settings',
			function ( $settings ) {
				$settings['interval'] = 15;
				return $settings;
			}
		);
		PrestaHelper::do_action( 'elementor/editor/init' );
		$this->print_editor_template();
		if ( false !== $die ) {
			die;
		}
	}

	/**
	 * Retrieve post ID.
	 *
	 * Get the ID of the current post.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return int Post ID.
	 */
	public function get_post_id() {
		 return $this->_post_id;
	}

	/**
	 * Fired by `template_redirect` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function redirect_to_new_url() {
		if ( ! isset( $_GET['elementor'] ) ) {
			return;
		}

		$document = Plugin::$instance->documents->get( get_the_ID() );

		if ( ! $document ) {
			wp_die( PrestaHelper::__( 'Document not found.', 'elementor' ) );
		}

		if ( ! $document->is_editable_by_current_user() || ! $document->is_built_with_elementor() ) {
			return;
		}

		wp_safe_redirect( $document->get_edit_url() );
		die;
	}

	/**
	 * Whether the edit mode is active.
	 *
	 * Used to determine whether we are in the edit mode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id Optional. Post ID. Default is `null`, the current
	 *                     post ID.
	 *
	 * @return bool Whether the edit mode is active.
	 */
	public function is_edit_mode( $post_id = null ) {
		if ( null !== $this->_is_edit_mode ) {
			return $this->_is_edit_mode;
		}

		if ( empty( $post_id ) ) {
			$post_id = $this->_post_id;
		}

		$document = Plugin::$instance->documents->get( $post_id );

		// Ajax request as Editor mode
		$actions = array(
			'elementor',
			// Templates
			'elementor_get_templates',
			'elementor_save_template',
			'elementor_get_template',
			'elementor_delete_template',
			'elementor_import_template',
			'elementor_library_direct_actions',
		);

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Lock post.
	 *
	 * Mark the post as currently being edited by the current user.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id The ID of the post being edited.
	 */
	public function lock_post( $post_id ) {
		if ( ! function_exists( 'wp_set_post_lock' ) ) {
			require_once ABSPATH . 'wp-admin/includes/post.php';
		}

		wp_set_post_lock( $post_id );
	}

	/**
	 * Get locked user.
	 *
	 * Check what user is currently editing the post.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param int $post_id The ID of the post being edited.
	 *
	 * @return \WP_User|false User information or false if the post is not locked.
	 */
	public function get_locked_user( $post_id ) {
		if ( ! function_exists( 'wp_check_post_lock' ) ) {
			require_once ABSPATH . 'wp-admin/includes/post.php';
		}

		$locked_user = wp_check_post_lock( $post_id );
		if ( ! $locked_user ) {
			return false;
		}

		return get_user_by( 'id', $locked_user );
	}

	/**
	 * Print Editor Template.
	 *
	 * Include the wrapper template of the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function print_editor_template() {
		include CRAZY_PATH . 'includes/editor-templates/editor-wrapper.php';
	}

	/**
	 * Enqueue scripts.
	 *
	 * Registers all the editor scripts and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function document_config() {
		$context = \Context::getContext();

		$id_lang = PrestaHelper::$id_lang_global;
		$id_shop = PrestaHelper::$id_shop_global;
		$id      = \Tools::getValue( 'id' );
		$hook    = \Tools::getValue( 'hook' );
		$token   = \Tools::getValue( 'token' );
		$module  = \Module::getInstanceByName( 'crazyelements' );
		switch ( $hook ) {
			case 'cms':
				$preview      = $context->link->getCMSLink( $id, null, true, $id_lang );
				$preview_w_qs = $context->link->getCMSLink( $id, null, true, $id_lang ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			case 'product':
				$preview      = $context->link->getProductLink( $id, null, null, null, $id_lang );
				$preview_w_qs = $context->link->getProductLink( $id, null, null, null, $id_lang ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			case 'category':
				$preview      = $context->link->getCategoryLink( $id, null, $id_lang );
				$preview_w_qs = $context->link->getCategoryLink( $id, null, $id_lang ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			case 'supplier':
				$preview      = $context->link->getSupplierLink( $id, null, $id_lang );
				$preview_w_qs = $context->link->getSupplierLink( $id, null, $id_lang ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			case 'manufacturer':
				$preview      = $context->link->getManufacturerLink( $id, null, $id_lang );
				$preview_w_qs = $context->link->getManufacturerLink( $id, null, $id_lang ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			case 'extended':
				$preview           = PrestaHelper::setPreviewForHook( $hook );
				$preview_w_qs      = PrestaHelper::setPreviewForHook( $hook ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
				$exit_to_dashboard = PrestaHelper::$current_url;
				break;
			default:
					$preview           = PrestaHelper::setPreviewForHook( $hook );
					$preview_w_qs      = PrestaHelper::setPreviewForHook( $hook ) . '?hook=' . $hook . '&id=' . $id . '&id_lang=' . $id_lang . '&id_shop=' . $id_shop . '&token=' . $token . PrestaHelper::$disable_activity;
					if(isset( $_SERVER['HTTP_REFERER'])){
					$exit_to_dashboard = $_SERVER['HTTP_REFERER'];
				}else{
					$exit_to_dashboard = $context->link->getAdminLink( 'AdminDashboard' );
				}
				break;
		}

		$custom = array();
		$catgs  = array(
			'basic'        => array(
				'title' => 'Basic',
				'icon'  => 'ceicon-font',
			),
			'general'      => array(
				'title' => 'General',
				'icon'  => 'ceicon-font',
			),
			'crazy_addons_free' => array(
				'title'  => 'Crazy Elements Addons',
				'active' => true,
				'icon'   => 'ceicon-presta-widget',
			),
			'products_free'     => array(
				'title'  => 'Products Addons',
				'active' => true,
				'icon'   => 'ceicon-presta-widget',
			),
			'crazy_addons' => array(
				'title'  => 'Crazy Elements Addons (Pro)',
				'active' => true,
				'icon'   => 'ceicon-presta-widget',
			),
			'products'     => array(
				'title'  => 'Products Addons (Pro)',
				'active' => true,
				'icon'   => 'ceicon-presta-widget',
			)
		);

		$temp_catgs = array();
		foreach($catgs as $key => $catg){
			$temp_catgs[$key] = $catg;
		}


		$catgs = $temp_catgs;

		$custom_place['custom'] = &$custom;
		

		\Hook::exec( 'actionCrazyAddCategory', $custom_place );
		
		foreach($custom_place['custom'] as $cust){
			$catgs = $catgs + $cust;
		}

		return array(
			'id'            => $id,
			'type'          => 'page',
			'version'       => '2.6.2',
			'last_edited'   => '',
			'remoteLibrary' => array(
				'type'               => 'page',
				'category'           => 'Pro',
				'autoImportSettings' => false,
			),
			'panel'         => array(
				'widgets_settings'    => array(),
				'elements_categories' => $catgs,
				'messages'            => array(
					'publish_notification' => PrestaHelper::__( 'Hurray! Your Document is live.', 'elementor' ),
				),
			),

			'container'     => 'body',
			'urls'          => array(
				'exit_to_dashboard' => $exit_to_dashboard,
				'preview'           => $preview_w_qs,
				'wp_preview'        => $preview,
				'permalink'         => $preview,
			),
		);
	}
	public function enqueue_scripts() {

		// Set the global data like $post, $authordata and etc
		$plugin = Plugin::$instance;

		// Reset global variable
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'CRAZY_TESTS' ) && CRAZY_TESTS ) ? '' : '.min';

		PrestaHelper::wp_register_script(
			'elementor-editor-modules',
			CRAZY_ASSETS_URL . 'js/editor-modules' . $suffix . '.js',
			array(
				'elementor-common-modules',
			),
			CRAZY_VERSION,
			true
		);
		// Hack for waypoint with editor mode.
		PrestaHelper::wp_register_script(
			'elementor-waypoints',
			CRAZY_ASSETS_URL . 'lib/waypoints/waypoints-for-editor.js',
			array(
				'jquery',
			),
			'4.0.2',
			true
		);
		PrestaHelper::wp_register_script(
			'backbone-marionette',
			CRAZY_ASSETS_URL . 'lib/backbone/backbone.marionette.js',
			array(
				'backbone',
			),
			'2.4.5',
			true
		);

		PrestaHelper::wp_register_script(
			'perfect-scrollbar',
			CRAZY_ASSETS_URL . 'lib/perfect-scrollbar/js/perfect-scrollbar' . $suffix . '.js',
			array(),
			'1.4.0',
			true
		);

		PrestaHelper::wp_register_script(
			'jquery-easing',
			CRAZY_ASSETS_URL . 'lib/jquery-easing/jquery-easing' . $suffix . '.js',
			array(
				'jquery',
			),
			'1.3.2',
			true
		);

		PrestaHelper::wp_register_script(
			'nprogress',
			CRAZY_ASSETS_URL . 'lib/nprogress/nprogress' . $suffix . '.js',
			array(),
			'0.2.0',
			true
		);

		PrestaHelper::wp_register_script(
			'tipsy',
			CRAZY_ASSETS_URL . 'lib/tipsy/tipsy' . $suffix . '.js',
			array(
				'jquery',
			),
			'1.0.0',
			true
		);

		PrestaHelper::wp_register_script(
			'jquery-elementor-select2',
			CRAZY_ASSETS_URL . 'lib/e-select2/js/e-select2.full' . $suffix . '.js',
			array(
				'jquery',
			),
			'4.0.6-rc.1',
			true
		);

		PrestaHelper::wp_register_script(
			'flatpickr',
			CRAZY_ASSETS_URL . 'lib/flatpickr/flatpickr' . $suffix . '.js',
			array(
				'jquery',
			),
			'1.12.0',
			true
		);

		PrestaHelper::wp_register_script(
			'ace',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ace.js',
			array(),
			'1.2.5',
			true
		);

		PrestaHelper::wp_register_script(
			'ace-language-tools',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ext-language_tools.js',
			array(
				'ace',
			),
			'1.2.5',
			true
		);

		PrestaHelper::wp_register_script(
			'jquery-hover-intent',
			CRAZY_ASSETS_URL . 'lib/jquery-hover-intent/jquery-hover-intent' . $suffix . '.js',
			array(),
			'1.0.0',
			true
		);

		PrestaHelper::wp_register_script(
			'nouislider',
			CRAZY_ASSETS_URL . 'lib/nouislider/nouislider' . $suffix . '.js',
			array(),
			'13.0.0',
			true
		);

		PrestaHelper::wp_register_script(
			'autocomplete',
			CRAZY_ASSETS_URL . 'js/autocomplete.min.js',
			array(),
			'13.0.0',
			true
		);
		PrestaHelper::wp_register_script(
			'imagesloaded',
			CRAZY_ASSETS_URL . 'js/imagesloaded.min.js',
			array(),
			'13.0.0',
			true
		);
		PrestaHelper::wp_register_script(
			'jquery-fancybox',
			CRAZY_ASSETS_URL . 'js/fancybox/jquery.fancybox.js',
			array(),
			CRAZY_VERSION,
			true
		);
		PrestaHelper::wp_register_script(
			'clone',
			CRAZY_ASSETS_URL . 'js/clone.js',
			array(),
			CRAZY_VERSION,
			true
		);

		PrestaHelper::wp_register_script(
			'elementor-editor',
			CRAZY_ASSETS_URL . 'js/editor' . '.js',
			array(
				'elementor-common',
				'elementor-editor-modules',
				// 'wp-auth-check',
				'jquery-ui-sortable',
				'jquery-ui-resizable',
				'perfect-scrollbar',
				'nprogress',
				'tipsy',
				'heartbeat',
				'jquery-elementor-select2',
				'flatpickr',
				'ace',
				'ace-language-tools',
				'jquery-hover-intent',
				'nouislider',
				'autocomplete',
				'imagesloaded',
				'jquery-fancybox',
				'clone',
			),
			CRAZY_VERSION,
			true
		);

		/**
		 * Before editor enqueue scripts.
		 *
		 * Fires before CrazyElements editor scripts are enqueued.
		 *
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/editor/before_enqueue_scripts' );
		$document = Plugin::$instance->documents->get_doc_or_auto_save( $this->_post_id );

		// Get document data *after* the scripts hook - so plugins can run compatibility before get data, but *before* enqueue the editor script - so elements can enqueue their own scripts that depended in editor script.
		$editor_data = $this->get_elements_raw_data(); // lets_have_a_look

		$settings = SettingsManager::get_settings_managers_config( true );
		$config   = array(
			'version'                  => CRAZY_VERSION,
			'home_url'                 => __PS_BASE_URI__,
			'data'                     => $editor_data,
			'document'                 => $this->document_config(), // $document->get_config(),
			'current_user_can_publish' => true, // $current_user_can_publish,
			'controls'                 => $plugin->controls_manager->get_controls_data(),
			'elements'                 => $plugin->elements_manager->get_element_types_config(),
			'widgets'                  => $plugin->widgets_manager->get_widget_types_config(),
			'schemes'                  => array(
				'items'           => $plugin->schemes_manager->get_registered_schemes_data(),
				'enabled_schemes' => Schemes_Manager::get_enabled_schemes(),
			),
			'ui' => [
				'darkModeStylesheetURL' => CRAZY_ASSETS_URL . 'css/editor-dark-mode.css',
			],
			'icons'                    => array(
				'libraries' => Icons_Manager::get_icon_manager_tabs_config(),
				'goProURL'  => Utils::get_pro_link( 'https://classydevs.com/pro/?utm_source=icon-library-go-pro&utm_campaign=gopro&utm_medium=wp-dash' ),
			),
			'fa4_to_fa5_mapping_url'   => CRAZY_ASSETS_URL . 'lib/font-awesome/migration/mapping.json',
			'default_schemes'          => $plugin->schemes_manager->get_schemes_defaults(),
			'settings'                 => $settings,
			'system_schemes'           => $plugin->schemes_manager->get_system_schemes(),
			'wp_editor'                => $this->get_wp_editor_config(),
			'settings_page_link'       => PrestaHelper::get_setting_page_url(),
			'elementor_site'           => 'https://classydevs.com/docs/crazy-elements/',
			'docs_elementor_site'      => 'https://classydevs.com/docs/crazy-elements/',
			'help_the_content_url'     => 'https://classydevs.com/docs/crazy-elements/',
			'help_right_click_url'     => 'https://classydevs.com/docs/crazy-elements/',
			'help_flexbox_bc_url'      => 'https://classydevs.com/docs/crazy-elements/',
			'elementPromotionURL'      => 'https://classydevs.com/docs/crazy-elements/',
			'revPromotionURL'          => 'https://classydevs.com/slider-revolution-prestashop/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=revslider&utm_term=revslider&utm_content=revslider',
			'elementActivateURL'       => "https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree",

			'additional_shapes'        => Shapes::get_additional_shapes_for_config(),
			'locked_user'              => false, // $locked_user,
			'user'                     => array(
				'restrictions'     => array(), // $plugin->role_manager->get_user_restrictions_array(),
				'is_administrator' => true, // current_user_can( 'manage_options' ),
				'introduction'     => 'All user', // User::get_introduction_meta(),
			),
			'preview'                  => array(
				'help_preview_error_url'          => 'https://classydevs.com/docs/crazy-elements/',
				'help_preview_http_error_url'     => 'https://classydevs.com/docs/crazy-elements/',
				'help_preview_http_error_500_url' => 'https://classydevs.com/docs/crazy-elements/',
				'debug_data'                      => Loading_Inspection_Manager::instance()->run_inspections(),
			),
			'locale'                   => 'en_US', // get_locale(),
			'rich_editing_enabled'     => true, // filter_var( get_user_meta( get_current_user_id(), 'rich_editing', true ), FILTER_VALIDATE_BOOLEAN ),
			'page_title_selector'      => 'Set the title', // $page_title_selector,
			'tinymceHasCustomConfig'   => class_exists( 'Tinymce_Advanced' ),
			'inlineEditing'            => Plugin::$instance->widgets_manager->get_inline_editing_config(),
			'dynamicTags'              => Plugin::$instance->dynamic_tags->get_config(),
			'editButtons'              => PrestaHelper::get_option( 'elementor_edit_buttons' ),
			'is_connected'             => PrestaHelper::get_option( 'connect_access_token' ),
			'i18n'                     => array(
				'elementor'                                => PrestaHelper::__( 'CrazyElements', 'elementor' ),
				'delete'                                   => PrestaHelper::__( 'Delete', 'elementor' ),
				'cancel'                                   => PrestaHelper::__( 'Cancel', 'elementor' ),
				'got_it'                                   => PrestaHelper::__( 'Got It', 'elementor' ),
				/* translators: %s: Element type. */
				'add_element'                              => PrestaHelper::__( 'Add %s', 'elementor' ),
				/* translators: %s: Element name. */
				'edit_element'                             => PrestaHelper::__( 'Edit %s', 'elementor' ),
				/* translators: %s: Element type. */
				'duplicate_element'                        => PrestaHelper::__( 'Duplicate %s', 'elementor' ),
				/* translators: %s: Element type. */
				'delete_element'                           => PrestaHelper::__( 'Delete %s', 'elementor' ),
				'flexbox_attention_header'                 => PrestaHelper::__( 'Note: Flexbox Changes', 'elementor' ),
				'flexbox_attention_message'                => PrestaHelper::__( 'CrazyElements 1.0 introduces key changes to the layout using CSS Flexbox. Your existing pages might have been affected, please review your page before publishing.', 'elementor' ),

				// Menu.
				'about_elementor'                          => PrestaHelper::__( 'About Crazyelements', 'elementor' ),
				'color_picker'                             => PrestaHelper::__( 'Color Picker', 'elementor' ),
				'elementor_settings'                       => PrestaHelper::__( 'Dashboard Settings', 'elementor' ),
				'global_colors'                            => PrestaHelper::__( 'Default Colors', 'elementor' ),
				'global_fonts'                             => PrestaHelper::__( 'Default Fonts', 'elementor' ),
				'global_style'                             => PrestaHelper::__( 'Style', 'elementor' ),
				'settings'                                 => PrestaHelper::__( 'Settings', 'elementor' ),
				'go_to'                                    => PrestaHelper::__( 'Go To', 'elementor' ),
				'view_page'                                => PrestaHelper::__( 'View Page', 'elementor' ),
				'exit_to_dashboard'                        => PrestaHelper::__( 'Exit To Dashboard', 'elementor' ),

				// Elements.
				'inner_section'                            => PrestaHelper::__( 'Inner Section', 'elementor' ),

				// Control Order.
				'asc'                                      => PrestaHelper::__( 'Ascending order', 'elementor' ),
				'desc'                                     => PrestaHelper::__( 'Descending order', 'elementor' ),

				// Clear Page.
				'clear_page'                               => PrestaHelper::__( 'Delete All Content', 'elementor' ),
				'dialog_confirm_clear_page'                => PrestaHelper::__( 'Attention: We are going to DELETE ALL CONTENT from this page. Are you sure you want to do that?', 'elementor' ),

				// Enable SVG uploads.
				'enable_svg'                               => PrestaHelper::__( 'Enable SVG Uploads', 'elementor' ),
				'dialog_confirm_enable_svg'                => PrestaHelper::__( 'Before you enable SVG upload, note that SVG files include a security risk. CrazyElements does run a process to remove possible malicious code, but there is still risk involved when using such files.', 'elementor' ),

				// Enable fontawesome 5 if needed.
				'enable_fa5'                               => PrestaHelper::__( 'CrazyElements\'s New Icon Library', 'elementor' ),
				'dialog_confirm_enable_fa5'                => PrestaHelper::__( 'CrazyElements v1.0 includes an upgrade from Font Awesome 4 to 5. In order to continue using icons, be sure to click "Upgrade".', 'elementor' ) . ' <a href="https://classydevs.com/docs/crazy-elements/" target="_blank">' . PrestaHelper::__( 'Learn More', 'elementor' ) . '</a>',

				// Panel Preview Mode.
				'back_to_editor'                           => PrestaHelper::__( 'Show Panel', 'elementor' ),
				'preview'                                  => PrestaHelper::__( 'Hide Panel', 'elementor' ),

				// Inline Editing.
				'type_here'                                => PrestaHelper::__( 'Type Here', 'elementor' ),

				// Library.
				'an_error_occurred'                        => PrestaHelper::__( 'An error occurred', 'elementor' ),
				'category'                                 => PrestaHelper::__( 'Category', 'elementor' ),
				'delete_template'                          => PrestaHelper::__( 'Delete Template', 'elementor' ),
				'delete_template_confirm'                  => PrestaHelper::__( 'Are you sure you want to delete this template?', 'elementor' ),
				'import_template_dialog_header'            => PrestaHelper::__( 'Import Document Settings', 'elementor' ),
				'import_template_dialog_message'           => PrestaHelper::__( 'Do you want to also import the document settings of the template?', 'elementor' ),
				'import_template_dialog_message_attention' => PrestaHelper::__( 'Attention: Importing may override previous settings.', 'elementor' ),
				'library'                                  => PrestaHelper::__( 'Library', 'elementor' ),
				'no'                                       => PrestaHelper::__( 'No', 'elementor' ),
				'page'                                     => PrestaHelper::__( 'Page', 'elementor' ),
				/* translators: %s: Template type. */
				'save_your_template'                       => PrestaHelper::__( 'Save Your %s to Library', 'elementor' ),
				'save_your_template_description'           => PrestaHelper::__( 'Your designs will be available for export and reuse on any page or website', 'elementor' ),
				'section'                                  => PrestaHelper::__( 'Section', 'elementor' ),
				'templates_empty_message'                  => PrestaHelper::__( 'This is where your templates should be. Design it. Save it. Reuse it.', 'elementor' ),
				'templates_empty_title'                    => PrestaHelper::__( 'Haven’t Saved Templates Yet?', 'elementor' ),
				'templates_no_favorites_message'           => PrestaHelper::__( 'You can mark any pre-designed template as a favorite.', 'elementor' ),
				'templates_no_favorites_title'             => PrestaHelper::__( 'No Favorite Templates', 'elementor' ),
				'templates_no_results_message'             => PrestaHelper::__( 'Please make sure your search is spelled correctly or try a different words.', 'elementor' ),
				'templates_no_results_title'               => PrestaHelper::__( 'No Results Found', 'elementor' ),
				'templates_request_error'                  => PrestaHelper::__( 'The following error(s) occurred while processing the request:', 'elementor' ),
				'yes'                                      => PrestaHelper::__( 'Yes', 'elementor' ),
				'blocks'                                   => PrestaHelper::__( 'Blocks', 'elementor' ),
				'pages'                                    => PrestaHelper::__( 'Pages', 'elementor' ),
				'my_templates'                             => PrestaHelper::__( 'My Templates', 'elementor' ),

				// Incompatible Device.
				'device_incompatible_header'               => PrestaHelper::__( 'Your browser isn\'t compatible', 'elementor' ),
				'device_incompatible_message'              => PrestaHelper::__( 'Your browser isn\'t compatible with all of CrazyElements\'s editing features. We recommend you switch to another browser like Chrome or Firefox.', 'elementor' ),
				'proceed_anyway'                           => PrestaHelper::__( 'Proceed Anyway', 'elementor' ),

				// Preview not loaded.
				'learn_more'                               => PrestaHelper::__( 'Learn More', 'elementor' ),
				'preview_el_not_found_header'              => PrestaHelper::__( 'Sorry, the content area was not found in your page.', 'elementor' ),
				'preview_el_not_found_message'             => PrestaHelper::__( PrestaHelper::getCurrentError( 'You must call \'the_content\' function in the current template, in order for CrazyElements to work on this page.' ), 'elementor' ),
				// Gallery.
				'delete_gallery'                           => PrestaHelper::__( 'Reset Gallery', 'elementor' ),
				'dialog_confirm_gallery_delete'            => PrestaHelper::__( 'Are you sure you want to reset this gallery?', 'elementor' ),
				/* translators: %s: The number of images. */
				'gallery_images_selected'                  => PrestaHelper::__( '%s Images Selected', 'elementor' ),
				'gallery_no_images_selected'               => PrestaHelper::__( 'No Images Selected', 'elementor' ),
				'insert_media'                             => PrestaHelper::__( 'Insert Media', 'elementor' ),

				// Take Over.
				/* translators: %s: User name. */
				'dialog_user_taken_over'                   => PrestaHelper::__( '%s has taken over and is currently editing. Do you want to take over this page editing?', 'elementor' ),
				'go_back'                                  => PrestaHelper::__( 'Go Back', 'elementor' ),
				'take_over'                                => PrestaHelper::__( 'Take Over', 'elementor' ),

				'login_text'                               => PrestaHelper::__( 'Login', 'elementor' ),
				'cancel_text'                              => PrestaHelper::__( 'Cencel', 'elementor' ),
				'login_title'                              => PrestaHelper::__( 'Login With PS CrazyElements', 'elementor' ),

				// Revisions.
				'dialog_confirm_delete'                    => PrestaHelper::__( 'Are you sure you want to remove this %s?', 'elementor' ),

				// Saver.
				'before_unload_alert'                      => PrestaHelper::__( 'Please note: All unsaved changes will be lost.', 'elementor' ),
				'published'                                => PrestaHelper::__( 'Published', 'elementor' ),
				'publish'                                  => PrestaHelper::__( 'Publish', 'elementor' ),
				'save'                                     => PrestaHelper::__( 'Save', 'elementor' ),
				'saved'                                    => PrestaHelper::__( 'Saved', 'elementor' ),
				'update'                                   => PrestaHelper::__( 'Update', 'elementor' ),
				'enable'                                   => PrestaHelper::__( 'Enable', 'elementor' ),
				'submit'                                   => PrestaHelper::__( 'Submit', 'elementor' ),
				'working_on_draft_notification'            => PrestaHelper::__( 'This is just a draft. Play around and when you\'re done - click update.', 'elementor' ),
				'keep_editing'                             => PrestaHelper::__( 'Keep Editing', 'elementor' ),
				'have_a_look'                              => PrestaHelper::__( 'Have a look', 'elementor' ),
				'view_all_revisions'                       => PrestaHelper::__( 'View All Revisions', 'elementor' ),
				'dismiss'                                  => PrestaHelper::__( 'Dismiss', 'elementor' ),
				'saving_disabled'                          => PrestaHelper::__( 'Saving has been disabled until you’re reconnected.', 'elementor' ),

				// Ajax
				'server_error'                             => PrestaHelper::__( 'Server Error', 'elementor' ),
				'server_connection_lost'                   => PrestaHelper::__( 'Connection Lost', 'elementor' ),
				'unknown_error'                            => PrestaHelper::__( 'Unknown Error', 'elementor' ),

				// Context Menu
				'duplicate'                                => PrestaHelper::__( 'Duplicate', 'elementor' ),
				'copy'                                     => PrestaHelper::__( 'Copy', 'elementor' ),
				'paste'                                    => PrestaHelper::__( 'Paste', 'elementor' ),
				'copy_style'                               => PrestaHelper::__( 'Copy Style', 'elementor' ),
				'paste_style'                              => PrestaHelper::__( 'Paste Style', 'elementor' ),
				'reset_style'                              => PrestaHelper::__( 'Reset Style', 'elementor' ),
				'save_as_global'                           => PrestaHelper::__( 'Save as a Global', 'elementor' ),
				'save_as_block'                            => PrestaHelper::__( 'Save as Template', 'elementor' ),
				'new_column'                               => PrestaHelper::__( 'Add New Column', 'elementor' ),
				'copy_all_content'                         => PrestaHelper::__( 'Copy All Content', 'elementor' ),
				'delete_all_content'                       => PrestaHelper::__( 'Delete All Content', 'elementor' ),
				'navigator'                                => PrestaHelper::__( 'Navigator', 'elementor' ),

				// Right Click Introduction
				'meet_right_click_header'                  => PrestaHelper::__( 'Meet Right Click', 'elementor' ),
				'meet_right_click_message'                 => PrestaHelper::__( 'Now you can access all editing actions using right click.', 'elementor' ),

				// Hotkeys screen
				'keyboard_shortcuts'                       => PrestaHelper::__( 'Keyboard Shortcuts', 'elementor' ),

				// Deprecated Control
				'deprecated_notice'                        => PrestaHelper::__( 'The <strong>%1$s</strong> widget has been deprecated since %2$s %3$s.', 'elementor' ),
				'deprecated_notice_replacement'            => PrestaHelper::__( 'It has been replaced by <strong>%1$s</strong>.', 'elementor' ),
				'deprecated_notice_last'                   => PrestaHelper::__( 'Note that %1$s will be completely removed once %2$s %3$s is released.', 'elementor' ),

				// Preview Debug
				'preview_debug_link_text'                  => PrestaHelper::__( 'Click here for preview debug', 'elementor' ),

				'icon_library'                             => PrestaHelper::__( 'Icon Library', 'elementor' ),
				'my_libraries'                             => PrestaHelper::__( 'My Libraries', 'elementor' ),
				'upload'                                   => PrestaHelper::__( 'Upload', 'elementor' ),
				'icons_promotion'                          => PrestaHelper::__( 'Become a Pro user to upload unlimited font icon folders to your website.', 'elementor' ),
				'go_pro_»'                                 => PrestaHelper::__( 'Go Pro »', 'elementor' ),
				'custom_positioning'                       => PrestaHelper::__( 'Custom Positioning', 'elementor' ),
				'element_promotion_dialog_header'          => PrestaHelper::__( '%s Widget', 'elementor' ),
				'element_promotion_dialog_message'         => PrestaHelper::__( 'Get the pro version of Crazy Elements to use %s widget and dozens more awesome features to extend your toolbox and build sites faster and better.', 'elementor' ),
				'revsix_module_name'                       => 'revsliderprestashop_sixaddons',
				'revsix_promotion'                         => PrestaHelper::__( 'Install Slider Revolution 6 for PrestaShop to use this addon and build and show awesome sliders. <a href="https://classydevs.com/slider-revolution-prestashop/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=revslider&utm_term=revslider&utm_content=revslider" target="_blank"> <img class="rev-promo-img" src="' . CRAZY_ASSETS_URL . 'images/panel-slider-1.png"></a>', 'elementor' ),
				'see_it_in_action'                         => PrestaHelper::__( 'See it in Action', 'elementor' ),
				'activate_now_bt'                          => PrestaHelper::__( 'Get Now!!!', 'elementor' ),
				'get_rev_bt'                               => PrestaHelper::__( 'Get Slider Revolution 6', 'elementor' ),

				// TODO: Remove.
				'autosave'                                 => PrestaHelper::__( 'Autosave', 'elementor' ),
				'elementor_docs'                           => PrestaHelper::__( 'Documentation', 'elementor' ),
				'reload_page'                              => PrestaHelper::__( 'Reload Page', 'elementor' ),
				'session_expired_header'                   => PrestaHelper::__( 'Timeout', 'elementor' ),
				'session_expired_message'                  => PrestaHelper::__( 'Your session has expired. Please reload the page to continue editing.', 'elementor' ),
				'soon'                                     => PrestaHelper::__( 'Soon', 'elementor' ),
				'unknown_value'                            => PrestaHelper::__( 'Unknown Value', 'elementor' ),
				'librarytitle'                             => PrestaHelper::__( 'Connect to Crazyelements Template Library', 'elementor' ),
				'librarymessage'                           => PrestaHelper::__( 'Create a personal account for free and access this template and our entire library.', 'elementor' ),
				'librarybutton'                            => PrestaHelper::__( 'Get Started', 'elementor' ),
			),
		);

		$localized_settings = array();

		/**
		 * Localize editor settings.
		 *
		 * Filters the editor localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $localized_settings Localized settings.
		 * @param int   $post_id            The ID of the current post being edited.
		 */
		$localized_settings = PrestaHelper::apply_filters( 'elementor/editor/localize_settings', $localized_settings, $this->_post_id );

		if ( ! empty( $localized_settings ) ) {
			$config = array_replace_recursive( $config, $localized_settings );
		}

		Utils::print_js_config( 'elementor-editor', 'ElementorConfig', $config );
		PrestaHelper::wp_enqueue_script( 'elementor-editor' );

		$plugin->controls_manager->enqueue_control_scripts();

		/**
		 * After editor enqueue scripts.
		 *
		 * Fires after CrazyElements editor scripts are enqueued.
		 *
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/editor/after_enqueue_scripts' );
	}


	function get_elements_raw_data() {
		$get_elements_data = CrazyElements::dataProcessing( null, 'get_elements_data' );
		$with_html_content = true;
		$get_elements_data = json_decode( $get_elements_data, true );
		$editor_data       = array();
		if ( $get_elements_data == null ) {
			$get_elements_data = array();

		}
		foreach ( $get_elements_data as $element_data ) {
			$element = Plugin::$instance->elements_manager->create_element_instance( $element_data );
			if ( ! $element ) {
				continue;
			}
			$editor_data[] = $element->get_raw_data( $with_html_content );
		}
		return $editor_data;
	}
	/**
	 * Enqueue styles.
	 *
	 * Registers all the editor styles and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		/**
		   * Before editor enqueue styles.
		   *
		   * Fires before CrazyElements editor styles are enqueued.
		   *
		   * @since 1.0.0
		   */
		PrestaHelper::do_action( 'elementor/editor/before_enqueue_styles' );

		$suffix = Utils::is_script_debug() ? '' : '.min';

		$direction_suffix = PrestaHelper::is_rtl() ? '-rtl' : '';

		PrestaHelper::wp_register_style(
			'font-awesome',
			CRAZY_ASSETS_URL . 'lib/font-awesome/css/font-awesome' . $suffix . '.css',
			array(),
			'4.7.0'
		);

		PrestaHelper::wp_register_style(
			'elementor-common',
			CRAZY_ASSETS_URL . 'css/common' . $suffix . '.css',
			array(),
			CRAZY_VERSION
		);

		PrestaHelper::wp_register_style(
			'elementor-select2',
			CRAZY_ASSETS_URL . 'lib/e-select2/css/e-select2' . $suffix . '.css',
			array(),
			'4.0.6-rc.1'
		);

		PrestaHelper::wp_register_style(
			'google-font-roboto',
			'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700',
			array(),
			CRAZY_VERSION
		);
		PrestaHelper::wp_register_style(
			'google-font-Material',
			'https://fonts.googleapis.com/icon?family=Material+Icons',
			array(),
			CRAZY_VERSION
		);

		PrestaHelper::wp_register_style(
			'flatpickr',
			CRAZY_ASSETS_URL . 'lib/flatpickr/flatpickr' . $suffix . '.css',
			array(),
			'1.12.0'
		);

		PrestaHelper::wp_register_style(
			'query-fancybox-css',
			CRAZY_ASSETS_URL . 'js/fancybox/jquery.fancybox.css',
			array(),
			CRAZY_VERSION
		);

		PrestaHelper::wp_register_style(
			'elementor-editor',
			CRAZY_ASSETS_URL . 'css/editor' . $direction_suffix . $suffix . '.css',
			array(
				'font-awesome',
				'elementor-common',
				'elementor-select2',
				'ce-icons',
				'google-font-roboto',
				'google-font-Material',
				'flatpickr',
				'query-fancybox-css',
			),
			CRAZY_VERSION
		);

		PrestaHelper::wp_enqueue_style( 'elementor-editor' );

		if ( Responsive::has_custom_breakpoints() ) {
			$breakpoints = Responsive::get_breakpoints();

			wp_add_inline_style( 'elementor-editor', '.elementor-device-tablet #elementor-preview-responsive-wrapper { width: ' . $breakpoints['md'] . 'px; }' );
		}

		/**
		 * After editor enqueue styles.
		 *
		 * Fires after CrazyElements editor styles are enqueued.
		 *
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/editor/after_enqueue_styles' );
	}

	/**
	 * Get WordPress editor config.
	 *
	 * Config the default WordPress editor with custom settings for CrazyElements use.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function get_wp_editor_config() {
		ob_start();

		Editor_core::editor(
			'%%EDITORCONTENT%%',
			'elementorwpeditor',
			array(
				'editor_class'     => 'elementor-wp-editor',
				'editor_height'    => 250,
				'drag_drop_upload' => true,
			)
		);

		$config = ob_get_clean();
		Editor_core::enqueue_scripts();
		Editor_core::editor_js();

		return $config;
	}

	/**
	 * Editor head trigger.
	 *
	 * Fires the 'elementor/editor/wp_head' action in the head tag in CrazyElements
	 * editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_head_trigger() {
		 /**
		 * CrazyElements editor head.
		 *
		 * Fires on CrazyElements editor head tag.
		 *
		 * Used to prints scripts or any other data in the head tag.
		 *
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/editor/wp_head' );
	}

	/**
	 * Add editor template.
	 *
	 * Registers new editor templates.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param string $template Can be either a link to template file or template
	 *                         HTML content.
	 * @param string $type     Optional. Whether to handle the template as path
	 *                         or text. Default is `path`.
	 */
	public function add_editor_template( $template, $type = 'path' ) {
		$common = Plugin::$instance->common;

		if ( $common ) {
			Plugin::$instance->common->add_template( $template, $type );
		}
	}

	/**
	 * WP footer.
	 *
	 * Prints CrazyElements editor with all the editor templates, and render controls,
	 * widgets and content elements.
	 *
	 * Fired by `wp_footer` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_footer() {
		$plugin = Plugin::$instance;

		$plugin->controls_manager->render_controls();

		$plugin->widgets_manager->render_widgets_content();
		$plugin->elements_manager->render_elements_content();

		$plugin->schemes_manager->print_schemes_templates();

		$plugin->dynamic_tags->print_templates();

		$this->init_editor_templates();

		/**
		 * CrazyElements editor footer.
		 *
		 * Fires on CrazyElements editor before closing the body tag.
		 *
		 * Used to prints scripts or any other HTML before closing the body tag.
		 *
		 * @since 1.0.0
		 */

		PrestaHelper::do_action( 'elementor/editor/footer' );
	}

	/**
	 * Set edit mode.
	 *
	 * Used to update the edit mode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool $edit_mode Whether the edit mode is active.
	 */
	public function set_edit_mode( $edit_mode ) {
		$this->_is_edit_mode = $edit_mode;
	}

	/**
	 * Editor constructor.
	 *
	 * Initializing CrazyElements editor and redirect from old URL structure of
	 * CrazyElements editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		PrestaHelper::add_action( 'admin_action_elementor', array( $this, 'init' ) );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function filter_wp_link_query_args( $query ) {
		$library_cpt_key = array_search( Source_Local::CPT, $query['post_type'], true );
		if ( false !== $library_cpt_key ) {
			unset( $query['post_type'][ $library_cpt_key ] );
		}

		return $query;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function filter_wp_link_query( $results ) {
		if ( isset( $_POST['editor'] ) && 'elementor' === $_POST['editor'] ) {
			$post_type_object = get_post_type_object( 'post' );
			$post_label       = $post_type_object->labels->singular_name;

			foreach ( $results as &$result ) {
				if ( 'post' === get_post_type( $result['ID'] ) ) {
					$result['info'] = $post_label;
				}
			}
		}

		return $results;
	}

	/**
	 * Create nonce.
	 *
	 * If the user has edit capabilities, it creates a cryptographic token to
	 * give him access to CrazyElements editor.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param string $post_type The post type to check capabilities.
	 *
	 * @return null|string The nonce token, or `null` if the user has no edit
	 *                     capabilities.
	 */
	public function create_nonce( $post_type ) {
		// _deprecated_function( __METHOD__, '2.3.0', 'Plugin::$instance->common->get_component( \'ajax\' )->create_nonce()' );

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		return $ajax->create_nonce();
	}

	/**
	 * Verify nonce.
	 *
	 * The user is given an amount of time to use the token, so therefore, since
	 * the user ID and `$action` remain the same, the independent variable is
	 * the time.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param string $nonce Nonce to verify.
	 *
	 * @return false|int If the nonce is invalid it returns `false`. If the
	 *                   nonce is valid and generated between 0-12 hours ago it
	 *                   returns `1`. If the nonce is valid and generated
	 *                   between 12-24 hours ago it returns `2`.
	 */
	public function verify_nonce( $nonce ) {
		// _deprecated_function( __METHOD__, '2.3.0', 'wp_verify_nonce()' );

		return wp_verify_nonce( $nonce );
	}

	/**
	 * Verify request nonce.
	 *
	 * Whether the request nonce verified or not.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return bool True if request nonce verified, False otherwise.
	 */
	public function verify_request_nonce() {
		// _deprecated_function( __METHOD__, '2.3.0', 'Plugin::$instance->common->get_component( \'ajax\' )->verify_request_nonce()' );

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		return $ajax->verify_request_nonce();
	}

	/**
	 * Verify ajax nonce.
	 *
	 * Verify request nonce and send a JSON request, if not verified returns an
	 * error.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function verify_ajax_nonce() {

		/** @var Ajax $ajax */
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		if ( ! $ajax->verify_request_nonce() ) {
			wp_send_json_error( new \WP_Error( 'token_expired', 'Nonce token expired.' ) );
		}
	}

	/**
	 * @since 1.0.0
	 * @access private
	 */
	private function init_editor_templates() {
		$template_names = array(
			'global',
			'panel',
			'panel-elements',
			'repeater',
			'templates',
			'navigator',
			'hotkeys',
			'history-panel-template',
			'revisions-panel-template',
		);

		foreach ( $template_names as $template_name ) {
			Plugin::$instance->common->add_template( CRAZY_PATH . "includes/editor-templates/$template_name.php" );
		}
	}
}