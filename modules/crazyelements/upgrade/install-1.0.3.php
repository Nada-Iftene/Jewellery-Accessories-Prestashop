<?php
if (!defined('_PS_VERSION_')) {
	exit;
}

function upgrade_module_1_0_3($object)
{
	$object->registerHook('actionCmsPageFormBuilderModifier');
	return true;
}