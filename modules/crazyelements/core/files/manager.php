<?php
namespace CrazyElements\Core\Files;

use CrazyElements\Core\Files\CSS\Global_CSS;
use CrazyElements\Core\Files\CSS\Post as Post_CSS;
use CrazyElements\Core\Files\Svg\Svg_Handler;
use CrazyElements\Core\Responsive\Files\Frontend;
use CrazyElements\Utils;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Manager {

	private $files = [];

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->register_actions();
	}

	public function get( $class, $args ) {
		
		$id = $class . '-' . \Tools::jsonEncode( $args );
		if ( ! isset( $this->files[ $id ] ) ) {
			// Create an instance from dynamic args length.
			$reflection_class = new \ReflectionClass( $class );
			$this->files[ $id ] = $reflection_class->newInstanceArgs( $args );
		}

		return $this->files[ $id ];
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $post_id Post ID.
	 */
	public function on_delete_post( $post_id ) {
		if ( ! Utils::is_post_support( $post_id ) ) {
			return;
		}
        $type = PrestaHelper::$hook_current;
        $post_id = PrestaHelper::$id_content_global;
		
        if($type != 'cms' &&
            $type != 'product' &&
            $type != 'supplier' &&
            $type != 'category' &&
            $type != 'manufacturer'
        ){
            $type = 'page';
        }
		

        $css_file =  Post_CSS::create( $post_id,$type );
        $css_file->enqueue();
		

		$css_file->delete();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param bool   $skip     Whether to skip the current post meta.
	 * @param string $meta_key Current meta key.
	 *
	 * @return bool Whether to skip the post CSS meta.
	 */
	public function on_export_post_meta( $skip, $meta_key ) {
		if ( Post_CSS::META_KEY === $meta_key ) {
			$skip = true;
		}

		return $skip;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function clear_cache() {
		delete_post_meta_by_key( Post_CSS::META_KEY );
		delete_post_meta_by_key( Post_CSS::META_KEY_CMS );
		delete_post_meta_by_key( Post_CSS::META_KEY_CATEGORY );
		delete_post_meta_by_key( Post_CSS::META_KEY_PRODUCT );
		delete_post_meta_by_key( Post_CSS::META_KEY_SUPPLIER );
		delete_post_meta_by_key( Post_CSS::META_KEY_MANUFACTURER );

		delete_option( Global_CSS::META_KEY );

		delete_option( Frontend::META_KEY );

		// Delete files.
		$path = Base::get_base_uploads_dir() . Base::DEFAULT_FILES_DIR . '*';

		foreach ( glob( $path ) as $file_path ) {
			unlink( $file_path );
		}

		/**
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/core/files/clear_cache' );
	}

	/**
	 * @since 1.0.0
	 * @access private
	 */
	private function register_actions() {
		PrestaHelper::add_action( 'deleted_post', [ $this, 'on_delete_post' ] );
		PrestaHelper::add_filter( 'wxr_export_skip_postmeta', [ $this, 'on_export_post_meta' ], 10, 2 );
	}
}
