<?php
class PseIcon extends ObjectModel
{
	public $id;

	/** @var string title  */
	public $option_name ;

	/** @var string font_weight  */
	public $option_value ;


	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'crazy_options',
		'primary' => 'id_options',
		'fields' => array(
			'option_name' => 	array('type' => self::TYPE_STRING,  'required' => true, 'size' => 256),
			'option_value' => 	array('type' => self::TYPE_STRING,  'required' => true, 'size' => 256),
		),
	);
	
}