<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:10:40
  from 'C:\xampp-v7\htdocs\prestashop\modules\ps_themecusto\views\templates\admin\controllers\configuration\dropdownList.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_632483e0d21664_13057644',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ad6a828316023db97993a33f440f2b77fcf23d76' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\modules\\ps_themecusto\\views\\templates\\admin\\controllers\\configuration\\dropdownList.tpl',
      1 => 1663228758,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./elem/wireframe_".((string)$_smarty_tpl->tpl_vars[\'idModal\']->value).".tpl' => 1,
    'file:./elem/module_actions.tpl' => 1,
  ),
),false)) {
function content_632483e0d21664_13057644 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="<?php echo $_smarty_tpl->tpl_vars['idModal']->value;?>
Modal" class="modalCusto clearfix <?php echo $_smarty_tpl->tpl_vars['defaultModalClass']->value;?>
">
    <div class="col-xl-4 col-lg-5 col-md-5 col-sm-6 col-xs-12">
        <?php $_smarty_tpl->_subTemplateRender("file:./elem/wireframe_".((string)$_smarty_tpl->tpl_vars['idModal']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
    </div>
    <div class="col-xl-8 col-lg-7 col-md-7 col-sm-6 col-xs-12 sticky">
        <div class="row">
            <div class="col-xl-1 col-lg-1 col-md-0 col-sm-0"></div>
            <div class="col-xl-11 col-lg-12 col-md-12 col-sm-12 col-xs-12 module-list">
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elementsList']->value, 'categories', false, 'categoryname', 'cat', array (
));
$_smarty_tpl->tpl_vars['categories']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['categoryname']->value => $_smarty_tpl->tpl_vars['categories']->value) {
$_smarty_tpl->tpl_vars['categories']->do_else = false;
?>
                    <div class="row configuration-rectangle">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 js-module-name js-title-<?php echo $_smarty_tpl->tpl_vars['categoryname']->value;?>
" data-module_name="<?php echo $_smarty_tpl->tpl_vars['categoryname']->value;?>
">
                            <span class="col-lg-11 col-sm-11 col-xs-11 col-md-11">
                                <?php echo $_smarty_tpl->tpl_vars['listCategories']->value[$_smarty_tpl->tpl_vars['categoryname']->value];?>

                            </span>
                            <span class="col-lg-1 col-sm-1 col-xs-1 col-md-1 configuration-rectangle-caret">
                                <i class="material-icons down">keyboard_arrow_down</i>
                                <i class="material-icons up">keyboard_arrow_up</i>
                            </span>
                        </div>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categories']->value, 'elements', false, 'type');
$_smarty_tpl->tpl_vars['elements']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['type']->value => $_smarty_tpl->tpl_vars['elements']->value) {
$_smarty_tpl->tpl_vars['elements']->do_else = false;
?>
                            <?php if ($_smarty_tpl->tpl_vars['type']->value == 'pages') {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'page');
$_smarty_tpl->tpl_vars['page']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
$_smarty_tpl->tpl_vars['page']->do_else = false;
?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 module-informations">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <?php if ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminCategories') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
category_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminCmsContent') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
cms_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminAttributesGroups') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
manage_attributes_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminManufacturers') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
brands_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminProducts') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
manage_catalog_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_product_catalog') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
manage_catalog_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_stock_overview') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
display_stock_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminStockManagement') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
display_stock_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminStores') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
shop_ino_link_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_product_preferences') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
display_product_feature.png" class="img-fluid module-logo">
                                                <?php } else { ?>
                                                    <i class="icon-cogs"></i>
                                                <?php }?>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <b><?php echo $_smarty_tpl->tpl_vars['page']->value['displayName'];?>
</b>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 description">
                                                    <?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>

                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 general-action">
                                                    <a class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button" href="<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
">
                                                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configure','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php } elseif ($_smarty_tpl->tpl_vars['type']->value == 'sfRoutePages') {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'page');
$_smarty_tpl->tpl_vars['page']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
$_smarty_tpl->tpl_vars['page']->do_else = false;
?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 module-informations">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <?php if ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminCategories') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
category_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminCmsContent') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
cms_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminAttributesGroups') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
manage_attributes_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminManufacturers') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
brands_page_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_product_catalog') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
manage_catalog_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_stock_overview') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
display_stock_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'AdminStores') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
shop_ino_link_feature.png" class="img-fluid module-logo">
                                                <?php } elseif ($_smarty_tpl->tpl_vars['page']->value['name'] == 'admin_product_preferences') {?>
                                                    <img src="<?php echo $_smarty_tpl->tpl_vars['moduleImgUri']->value;?>
display_stock_feature.png" class="img-fluid module-logo">
                                                <?php } else { ?>
                                                    <i class="icon-cogs"></i>
                                                <?php }?>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <b><?php echo $_smarty_tpl->tpl_vars['page']->value['displayName'];?>
</b>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 description">
                                                    <?php echo $_smarty_tpl->tpl_vars['page']->value['description'];?>

                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 general-action">
                                                    <a class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button" href="<?php echo $_smarty_tpl->tpl_vars['page']->value['url'];?>
">
                                                        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Configure','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php } else { ?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['elements']->value, 'module', false, NULL, 'mods', array (
));
$_smarty_tpl->tpl_vars['module']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['module']->value) {
$_smarty_tpl->tpl_vars['module']->do_else = false;
?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 module-informations">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                                <img class="module-logo" src="<?php echo ($_smarty_tpl->tpl_vars['ps_uri']->value).($_smarty_tpl->tpl_vars['module']->value['logo']);?>
"/>
                                            </div>
                                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <b><?php echo $_smarty_tpl->tpl_vars['module']->value['displayName'];?>
</b>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 description">
                                                    <?php echo $_smarty_tpl->tpl_vars['module']->value['description'];?>

                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1">
                                                    <?php $_smarty_tpl->_subTemplateRender("file:./elem/module_actions.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php }?>
                        <?php
}
if ($_smarty_tpl->tpl_vars['elements']->do_else) {
?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 module-informations">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <i class="material-icons hidden-xs">extension</i>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <b><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'There is no module for this section','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>
</b>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7 description">
                                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'You can install a module for this section from our Modules Selection','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 general-action">
                                            <a class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-radius-right btn btn-primary-reverse btn-outline-primary light-button" href="<?php echo $_smarty_tpl->tpl_vars['selectionModulePage']->value;?>
" >
                                                <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See modules selection','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </div>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                <div class="row">
                    <div class="col-lg-4 col-lg-offset-8">
                        <a class="btn btn-primary btn-lg btn-block" href="<?php echo $_smarty_tpl->tpl_vars['installedModulePage']->value;?>
#theme_modules">
                            <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'See all theme\'s modules','mod'=>'ps_themecusto'),$_smarty_tpl ) );?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }
}
