<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:08
  from 'module:psmainmenupsmainmenu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_632483489895a9_76071900',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '41df1985130dffd7d3fe4cb369091546a0b40be7' => 
    array (
      0 => 'module:psmainmenupsmainmenu.tpl',
      1 => 1663266853,
      2 => 'module',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_632483489895a9_76071900 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->smarty->ext->_tplFunction->registerTplFunctions($_smarty_tpl, array (
  'menu' => 
  array (
    'compiled_filepath' => 'C:\\xampp-v7\\htdocs\\prestashop\\var\\cache\\prod\\smarty\\compile\\shopkart_lite\\41\\df\\19\\41df1985130dffd7d3fe4cb369091546a0b40be7_2.module.psmainmenupsmainmenu.tpl.php',
    'uid' => '41df1985130dffd7d3fe4cb369091546a0b40be7',
    'call_name' => 'smarty_template_function_menu_16118787156324834893dbc4_60181477',
  ),
));
?> 
<?php $_smarty_tpl->_assignInScope('_counter', 0);?>

                    <div class="top-navigation-menu ht-main-menu">
                        <div class="container">
                            <div class="top-navigation-menu-inner">
                                <div class="main-menu menu js-top-menu position-static hidden-sm-down" id="_desktop_top_menu">
                                    <?php $_smarty_tpl->smarty->ext->_tplFunction->callTemplateFunction($_smarty_tpl, 'menu', array('nodes'=>$_smarty_tpl->tpl_vars['menu']->value['children']), true);?>

                                </div>
                            </div>
                        </div>
                    </div><?php }
/* smarty_template_function_menu_16118787156324834893dbc4_60181477 */
if (!function_exists('smarty_template_function_menu_16118787156324834893dbc4_60181477')) {
function smarty_template_function_menu_16118787156324834893dbc4_60181477(Smarty_Internal_Template $_smarty_tpl,$params) {
$params = array_merge(array('nodes'=>array(),'depth'=>0,'parent'=>null), $params);
foreach ($params as $key => $value) {
$_smarty_tpl->tpl_vars[$key] = new Smarty_Variable($value, $_smarty_tpl->isRenderingCache);
}
?>

    <?php if (count($_smarty_tpl->tpl_vars['nodes']->value)) {?>
        <ul class="top-menu <?php if ((isset($_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value)) && $_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value == '1') {?> ht-default <?php } elseif ((isset($_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value)) && $_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value == '2') {?> ht-fullwidth <?php } elseif ((isset($_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value)) && $_smarty_tpl->tpl_vars['THEMECPANEL_SUBMENU_STYLE']->value == '3') {?> ht-classic <?php }?>" <?php if ($_smarty_tpl->tpl_vars['depth']->value == 0) {?>id="top-menu"<?php }?> data-depth="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['depth']->value, ENT_QUOTES, 'UTF-8');?>
">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['nodes']->value, 'node');
$_smarty_tpl->tpl_vars['node']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['node']->value) {
$_smarty_tpl->tpl_vars['node']->do_else = false;
?>
                <li class="ht-level-<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['depth']->value, ENT_QUOTES, 'UTF-8');?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['node']->value['type'], ENT_QUOTES, 'UTF-8');
if ($_smarty_tpl->tpl_vars['node']->value['current']) {?> current <?php }?> <?php if (count($_smarty_tpl->tpl_vars['node']->value['children'])) {?> has-child <?php }?>" id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['node']->value['page_identifier'], ENT_QUOTES, 'UTF-8');?>
">
                    <?php $_smarty_tpl->_assignInScope('_counter', $_smarty_tpl->tpl_vars['_counter']->value+1);?>
                    <a
                        class="<?php if ($_smarty_tpl->tpl_vars['depth']->value >= 0) {?>dropdown-item<?php }
if ($_smarty_tpl->tpl_vars['depth']->value === 1) {?> dropdown-submenu<?php }?>"
                        href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['node']->value['url'], ENT_QUOTES, 'UTF-8');?>
" data-depth="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['depth']->value, ENT_QUOTES, 'UTF-8');?>
"
                        <?php if ($_smarty_tpl->tpl_vars['node']->value['open_in_new_window']) {?> target="_blank" <?php }?>
                        >
                        <?php if (count($_smarty_tpl->tpl_vars['node']->value['children'])) {?>
                                                        <?php $_smarty_tpl->_assignInScope('_expand_id', mt_rand(10,100000));?>
                            <span class="float-xs-right hidden-md-up">
                                <span data-target="#top_sub_menu_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_expand_id']->value, ENT_QUOTES, 'UTF-8');?>
" data-toggle="collapse" class="navbar-toggler collapse-icons">
                                    <i class="material-icons add">&#xE313;</i>
                                    <i class="material-icons remove">&#xE316;</i>
                                </span>
                            </span>
                        <?php }?>
                        <span><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['node']->value['label'], ENT_QUOTES, 'UTF-8');?>
<span>
                                </a>
                                <?php if (count($_smarty_tpl->tpl_vars['node']->value['children'])) {?>
                                    <div <?php if ($_smarty_tpl->tpl_vars['depth']->value === 0) {?> class="popover sub-menu js-sub-menu collapse"<?php } else { ?> class="collapse"<?php }?> id="top_sub_menu_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_expand_id']->value, ENT_QUOTES, 'UTF-8');?>
">
                                        <?php $_smarty_tpl->smarty->ext->_tplFunction->callTemplateFunction($_smarty_tpl, 'menu', array('nodes'=>$_smarty_tpl->tpl_vars['node']->value['children'],'depth'=>$_smarty_tpl->tpl_vars['node']->value['depth'],'parent'=>$_smarty_tpl->tpl_vars['node']->value), true);?>


                                        <?php if (count($_smarty_tpl->tpl_vars['node']->value['image_urls']) && $_smarty_tpl->tpl_vars['depth']->value === 0) {?>
                                            <div class="menu-images-container menu-banner">
                                                <div class="menu-banner-img">
                                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['node']->value['image_urls'], 'image_url');
$_smarty_tpl->tpl_vars['image_url']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['image_url']->value) {
$_smarty_tpl->tpl_vars['image_url']->do_else = false;
?>
                                                        <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['image_url']->value, ENT_QUOTES, 'UTF-8');?>
">
                                                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                                </div>
                                            </div>
                                        <?php }?>

                                    </div>
                                <?php }?>
                                </li>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            </ul>
                        <?php }?>
                    <?php
}}
/*/ smarty_template_function_menu_16118787156324834893dbc4_60181477 */
}
