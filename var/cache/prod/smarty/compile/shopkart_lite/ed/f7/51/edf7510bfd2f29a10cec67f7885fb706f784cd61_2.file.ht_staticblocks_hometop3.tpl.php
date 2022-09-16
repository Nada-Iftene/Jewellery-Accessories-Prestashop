<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:10
  from 'C:\xampp-v7\htdocs\prestashop\modules\ht_staticblocks\views\templates\hook\ht_staticblocks_hometop3.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6324834a6c9045_38606547',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'edf7510bfd2f29a10cec67f7885fb706f784cd61' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\modules\\ht_staticblocks\\views\\templates\\hook\\ht_staticblocks_hometop3.tpl',
      1 => 1663266851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6324834a6c9045_38606547 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!-- Static Block module -->
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['block_list']->value, 'block');
$_smarty_tpl->tpl_vars['block']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->do_else = false;
?>
	<?php if ((isset($_smarty_tpl->tpl_vars['block']->value['content']))) {?>
		<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['block']->value['content'],'quotes','UTF-8' ));?>

	<?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
<!-- /Static block module --><?php }
}
