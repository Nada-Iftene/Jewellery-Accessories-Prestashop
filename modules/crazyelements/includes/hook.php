<?php
$GetAlldisplayHooks = array(
	array(
		'id'   => 'displayTop',
		'name' => 'displayTop',
	),
	array(
		'id'   => 'displayTopColumn',
		'name' => 'displayTopColumn',
	),
	array(
		'id'   => 'displayHome',
		'name' => 'displayHome',
	),
	array(
		'id'   => 'displayNavFullWidth',
		'name' => 'displayNavFullWidth',
	),
	array(
		'id'   => 'displayFooterBefore',
		'name' => 'displayFooterBefore',
	),
	array(
		'id'   => 'displayFooter',
		'name' => 'displayFooter',
	)
);
$temparr = array();
$extended_mods = \Hook::exec( 'actionCrazyAddHooks', $GetAlldisplayHooks , null, true );

foreach($extended_mods as $extended_hooks){
	foreach($extended_hooks as $extended_hook){
		$temparr[] = array(
			'id' => $extended_hook,
			'name' => $extended_hook
		);
	}
	 	
}
$GetAlldisplayHooks = array_merge($GetAlldisplayHooks,$temparr);