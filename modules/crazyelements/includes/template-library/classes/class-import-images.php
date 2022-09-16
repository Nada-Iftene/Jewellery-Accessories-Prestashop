<?php
namespace CrazyElements\TemplateLibrary\Classes;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Import_Images {

	/**
	 * 
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	// private $_replace_image_ids = [];

	private static $target = 'cms/';
    private static $default  = 'placeholder.png';

    public  static $allowed_ext = array('jpg', 'jpe', 'jpeg', 'png', 'gif', 'svg');

    private static $available = array();

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attachment The attachment.
	 *
	 * @return false|array Available image data, or false.
	 */
	public function import( $attachment ) {
        
		$url = $attachment['url'];
        if (isset(self::$available[$url])) {
            return self::$available[$url];
        }
        $filename = basename($url);
        if (self::$default == $filename) {
            return self::$available[$url] = false;
        }
        $file_content = \Tools::file_get_contents($url);
        if ( empty( $file_content ) ) {
            return $attachment;
		}
        
        $file_info = pathinfo($filename);
        if(!isset($file_info['extension'])){
            return $attachment;
        }
        if (in_array(\Tools::strToLower($file_info['extension']), self::$allowed_ext)) {
            $file_path = _PS_IMG_DIR_ . self::$target . $filename;
           
            if (file_exists($file_path)) {
                $existing_content = \Tools::file_get_contents($file_path);
                if (md5($file_content) == md5($existing_content)) {
                     return self::$available[$url]=self::get_file_array( $filename);
                }
                $filename = $file_info['filename'] . '_' . base64_encode(mt_rand(mt_rand(0,10000),mt_rand(10001,999999))). '.' . $file_info['extension'];
                $file_path = _PS_IMG_DIR_ . self::$target . $filename;
            }
           if(!is_dir (_PS_IMG_DIR_ . self::$target)){
                mkdir(_PS_IMG_DIR_ . self::$target);
                chmod(_PS_IMG_DIR_ . self::$target, 0777);
            }
             chmod(_PS_IMG_DIR_ . self::$target, 0777);
            if (file_put_contents($file_path, $file_content)) {
                return self::$available[$url]=self::get_file_array( $filename); 
            }
        }else{

        	return false;
        }
        return $attachment;

	}

	private static function get_file_array($filename) {
		return array(
                    'id' => 0,
                    'url' => \Tools::getShopDomain(true)._PS_IMG_ . self::$target . $filename,
                );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
	}
}
