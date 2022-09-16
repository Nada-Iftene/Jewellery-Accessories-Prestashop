<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:11:32
  from 'C:\xampp-v7\htdocs\prestashop\modules\crazyelements\views\templates\front\button.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_63248414d0c440_58917459',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a283f4d5fa3ae488e7053d25adf4c8eb20548d5' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\modules\\crazyelements\\views\\templates\\front\\button.tpl',
      1 => 1663329783,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63248414d0c440_58917459 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/html" id="edit_with_button">
	<a href="<?php echo $_smarty_tpl->tpl_vars['proper_href']->value;?>
" id="edit_with_button_link" class="button button-primary button-hero"><img src="<?php echo $_smarty_tpl->tpl_vars['icon_url']->value;?>
" alt="Crazy Elements Logo"> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Edit with Crazyelements','mod'=>'crazyelements'),$_smarty_tpl ) );?>
</a>
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/html" id="edit_catg_with_crazy">
		<a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_catg_desc&utm_medium=crazyfree_catg_desc&utm_campaign=crazyfree_catg_desc&utm_id=crazyfree_catg_desc&utm_term=crazyfree_catg_desc&utm_content=crazyfree_catg_desc"  id="edit_catg_with_crazy_link" class="button button-primary button-hero" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['icon_url']->value;?>
" alt="Crazy Elements Logo"> <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Get Crazyelements Pro and Edit Your Category Description More Beautifully','d'=>'Modules.Crazyelements.Addjs'),$_smarty_tpl ) );?>
</a>
<?php echo '</script'; ?>
><?php }
}
