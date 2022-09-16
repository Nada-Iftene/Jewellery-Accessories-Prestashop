<?php

namespace CrazyElements\Core\Base;

use CrazyElements\Core\Files\CSS\Post as Post_CSS;
use CrazyElements\Core\Utils\Exceptions;
use CrazyElements\Plugin;
use CrazyElements\DB;
use CrazyElements\Controls_Manager;
use CrazyElements\Controls_Stack;
use CrazyElements\User;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\Utils;
use CrazyElements\Widget_Base;
use CrazyElements\Core\Revisions\Revisions_Manager;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor document.
 *
 * An abstract class that provides the needed properties and methods to
 * manage and handle documents in inheriting classes.
 *
 * @since 1.0
 * @abstract
 */
abstract class Document extends Controls_Stack {

	/**
	 * Document type meta key.
	 */
	const TYPE_META_KEY              = '_elementor_template_type';
	const PAGE_META_KEY              = '_elementor_page_settings';
	const ELEMENTS_USAGE_META_KEY    = '_elementor_elements_usage';
	const ELEMENTS_USAGE_OPTION_NAME = 'elementor_elements_usage';

	private $main_id;

	private static $properties = array();

	/**
	 * Document post data.
	 *
	 * Holds the document post data.
	 *
	 * @since 1.0
	 * @access protected
	 *
	 * @var \WP_Post WordPress post data.
	 */
	protected $post;


	protected $post_id;

	/**
	 * @since 1.0
	 * @access protected
	 * @static
	 */
	protected static function get_editor_panel_categories() {
		return Plugin::$instance->elements_manager->get_categories();
	}

	/**
	 * Get properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		return array(
			'is_editable' => true,
		);
	}

	/**
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static function get_editor_panel_config() {
		return array(
			'widgets_settings'    => array(),
			'messages'            => array(
				'publish_notification' => sprintf( PrestaHelper::__( 'Hurray! Your %s is live.', 'elementor' ), static::get_title() ),
			),
			'elements_categories' => static::get_editor_panel_categories(),

		);
	}

	/**
	 * Get element title.
	 *
	 * Retrieve the element title.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @return string Element title.
	 */
	public static function get_title() {
		return PrestaHelper::__( 'Document', 'elementor' );
	}

	/**
	 * Get property.
	 *
	 * Retrieve the document property.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @param string $key The property key.
	 *
	 * @return mixed The property value.
	 */
	public static function get_property( $key ) {
		$id = static::get_class_full_name();

		if ( ! isset( self::$properties[ $id ] ) ) {
			self::$properties[ $id ] = static::get_properties();
		}

		return self::get_items( self::$properties[ $id ], $key );
	}

	/**
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static function get_class_full_name() {
		return get_called_class();
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function get_unique_name() {
		return $this->get_name() . '-' . $this->post_id;
	}

	/**
	 * @since 2.3.0
	 * @access public
	 */
	public function get_post_type_title() {
		$post_type_object = get_post_type_object( $this->post->post_type );

		return $post_type_object->labels->singular_name;
	}

