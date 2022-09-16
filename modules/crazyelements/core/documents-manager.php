<?php
namespace CrazyElements\Core;

use CrazyElements;
use CrazyElements\Core\Base\Document;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\DocumentTypes\Post;
use CrazyElements\DB;
use CrazyElements\Plugin;
use CrazyElements\TemplateLibrary\Source_Local;
use CrazyElements\Utils;
use CrazyElements\Tools;
use CrazyElements\Classes\AdminCrazyContent;
use  CrazyElements\Frontend;
use CrazyElements\TemplateLibrary\Classes\Import_Images;
use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

require_once _PS_MODULE_DIR_ . 'crazyelements/classes/CrazyContent.php';

/**
 * @since 1.0
 */

class Documents_Manager {

	/**
	 * Registered types.
	 *
	 * Holds the list of all the registered types.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @var Document[]
	 */

	protected $types = [];

	/**
	 * Registered documents.
	 *
	 * Holds the list of all the registered documents.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @var Document[]
	 */

	protected $documents = [];

	/**
	 * Current document.
	 *
	 * Holds the current document.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @var Document
	 */

	protected $current_doc;

	/**
	 * Switched data.
	 *
	 * Holds the current document when changing to the requested post.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @var array
	 */

	protected $switched_data = [];

	protected $cpt = [];

	/**
	 * Documents manager constructor.
	 *
	 * @since 1.0
	 * @access public
	 */

	public function __construct() {
		PrestaHelper::add_action( 'elementor/documents/register', [ $this, 'register_default_types' ], 0 );
		PrestaHelper::add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
		PrestaHelper::add_filter( 'post_row_actions', [ $this, 'filter_post_row_actions' ], 11, 2 );
		PrestaHelper::add_filter( 'page_row_actions', [ $this, 'filter_post_row_actions' ], 11, 2 );
		PrestaHelper::add_filter( 'user_has_cap', [ $this, 'remove_user_edit_cap' ], 10, 3 );
		PrestaHelper::add_filter( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );
	}

