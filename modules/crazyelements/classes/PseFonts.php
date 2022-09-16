<?php

class PseFonts extends ObjectModel
{
	public $id;

	/** @var string title  */
	public $title ;

	/** @var string font_weight  */
	public $font_weight ;


	/** @var string font_style  */
	public $font_style ;

	/** @var string woff  */
	public $woff ;


	/** @var string woff2  */
	public $woff2 ;


	/** @var string ttf  */
	public $ttf ;


	/** @var string sgv  */
	public $svg ;


	/** @var string eot  */
	public $eot ;


	/** @var boolean Apply to active */
	public $active;

	/** @var string eot  */
	public $fontname ;

	
	/**
	 * @see ObjectModel::$definition
	 */

	public static $definition = array(
		'table' => 'crazy_fonts',
		'primary' => 'id_crazy_fonts',
		'fields' => array(
			'title' => 			array('type' => self::TYPE_STRING, 'validate' => 'isName', 'required' => true, 'size' => 64),
			'font_weight' => 	array('type' => self::TYPE_STRING,  'required' => true, 'size' => 256),
			'font_style' => 	array('type' => self::TYPE_STRING,  'required' => true, 'size' => 256),
			'woff' => 			array('type' => self::TYPE_STRING,  'size' => 256),
			'woff2' => 			array('type' => self::TYPE_STRING,  'size' => 256),
			'ttf' => 			array('type' => self::TYPE_STRING,  'size' => 256),
			'svg' => 			array('type' => self::TYPE_STRING,  'size' => 256),
			'eot' => 			array('type' => self::TYPE_STRING,  'size' => 256),
			'fontname' => 		array('type' => self::TYPE_STRING,  'required' => true,'size' => 256),

			'active' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

		),
	);


	public static function get_data_font(){
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('crazy_fonts');
		$sql->where('active = 1');
		return Db::getInstance()->executeS($sql);
	}
	
}