<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:10:41
  from 'C:\xampp-v7\htdocs\prestashop\modules\ps_themecusto\views\templates\admin\controllers\configuration\elem\module_actions.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_632483e1a53242_40789879',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd415b05d99bca90a501e07fbd1675c0d7678fcb9' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\modules\\ps_themecusto\\views\\templates\\admin\\controllers\\configuration\\elem\\module_actions.tpl',
      1 => 1663228758,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_632483e1a53242_40789879 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="loader src_loader_<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
"></div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 btn-group form-action-button-container src_parent_<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
" data-id_module="<?php echo $_smarty_tpl->tpl_vars['module']->value['id_module'];?>
" >
    <?php if ($_smarty_tpl->tpl_vars['module']->value['url_active'] != 'install') {?>
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 no-padding general-action" data-module_name="<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
" data-action="<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-module_displayname="<?php echo $_smarty_tpl->tpl_vars['module']->value['displayName'];?>
">
            <?php if ($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'configure') {?>
            <a class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button module_action_menu_<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['module']->value['actions_url']['configure'];?>
">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configure','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

            </a>
            <?php } elseif ($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'disable') {?>
            <button type="button" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button module_action_menu_<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-confirm_modal="module-modal-confirm-<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
-<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-toggle="modal" data-target="#moduleActionModal">
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Disable','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

            </button>
            <?php } else { ?>
            <button type="button" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button module_action_menu_<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-confirm_modal="module-modal-confirm-<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
-<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" >
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enable','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

            </button>
            <?php }?>
        </div>

        <input type="hidden" class="btn">

        <button type="button" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 btn btn-outline-primary dropdown-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">keyboard_arrow_down</i>
        </button>
        <div class="dropdown-menu">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['moduleActions']->value, 'action', false, 'key');
$_smarty_tpl->tpl_vars['action']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['action']->value) {
$_smarty_tpl->tpl_vars['action']->do_else = false;
?>
                <?php if (($_smarty_tpl->tpl_vars['action']->value != $_smarty_tpl->tpl_vars['module']->value['url_active']) && !($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'configure' && $_smarty_tpl->tpl_vars['action']->value == 'enable') && !($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'enable' && $_smarty_tpl->tpl_vars['action']->value == 'disable') && !($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'disable' && $_smarty_tpl->tpl_vars['action']->value == 'enable') && !($_smarty_tpl->tpl_vars['module']->value['url_active'] == 'disable' && $_smarty_tpl->tpl_vars['action']->value == 'configure') && !(!$_smarty_tpl->tpl_vars['module']->value['can_configure'] && $_smarty_tpl->tpl_vars['action']->value == 'configure') && !($_smarty_tpl->tpl_vars['action']->value == 'install') && !($_smarty_tpl->tpl_vars['action']->value == 'uninstall' && $_smarty_tpl->tpl_vars['isPsReady']->value) && !($_smarty_tpl->tpl_vars['action']->value == 'install' && $_smarty_tpl->tpl_vars['isPsReady']->value)) {?>
                <li>
                    <div data-action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" data-module_name="<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
" data-module_displayname="<?php echo $_smarty_tpl->tpl_vars['module']->value['displayName'];?>
">
                        <?php if ($_smarty_tpl->tpl_vars['action']->value == 'uninstall' || $_smarty_tpl->tpl_vars['action']->value == 'disable' || $_smarty_tpl->tpl_vars['action']->value == 'reset') {?>
                        <button type="button" class="dropdown-item module_action_menu_<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" data-confirm_modal="module-modal-confirm-<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
-<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" data-toggle="modal" data-target="#moduleActionModal">
                            <?php if ($_smarty_tpl->tpl_vars['action']->value == 'uninstall') {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Uninstall','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                            <?php } elseif ($_smarty_tpl->tpl_vars['action']->value == 'disable') {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Disable','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                            <?php } elseif ($_smarty_tpl->tpl_vars['action']->value == 'reset') {?>
                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Reset','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                            <?php }?>
                        </button>
                        <?php } elseif ($_smarty_tpl->tpl_vars['action']->value == 'configure') {?>
                        <a class="dropdown-item module_action_menu_configure" href="<?php echo $_smarty_tpl->tpl_vars['module']->value['actions_url']['configure'];?>
">
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configure','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                        </a>
                        <?php } else { ?>
                            <?php if ($_smarty_tpl->tpl_vars['action']->value == 'enable') {?>
                                <button type="button" class="dropdown-item module_action_menu_<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
                                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enable','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                </button>
                            <?php } elseif (($_smarty_tpl->tpl_vars['module']->value['enable_mobile'] == 7 || $_smarty_tpl->tpl_vars['module']->value['enable_mobile'] == 1 || $_smarty_tpl->tpl_vars['module']->value['enable_mobile'] == 0) && $_smarty_tpl->tpl_vars['action']->value == 'disable_mobile') {?>
                                <button type="button" class="dropdown-item module_action_menu_<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
                                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Disable mobile','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                </button>
                            <?php } elseif (($_smarty_tpl->tpl_vars['module']->value['enable_mobile'] == 3 || $_smarty_tpl->tpl_vars['module']->value['enable_mobile'] == 0) && $_smarty_tpl->tpl_vars['action']->value == 'enable_mobile') {?>
                                <button type="button" class="dropdown-item module_action_menu_<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
">
                                    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Enable mobile','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                </button>
                            <?php }?>
                        <?php }?>
                    </div>
                </li>
                <?php }?>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
    <?php } else { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding general-action" data-module_name="<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
" data-action="<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-module_displayname="<?php echo $_smarty_tpl->tpl_vars['module']->value['displayName'];?>
">
            <button type="button" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button module_action_menu_<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" data-confirm_modal="module-modal-confirm-<?php echo $_smarty_tpl->tpl_vars['module']->value['name'];?>
-<?php echo $_smarty_tpl->tpl_vars['module']->value['url_active'];?>
" >
                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Install','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

            </button>
        </div>
    <?php }?>
</div>
<?php }
}
