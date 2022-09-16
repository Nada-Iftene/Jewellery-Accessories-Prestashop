<?php
namespace CrazyElements\Core\Settings\Page;

use CrazyElements\Core\Settings\Base\Model as BaseModel;
use CrazyElements\Plugin;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Model extends BaseModel {

	private $post;

	/**
	 * @var \WP_Post
	 */
	private $post_parent;

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data Optional. Model data. Default is an empty array.
	 */
	public function __construct( array $data = [] ) {
		$elementor_library = \Tools::getValue('elementor_library');
		
		if($elementor_library!=''){
			$title='';
		}else{
			$title=PrestaHelper::get_title();
		}
		$id= PrestaHelper::$id_lang_global;
		$this->post =  array(
            'ID' => $id,
            'post_author' => 1,
            'post_date' => '',
            'post_date_gmt' => '',
            'post_content' => '',
            'post_title' => $title,
            'post_excerpt' => '',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'open',
            'post_password' => '',
            'post_name' => $title,
            'to_ping' => '',
            'pinged' => '',
            'post_modified' => '',
            'post_modified_gmt' => '',
            'post_content_filtered' => '',
            'post_parent' => '',
            'guid' => '',
            'menu_order' => 0,
            'post_type' => 'page',
            'post_mime_type' => '',
            'comment_count' => 0,
            'filter' => 'raw'
        ); 
		parent::__construct( $data );
	}

	/**
	 * Get model name.
	 *
	 * Retrieve page settings model name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Model name.
	 */
	public function get_name() {
		return 'page-settings';
	}

	/**
	 * Get model unique name.
	 *
	 * Retrieve page settings model unique name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Model unique name.
	 */
	public function get_unique_name() {
		return $this->get_name() . '-' . PrestaHelper::$id_content_global;
	}

	/**
	 * Get CSS wrapper selector.
	 *
	 * Retrieve the wrapper selector for the page settings model.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string CSS wrapper selector.
	 */
	public function get_css_wrapper_selector() {
		
		$elementor_library = \Tools::getValue('elementor_library');
		$id = PrestaHelper::$id_content_global;
		if($elementor_library!=''){
			$id=$elementor_library;
		}
		if($id!=''){
			$document = Plugin::$instance->documents->get( $id );
			return $document->get_css_wrapper_selector();
		}
		
        
        return '';
	}

	/**
	 * Get panel page settings.
	 *
	 * Retrieve the panel setting for the page settings model.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array {
	 *    Panel settings.
	 *
	 *    @type string $title The panel title.
	 * }
	 */
	public function get_panel_page_settings() {
		$elementor_library = \Tools::getValue('elementor_library');
		
		if($elementor_library!=''){
			$title='';
		}else{
			$title=PrestaHelper::get_title();
		}
        return [
			/* translators: %s: Document title */
			'title' => sprintf( PrestaHelper::__( '%s Settings', 'elementor' ),$title),
		];
	}

	/**
	 * On export post meta.
	 *
	 * When exporting data, check if the post is not using page template and
	 * exclude it from the exported Elementor data.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $element_data Element data.
	 *
	 * @return array Element data to be exported.
	 */
	public function on_export( $element_data ) {
		if ( ! empty( $element_data['settings']['template'] ) ) {
			$page_templates_module = Plugin::$instance->modules_manager->get_modules( 'page-templates' );
			$is_elementor_template = ! ! $page_templates_module->get_template_path( $element_data['settings']['template'] );

			if ( ! $is_elementor_template ) {
				unset( $element_data['settings']['template'] );
			}
		}

		return $element_data;
	}

	/**
	 * Register model controls.
	 *
	 * Used to add new controls to the page settings model.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
	
		if ( PrestaHelper::$id_lang_global ) {
			$document = Plugin::$instance->documents->get_doc_or_auto_save( PrestaHelper::$id_lang_global );
			if ( $document ) {
				$controls = $document->get_controls();
				foreach ( $controls as $control_id => $args ) {
					$this->add_control( $control_id, $args );
				}
			}
		}
		
	}
}
