<?php
namespace CrazyElements\Core\Revisions;

ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );
use CrazyElements;
use CrazyElements\Core\Common\Modules\Ajax\Module as Ajax;
use CrazyElements\Core\Base\Document;
use CrazyElements\Core\Files\CSS\Post as Post_CSS;
use CrazyElements\Core\Settings\Manager;
use CrazyElements\Plugin;
use CrazyElements\Utils;
use CrazyElements\PrestaHelper;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

define( 'CE_MINUTE_IN_SECONDS', 60 );
define( 'CE_HOUR_IN_SECONDS', 60 * CE_MINUTE_IN_SECONDS );
define( 'CE_DAY_IN_SECONDS', 24 * CE_HOUR_IN_SECONDS );
define( 'CE_WEEK_IN_SECONDS', 7 * CE_DAY_IN_SECONDS );
define( 'CE_MONTH_IN_SECONDS', 30 * CE_DAY_IN_SECONDS );
define( 'CE_YEAR_IN_SECONDS', 365 * CE_DAY_IN_SECONDS );

/**
 * @since 1.0.0
 */
class Revisions_Manager {


	/**
	 * Maximum number of revisions to display.
	 */
	const MAX_REVISIONS_TO_DISPLAY = 100;

	/**
	 * Authors list.
	 *
	 * Holds all the authors.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private static $authors = array();

	/**
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		self::register_actions();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function handle_revision() {
		add_filter( 'wp_save_post_revision_check_for_changes', '__return_false' );
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @param $post_content
	 * @param $post_id
	 *
	 * @return string
	 */
	public static function avoid_delete_auto_save( $post_content, $post_id ) {
		if ( $post_id && Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
			$post_content .= '<!-- Created with CrazyElements -->';
		}

		return $post_content;
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function remove_temp_post_content() {
		global $post;

		if ( Plugin::$instance->db->is_built_with_elementor( $post->ID ) ) {
			$post->post_content = str_replace( '<!-- Created with CrazyElements -->', '', $post->post_content );
		}
	}

	public static function get_post_data() {
		$context    = \Context::getContext();
		$shop_id    = $context->shop->id;
		$id_lang    = \Tools::getValue( 'id_lang', $context->language->id );
		$table_name = _DB_PREFIX_ . 'crazy_revision';
		$post_id    = PrestaHelper::$id_content_global;

		$type = PrestaHelper::$hook_current;

		if ( $type != 'cms'
			&& $type != 'product'
			&& $type != 'supplier'
			&& $type != 'category'
			&& $type != 'manufacturer'
		) {
			$type = 'page';
		}
		$target = $post_id;

		return array(
			'shop_id'        => $shop_id,
			'post_id'        => $target,
			'lang_id'        => $id_lang,
			'type'           => $type,
			'table_name'     => $table_name,
			'table_name_raw' => 'crazy_revision',
		);

	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @param int   $post_id
	 * @param array $query_args
	 * @param bool  $parse_result
	 *
	 * @return array
	 */
	public static function get_revisions( $post_id = 0, $query_args = array(), $parse_result = true ) {
		$revisions = array();

		$post_value = self::get_post_data();
		

		$results      = \Db::getInstance()->executeS( 'SELECT * FROM ' . $post_value['table_name'] . '  WHERE   id_lang =' . $post_value['lang_id'] . ' AND id_shop =  ' . $post_value['shop_id'] . ' AND id_post=' . $post_value['post_id'] . " AND type='" . $post_value['type'] . "' order by id_crazy_revision  DESC LIMIT " . self::MAX_REVISIONS_TO_DISPLAY );
		$current_time = self::current_time();

		if ( ! empty( $results ) ) {

			foreach ( $results as $key => $revision ) {

				$date = date( PrestaHelper::__( 'M j @ H:i', 'revision date format', 'elementor' ), strtotime( $revision['post_modified'] ) );

				$human_time = self::human_time_diff( strtotime( $revision['post_modified'] ), $current_time );

				if ( $key === 0 ) {
					$type = 'current';
				} elseif ( false !== strpos( $revision['title'], 'autosave' ) ) {
					$type = 'autosave';
				} else {
					$type = 'revision';
				}
				$context = \Context::getContext();
				$revisions[] = [
					'id' => $revision['id_crazy_revision'],
					'author' => \Tools::getValue('employee_name'),
					'timestamp' => strtotime( $revision['post_modified'] ),
					'date'      => sprintf(
					  /* translators: 1: Human readable time difference, 2: Date */
						PrestaHelper::__( '%1$s ago (%2$s)', 'elementor' ),
						$human_time,
						$date
					),
					'type' => $type,
					'gravatar' =>'',
				];
			}
		}
		return $revisions;
	}

	static function human_time_diff( $from, $to = 0 ) {
		if ( empty( $to ) ) {
			$to = time();
		}

		$diff = (int) abs( $to - $from );

		if ( $diff < CE_MINUTE_IN_SECONDS ) {
			$time     = $diff;
			$timetext = 'seconds';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'second';
			}
		} elseif ( $diff < CE_HOUR_IN_SECONDS && $diff >= CE_MINUTE_IN_SECONDS ) {
			$time     = round( $diff / CE_MINUTE_IN_SECONDS );
			$timetext = 'mins';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'min';
			}
		} elseif ( $diff < CE_DAY_IN_SECONDS && $diff >= CE_HOUR_IN_SECONDS ) {
			$time     = round( $diff / CE_HOUR_IN_SECONDS );
			$timetext = 'hours';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'hour';
			}
		} elseif ( $diff < CE_WEEK_IN_SECONDS && $diff >= CE_DAY_IN_SECONDS ) {
			$time     = round( $diff / CE_DAY_IN_SECONDS );
			$timetext = 'days';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'day';
			}
		} elseif ( $diff < CE_MONTH_IN_SECONDS && $diff >= CE_WEEK_IN_SECONDS ) {
			$time     = round( $diff / CE_WEEK_IN_SECONDS );
			$timetext = 'weeks';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'week';
			}
		} elseif ( $diff < CE_YEAR_IN_SECONDS && $diff >= CE_MONTH_IN_SECONDS ) {
			$time     = round( $diff / CE_MONTH_IN_SECONDS );
			$timetext = 'months';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'month';
			}
		} elseif ( $diff >= CE_YEAR_IN_SECONDS ) {
			$time     = round( $diff / CE_YEAR_IN_SECONDS );
			$timetext = 'years';
			if ( $time <= 1 ) {
				$time     = 1;
				$timetext = 'year';
			}
		}

		$since = sprintf( '%s %s', $time, $timetext );
		return $since;
	}


	public static function current_time() {

		$datetime = new \DateTime( null, new \DateTimeZone( \Configuration::get( 'PS_TIMEZONE' ) ) );
		return $datetime->getTimestamp();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function update_autosave( $autosave_data ) {
		self::save_revision( $autosave_data['ID'] );
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function save_revision( $revision_id ) {
		$parent_id = wp_is_post_revision( $revision_id );

		if ( $parent_id ) {
			Plugin::$instance->db->safe_copy_elementor_meta( $parent_id, $revision_id );
		}
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function restore_revision( $parent_id, $revision_id ) {
		$is_built_with_elementor = Plugin::$instance->db->is_built_with_elementor( $revision_id );

		Plugin::$instance->db->set_is_elementor_page( $parent_id, $is_built_with_elementor );

		if ( ! $is_built_with_elementor ) {
			return;
		}

		Plugin::$instance->db->copy_elementor_meta( $revision_id, $parent_id );

		$post_css = new Post_CSS( $parent_id );

		$post_css->update();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @param $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function ajax_get_revision_data( array $data ) {
		if ( ! isset( $data['id'] ) ) {
			throw new \Exception( 'You must set the revision ID.' );
		}
		$post_value = self::get_post_data();
		$revision   = \Db::getInstance()->getRow( 'SELECT * FROM ' . $post_value['table_name'] . '  WHERE   id_crazy_revision =' . $data['id'] );
		if ( empty( $revision ) ) {
			throw new \Exception( 'Invalid revision.' );
		}

		$revision_data = array(
			'settings' => Manager::get_settings_managers( 'page' )->get_model( $revision['id_crazy_revision'] )->get_settings(),
			'elements' => \Tools::jsonDecode( $revision['resource'], true ),  // Plugin::$instance->db->get_plain_editor( $revision['id_crazy_revision'] ),
		);

		return $revision_data;
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 *
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	public static function ajax_delete_revision( array $data ) {
		if ( empty( $data['id'] ) ) {
			throw new \Exception( 'You must set the revision ID.' );
		}

		return \Db::getInstance()->delete( 'crazy_revision', 'id_crazy_revision = ' . $data['id'] );
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function add_revision_support_for_all_post_types() {
		$post_types = get_post_types_by_support( 'elementor' );
		foreach ( $post_types as $post_type ) {
			add_post_type_support( $post_type, 'revisions' );
		}
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 * @param  array    $return_data
	 * @param  Document $document
	 *
	 * @return array
	 */
	public static function on_ajax_save_builder_data( $data ) {

		$data['settings']['post_title'] = '';
		$post_value                     = self::get_post_data();
		\Db::getInstance()->insert(
			$post_value['table_name_raw'],
			array(
				'id_lang'  => $post_value['lang_id'],
				'id_shop'  => $post_value['shop_id'],
				'id_post'  => $post_value['post_id'],
				'title'    => $data['settings']['post_title'],
				'resource' => addslashes( \Tools::jsonEncode( $data['elements'] ) ),
				'type'     => $post_value['type'],
				'settings' => pSQL(\Tools::jsonEncode( $data['settings'] )),
			)
		);

		return \Db::getInstance()->Insert_ID();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function db_before_save( $status, $has_changes ) {
		if ( $has_changes ) {
			self::handle_revision();
		}
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function editor_settings( $settings, $post_id ) {
		$settings = array_replace_recursive( $settings, [
			'revisions_enabled' => ( $post_id && wp_revisions_enabled( get_post( $post_id ) ) ),
			'current_revision_id' => self::current_revision_id( $post_id ),
			'i18n' => [
				'edit_draft' => PrestaHelper::__( 'Edit Draft', 'elementor' ),
				'edit_published' => PrestaHelper::__( 'Edit Published', 'elementor' ),
				'no_revisions_1' => PrestaHelper::__( 'Revision history lets you save your previous versions of your work, and restore them any time.', 'elementor' ),
				'no_revisions_2' => PrestaHelper::__( 'Start designing your page and you\'ll be able to see the entire revision history here.', 'elementor' ),
				'current' => PrestaHelper::__( 'Current Version', 'elementor' ),
				'restore' => PrestaHelper::__( 'Restore', 'elementor' ),
				'restore_auto_saved_data' => PrestaHelper::__( 'Restore Auto Saved Data', 'elementor' ),
				'restore_auto_saved_data_message' => PrestaHelper::__( 'There is an autosave of this post that is more recent than the version below. You can restore the saved data fron the Revisions panel', 'elementor' ),
				'revision' => PrestaHelper::__( 'Revision', 'elementor' ),
				'revision_history' => PrestaHelper::__( 'Revision History', 'elementor' ),
				'revisions_disabled_1' => PrestaHelper::__( 'It looks like the post revision feature is unavailable in your website.', 'elementor' ),
				'revisions_disabled_2' => '',
			],
		] );

		return $settings;
	}

	public static function ajax_get_revisions() {
		return self::get_revisions();
	}

	/**
	 * @since  1.0.0
	 * @access public
	 * @static
	 */
	public static function register_ajax_actions( Ajax $ajax ) {
		$ajax->register_ajax_action( 'get_revisions', array( __CLASS__, 'ajax_get_revisions' ) );
		$ajax->register_ajax_action( 'get_revision_data', array( __CLASS__, 'ajax_get_revision_data' ) );
		$ajax->register_ajax_action( 'delete_revision', array( __CLASS__, 'ajax_delete_revision' ) );
	}

	/**
	 * @since  1.0.0
	 * @access private
	 * @static
	 */
	private static function register_actions() {
		PrestaHelper::add_action( 'wp_restore_post_revision', array( __CLASS__, 'restore_revision' ), 10, 2 );
		PrestaHelper::add_action( 'init', array( __CLASS__, 'add_revision_support_for_all_post_types' ), 9999 );
		PrestaHelper::add_filter( 'elementor/editor/localize_settings', array( __CLASS__, 'editor_settings' ), 10, 2 );
		PrestaHelper::add_action( 'elementor/db/before_save', array( __CLASS__, 'db_before_save' ), 10, 2 );
		PrestaHelper::add_action( '_wp_put_post_revision', array( __CLASS__, 'save_revision' ) );
		PrestaHelper::add_action( 'wp_creating_autosave', array( __CLASS__, 'update_autosave' ) );
		PrestaHelper::add_action( 'elementor/ajax/register_actions', array( __CLASS__, 'register_ajax_actions' ) );

		// Hack to avoid delete the auto-save revision in WP editor.
		PrestaHelper::add_filter( 'edit_post_content', array( __CLASS__, 'avoid_delete_auto_save' ), 10, 2 );
		PrestaHelper::add_action( 'edit_form_after_title', array( __CLASS__, 'remove_temp_post_content' ) );

		if ( Utils::is_ajax() ) {
			PrestaHelper::add_filter( 'elementor/documents/ajax_save/return_data', array( __CLASS__, 'on_ajax_save_builder_data' ) );
		}
	}

	/**
	 * @since  1.0.0
	 * @access private
	 * @static
	 */
	private static function current_revision_id( $post_id ) {

		$post_id = PrestaHelper::$id_content_global;
		$type    = PrestaHelper::$hook_current;

		if ( $type != 'cms'
			&& $type != 'product'
			&& $type != 'supplier'
			&& $type != 'category'
			&& $type != 'manufacturer'
		) {
			$type = 'page';
		}

		$current_revision_id = $post_id;
		$autosave            = Utils::get_post_autosave( $post_id, $type );

		if ( is_object( $autosave ) ) {
			$current_revision_id = $autosave->id_crazy_revision;
		}
		return $current_revision_id;
	}
}
