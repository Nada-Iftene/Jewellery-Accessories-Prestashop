<?php

class PseImageType extends ObjectModel
{
	public $id;
	
	/** @var string Name */
	public $name;

	/** @var integer Width */
	public $width;

	/** @var integer Height */
	public $height;

	/** @var boolean Apply to active */
	public $active;

	

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'pse_image_type',
		'primary' => 'id_pse_image_type',
		'fields' => array(
			'name' => 			array('type' => self::TYPE_STRING, 'validate' => 'isImageTypeName', 'required' => true, 'size' => 64),
			'width' => 			array('type' => self::TYPE_INT, 'validate' => 'isImageSize', 'required' => true),
			'height' => 		array('type' => self::TYPE_INT, 'validate' => 'isImageSize', 'required' => true),
			'active' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

		),
	);

	/**
	 * @var array Image types cache
	 */
	protected static $images_types_cache = array();
	
	protected static $images_types_name_cache = array();

	protected $webserviceParameters = array();

	/**
	* Returns image type definitions
	*
	* @param string|null Image type
	* @return array Image type definitions
	*/
	public static function getImageTypeByName($type)
	{
		if(!empty($type)){
		
                    $where = 'WHERE 1';
                    if (!empty($type))
                            $where .= ' AND `name`="'.bqSQL($type).'"';

                    $query = 'SELECT * FROM `'._DB_PREFIX_.'pse_image_type` '.$where.' ORDER BY `name` ASC';
                    return Db::getInstance()->executeS($query);
                }
		return false;
	}
	public static function getImageTypeById($type)
	{
		if(!empty($type)){
		
                    $where = 'WHERE 1';
                    if (!empty($type))
                            $where .= ' AND `id_pse_image_type`='.bqSQL($type);

                    $query = 'SELECT * FROM `'._DB_PREFIX_.'pse_image_type` '.$where.' ORDER BY `name` ASC';
                    return Db::getInstance()->executeS($query);
                }
		return false;
	}
	public static function getImagesTypes($type = null)
	{
		if (!isset(self::$images_types_cache[$type]))
		{
			$where = 'WHERE 1';
			if (!empty($type))
				$where .= ' AND `'.bqSQL($type).'` = 1 ';

			$query = 'SELECT * FROM `'._DB_PREFIX_.'pse_image_type` '.$where.' ORDER BY `name` ASC';
			self::$images_types_cache[$type] = Db::getInstance()->executeS($query);
		}
		return self::$images_types_cache[$type];
	}

	/**
	* Check if type already is already registered in database
	*
	* @param string $typeName Name
	* @return integer Number of results found
	*/
	public static function typeAlreadyExists($typeName)
	{
		if (!Validate::isImageTypeName($typeName))
			die(Tools::displayError());

		Db::getInstance()->executeS('
			SELECT `id_pse_image_type`
			FROM `'._DB_PREFIX_.'pse_image_type`
			WHERE `name` = \''.pSQL($typeName).'\'');

		return Db::getInstance()->NumRows();
	}

	/**
	 * Finds image type definition by name and type
	 * @param string $name
	 * @param string $type
	 */
	public static function getByNameNType($name, $type = null, $order = null)
	{
		if (!isset(self::$images_types_name_cache[$name.'_'.$type.'_'.$order]))
		{
			self::$images_types_name_cache[$name.'_'.$type.'_'.$order] = Db::getInstance()->getRow('
				SELECT `id_pse_image_type`, `name`, `width`, `height`, `active`
				FROM `'._DB_PREFIX_.'pse_image_type` 
				WHERE 
				`name` LIKE \''.pSQL($name).'\''
				.(!is_null($type) ? ' AND `'.pSQL($type).'` = 1' : '')
				.(!is_null($order) ? ' ORDER BY `'.bqSQL($order).'` ASC' : '')
			);
		}
        $id_shop = \CrazyElements\PrestaHelper::$id_shop_global;
        $id_lang = \CrazyElements\PrestaHelper::$id_lang_global;
        return self::$images_types_name_cache[$name.'_'.$type.'_'.$id_shop.'_'.$id_lang.$order];
	}
	
	public static function getFormatedName($name)
	{
		$theme_name = Context::getContext()->shop->theme_name;
		$name_without_theme_name = str_replace(array('_'.$theme_name, $theme_name.'_'), '', $name);

		//check if the theme name is already in $name if yes only return $name
		if (strstr($name, $theme_name) && self::getByNameNType($name))
			return $name;
		else if (self::getByNameNType($name_without_theme_name.'_'.$theme_name))
			return $name_without_theme_name.'_'.$theme_name;
		else if (self::getByNameNType($theme_name.'_'.$name_without_theme_name))
			return $theme_name.'_'.$name_without_theme_name;
		else
			return $name_without_theme_name.'_default';
	}
	
}