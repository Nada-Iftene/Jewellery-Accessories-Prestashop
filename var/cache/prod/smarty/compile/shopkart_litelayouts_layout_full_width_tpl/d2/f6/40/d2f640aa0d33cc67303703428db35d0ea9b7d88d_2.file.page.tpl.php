<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:06
  from 'C:\xampp-v7\htdocs\prestashop\themes\shopkart_lite\templates\page.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_632483462d8ce5_34413957',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd2f640aa0d33cc67303703428db35d0ea9b7d88d' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\themes\\shopkart_lite\\templates\\page.tpl',
      1 => 1663336120,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_632483462d8ce5_34413957 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>
 


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_760918148632483462c8531_39308085', 'content');
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, $_smarty_tpl->tpl_vars['layout']->value);
}
/* {block 'page_title'} */
class Block_1479196165632483462ca0a5_47274993 extends Smarty_Internal_Block
{
public $callsChild = 'true';
public $hide = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        <header class="page-header">
          <h1><?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
</h1>
        </header>
      <?php
}
}
/* {/block 'page_title'} */
/* {block 'page_header_container'} */
class Block_1700417221632483462c9225_42668321 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1479196165632483462ca0a5_47274993', 'page_title', $this->tplIndex);
?>

    <?php
}
}
/* {/block 'page_header_container'} */
/* {block 'page_content_top'} */
class Block_1501621411632483462d3bd6_27259486 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'page_content'} */
class Block_1562551968632483462d4b77_23091493 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Page content -->
  
        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_1249159715632483462d2fa0_63656192 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-content card card-block">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1501621411632483462d3bd6_27259486', 'page_content_top', $this->tplIndex);
?>

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1562551968632483462d4b77_23091493', 'page_content', $this->tplIndex);
?>

      </section>
    <?php
}
}
/* {/block 'page_content_container'} */
/* {block 'page_footer'} */
class Block_1898556911632483462d6dd8_35305101 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

          <!-- Footer content -->
        <?php
}
}
/* {/block 'page_footer'} */
/* {block 'page_footer_container'} */
class Block_289029449632483462d6346_46231129 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <footer class="page-footer">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1898556911632483462d6dd8_35305101', 'page_footer', $this->tplIndex);
?>

      </footer>
    <?php
}
}
/* {/block 'page_footer_container'} */
/* {block 'content'} */
class Block_760918148632483462c8531_39308085 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_760918148632483462c8531_39308085',
  ),
  'page_header_container' => 
  array (
    0 => 'Block_1700417221632483462c9225_42668321',
  ),
  'page_title' => 
  array (
    0 => 'Block_1479196165632483462ca0a5_47274993',
  ),
  'page_content_container' => 
  array (
    0 => 'Block_1249159715632483462d2fa0_63656192',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1501621411632483462d3bd6_27259486',
  ),
  'page_content' => 
  array (
    0 => 'Block_1562551968632483462d4b77_23091493',
  ),
  'page_footer_container' => 
  array (
    0 => 'Block_289029449632483462d6346_46231129',
  ),
  'page_footer' => 
  array (
    0 => 'Block_1898556911632483462d6dd8_35305101',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>


  <section id="main">

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1700417221632483462c9225_42668321', 'page_header_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1249159715632483462d2fa0_63656192', 'page_content_container', $this->tplIndex);
?>


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_289029449632483462d6346_46231129', 'page_footer_container', $this->tplIndex);
?>


  </section>

<?php
}
}
/* {/block 'content'} */
}
