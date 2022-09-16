<?php

namespace CrazyElements\Core\Files;

use CrazyElements\Core\Files\Manager as Files_Manager;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base {

	const UPLOADS_DIR = 'elementor/';

	const DEFAULT_FILES_DIR = 'css/';

	const META_KEY = '';

	private static $wp_uploads_dir = [];

	private $files_dir;

	private $file_name;

	/**
	 * File path.
	 *
	 * Holds the file path.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Content.
	 *
	 * Holds the file content.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $content;

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_base_uploads_dir() {
		$wp_upload_dir = self::get_wp_uploads_dir();

		return  _PS_MODULE_DIR_ . 'crazyelements/assets/css/frontend/';
	}

	/**
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_base_uploads_url() {
		$wp_upload_dir = self::get_wp_uploads_dir();

		return $wp_upload_dir['baseurl'] . '/' . self::UPLOADS_DIR;
	}

	/**
	 * Use a create function for PhpDoc (@return static).
	 *
	 * @return static
	 */
	public static function create() {
		
		$file_manager=new Files_Manager;
		return  $file_manager->get( get_called_class(), func_get_args() );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( $file_name ) {

		

		/**
		 * Elementor File Name
		 *
		 * Filters the File name
		 *
		 * @since 1.0.0
		 *
		 * @param string   $file_name
		 * @param object $this The file instance
		 */
		$file_name = PrestaHelper::apply_filters( 'elementor/files/file_name', $file_name, $this );

		$this->set_file_name( $file_name );

		$this->set_files_dir( static::DEFAULT_FILES_DIR );

		$this->set_path();
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function set_files_dir( $files_dir ) {
		$this->files_dir = $files_dir;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function set_file_name( $file_name ) {



		$this->file_name = $file_name;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_file_name() {
		return $this->file_name;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_url() {
		
		$url = CRAZY_URL."assets/css/frontend/".$this->files_dir . $this->file_name;

		return $url;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_content() {
		if ( ! $this->content ) {
			$this->content = $this->parse_content();
		}
		return $this->content;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function update() {
		$status=$this->update_file();


		$meta = $this->get_meta();

		$meta['time'] = time();
		$meta['status'] = $status;
		$elementor_library = \Tools::getValue('elementor_library');
		if($elementor_library==''){
			$this->update_meta( $meta );
		}
		
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function update_file() {
		$this->content = $this->parse_content();
		
		$elementor_library = \Tools::getValue('elementor_library');

		
		if($elementor_library!=''){
			echo "<style>".$this->content."</style>";
		}else{
			if ( $this->content ) {
				// die(__FILE__ . ' : ' . __LINE__);
				$this->write();
			} else {
				$this->delete();
			}
		}
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function write() {
		return file_put_contents( $this->path, $this->content );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function delete() {
		if ( file_exists( $this->path ) ) {
			unlink( $this->path );
		}

		$this->delete_meta();
	}

	/**
	 * Get meta data.
	 *
	 * Retrieve the CSS file meta data. Returns an array of all the data, or if
	 * custom property is given it will return the property value, or `null` if
	 * the property does not exist.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $property Optional. Custom meta data property. Default is
	 *                         null.
	 *
	 * @return array|null An array of all the data, or if custom property is
	 *                    given it will return the property value, or `null` if
	 *                    the property does not exist.
	 */
	public function get_meta( $property = null ) {
		$default_meta = $this->get_default_meta();
		$meta = array_merge( $default_meta, (array) $this->load_meta() );

		if ( $property ) {
			return isset( $meta[ $property ] ) ? $meta[ $property ] : null;
		}

		return $meta;
	}

	/**
	 * Load meta.
	 *
	 * Retrieve the file meta data.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function load_meta() {
		$load_meta= PrestaHelper::get_option( static::META_KEY );
		return \Tools::jsonDecode($load_meta,true);
	}

	/**
	 * Update meta.
	 *
	 * Update the file meta data.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $meta New meta data.
	 */
	protected function update_meta( $meta ) {
		PrestaHelper::update_option( static::META_KEY, $meta );
	}

	/**
	 * Delete meta.
	 *
	 * Delete the file meta data.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function delete_meta() {
		PrestaHelper::delete_option( static::META_KEY );
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_default_meta() {
		return [
			'time' => 0,
		];
	}

	/**
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static function get_wp_uploads_dir() {
		global $blog_id;
		if ( empty( self::$wp_uploads_dir[ $blog_id ] ) ) {
			self::$wp_uploads_dir[ $blog_id ] =  _PS_MODULE_DIR_ . 'crazyelements/assets/css/frontend/';
		}

		return self::$wp_uploads_dir[ $blog_id ];
	}

	/**
	 * @since 1.0.0
	 * @access private
	 */
	private function set_path() {
		$dir_path = self::get_base_uploads_dir() . $this->files_dir;
		if ( ! is_dir( $dir_path ) ) {
            mkdir( $dir_path,0777, true );
		}
		$this->path = $dir_path . $this->file_name;
	}
}
