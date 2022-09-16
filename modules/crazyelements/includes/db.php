<?php
namespace CrazyElements;

use CrazyElements\Core\DynamicTags\Manager;
use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class DB {

	const DB_VERSION = '0.4';
	const STATUS_PUBLISH = 'publish';
	const STATUS_DRAFT = 'draft';
	const STATUS_PRIVATE = 'private';
	const STATUS_AUTOSAVE = 'autosave';
	const STATUS_PENDING = 'pending';

	protected $switched_post_data = [];
	protected $switched_data = [];

	public function save_editor( $post_id, $data, $status = self::STATUS_PUBLISH ) {
		_deprecated_function( __METHOD__, '2.6.0', 'Plugin::$instance->documents->get( $post_id )->save()' );
		$document = Plugin::$instance->documents->get( $post_id );
		if ( self::STATUS_AUTOSAVE === $status ) {
			$document = $document->get_autosave( 0, true );
		}
		return $document->save( [
			'elements' => $data,
			'settings' => [
				'post_status' => 'publish',
			],
		] );
	}

	public function get_builder( $post_id, $status = self::STATUS_PUBLISH ) {
		if ( self::STATUS_DRAFT === $status ) {
			$document = Plugin::$instance->documents->get_doc_or_auto_save( $post_id );
		} else {
			$document = Plugin::$instance->documents->get( $post_id );
		}
		if ( $document ) {
			$editor_data = $document->get_elements_raw_data( null, true );
		} else {
			$editor_data = [];
		}
		return $editor_data;
	}

	protected function _get_json_meta( $post_id, $key ) {
		$meta = PrestaHelper::get_post_meta( $post_id, $key, true );
		if ( is_string( $meta ) && ! empty( $meta ) ) {
			$meta = json_decode( $meta, true );
		}
		if ( empty( $meta ) ) {
			$meta = [];
		}
		return $meta;
	}

	public function get_plain_editor( $post_id, $status = self::STATUS_PUBLISH ) {
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			return $document->get_elements_data( $status, $post_id );
		}
		return [];
	}

	public function get_newer_autosave( $post_id ) {
		_deprecated_function( __METHOD__, '2.0.0', 'Plugin::$instance->documents->get( $post_id )->get_newer_autosave()' );
		$document = Plugin::$instance->documents->get( $post_id );
		return $document->get_newer_autosave();
	}

	public function get_new_editor_from_wp_editor( $post_id ) {
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			return $document->convert_to_elementor();
		}
		return [];
	}


	public function _get_new_editor_from_wp_editor( $post_id ) {
		_deprecated_function( __METHOD__, '2.1.0', 'Plugin::$instance->documents->get( $post_id )->convert_to_elementor()' );
		return $this->get_new_editor_from_wp_editor( $post_id );
	}

	public function set_is_elementor_page( $post_id, $is_elementor = true ) {
		if ( $is_elementor ) {
			PrestaHelper::update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		} else {
			PrestaHelper::delete_post_meta( $post_id, '_elementor_edit_mode' );
		}
	}

	private function render_element_plain_content( $element_data ) {
		if ( 'widget' === $element_data['elType'] ) {
			$widget = Plugin::$instance->elements_manager->create_element_instance( $element_data );
			if ( $widget ) {
				$widget->render_plain_content();
			}
		}
		if ( ! empty( $element_data['elements'] ) ) {
			foreach ( $element_data['elements'] as $element ) {
				$this->render_element_plain_content( $element );
			}
		}
	}

	public function save_plain_text( $post_id ) {
		$dynamic_tags = Plugin::$instance->dynamic_tags;
		$parsing_mode = $dynamic_tags->get_parsing_mode();
		$dynamic_tags->set_parsing_mode( Manager::MODE_REMOVE );
		$plain_text = $this->get_plain_text( $post_id );
		wp_update_post(
			[
				'ID' => $post_id,
				'post_content' => $plain_text,
			]
		);
		// Restore parsing mode.
		$dynamic_tags->set_parsing_mode( $parsing_mode );
	}

	public function iterate_data( $data_container, $callback, $args = [] ) {
		if ( isset( $data_container['elType'] ) ) {
			if ( ! empty( $data_container['elements'] ) ) {
				$data_container['elements'] = $this->iterate_data( $data_container['elements'], $callback, $args );
			}
			return call_user_func( $callback, $data_container, $args );
		}
		foreach ( $data_container as $element_key => $element_value ) {
			$element_data = $this->iterate_data( $data_container[ $element_key ], $callback, $args );
			if ( null === $element_data ) {
				continue;
			}
			$data_container[ $element_key ] = $element_data;
		}
		return $data_container;
	}

	public function safe_copy_elementor_meta( $from_post_id, $to_post_id ) {
		if ( ! did_action( 'elementor/db/before_save' ) ) {
			if ( ! Plugin::$instance->db->is_built_with_elementor( $from_post_id ) ) {
				return;
			}
			// It's an exited Elementor auto-save
			if ( PrestaHelper::get_post_meta( $to_post_id, '_elementor_data', true ) ) {
				return;
			}
		}
		$this->copy_elementor_meta( $from_post_id, $to_post_id );
	}

	public function copy_elementor_meta( $from_post_id, $to_post_id ) {
		$from_post_meta = PrestaHelper::get_post_meta( $from_post_id );
		$core_meta = [
			'_wp_page_template',
			'_thumbnail_id',
		];
		foreach ( $from_post_meta as $meta_key => $values ) {
			if ( 0 === strpos( $meta_key, '_elementor' ) || in_array( $meta_key, $core_meta, true ) ) {
				$value = $values[0];
				if ( '_elementor_data' === $meta_key ) {
					$value = wp_slash( $value );
				} else {
					$value = maybe_unserialize( $value );
				}
				update_metadata( 'post', $to_post_id, $meta_key, $value );
			}
		}
	}

	public function is_built_with_elementor( $post_id ) {
		return ! ! PrestaHelper::get_post_meta( $post_id, '_elementor_edit_mode', true );
	}

	public function switch_to_post( $post_id ) {
		$post_id = $editor_post_id = abs( intval( $post_id) );  //absint( $post_id );
		// If is already switched, or is the same post, return.
		if ( get_the_ID() === $post_id ) {
			$this->switched_post_data[] = false;
			return;
		}
		$this->switched_post_data[] = [
			'switched_id' => $post_id,
			'original_id' => get_the_ID(), // Note, it can be false if the global isn't set
		];
		$GLOBALS['post'] = get_post( $post_id ); // WPCS: override ok.
		setup_postdata( $GLOBALS['post'] );
	}

	public function restore_current_post() {
		$data = array_pop( $this->switched_post_data );
		if ( ! $data ) {
			return;
		}
		// It was switched from an empty global post, restore this state and unset the global post
		if ( false === $data['original_id'] ) {
			unset( $GLOBALS['post'] );
			return;
		}
		$GLOBALS['post'] = get_post( $data['original_id'] ); // WPCS: override ok.
		setup_postdata( $GLOBALS['post'] );
	}


	public function switch_to_query( $query_vars, $force_global_post = false ) {
		global $wp_query;
		$current_query_vars = $wp_query->query;
		// If is already switched, or is the same query, return.
		if ( $current_query_vars === $query_vars ) {
			$this->switched_data[] = false;
			return;
		}
		$new_query = new \WP_Query( $query_vars );
		$switched_data = [
			'switched' => $new_query,
			'original' => $wp_query,
		];
		if ( ! empty( $GLOBALS['post'] ) ) {
			$switched_data['post'] = $GLOBALS['post'];
		}
		$this->switched_data[] = $switched_data;
		$wp_query = $new_query; // WPCS: override ok.
		// Ensure the global post is set only if needed
		unset( $GLOBALS['post'] );
		if ( isset( $new_query->posts[0] ) ) {
			if ( $force_global_post || $new_query->is_singular() ) {
				$GLOBALS['post'] = $new_query->posts[0]; // WPCS: override ok.
				setup_postdata( $GLOBALS['post'] );
			}
		}
		if ( $new_query->is_author() ) {
			$GLOBALS['authordata'] = get_userdata( $new_query->get( 'author' ) ); // WPCS: override ok.
		}
	}

	public function restore_current_query() {
		$data = array_pop( $this->switched_data );
		// If not switched, return.
		if ( ! $data ) {
			return;
		}
		global $wp_query;
		$wp_query = $data['original']; // WPCS: override ok.
		// Ensure the global post/authordata is set only if needed.
		unset( $GLOBALS['post'] );
		unset( $GLOBALS['authordata'] );
		if ( ! empty( $data['post'] ) ) {
			$GLOBALS['post'] = $data['post']; // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
		}
		if ( $wp_query->is_author() ) {
			$GLOBALS['authordata'] = get_userdata( $wp_query->get( 'author' ) ); // WPCS: override ok.
		}
	}

	public function get_plain_text( $post_id ) {
		$document = Plugin::$instance->documents->get( $post_id );
		$data = $document ? $document->get_elements_data() : [];
		return $this->get_plain_text_from_data( $data );
	}

	public function get_plain_text_from_data( $data ) {
		ob_start();
		if ( $data ) {
			foreach ( $data as $element_data ) {
				$this->render_element_plain_content( $element_data );
			}
		}
		$plain_text = ob_get_clean();
		// Remove unnecessary tags.
		$plain_text = preg_replace( '/<\/?div[^>]*\>/i', '', $plain_text );
		$plain_text = preg_replace( '/<\/?span[^>]*\>/i', '', $plain_text );
		$plain_text = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $plain_text );
		$plain_text = preg_replace( '/<i [^>]*><\\/i[^>]*>/', '', $plain_text );
		$plain_text = preg_replace( '/ class=".*?"/', '', $plain_text );
		// Remove empty lines.
		$plain_text = preg_replace( '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $plain_text );
		$plain_text = trim( $plain_text );
		return $plain_text;
	}
}