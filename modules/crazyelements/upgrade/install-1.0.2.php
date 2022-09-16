<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

function upgrade_module_1_0_2($object)
{
	$id_tab   = (int) Tab::getIdFromClassName( 'AdminCrazyPrdlayouts' );
	$id_parent = (int) Tab::getIdFromClassName( 'AdminCrazyEditor' );


	if ( ! $id_tab ) {
		$tab             = new Tab();
		$tab->active     = 1;
		$tab->class_name = 'AdminCrazyPrdlayouts';
		$tab->name       = array();
		foreach ( Language::getLanguages( true ) as $lang ) {
			$tab->name[ $lang['id_lang'] ] = 'Product Layout Builder (Pro)';
		}
		$tab->id_parent = $id_parent;
		$tab->module    = $object->name;
		$tab->add();
	}

	return true;
}