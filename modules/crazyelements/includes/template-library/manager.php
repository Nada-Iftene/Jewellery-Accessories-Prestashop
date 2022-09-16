<?php
namespace CrazyElements\TemplateLibrary;

use CrazyElements\Api;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\TemplateLibrary\Classes\Import_Images;
use CrazyElements\Plugin;
use CrazyElements\User;
use Db;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Manager {



	/**
	 * Registered template sources.
	 *
	 * Holds a list of all the supported sources with their instances.
	 *
	 * @access protected
	 *
	 * @var Source_Base[]
	 */
	protected $_registered_sources = array();

	/**
	 * Imported template images.
	 *
	 * Holds an instance of `Import_Images` class.
	 *
	 * @access private
	 *
	 * @var Import_Images
	 */
	private $_import_images = null;

	/**
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->register_default_sources();

		$this->add_actions();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 */
	public function add_actions() {
		PrestaHelper::add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
		PrestaHelper::add_action( 'wp_ajax_elementor_library_direct_actions', array( $this, 'handle_direct_actions' ) );

		PrestaHelper::add_action(
			'wp_ajax_elementor_update_templates',
			function () {
				if ( ! isset( $_POST['templates'] ) ) {
					return;
				}

				foreach ( $_POST['templates'] as & $template ) {
					if ( ! isset( $template['content'] ) ) {
						return;
					}

					$template['content'] = stripslashes( $template['content'] );
				}

				wp_send_json_success( $this->handle_ajax_request( 'update_templates', $_POST ) );
			}
		);
	}

	/**
	 * @since  1.0.0
	 * @access public
	 *
	 * @return Import_Images Imported images instance.
	 */
	public function get_import_images_instance() {
		if ( null === $this->_import_images ) {
			$this->_import_images = new Import_Images();
		}

		return $this->_import_images;
	}

	/**
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $source_class The name of source class.
	 * @param array  $args         Optional. Class arguments. Default is an
	 *                             empty array.
	 */
	public function register_source( $source_class, $args = array() ) {
		if ( ! class_exists( $source_class ) ) {
			return new \WP_Error( 'source_class_name_not_exists' );
		}

		$source_instance = new $source_class( $args );

		if ( ! $source_instance instanceof Source_Base ) {
			return new \WP_Error( 'wrong_instance_source' );
		}
		$this->_registered_sources[ $source_instance->get_id() ] = $source_instance;

		return true;
	}

	/**
	 * Unregister template source.
	 *
	 * Remove an existing template sources from the list of registered template
	 * sources.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $id The source ID.
	 *
	 * @return bool Whether the source was unregistered.
	 */
	public function unregister_source( $id ) {
		if ( ! isset( $this->_registered_sources[ $id ] ) ) {
			return false;
		}

		unset( $this->_registered_sources[ $id ] );

		return true;
	}

	/**
	 * Get registered template sources.
	 *
	 * Retrieve registered template sources.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return Source_Base[] Registered template sources.
	 */
	public function get_registered_sources() {
		return $this->_registered_sources;
	}

	/**
	 * Get template source.
	 *
	 * Retrieve single template sources for a given template ID.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $id The source ID.
	 *
	 * @return false|Source_Base Template sources if one exist, False otherwise.
	 */
	public function get_source( $id ) {
		$sources = $this->get_registered_sources();

		if ( ! isset( $sources[ $id ] ) ) {
			return false;
		}

		return $sources[ $id ];
	}

	/**
	 * Get templates.
	 *
	 * Retrieve all the templates from all the registered sources.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Templates array.
	 */
	public function get_templates( $templates ) {

		foreach ( $templates as $template ) {
			$probt = '';
			if($template['is_pro']){
				$probt = 'get-pro-button';
			}
			
			$data[] = array(
				'template_id'     => $template['id'],
				'source'          => 'remote',
				'type'            => $template['type'],
				'title'           => $template['title'],
				'thumbnail'       => $template['thumbnail'],
				'date'            => $template['tmpl_created'],
				'human_date'      => date( 'm/d/Y', $template['tmpl_created'] ),
				'author'          => $template['author'],
				'hasPageSettings' => $template['has_page_settings'],
				'tags'            => $template['tags'],
				'export_link'     => $template['id'],
				'url'             => $template['url'],
				'favorite'        => '',
				'isPro'        => $probt
			);

		}

		$query = 'SELECT * FROM ' . _DB_PREFIX_ . 'crazy_library';
		$posts = Db::getInstance()->executeS( $query );
		foreach ( $posts as $post ) {
			$data[] = array(
				'template_id'     => $post['id_crazy_library'],
				'source'          => $post['source'],
				'type'            => $post['type'],
				'title'           => $post['title'],
				'thumbnail'       => $post['thumbnail'],
				'date'            => $post['date'],
				'human_date'      => $post['human_date'],
				'author'          => $post['author'],
				'hasPageSettings' => $post['hasPageSettings'],
				'tags'            => $post['tags'],
				'export_link'     => ( $post['export_link'] ) ? $post['export_link'] : $this->get_export_link( $post['id_crazy_library'], $post['source'] ),
				'url'             => ( $post['url'] ) ? $post['url'] : $this->get_preview_url( $post['id_crazy_library'] ), // $post['url'],
			);

		}
		return $data;
	}

	private function get_preview_url( $template_id ) {

		$http_build_query =
		array(
			'elementor_library' => $template_id,
		);
		return \Context::getContext()->link->getModuleLink( 'crazyelements', 'display', $http_build_query ); // true,array(),$http_build_query
	}



	private function get_export_link( $template_id, $source ) {

		$http_build_query =
		array(
			'action'         => 'elementor_library_direct_actions',
			'library_action' => 'export_template',
			'template_id'    => $template_id,
			'source'         => $source,

		);
		return PrestaHelper::getAjaxUrl() . '&' . http_build_query( $http_build_query );
	}

	/**
	 * Get library data.
	 *
	 * Retrieve the library data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Library arguments.
	 *
	 * @return array Library data.
	 */
	public function get_library_data( array $args ) {
		$library_data = Api::get_library_data( ! empty( $args['sync'] ) );
		$process_library_data= \Tools::jsonDecode($library_data,true);
		if(empty($process_library_data) || $process_library_data==''){
			$process_library_data=(stripslashes($library_data));
			$process_library_data= \Tools::jsonDecode($process_library_data,true);
		}
		if(empty($process_library_data) || $process_library_data==''){
			$process_library_data= \Tools::jsonDecode(addslashes($library_data),true);

		}
		return [
			'templates' => $this->get_templates($process_library_data['templates']),
			'config' => $process_library_data['types_data'],
		];
	}

	/**
	 * save connect token.
	 *
	 * save connect token.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Token.
	 *
	 * @return array Library data.
	 */
	public function library_connect_token( $args ) {
		PrestaHelper::update_option( 'connect_access_token', $args['token'] );
		return array(
			'success' => true,

		);
	}

	/**
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 */
	public function save_template( array $args ) {
		

		$validate_args = $this->ensure_args( [ 'post_id', 'source', 'content', 'type' ], $args );

		if ( PrestaHelper::is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}
		

		$args['content'] = json_decode( $args['content'], true );

		$page = SettingsManager::get_settings_managers( 'page' )->get_model( $args['post_id'] );


		$args['page_settings'] = $page->get_data( 'settings' );

		$template_id = $source->save_item( $args );
        

		if ( PrestaHelper::is_wp_error( $template_id ) ) {
			return $template_id;
		}

		return $source->get_item( $template_id );
	}

	/**
	 * Update template.
	 *
	 * Update template on the database.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $template_data New template data.
	 */
	public function update_template( array $template_data ) {
		$validate_args = $this->ensure_args( array( 'source', 'content', 'type' ), $template_data );

		if ( PrestaHelper::is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $template_data['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		$template_data['content'] = json_decode( $template_data['content'], true );

		$update = $source->update_item( $template_data );

		if ( PrestaHelper::is_wp_error( $update ) ) {
			return $update;
		}

		return $source->get_item( $template_data['id'] );
	}

	/**
	 * Update templates.
	 *
	 * Update template on the database.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 */
	public function update_templates( array $args ) {
		foreach ( $args['templates'] as $template_data ) {
			$result = $this->update_template( $template_data );

			if ( PrestaHelper::is_wp_error( $result ) ) {
				return $result;
			}
		}

		return true;
	}

	/**
	 * Get template data.
	 *
	 * Retrieve the template data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 *
	 * @return \WP_Error|bool|array ??
	 */
	public function get_template_data( array $args ) {
		
		$validate_args = $this->ensure_args( array( 'source', 'template_id' ), $args );


		if ( PrestaHelper::is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		if ( isset( $args['edit_mode'] ) ) {
			Plugin::$instance->editor->set_edit_mode( $args['edit_mode'] );
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		PrestaHelper::do_action( 'elementor/template-library/before_get_source_data', $args, $source );
		$data = $source->get_data( $args );
		PrestaHelper::do_action( 'elementor/template-library/after_get_source_data', $args, $source );

		return $data;
	}

	/**
	 * Delete template.
	 *
	 * Delete template from the database.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 */
	public function delete_template( array $args ) {
		$validate_args = $this->ensure_args( array( 'source', 'template_id' ), $args );

		if ( PrestaHelper::is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found.' );
		}

		return $source->delete_template( $args['template_id'] );
	}

	/**
	 * Export template.
	 *
	 * Export template to a file.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 *
	 * @return mixed Whether the export succeeded or failed.
	 */
	public function export_template( array $args ) {

		$source = $this->get_source( $args['source'] );

		if ( ! $source ) {
			return new \WP_Error( 'template_error', 'Template source not found' );
		}

		return $source->export_template( $args['template_id'] );
	}

	/**
	 * @since  2.3.0
	 * @access public
	 */
	public function direct_import_template() {
		/**
	   * @var Source_Local $source
*/
		$source = $this->get_source( 'local' );

		return $source->import_template( $_FILES['file']['name'], $_FILES['file']['tmp_name'] );
	}

	/**
	 * Import template.
	 *
	 * Import template from a file.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $data
	 *
	 * @return mixed Whether the export succeeded or failed.
	 */
	public function import_template( array $data ) {
		/**
	   * @var Source_Local $source
*/
		$file_content = base64_decode( $data['fileData'] );

		$tmp_file = tmpfile();

		fwrite( $tmp_file, $file_content );

		$source = $this->get_source( 'local' );

		$result = $source->import_template( $data['fileName'], stream_get_meta_data( $tmp_file )['uri'] );

		fclose( $tmp_file );

		return $result;
	}

	/**
	 * Mark template as favorite.
	 *
	 * Add the template to the user favorite templates.
	 *
	 * @since  1.9.0
	 * @access public
	 *
	 * @param array $args Template arguments.
	 *
	 * @return mixed Whether the template marked as favorite.
	 */
	public function mark_template_as_favorite( $args ) {
		$validate_args = $this->ensure_args( array( 'source', 'template_id', 'favorite' ), $args );

		if ( PrestaHelper::is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$source = $this->get_source( $args['source'] );

		return $source->mark_as_favorite( $args['template_id'], filter_var( $args['favorite'], FILTER_VALIDATE_BOOLEAN ) );
	}

	/**
	 * Register default template sources.
	 *
	 * Register the 'local' and 'remote' template sources that Elementor use by
	 * default.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function register_default_sources() {
		$sources = array(
			'local',
			'remote',
		);

		foreach ( $sources as $source_filename ) {
			$class_name = ucwords( $source_filename );
			$class_name = str_replace( '-', '_', $class_name );

			$this->register_source( __NAMESPACE__ . '\Source_' . $class_name );
		}
	}

	/**
	 * Handle ajax request.
	 *
	 * Fire authenticated ajax actions for any given ajax request.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @param string $ajax_request Ajax request.
	 *
	 * @param array  $data
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	private function handle_ajax_request( $ajax_request, array $data ) {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			throw new \Exception( 'Access Denied' );
		}

		if ( ! empty( $data['editor_post_id'] ) ) {
			$editor_post_id = abs( intval( $data['editor_post_id'] ) );
		}
		$result = call_user_func( array( $this, $ajax_request ), $data );

		if ( PrestaHelper::is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message() );
		}

		return $result;
	}

	/**
	 * Init ajax calls.
	 *
	 * Initialize template library ajax calls for allowed ajax requests.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param Ajax $ajax
	 */
	public function register_ajax_actions( Ajax $ajax ) {
		$library_ajax_requests = array(
			'get_library_data',
			'get_template_data',
			'save_template',
			'update_templates',
			'delete_template',
			'import_template',
			'mark_template_as_favorite',
			'library_connect_token',
		);


		foreach ( $library_ajax_requests as $ajax_request ) {
			$ajax->register_ajax_action(
				$ajax_request,
				function ( $data ) use ( $ajax_request ) {
					return $this->handle_ajax_request( $ajax_request, $data );
				}
			);
		}
	}

	/**
	 * @since  1.0.0
	 * @access public
	 */
	public function handle_direct_actions() {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			return;
		}
		$ajax = Plugin::$instance->common->get_component( 'ajax' );

		$action = $_REQUEST['library_action'];

		$result = $this->$action( $_REQUEST );

		if ( PrestaHelper::is_wp_error( $result ) ) {
			$this->handle_direct_action_error( $result->get_error_message() . '.' );
		}

		$callback = "on_{$action}_success";

		if ( method_exists( $this, $callback ) ) {
			$this->$callback( $result );
		}

		die;
	}

	/**
	 * @since  1.0.0
	 * @access private
	 */
	private function on_direct_import_template_success() {
		wp_safe_redirect( PrestaHelper::admin_url( Source_Local::ADMIN_MENU_SLUG ) );
	}

	/**
	 * @since  1.0.0
	 * @access private
	 */
	private function handle_direct_action_error( $message ) {
		_default_wp_die_handler( $message, 'Elementor Library' );
	}

	/**
	 * @since  1.0.0
	 * @access private
	 *
	 * @param array $required_args  Required arguments to check whether they
	 *                              exist.
	 * @param array $specified_args The list of all the specified arguments to
	 *                              check against.
	 */
	private function ensure_args( array $required_args, array $specified_args ) {
		$not_specified_args = array_diff( $required_args, array_keys( array_filter( $specified_args ) ) );

		if ( $not_specified_args ) {
			return new \WP_Error( 'arguments_not_specified', sprintf( 'The required argument(s) "%s" not specified.', implode( ', ', $not_specified_args ) ) );
		}

		return true;
	}
}