	/**
	 * @since 1.0
	 * @deprecated 2.4.0 Use `Document::get_remote_library_config()` instead
	 * @access public
	 */
	public function get_remote_library_type() {
		// _deprecated_function( __METHOD__, '2.4.0', __CLASS__ . '::get_remote_library_config()' );
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function get_main_id() {
		if ( ! $this->main_id ) {
			$post_id = $this->post->ID;

			$parent_post_id = wp_is_post_revision( $post_id );

			if ( $parent_post_id ) {
				$post_id = $parent_post_id;
			}

			$this->main_id = $post_id;
		}

		return $this->main_id;
	}

	/**
	 * @since 1.0
	 * @access public
	 *
	 * @param $data
	 *
	 * @throws \Exception If the widget was not found.
	 *
	 * @return string
	 */
	public static function render_element( $data ) {
		// Start buffering
		ob_start();

		/** @var Widget_Base $widget */
		$widget = Plugin::$instance->elements_manager->create_element_instance( $data );

		if ( ! $widget ) {
			throw new \Exception( 'Widget not found.' );
		}

		$widget->render_content();

		$render_html = ob_get_clean();

		return $render_html;
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function get_main_post() {
		return get_post( $this->get_main_id() );
	}

	/**
	 * @since 1.0
	 * @deprecated 2.4.0 Use `Document::get_container_attributes()` instead
	 * @access public
	 */
	public function get_container_classes() {
		// _deprecated_function( __METHOD__, '2.4.0', __CLASS__ . '::get_container_attributes()' );

		return '';
	}

	public function get_container_attributes() {
		$id = $this->get_main_id();

		$attributes = array(
			'data-elementor-type' => $this->get_name(),
			'data-elementor-id'   => $id,
			'class'               => 'elementor elementor-' . $id,
		);

		$version_meta = $this->get_main_meta( '_elementor_version' );

		if ( version_compare( $version_meta, '2.5.0', '<' ) ) {
			$attributes['class'] .= ' elementor-bc-flex-widget';
		}

		if ( ! Plugin::$instance->preview->is_preview_mode( $id ) ) {
			$attributes['data-elementor-settings'] = json_encode( $this->get_frontend_settings() );
		}

		return $attributes;
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function get_wp_preview_url() {
		$main_post_id = $this->get_main_id();

		$url = get_preview_post_link(
			$main_post_id,
			array(
				'preview_id'    => $main_post_id,
				'preview_nonce' => wp_create_nonce( 'post_preview_' . $main_post_id ),
			)
		);

		/**
		 * Document "WordPress preview" URL.
		 *
		 * Filters the WordPress preview URL.
		 *
		 * @since 1.0
		 *
		 * @param string   $url  WordPress preview URL.
		 * @param Document $this The document instance.
		 */
		$url = PrestaHelper::apply_filters( 'elementor/document/urls/wp_preview', $url, $this );

		return $url;
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function get_exit_to_dashboard_url() {
		$url = get_edit_post_link( $this->get_main_id(), 'raw' );

		/**
		 * Document "exit to dashboard" URL.
		 *
		 * Filters the "Exit To Dashboard" URL.
		 *
		 * @since 1.0
		 *
		 * @param string   $url  The exit URL
		 * @param Document $this The document instance.
		 */
		$url = PrestaHelper::apply_filters( 'elementor/document/urls/exit_to_dashboard', $url, $this );

		return $url;
	}

	/**
	 * Get auto-saved post revision.
	 *
	 * Retrieve the auto-saved post revision that is newer than current post.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return bool|Document
	 */

	public function get_newer_autosave() {
		$autosave = $this->get_autosave();

		// Detect if there exists an autosave newer than the post.
		if ( $autosave && mysql2date( 'U', $autosave->get_post()->post_modified_gmt, false ) > mysql2date( 'U', $this->post->post_modified_gmt, false ) ) {
			return $autosave;
		}

		return false;
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function is_autosave() {
		return wp_is_post_autosave( $this->post_id );
	}

	/**
	 * @since 1.0
	 * @access public
	 *
	 * @param int  $user_id
	 * @param bool $create
	 *
	 * @return bool|Document
	 */
	public function get_autosave( $user_id = 0, $create = false ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$autosave_id = $this->get_autosave_id( $user_id );

		if ( $autosave_id ) {
			$document = Plugin::$instance->documents->get( $autosave_id );
		} elseif ( $create ) {
			$autosave_id = wp_create_post_autosave(
				array(
					'post_ID'       => $this->post->ID,
					'post_type'     => $this->post->post_type,
					'post_title'    => $this->post->post_title,
					'post_excerpt'  => $this->post->post_excerpt,
					// Hack to cause $autosave_is_different=true in `wp_create_post_autosave`.
					'post_content'  => '<!-- Created With Elementor -->',
					'post_modified' => current_time( 'mysql' ),
				)
			);

			Plugin::$instance->db->copy_elementor_meta( $this->post->ID, $autosave_id );

			$document = Plugin::$instance->documents->get( $autosave_id );
			$document->save_template_type();
		} else {
			$document = false;
		}

		return $document;
	}




	/**
	 * Add/Remove edit link in dashboard.
	 *
	 * Add or remove an edit link to the post/page action links on the post/pages list table.
	 *
	 * Fired by `post_row_actions` and `page_row_actions` filters.
	 *
	 * @access public
	 *
	 * @param array $actions An array of row action links.
	 *
	 * @return array An updated array of row action links.
	 */
	public function filter_admin_row_actions( $actions ) {
		if ( $this->is_built_with_elementor() && $this->is_editable_by_current_user() ) {
			$actions['edit_with_elementor'] = sprintf(
				'<a href="%1$s">%2$s</a>',
				$this->get_edit_url(),
				PrestaHelper::__( 'Edit with Elementor', 'elementor' )
			);
		}

		return $actions;
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function is_editable_by_current_user() {
		return self::get_property( 'is_editable' ) && User::is_current_user_can_edit( $this->get_main_id() );
	}

	/**
	 * @since 1.0
	 * @access protected
	 */
	protected function _get_initial_config() {
		return array(
			'id'            => $this->get_main_id(),
			'type'          => $this->get_name(),
			'version'       => $this->get_main_meta( '_elementor_version' ),
			'remoteLibrary' => $this->get_remote_library_config(),
			'last_edited'   => $this->get_last_edited(),
			'panel'         => static::get_editor_panel_config(),
			'container'     => 'body',
			'urls'          => array(
				'exit_to_dashboard' => $this->get_exit_to_dashboard_url(),
				'preview'           => $this->get_preview_url(),
				'wp_preview'        => $this->get_wp_preview_url(),
				'permalink'         => $this->get_permalink(),
			),
		);
	}

	/**
	 * @since 1.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'document_settings',
			array(
				'label' => PrestaHelper::__( 'General Settings', 'elementor' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);
		$this->add_control(
			'post_title',
			array(
				'label'       => PrestaHelper::__( 'Title', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				PrestaHelper::get_title(),
				'label_block' => true,
				'separator'   => 'none',
			)
		);
		$post_type_object = 'page';
		$statuses = array(
			'publish' => PrestaHelper::__( 'Published' ),
		);
		$this->add_control(
			'post_status',
			array(
				'label'   => PrestaHelper::__( 'Status', 'elementor' ),
				'type'    => Controls_Manager::SELECT,
				// 'default' => $this->get_main_post()->post_status,
				'default' => 'publish',
				'options' => $statuses,
			)
		);
		$this->end_controls_section();

		/**
		 * Register document controls.
		 *
		 * Fires after Elementor registers the document controls.
		 *
		 * @since 1.0
		 *
		 * @param Document $this The document instance.
		 */
		PrestaHelper::do_action( 'elementor/documents/register_controls', $this );
	}

	/**
	 * @since 1.0
	 * @access public
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function save( $data ) {
		PrestaHelper::do_action( 'elementor/document/before_save', $this, $data );
		if ( ! empty( $data['settings'] ) ) {
			$this->save_settings( $data['settings'] );
		}
		// Don't check is_empty, because an empty array should be saved.
		if ( isset( $data['elements'] ) && is_array( $data['elements'] ) ) {
			$this->save_elements( $data['elements'] );
		}
		$revision_id = Plugin::$instance->revisions_manager->on_ajax_save_builder_data( $data );
		$this->save_settings( $data['settings'], $revision_id );
		$post_id = PrestaHelper::$id_content_global;
		$type    = PrestaHelper::$hook_current;
        $type = pSQL($type);
		$post_id = pSQL($post_id); 
		if ( $type != 'cms' &&
			$type != 'product' &&
			$type != 'supplier' &&
			$type != 'category' &&
			$type != 'manufacturer'
		) {
            if($type == 'extended'){
				$sql      = 'SELECT * FROM ' . _DB_PREFIX_ . "crazy_content WHERE hook='extended' AND id_content_type = " . $post_id;
				$row_data = \DB::getInstance()->executeS( $sql );
				$post_css = new Post_CSS( $row_data[0]['id_crazy_content'], $type );
			}else{
				$post_css = new Post_CSS( $post_id, 'page' );
			}
		} else {
			$sql      = 'SELECT * FROM ' . _DB_PREFIX_ . "crazy_content WHERE hook= '$type' AND id_content_type = " . $post_id;
			$row_data = \DB::getInstance()->executeS( $sql );
			$post_css = new Post_CSS( $row_data[0]['id_crazy_content'], $type );
		}
		$post_css->delete();
		PrestaHelper::do_action( 'elementor/document/after_save', $this, $data );
		return true;
	}

	public function is_built_with_elementor() {
		return ! ! PrestaHelper::get_post_meta( $this->post->ID, '_elementor_edit_mode', true );
	}

	public function get_edit_url() {
		$url = add_query_arg(
			array(
				'post'   => $this->get_main_id(),
				'action' => 'elementor',
			),
			PrestaHelper::admin_url( 'post.php' )
		);
		$url = PrestaHelper::apply_filters( 'elementor/document/urls/edit', $url, $this );
		return $url;
	}

	public function get_preview_url() {
		static $url;
		if ( empty( $url ) ) {
			PrestaHelper::add_filter( 'pre_option_permalink_structure', '__return_empty_string' );
			$url = set_url_scheme(
				add_query_arg(
					array(
						'elementor-preview' => $this->get_main_id(),
						'ver'               => time(),
					),
					$this->get_permalink()
				)
			);
			remove_filter( 'pre_option_permalink_structure', '__return_empty_string' );
			$url = PrestaHelper::apply_filters( 'elementor/document/urls/preview', $url, $this );
		}
		return $url;
	}

	
	public function get_json_meta( $key ) {
		$meta = PrestaHelper::get_post_meta( $this->post->ID, $key, true );
		if ( is_string( $meta ) && ! empty( $meta ) ) {
			$meta = json_decode( $meta, true );
		}
		if ( empty( $meta ) ) {
			$meta = array();
		}
		return $meta;
	}


	public function get_elements_raw_data( $data = null, $with_html_content = false ) {
		if ( is_null( $data ) ) {
			$data = $this->get_elements_data();
		}
		// Change the current documents, so widgets can use `documents->get_current` and other post data
		Plugin::$instance->documents->switch_to_document( $this );
		$editor_data = array();
		foreach ( $data as $element_data ) {
			$element = Plugin::$instance->elements_manager->create_element_instance( $element_data );
			if ( ! $element ) {
				continue;
			}
			$editor_data[] = $element->get_raw_data( $with_html_content );
		} // End foreach().
		Plugin::$instance->documents->restore_document();
		return $editor_data;
	}

	public function get_elements_data( $status = DB::STATUS_PUBLISH, $post_id = '' ) {
		$elements = $this->get_json_meta( '_elementor_data' );
        $post_id = pSQL($post_id);
		$elements = \Db::getInstance()->getRow( 'SELECT * FROM ps_crazy_revision  WHERE   id_crazy_revision =' . $post_id );
		$elements = $elements[' 	resource '];
		$elements = $this->convert_to_elementor();
		return $elements;
	}

	public function convert_to_elementor() {
		$this->save( array() );

		if ( empty( $this->post->post_content ) ) {
			return array();
		}
		preg_match_all( '/' . get_shortcode_regex() . '/', $this->post->post_content, $matches, PREG_SET_ORDER );
		if ( ! empty( $matches ) ) {
			foreach ( $matches as $shortcode ) {
				if ( trim( $this->post->post_content ) === $shortcode[0] ) {
					$widget_type = Plugin::$instance->widgets_manager->get_widget_types( 'shortcode' );
					$settings    = array(
						'shortcode' => $this->post->post_content,
					);
					break;
				}
			}
		}

		if ( empty( $widget_type ) ) {
			$widget_type = Plugin::$instance->widgets_manager->get_widget_types( 'text-editor' );
			$settings    = array(
				'editor' => $this->post->post_content,
			);
		}

		// TODO: Better coding to start template for editor
		return array(
			array(
				'id'       => Utils::generate_random_string(),
				'elType'   => 'section',
				'elements' => array(
					array(
						'id'       => Utils::generate_random_string(),
						'elType'   => 'column',
						'elements' => array(
							array(
								'id'         => Utils::generate_random_string(),
								'elType'     => $widget_type::get_type(),
								'widgetType' => $widget_type->get_name(),
								'settings'   => $settings,
							),
						),
					),
				),
			),
		);
	}

	public function print_elements_with_wrapper( $elements_data = null ) {
		if ( ! $elements_data ) {
			$elements_data = $this->get_elements_data();
		}
		?>
		<div <?php echo Utils::render_html_attributes( $this->get_container_attributes() ); ?>>
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
					<?php $this->print_elements( $elements_data ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	
	public function get_css_wrapper_selector() {
		return '';
	}

	
	public function get_panel_page_settings() {
		return array(
			'title' => sprintf( PrestaHelper::__( '%s Settings', 'elementor' ), static::get_title() ),
		);
	}

	public function get_post() {
		return $this->post;
	}

	public function get_permalink() {
		return get_permalink( $this->get_main_id() );
	}

	public function get_content( $with_css = false ) {
		return Plugin::$instance->frontend->get_builder_content( $this->post->ID, $with_css );
	}

	public function delete() {
		if ( 'revision' === $this->post->post_type ) {
			$deleted = wp_delete_post_revision( $this->post );
		} else {
			$deleted = wp_delete_post( $this->post->ID );
		}

		return $deleted && ! PrestaHelper::is_wp_error( $deleted );
	}

	protected function save_elements( $elements ) {
		$editor_data = $this->get_elements_raw_data( $elements );
	}

	public function get_autosave_id( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		$autosave = Utils::get_post_autosave( $this->post->ID, $user_id );
		if ( $autosave ) {
			return $autosave->ID;
		}
		return false;
	}

	public function save_version() {
		if ( ! defined( 'IS_CRAZY_UPGRADE' ) ) {
			$this->update_meta( '_elementor_version', CRAZY_VERSION );
			PrestaHelper::do_action( 'elementor/document/save_version', $this );
		}
	}


	public function save_type() {
		_deprecated_function( __METHOD__, '2.2.0', __CLASS__ . '::save_template_type()' );
		$this->save_template_type();
	}

	public function save_template_type() {
		return $this->update_main_meta( self::TYPE_META_KEY, $this->get_name() );
	}

	public function get_template_type() {
		return $this->get_main_meta( self::TYPE_META_KEY );
	}

	public function get_main_meta( $key ) {
		return PrestaHelper::get_post_meta( $this->get_main_id(), $key, true );
	}

	public function update_main_meta( $key, $value ) {
		return PrestaHelper::update_post_meta( $this->get_main_id(), $key, $value );
	}

	public function delete_main_meta( $key, $value = '' ) {

		return PrestaHelper::delete_post_meta( $this->get_main_id(), $key, $value );
	}

	public function get_meta( $key ) {
		return PrestaHelper::get_post_meta( $this->post->ID, $key, true );
	}


	public function update_meta( $key, $value ) {
		// Use `update_metadata` in order to work also with revisions.
		return update_metadata( 'post', $this->post->ID, $key, $value );
	}

	public function delete_meta( $key, $value = '' ) {
		// Use `delete_metadata` in order to work also with revisions.

		return delete_metadata( 'post', $this->post->ID, $key, $value );
	}

	public function get_last_edited() {
		$post          = $this->post;
		$autosave_post = $this->get_autosave();

		if ( $autosave_post ) {
			$post = $autosave_post->get_post();
		}

		$date         = date_i18n( PrestaHelper::_x( 'M j, H:i', 'revision date format', 'elementor' ), strtotime( $post->post_modified ) );
		$display_name = get_the_author_meta( 'display_name', $post->post_author );

		if ( $autosave_post || 'revision' === $post->post_type ) {
			/* translators: 1: Saving date, 2: Author display name */
			$last_edited = sprintf( PrestaHelper::__( 'Draft saved on %1$s by %2$s', 'elementor' ), '<time>' . $date . '</time>', $display_name );
		} else {
			/* translators: 1: Editing date, 2: Author display name */
			$last_edited = sprintf( PrestaHelper::__( 'Last edited on %1$s by %2$s', 'elementor' ), '<time>' . $date . '</time>', $display_name );
		}

		return $last_edited;
	}

	public function __construct( array $data = array() ) {
		if ( $data ) {
			if ( empty( $data['post_id'] ) ) {
				throw new \Exception( sprintf( 'Post ID #%s does not exist.', $data['post_id'] ), Exceptions::NOT_FOUND );
			}
			$this->post_id = $data['post_id'];
			$data['id'] = $data['post_id'];
			if ( ! isset( $data['settings'] ) ) {
				$data['settings'] = array();
			}
		}

		parent::__construct( $data );
	}

	protected function get_remote_library_config() {
		$config = array(
			'type'               => 'block',
			'category'           => $this->get_name(),
			'autoImportSettings' => false,
		);
		return $config;
	}

	protected function save_settings( $settings, $id = 0 ) {
		$page_settings_manager = SettingsManager::get_settings_managers( 'page' );
		if ( $id == 0 ) {
			$id = PrestaHelper::$id_content_global;
		}
		$page_settings_manager->save_settings( $settings, $id );
	}

	protected function print_elements( $elements_data ) {
		
		foreach ( $elements_data as $element_data ) {
			$element = Plugin::$instance->elements_manager->create_element_instance( $element_data );
			if ( ! $element ) {
				continue;
			}
			$element->print_element();
		}
	}

	private function save_usage( $elements ) {
		if ( DB::STATUS_PUBLISH !== $this->post->post_status ) {
			return;
		}
		if ( ! self::get_property( 'is_editable' ) ) {
			return;
		}
		$usage = array();
		Plugin::$instance->db->iterate_data(
			$elements,
			function ( $element ) use ( &$usage ) {
				if ( empty( $element['widgetType'] ) ) {
					$type = $element['elType'];
				} else {
					$type = $element['widgetType'];
				}
				if ( ! isset( $usage[ $type ] ) ) {
					$usage[ $type ] = 0;
				}
				$usage[ $type ]++;

				return $element;
			}
		);

		// Keep prev usage, before updating the new usage meta.
		$prev_usage = $this->get_meta( self::ELEMENTS_USAGE_META_KEY );
		$this->update_meta( self::ELEMENTS_USAGE_META_KEY, $usage );
		// Handle global usage.
		$doc_type = $this->get_name();
		$global_usage = PrestaHelper::get_option( self::ELEMENTS_USAGE_OPTION_NAME, array() );
		if ( $prev_usage ) {
			foreach ( $prev_usage as $type => $count ) {
				if ( isset( $global_usage[ $doc_type ][ $type ] ) ) {
					$global_usage[ $doc_type ][ $type ] -= $prev_usage[ $type ];
					if ( 0 === $global_usage[ $doc_type ][ $type ] ) {
						unset( $global_usage[ $doc_type ][ $type ] );
					}
				}
			}
		}

		foreach ( $usage as $type => $count ) {
			if ( ! isset( $global_usage[ $doc_type ] ) ) {
				$global_usage[ $doc_type ] = array();
			}

			if ( ! isset( $global_usage[ $doc_type ][ $type ] ) ) {
				$global_usage[ $doc_type ][ $type ] = 0;
			}

			$global_usage[ $doc_type ][ $type ] += $usage[ $type ];
		}

		PrestaHelper::update_option( self::ELEMENTS_USAGE_OPTION_NAME, $global_usage );
	}
}