<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 
if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Preview {

	private $post_id;

	public function init() {
		if ( PrestaHelper::is_admin() || ! $this->is_preview_mode() ) {
			return;
		}
		if ( isset( $_GET['preview-debug'] ) ) {
			register_shutdown_function( function () {
				$e = error_get_last();
				if ( $e ) {
					echo '<div id="elementor-preview-debug-error"><pre>';
					echo $e['message'];
					echo '</pre></div>';
				}
			} );
		}

		$this->post_id = PrestaHelper::$id_content_global;

		remove_action( 'template_redirect', 'redirect_canonical' );
		if ( class_exists( 'WPSEO_Frontend' ) ) {
			remove_action( 'template_redirect', [ \WPSEO_Frontend::get_instance(), 'clean_permalink' ], 1 );
		}
		PrestaHelper::add_filter( 'show_admin_bar', '__return_false' );
		PrestaHelper::add_action( 'wp_enqueue_scripts', function() {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		} );
		PrestaHelper::add_filter( 'the_content', [ $this, 'builder_wrapper' ], 999999 );
		PrestaHelper::add_action( 'wp_footer', [ $this, 'wp_footer' ] );
		PrestaHelper::add_filter( 'script_loader_tag', [ $this, 'rocket_loader_filter' ], 10, 3 );
		Utils::do_not_cache();
		PrestaHelper::do_action( 'elementor/preview/init', $this );
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function is_preview_mode( $post_id = 0 ) {
		if ( empty( $post_id ) ) {
			$post_id = PrestaHelper::$id_content_global;//get_the_ID();
		}
		if ( ! User::is_current_user_can_edit( $post_id ) ) {
			return false;
		}
		if ( ! isset( $_GET['elementor-preview'] ) || $post_id !== (int) $_GET['elementor-preview'] ) {
			return false;
		}
		return true;
	}

	
    
    //lets_have_a_look
	public function builder_wrapper( $content ) {
		if ( PrestaHelper::$id_content_global === $this->post_id ) {
			$document = Plugin::$instance->documents->get( $this->post_id );
			$attributes = $document->get_container_attributes();
			$attributes['id'] = 'elementor';
			$attributes['class'] .= ' elementor-edit-mode';
			$content = '<div ' . Utils::render_html_attributes( $attributes ) . '></div>';
		}
		return $content;
	}

	
	private function enqueue_styles() {
		// Hold-on all jQuery plugins after all HTML markup render.
		wp_add_inline_script( 'jquery-migrate', 'jQuery.holdReady( true );' );
		Plugin::$instance->frontend->enqueue_styles();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$direction_suffix = PrestaHelper::is_rtl() ? '-rtl' : '';
		PrestaHelper::wp_register_style(
			'elementor-select2',
			CRAZY_ASSETS_URL . 'lib/e-select2/css/e-select2' . $suffix . '.css',
			[],
			'4.0.6-rc.1'
		);
		PrestaHelper::wp_register_style(
			'editor-preview',
			CRAZY_ASSETS_URL . 'css/editor-preview' . $direction_suffix . $suffix . '.css',
			[
				'elementor-select2',
			],
			CRAZY_VERSION
		);
		PrestaHelper::wp_enqueue_style( 'editor-preview' );
		PrestaHelper::do_action( 'elementor/preview/enqueue_styles' );
	}

	private function enqueue_scripts() {
		Plugin::$instance->frontend->register_scripts();
		Plugin::$instance->widgets_manager->enqueue_widgets_scripts();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		PrestaHelper::wp_enqueue_script(
			'elementor-inline-editor',
			CRAZY_ASSETS_URL . 'lib/inline-editor/js/inline-editor' . $suffix . '.js',
			[],
			CRAZY_VERSION,
			true
		);
		PrestaHelper::do_action( 'elementor/preview/enqueue_scripts' );
	}

	public function rocket_loader_filter( $tag, $handle, $src ) {
		return str_replace( '<script', '<script data-cfasync="false"', $tag );
	}

	public function wp_footer() {
		$frontend = Plugin::$instance->frontend;
		if ( $frontend->has_elementor_in_page() ) {
			$frontend->wp_footer();
		} else {
			$frontend->enqueue_scripts();
		}
	}

	public function __construct() {
		PrestaHelper::add_action( 'template_redirect', [ $this, 'init' ], 0 );
	}
}