<?php
use CrazyElements\Core\DocumentTypes\Post;
use CrazyElements\PrestaHelper;

class CrazyExtendedmodules extends ObjectModel {

	public $id_crazy_extended_modules;
	public $title;
	public $module_name;
    public $controller_name;
    public $front_controller_name;
    public $field_name;
    public $extended_item_key;
    public $status;
	public $active = 1;
	public static $definition = array(
		'table'     => 'crazy_extended_modules',
		'primary'   => 'id_crazy_extended_modules',
		'fields'    => array(			
			'title'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'module_name'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'controller_name'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'front_controller_name'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'field_name'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'extended_item_key'           => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
				'required' => true,
			),
			'active'          => array(
				'type'     => self::TYPE_BOOL,
				'validate' => 'isBool',
				'required' => true,
			),
		),
	);

    public function __construct( $id = null, $id_lang = null, $id_shop = null ) {
       
        parent::__construct( $id, $id_lang, $id_shop );
    }
}