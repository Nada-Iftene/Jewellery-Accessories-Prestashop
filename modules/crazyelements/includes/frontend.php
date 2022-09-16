<?php
namespace CrazyElements;
use CrazyElements\Core\Base\App;
use CrazyElements\Core\Base\Document;
use CrazyElements\Core\Responsive\Files\Frontend as FrontendFile;
use CrazyElements\Core\Files\CSS\Global_CSS;
use CrazyElements\Core\Files\CSS\Post as Post_CSS;
use CrazyElements\Core\Files\CSS\Post_Preview;
use CrazyElements\Core\Responsive\Responsive;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\PrestaHelper;


if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Frontend extends App {

	const THE_CONTENT_FILTER_PRIORITY = 9;

	private $post_id;

	public $fonts_to_enqueue = [];

	private $registered_fonts = [];

	private $icon_fonts_to_enqueue = [];

	private $enqueued_icon_fonts = [];

	private $_has_elementor_in_page = false;

	private $_is_excerpt = false;

	private $content_removed_filters = [];

	private $admin_bar_edit_documents = [];

	private $body_classes = [
		'elementor-default',
	];

	public function __construct() {
		PrestaHelper::add_action( 'template_redirect', [ $this, 'init' ] );
		PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 5 );
		PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'register_styles' ], 5 );
		PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		$this->enqueue_styles();
		$this->add_content_filter();
		// Hack to avoid enqueue post CSS while it's a `the_excerpt` call.
		PrestaHelper::add_filter( 'get_the_excerpt', [ $this, 'start_excerpt_flag' ], 1 );
		PrestaHelper::add_filter( 'get_the_excerpt', [ $this, 'end_excerpt_flag' ], 20 );
	}

	public function get_name() {
		return 'frontend';
	}

	public function init() {
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}
		PrestaHelper::add_filter( 'body_class', [ $this, 'body_class' ] );
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}
		if ( current_user_can( 'manage_options' ) ) {
			Plugin::$instance->init_common();
		}
		$this->post_id = get_the_ID();
		if ( is_singular() && Plugin::$instance->db->is_built_with_elementor( $this->post_id ) ) {
			PrestaHelper::add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		}
		PrestaHelper::add_action( 'wp_head', [ $this, 'print_fonts_links' ], 7 );
		PrestaHelper::add_action( 'wp_footer', [ $this, 'wp_footer' ] );
	}

	public function add_body_class( $class ) {
		if ( is_array( $class ) ) {
			$this->body_classes = array_merge( $this->body_classes, $class );
		} else {
			$this->body_classes[] = $class;
		}
	}

	public function body_class( $classes = [] ) {
		$classes = array_merge( $classes, $this->body_classes );
		$id = get_the_ID();
		if ( is_singular() && Plugin::$instance->db->is_built_with_elementor( $id ) ) {
			$classes[] = 'elementor-page elementor-page-' . $id;
		}
		return $classes;
	}

	public function add_content_filter() {
		PrestaHelper::add_filter( 'the_content', [ $this, 'apply_builder_in_content' ], self::THE_CONTENT_FILTER_PRIORITY );
	}

	public function remove_content_filter() {
		remove_filter( 'the_content', [ $this, 'apply_builder_in_content' ], self::THE_CONTENT_FILTER_PRIORITY );
	}

	public function register_scripts() {
		PrestaHelper::do_action( 'elementor/frontend/before_register_scripts' );
		PrestaHelper::wp_register_script(
			'elementor-frontend-modules',
			$this->get_js_assets_url( 'frontend-modules' ),
			[
				'jquery',
			],
			CRAZY_VERSION,
			true
		);
		PrestaHelper::wp_register_script(
			'elementor-waypoints',
			$this->get_js_assets_url( 'waypoints', 'assets/lib/waypoints/' ),
			[
				'jquery',
			],
			'4.0.2',
			true
		);
		PrestaHelper::wp_register_script(
			'flatpickr',
			$this->get_js_assets_url( 'flatpickr', 'assets/lib/flatpickr/' ),
			[
				'jquery',
			],
			'4.1.4',
			true
		);
		PrestaHelper::wp_register_script(
			'imagesloaded',
			$this->get_js_assets_url( 'imagesloaded', 'assets/lib/imagesloaded/' ),
			[
				'jquery',
			],
			'4.1.0',
			true
		);
		PrestaHelper::wp_register_script(
			'jquery-numerator',
			$this->get_js_assets_url( 'jquery-numerator', 'assets/lib/jquery-numerator/' ),
			[
				'jquery',
			],
			'0.2.1',
			true
		);
		PrestaHelper::wp_register_script(
			'swiper',
			$this->get_js_assets_url( 'swiper', 'assets/lib/swiper/' ),
			[],
			'4.4.6',
			true
		);
		PrestaHelper::wp_register_script(
			'jquery-slick',
			$this->get_js_assets_url( 'slick', 'assets/lib/slick/' ),
			[
				'jquery',
			],
			'1.8.1',
			true
		);
		PrestaHelper::wp_register_script(
			'elementor-dialog',
			$this->get_js_assets_url( 'dialog', 'assets/lib/dialog/' ),
			[
				'jquery-ui-position',
			],
			'4.7.1',
			true
		);
		PrestaHelper::wp_register_script(
			'elementor-frontend',
			$this->get_js_assets_url( 'frontend' ),
			[
				'elementor-frontend-modules',
				'elementor-dialog',
				'elementor-waypoints',
				'swiper',
			],
			CRAZY_VERSION,
			true
		);
		PrestaHelper::do_action( 'elementor/frontend/after_register_scripts' );
	}

	public function register_styles() {
		PrestaHelper::do_action( 'elementor/frontend/before_register_styles' );
		PrestaHelper::wp_register_style(
			'font-awesome',
			$this->get_css_assets_url( 'font-awesome', 'assets/lib/font-awesome/css/' ),
			[],
			'4.7.0'
		);
		PrestaHelper::wp_register_style(
			'ce-icons',
			$this->get_css_assets_url( 'ce-icons', 'assets/lib/ceicons/css/' ),
			[],
			'5.3.0'
		);
		PrestaHelper::wp_register_style(
			'elementor-animations',
			$this->get_css_assets_url( 'animations', 'assets/lib/animations/', true ),
			[],
			CRAZY_VERSION
		);
		PrestaHelper::wp_register_style(
			'flatpickr',
			$this->get_css_assets_url( 'flatpickr', 'assets/lib/flatpickr/' ),
			[],
			'4.1.4'
		);
		$min_suffix = Utils::is_script_debug() ? '' : '.min';
		$direction_suffix = PrestaHelper::is_rtl() ? '-rtl' : '';
		$frontend_file_name = 'frontend' . $direction_suffix . $min_suffix . '.css';
		$has_custom_file = Responsive::has_custom_breakpoints();
		if ( $has_custom_file ) {
			$frontend_file = new FrontendFile( 'custom-' . $frontend_file_name, Responsive::get_stylesheet_templates_path() . $frontend_file_name );
			$time = $frontend_file->get_meta( 'time' );
			if ( ! $time ) {
				$frontend_file->update();
			}
			$frontend_file_url = $frontend_file->get_url();
		} else {
			$frontend_file_url = CRAZY_ASSETS_URL . 'css/' . $frontend_file_name;
		}
		PrestaHelper::wp_register_style(
			'elementor-frontend',
			$frontend_file_url,
			[],
			$has_custom_file ? null : CRAZY_VERSION
		);
		PrestaHelper::do_action( 'elementor/frontend/after_register_styles' );
	}

	public function enqueue_scripts() {
		PrestaHelper::do_action( 'elementor/frontend/before_enqueue_scripts' );
		PrestaHelper::wp_enqueue_script( 'elementor-frontend' );
		$this->print_config();
		PrestaHelper::do_action( 'elementor/frontend/after_enqueue_scripts' );
	}

	public function enqueue_styles() {
		PrestaHelper::do_action( 'elementor/frontend/after_enqueue_styles' );
		$this->parse_global_css_code();
		$post_id = PrestaHelper::$id_content_global;
        $type = PrestaHelper::$hook_current;
		if($post_id=='' || $post_id== null){
			$post_id = \Tools::getValue('elementor_library');
		}
		
		

        if($type != 'cms' &&
            $type != 'product' &&
            $type != 'supplier' &&
            $type != 'category' &&
            $type != 'manufacturer'
        ){
			$isextended = PrestaHelper::check_extended_frontcontroller($type);
			if(!$isextended){
				$type = 'page';
			}
        }

        $css_file =  Post_CSS::create( $post_id,$type );
        $css_file->enqueue();//  commented_line found 1
	}

	
	public function wp_footer() {
		if ( ! $this->_has_elementor_in_page ) {
			return;
		}
		$this->enqueue_styles();
		$this->enqueue_scripts();
		$this->print_fonts_links();
	}

	public function print_fonts_links() {
		$google_fonts = [
			'google' => [],
			'early' => [],
		];
		foreach ( $this->fonts_to_enqueue as $key => $font ) {
			$font_type = Fonts::get_font_type( $font );
			switch ( $font_type ) {
				case Fonts::GOOGLE:
					$google_fonts['google'][] = $font;
					break;
				case Fonts::EARLYACCESS:
					$google_fonts['early'][] = $font;
					break;
				case false:
					$this->maybe_enqueue_icon_font( $font );
					break;
				default:
					PrestaHelper::do_action( "elementor/fonts/print_font_links/{$font_type}", $font );
			}
		}
		$this->fonts_to_enqueue = [];
		$this->enqueue_google_fonts( $google_fonts );
		$this->enqueue_icon_fonts();
	}

	private function maybe_enqueue_icon_font( $icon_font_type ) {
		if(is_array($icon_font_type)){
			$icon_font_type=key($icon_font_type);
		}
		if ( ! Icons_Manager::is_migration_allowed() ) {
			return;
		}
		$icons_types = Icons_Manager::get_icon_manager_tabs();
		if ( ! isset( $icons_types[ $icon_font_type ] ) ) {
			return;
		}
		$icon_type = $icons_types[ $icon_font_type ];
		if ( isset( $icon_type['url'] ) ) {
			$this->icon_fonts_to_enqueue[ $icon_font_type ] = [ $icon_type['url'] ];
		}
	}

	private function enqueue_icon_fonts() {
		if ( empty( $this->icon_fonts_to_enqueue ) || ! Icons_Manager::is_migration_allowed() ) {
			return;
		}
		foreach ( $this->icon_fonts_to_enqueue as $icon_type => $css_url ) {
			PrestaHelper::wp_enqueue_style( 'ce-icons-' . $icon_type );
			$this->enqueued_icon_fonts[] = $css_url;
		}
		//clear enqueued icons
		$this->icon_fonts_to_enqueue = [];
	}

	
	private function enqueue_google_fonts( $google_fonts = [] ) {
		static $google_fonts_index = 0;

		$print_google_fonts = true;
		$print_google_fonts = PrestaHelper::apply_filters( 'elementor/frontend/print_google_fonts', $print_google_fonts );
		if ( ! $print_google_fonts ) {
			return;
		}
		// Print used fonts
		if ( ! empty( $google_fonts['google'] ) ) {
			$google_fonts_index++;
			foreach ( $google_fonts['google'] as &$font ) {
				$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
			}
			$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts['google'] ) );
			$subsets = [
				'ru_RU' => 'cyrillic',
				'bg_BG' => 'cyrillic',
				'he_IL' => 'hebrew',
				'el' => 'greek',
				'vi' => 'vietnamese',
				'uk' => 'cyrillic',
				'cs_CZ' => 'latin-ext',
				'ro_RO' => 'latin-ext',
				'pl_PL' => 'latin-ext',
			];
			$context=\Context::getContext();
			$locale= $context->language->iso_code;
			if ( isset( $subsets[ $locale ] ) ) {
				$fonts_url .= '&subset=' . $subsets[ $locale ];
			}
			printf( '<link rel="stylesheet" type="text/css" href="%s">',  $fonts_url ) ;
		}
		if ( ! empty( $google_fonts['early'] ) ) {
			foreach ( $google_fonts['early'] as $current_font ) {
				$google_fonts_index++;
				$font_url = sprintf( 'https://fonts.googleapis.com/earlyaccess/%s.css', strtolower( str_replace( ' ', '', $current_font ) ) );
				PrestaHelper::wp_enqueue_style( 'google-earlyaccess-' . $google_fonts_index, $font_url ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			}
		}
	}

	public function enqueue_font( $font ) {
		if ( in_array( $font, $this->registered_fonts ) ) {
			return;
		}
		$this->fonts_to_enqueue[] = $font;
		$this->registered_fonts[] = $font;
		$this->print_fonts_links();
	}


	protected function parse_global_css_code() {
		$scheme_css_file =  Global_CSS::create( 'global.css' );
		$scheme_css_file->enqueue();
	}

	
	public function apply_builder_in_content( $content ) {
		$this->restore_content_filters();
		if ( Plugin::$instance->preview->is_preview_mode() || $this->_is_excerpt ) {
			return $content;
		}
		// Remove the filter itself in order to allow other `the_content` in the elements
		$this->remove_content_filter();
		$post_id = get_the_ID();
		$builder_content = $this->get_builder_content( $post_id );
		if ( ! empty( $builder_content ) ) {
			$content = $builder_content;
			$this->remove_content_filters();
		}
		// Add the filter again for other `the_content` calls
		$this->add_content_filter();
		return $content;
	}

	public function get_builder_content( $post_id, $with_css = false ) {
		if ( post_password_required( $post_id ) ) {
			return '';
		}
		if ( ! Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
			return '';
		}
		$document = Plugin::$instance->documents->get_doc_for_frontend( $post_id );
		// Change the current post, so widgets can use `documents->get_current`.
		Plugin::$instance->documents->switch_to_document( $document );
		if ( $document->is_editable_by_current_user() ) {
			$this->admin_bar_edit_documents[ $document->get_main_id() ] = $document;
		}
		$data = $document->get_elements_data();
		$data = PrestaHelper::apply_filters( 'elementor/frontend/builder_content_data', $data, $post_id );
		if ( empty( $data ) ) {
			return '';
		}
		if ( ! $this->_is_excerpt ) {
			if ( $document->is_autosave() ) {
				$css_file = new Post_Preview( $document->get_post()->ID );
			} else {
				$post_id = PrestaHelper::$id_content_global;
                 $type = PrestaHelper::$hook_current;

				if($post_id=='' || $post_id== null){
					$post_id = \Tools::getValue('elementor_library');
				}
                if($type != 'cms' &&
                    $type != 'product' &&
                    $type != 'supplier' &&
                    $type != 'category' &&
                    $type != 'manufacturer'
                ){
                    $type = 'page';
                }
                $css_file =  Post_CSS::create( $post_id,$type );
			}
			$css_file->enqueue(); //commented_line
		}
		ob_start();
		if ( is_customize_preview() || wp_doing_ajax() ) {
			$with_css = true;
		}
		if ( ! empty( $css_file ) && $with_css ) {
			$css_file->print_css();
		}
		$document->print_elements_with_wrapper( $data );
		$content = ob_get_clean();
		$content = $this->process_more_tag( $content );
		$content = PrestaHelper::apply_filters( 'elementor/frontend/the_content', $content );
		if ( ! empty( $content ) ) {
			$this->_has_elementor_in_page = true;
		}
		Plugin::$instance->documents->restore_document();
		return $content;
	}

	public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		if ( empty( $this->admin_bar_edit_documents ) ) {
			return;
		}
		$queried_object_id = get_queried_object_id();
		$menu_args = [
			'id' => 'elementor_edit_page',
			'title' => PrestaHelper::__( 'Edit with Elementor', 'elementor' ),
		];
		if ( is_singular() && isset( $this->admin_bar_edit_documents[ $queried_object_id ] ) ) {
			$menu_args['href'] = $this->admin_bar_edit_documents[ $queried_object_id ]->get_edit_url();
			unset( $this->admin_bar_edit_documents[ $queried_object_id ] );
		}
		$wp_admin_bar->add_node( $menu_args );
		foreach ( $this->admin_bar_edit_documents as $document ) {
			$wp_admin_bar->add_menu( [
				'id' => 'elementor_edit_doc_' . $document->get_main_id(),
				'parent' => 'elementor_edit_page',
				'title' => sprintf( '<span class="elementor-edit-link-title">%s</span><span class="elementor-edit-link-type">%s</span>', $document->get_post()->post_title, $document::get_title() ),
				'href' => $document->get_edit_url(),
			] );
		}
	}

	public function get_builder_content_for_display( $post_id, $with_css = false ) {
		if ( ! get_post( $post_id ) ) {
			return '';
		}
		$editor = Plugin::$instance->editor;
		// Avoid recursion
		if ( get_the_ID() === (int) $post_id ) {
			$content = '';
			if ( $editor->is_edit_mode() ) {
				$content = '<div class="elementor-alert elementor-alert-danger">' . PrestaHelper::__( 'Invalid Data: The Template ID cannot be the same as the currently edited template. Please choose a different one.', 'elementor' ) . '</div>';
			}
			return $content;
		}
		// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
		$is_edit_mode = $editor->is_edit_mode();
		$editor->set_edit_mode( false );
		$with_css = $with_css ? true : $is_edit_mode;
		$content = $this->get_builder_content( $post_id, $with_css );
		// Restore edit mode state
		Plugin::$instance->editor->set_edit_mode( $is_edit_mode );
		return $content;
	}

	public function start_excerpt_flag( $excerpt ) {
		$this->_is_excerpt = true;
		return $excerpt;
	}

	public function end_excerpt_flag( $excerpt ) {
		$this->_is_excerpt = false;
		return $excerpt;
	}

	public function remove_content_filters() {
		$filters = [
			'wpautop',
			'shortcode_unautop',
			'wptexturize',
		];
		foreach ( $filters as $filter ) {
			// Check if another plugin/theme do not already removed the filter.
			if ( has_filter( 'the_content', $filter ) ) {
				remove_filter( 'the_content', $filter );
				$this->content_removed_filters[] = $filter;
			}
		}
	}

	public function has_elementor_in_page() {
		return $this->_has_elementor_in_page;
	}

    public function loadElementsFrontend($id_crazy_content = null){
        Plugin::$instance->documents->loadElementsFromManager($id_crazy_content);
    }
    public function loadElementsFrontendTemplate($element_data){
		Plugin::$instance->documents->loadElementsForTemplate($element_data);
    }
    
	protected function get_init_settings() {
		$is_preview_mode = Plugin::$instance->preview->is_preview_mode( Plugin::$instance->preview->get_post_id() );
		$is_preview_mode =PrestaHelper::is_admin();
		$settings = [
			'environmentMode' => [
				'edit' => $is_preview_mode,
				'wpPreview' => is_preview(),
			],
			'is_rtl' => PrestaHelper::is_rtl(),
			'breakpoints' => Responsive::get_breakpoints(),
			'version' => CRAZY_VERSION,
			'urls' => [
				'assets' => CRAZY_ASSETS_URL,
			],
		];
		$settings['settings'] = SettingsManager::get_settings_frontend_config();
		if ( is_singular() ) {
			$post = get_post();
			$settings['post'] = [
				'id' => $post->ID,
				'title' => $post->post_title,
				'excerpt' => $post->post_excerpt,
			];
		} else {
			$settings['post'] = [
				'id' => 0,
				'title' => wp_get_document_title(),
				'excerpt' => '',
			];
		}
		$empty_object = (object) [];
		if ( $is_preview_mode ) {
			$settings['elements'] = [
				'data' => $empty_object,
				'editSettings' => $empty_object,
				'keys' => $empty_object,
			];
		}
		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( ! empty( $user->roles ) ) {
				$settings['user'] = [
					'roles' => $user->roles,
				];
			}
		}
		return $settings;
	}

	private function restore_content_filters() {
		foreach ( $this->content_removed_filters as $filter ) {
			PrestaHelper::add_filter( 'the_content', $filter );
		}
		$this->content_removed_filters = [];
	}

	private function process_more_tag( $content ) {
		$post = get_post();
		$content = str_replace( '&lt;!--more--&gt;', '<!--more-->', $content );
		$parts = get_extended( $content );
		if ( empty( $parts['extended'] ) ) {
			return $content;
		}
		if ( is_singular() ) {
			return $parts['main'] . '<div id="more-' . $post->ID . '"></div>' . $parts['extended'];
		}
		if ( empty( $parts['more_text'] ) ) {
			$parts['more_text'] = PrestaHelper::__( '(more&hellip;)', 'elementor' );
		}
		$more_link_text = sprintf(
			'<span aria-label="%1$s">%2$s</span>',
			sprintf(
				/* translators: %s: Name of current post */
				PrestaHelper::__( 'Continue reading %s', 'elementor' ),
				the_title_attribute( [
					'echo' => false,
				] )
			),
			$parts['more_text']
		);
		$more_link = PrestaHelper::apply_filters( 'the_content_more_link', sprintf( ' <a href="%s#more-%s" class="more-link elementor-more-link">%s</a>', get_permalink(), $post->ID, $more_link_text ), $more_link_text );
		return force_balance_tags( $parts['main'] ) . $more_link;
	}
}