	/**
	 * @since 1.0
	 * @access public
	 *
	 * @param Ajax $ajax_manager An instance of the ajax manager.
	 */

	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'save_builder', [ $this, 'ajax_save' ] );
		$ajax_manager->register_ajax_action( 'discard_changes', [ $this, 'ajax_discard_changes' ] );
		$ajax_manager->register_ajax_action( 'pspaste_image', [ $this, 'pspaste_image' ] );
	}

	
	public function pspaste_image(  $request ) {
		preg_match_all( '/"url"\:\"([^\"]+)\"/', $request['elements'], $image_array );
		foreach ( $image_array[1] as $image ) {
			if (strpos($image, "localhost/") == false && $image!="#") { 
			    $attachment['url'] = stripslashes( $image );
				$addimage          = Import_Images::import( $attachment );
				$request['elements']   = str_replace( $image, addslashes( $addimage['url'] ), $request['elements'] );
			}
		}
		return $request;
	}


	public function register_default_types() {
		$default_types = [
			'post' => Post::get_class_full_name(),
		];
		foreach ( $default_types as $type => $class ) {
			$this->register_document_type( $type, $class );
		}
	}

    public function loadElementsFromManager($id_crazy_content){

       $this->print_elements_with_wrapper(null,$id_crazy_content);
	}
	
    public function loadElementsForTemplate($element_data){
        
       $this->print_elements_with_wrapper($element_data);
       $this->add_css_in_wrapper($element_data);
    }

    public function add_css_in_wrapper( $elements_data = null ) {
    	$context = \Context::getContext();
        $lang_id = $context->language->id;
        $elementor_library = \Tools::getValue('elementor_library');
        $preview = "";
        if ($elementor_library != '') {
            $preview = "_preview";
        }
        if ($lang_id != '') {
            $lang_id_preview = "-".$lang_id;
        }
    	$id =PrestaHelper::$id_content_global;
        $type = PrestaHelper::$hook_current;
        $id = PrestaHelper::$id_content_global;
        $id_shop = '-'.PrestaHelper::$id_shop_global;
        $urls=array();
        if(file_exists(CRAZY_ASSETS_PATH."css/frontend/css/post-".$type."-".$id.$lang_id_preview.$id_shop. $preview.'.css')){
        	$urls[] = CRAZY_URL."assets/css/frontend/css/post-".$type."-".$id.$lang_id_preview.$id_shop. $preview.'.css' ;
		}
        if(file_exists(CRAZY_ASSETS_PATH."css/frontend/css/global.css")){
        	$urls[] = CRAZY_URL."assets/css/frontend/css/global.css";
        }
        if(!empty($urls)){
        	foreach($urls as $url){
        		$context->controller->addCSS($url, 'all');
	    	}
        }
    	$metafont=$this->pse_load_meta($id,$lang_id);
    	$fontend= new Frontend;
		if ( ! empty( $metafont['fonts'] ) ) {
			foreach ( $metafont['fonts'] as $font ) {	
				$fontend->enqueue_font( $font );
			}
		}
    }


    protected function pse_load_meta($post_id,$lang_id)
    {
    	$type=PrestaHelper::$hook_current;
    	$globalmeta=  PrestaHelper::get_option( "_elementor_global_css" );
    	$globalmeta= \Tools::jsonDecode($globalmeta,true);
	    switch ($type) {
			case 'cms':
		    	$meta=PrestaHelper::get_post_meta($post_id, '_elementor_css_cms_'.$lang_id, true);
			break;
			case 'product':
		        $meta= PrestaHelper::get_post_meta($post_id, '_elementor_css_product_'.$lang_id, true);
			break;
			case 'supplier':
		        $meta= PrestaHelper::get_post_meta($post_id, '_elementor_css_supplier_'.$lang_id, true);
			break;
			case 'category':
		        $meta= PrestaHelper::get_post_meta($post_id, '_elementor_css_category_'.$lang_id, true);
			break;
			case 'manufacturer':
		        $meta= PrestaHelper::get_post_meta($post_id, '_elementor_css_manufacturer_'.$lang_id, true);
			break;
			default:
		        $meta= array();
			break;
        } 
        $returnmeta = array_merge( (array)$globalmeta, (array)$meta );
        return $returnmeta;
    }

    
    public function print_elements_with_wrapper( $elements_data = null ,$id_crazy_content = null) {
 		if (  $elements_data == null) {
			$elements_data = $this->get_elements_data(null,$id_crazy_content);
		}
		$id = PrestaHelper::$id_content_global;
		if($id==""){
			$id=$id_crazy_content;
		}
		$elementor_library = \Tools::getValue('elementor_library');
		if($elementor_library!=''){
			$id=$elementor_library;
		} 
		$token=\Tools::getValue("token");
		$editClass='';
		$elementor_ids='';
		if($token && PrestaHelper::$id_editor_global == PrestaHelper::$id_content_primary_global){
			$editClass='class="elementor elementor-'.$id.' elementor-edit-mode"';
			$elementor_ids='id="elementor"';
		}else{
			$editClass='class="elementor elementor-'.$id.'"';
			$elementor_ids='';
		}
		?>
		<div <?php echo $elementor_ids; ?> <?php echo $editClass ?> >
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
					<?php $this->print_elements( $elements_data ); ?>
				</div>
			</div>
		</div>
		<?php
	}
    
	public function get_container_attributes() {
		$id = PrestaHelper::$id_content_global;
		$attributes = [
			'data-elementor-type' =>'post',// $this->get_name(),
			'data-elementor-id' => $id,
			'class' => 'elementor elementor-' . $id,
		];
		return $attributes;
	}

    protected function print_elements( $elements_data ) {
        if($elements_data == null){
            $elements_data =array();
        }
		foreach ( $elements_data as $element_data ) {
			$element = Plugin::$instance->elements_manager->create_element_instance( $element_data );
			if ( ! $element ) {
				continue;
			}
			$element->print_element();
		}
	}

    public function get_elements_data( $status = null,$id_crazy_content) {
		$get_elements_data = CrazyElements::dataProcessing(null,'get_elements_data',$id_crazy_content);
        $get_elements_data= json_decode($get_elements_data,true);
        return $get_elements_data;
	}
	
	/**
	 * Register document type.
	 *
	 * Registers a single document.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $type  Document type name.
	 * @param Document $class The name of the class that registers the document type.
	 *                      Full name with the namespace.
	 *
	 * @return Documents_Manager The updated document manager instance.
	 */

	public function register_document_type( $type, $class ) {
		$this->types[ $type ] = $class;
		$cpt = $class::get_property( 'cpt' );
		if ( $cpt ) {
			foreach ( $cpt as $post_type ) {
				$this->cpt[ $post_type ] = $type;
			}
		}
		if ( $class::get_property( 'register_type' ) ) {
			Source_Local::add_template_type( $type );
		}
		return $this;
	}

	/**
	 * Get document.
	 *
	 * Retrieve the document data based on a post ID.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param int  $post_id    Post ID.
	 * @param bool $from_cache Optional. Whether to retrieve cached data. Default is true.
	 *
	 * @return false|Document Document data or false if post ID was not entered.
	 */

	public function get( $post_id, $from_cache = true ) {
		$this->register_types();
		if ( ! $post_id ) {
			return false;
		}
		
		$doc_type_class = $this->get_document_type( );
		$this->documents[ $post_id ] = new $doc_type_class( [
			'post_id' => $post_id,
		] );
		return $this->documents[ $post_id ];
	}

	public function GetEContentAnyWhereByHookPageFilter($id){
        $id_lang = (int)pSQL( PrestaHelper::$id_lang_global);
        $id_shop = (int) pSQL(PrestaHelper::$id_shop_global);
        $id = (int) pSQL($id);
        $sql = 'SELECT v.*,vl.content,vs.id_shop FROM `' . _DB_PREFIX_ . 'ps_crazy_content` v 
                INNER JOIN `' . _DB_PREFIX_ . 'ps_crazy_content_lang` vl ON (v.`id_crazy_content` = vl.`id_crazy_content` AND vl.`id_lang` = ' . $id_lang . ')
                INNER JOIN `' . _DB_PREFIX_ . 'ps_crazy_content_shop` vs ON (v.`id_crazy_content` = vs.`id_crazy_content` AND vs.`id_shop` = ' . $id_shop . ' AND v.`active` = 1)
                WHERE v.id_crazy_content='.$id;
        $sql .= ' ORDER BY `position` ASC';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }

	/**
	 * Get document or autosave.
	 *
	 * Retrieve either the document or the autosave.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param int $id      Optional. Post ID. Default is `0`.
	 * @param int $user_id Optional. User ID. Default is `0`.
	 *
	 * @return false|Document The document if it exist, False otherwise.
	 */

	public function get_doc_or_auto_save( $id, $user_id = 0 ) {
		$document = $this->get( $id );
		return $document;
	}

	/**
	 * Get document for frontend.
	 *
	 * Retrieve the document for frontend use.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param int $post_id Optional. Post ID. Default is `0`.
	 *
	 * @return false|Document The document if it exist, False otherwise.
	 */

	public function get_doc_for_frontend( $post_id ) {
		if ( is_preview() || Plugin::$instance->preview->is_preview_mode() ) {
			$document = $this->get_doc_or_auto_save( $post_id, get_current_user_id() );
		} else {
			$document = $this->get( $post_id );
		}
		return $document;
	}

	/**
	 * Get document type.
	 *
	 * Retrieve the type of any given document.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string $type
	 *
	 * @param string $fallback
	 *
	 * @return Document|bool The type of the document.
	 */

	public function get_document_type( $type='post' ) {
		$types = $this->get_document_types();
		if ( isset( $types[ $type ] ) ) {
			return $types[ $type ];
		}
		return false;
	}

	/**
	 * Get document types.
	 *
	 * Retrieve the all the registered document types.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param array $args      Optional. An array of key => value arguments to match against
	 *                               the properties. Default is empty array.
	 * @param string $operator Optional. The logical operation to perform. 'or' means only one
	 *                               element from the array needs to match; 'and' means all elements
	 *                               must match; 'not' means no elements may match. Default 'and'.
	 *
	 * @return Document[] All the registered document types.
	 */

	public function get_document_types( $args = [], $operator = 'and' ) {

		$this->register_types();
		$array=array("post"=>"\CrazyElements\Core\DocumentTypes\Post");
		return $array;
	}

	/**
	 * Create a document.
	 *
	 * Create a new document using any given parameters.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $type      Document type.
	 * @param array  $post_data An array containing the post data.
	 * @param array  $meta_data An array containing the post meta data.
	 *
	 * @return Document The type of the document.
	 */

	public function create( $type, $post_data = [], $meta_data = [] ) {
		$class = $this->get_document_type( $type, false );
		if ( ! $class ) {
			return new \WP_Error( 500, sprintf( 'Type %s does not exist.', $type ) );
		}
		if ( empty( $post_data['post_title'] ) ) {
			$post_data['post_title'] = PrestaHelper::__( 'Elementor', 'elementor' );
			if ( 'post' !== $type ) {
				$post_data['post_title'] = sprintf(
					PrestaHelper::__( 'Elementor %s', 'elementor' ),
					call_user_func( [ $class, 'get_title' ] )
				);
			}
			$update_title = true;
		}
		$meta_data['_elementor_edit_mode'] = 'builder';
		$meta_data[ Document::TYPE_META_KEY ] = $type;
		$post_data['meta_input'] = $meta_data;
		$post_id = wp_insert_post( $post_data );
		if ( ! empty( $update_title ) ) {
			$post_data['ID'] = $post_id;
			$post_data['post_title'] .= ' #' . $post_id;
			unset( $post_data['meta_input'] );
			wp_update_post( $post_data );
		}
		$document = new $class( [
			'post_id' => $post_id,
		] );
		$document->save( [] );
		return $document;
	}

	/**
	 * Remove user edit capabilities if document is not editable.
	 *
	 * Filters the user capabilities to disable editing in admin.
	 *
	 * @param array $allcaps An array of all the user's capabilities.
	 * @param array $caps    Actual capabilities for meta capability.
	 * @param array $args    Optional parameters passed to has_cap(), typically object ID.
	 *
	 * @return array
	 */

	public function remove_user_edit_cap( $allcaps, $caps, $args ) {
		global $pagenow;
		if ( ! in_array( $pagenow, [ 'post.php', 'edit.php' ], true ) ) {
			return $allcaps;
		}
		$capability = $args[0];
		if ( 'edit_post' !== $capability ) {
			return $allcaps;
		}
		if ( empty( $args[2] ) ) {
			return $allcaps;
		}
		$post_id = $args[2];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( ! $document ) {
			return $allcaps;
		}
		$allcaps[ $caps[0] ] = $document::get_property( 'is_editable' );
		return $allcaps;
	}

	/**
	 * Filter Post Row Actions.
	 *
	 * Let the Document to filter the array of row action links on the Posts list table.
	 *
	 * @param array $actions
	 * @param \WP_Post $post
	 *
	 * @return array
	 */

	public function filter_post_row_actions( $actions, $post ) {
		$document = $this->get( $post->ID );
		if ( $document ) {
			$actions = $document->filter_admin_row_actions( $actions );
		}
		return $actions;
	}

	/**
	 * Save document data using ajax.
	 *
	 * Save the document on the builder using ajax, when saving the changes, and refresh the editor.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param $request Post ID.
	 *
	 * @throws \Exception If current user don't have permissions to edit the post or the post is not using Elementor.
	 *
	 * @return array The document data after saving.
	 */

	public function ajax_save( $request ) {
        return CrazyElements::dataProcessing($request,'save_builder');
	}

	/**
	 * Ajax discard changes.
	 *
	 * Load the document data from an autosave, deleting unsaved changes.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param $request
	 *
	 * @return bool True if changes discarded, False otherwise.
	 */

	public function ajax_discard_changes( $request ) {
		$document = $this->get( $request['editor_post_id'] );
		$autosave = $document->get_autosave();
		if ( $autosave ) {
			$success = $autosave->delete();
		} else {
			$success = true;
		}
		return $success;
	}

	/**
	 * Switch to document.
	 *
	 * Change the document to any new given document type.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param Document $document The document to switch to.
	 */

	public function switch_to_document( $document ) {
		if ( $this->current_doc === $document ) {
			$this->switched_data[] = false;
			return;
		}
		$this->switched_data[] = [
			'switched_doc' => $document,
			'original_doc' => $this->current_doc, // Note, it can be null if the global isn't set
		];
		$this->current_doc = $document;
	}

	/**
	 * Restore document.
	 *
	 * Rollback to the original document.
	 *
	 * @since 1.0
	 * @access public
	 */

	public function restore_document() {
		$data = array_pop( $this->switched_data );
		if ( ! $data ) {
			return;
		}
		$this->current_doc = $data['original_doc'];
	}

	/**
	 * Get current document.
	 *
	 * Retrieve the current document.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return Document The current document.
	 */

	public function get_current() {
		return $this->current_doc;
	}

	/**
	 * Get groups.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return array
	 */

	public function get_groups() {
		return [];
	}

	public function localize_settings( $settings ) {
		$translations = [];
		foreach ( $this->get_document_types() as $type => $class ) {
			$translations[ $type ] = $class::get_title();
		}
		return array_replace_recursive( $settings, [
			'i18n' => $translations,
		] );
	}

	private function register_types() {
		PrestaHelper::do_action( 'elementor/documents/register', $this );
	}
}