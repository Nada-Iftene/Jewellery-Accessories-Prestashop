<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:11
  from 'module:htbrandlistviewstemplates' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6324834b264458_73635213',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '26648680361607d0e62b096e511235b03bfdacee' => 
    array (
      0 => 'module:htbrandlistviewstemplates',
      1 => 1663266851,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
    'module:ht_brandlist/views/templates/_partials/".((string)$_smarty_tpl->tpl_vars[\'brand_display_type\']->value).".tpl' => 1,
  ),
),false)) {
function content_6324834b264458_73635213 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="brand-section">
    <div class="container">
        <div class="section-title">
            <h2 class="title">
                <?php if ($_smarty_tpl->tpl_vars['display_link_brand']->value) {?><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page_link']->value, ENT_QUOTES, 'UTF-8');?>
" title="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Brands','d'=>'Modules.Brandlist.Shop'),$_smarty_tpl ) );?>
"><?php }?>
                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Brands','d'=>'Modules.Brandlist.Shop'),$_smarty_tpl ) );?>

                    <?php if ($_smarty_tpl->tpl_vars['display_link_brand']->value) {?></a><?php }?>
            </h2>
        </div>
        <div class="brand-content">
            <div class="brand-row">
                <?php if ($_smarty_tpl->tpl_vars['brands']->value) {?>
                    <?php $_smarty_tpl->_subTemplateRender("module:ht_brandlist/views/templates/_partials/".((string)$_smarty_tpl->tpl_vars['brand_display_type']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('brands'=>$_smarty_tpl->tpl_vars['brands']->value), 0, true);
?>
                <?php } else { ?>
                    <p><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'No brand','d'=>'Modules.Brandlist.Shop'),$_smarty_tpl ) );?>
</p>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php }
}
