<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:06
  from 'C:\xampp-v7\htdocs\prestashop\themes\shopkart_lite\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_632483460da217_98036704',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14e56ab9c2438b45890b893db27b4b445a3c256e' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\themes\\shopkart_lite\\templates\\index.tpl',
      1 => 1663335743,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_632483460da217_98036704 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>
 


    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_2090702487632483460c50d1_21629084', 'page_content_container');
?>



    <div>
  
<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, 'page.tpl');
}
/* {block 'page_content_top'} */
class Block_1396701702632483460c6783_24104621 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'page_content_top'} */
/* {block 'hook_home'} */
class Block_1986792217632483460c98c3_71280312 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

            <?php echo $_smarty_tpl->tpl_vars['HOOK_HOME']->value;?>

          <?php
}
}
/* {/block 'hook_home'} */
/* {block 'page_content'} */
class Block_186635613632483460c84a6_01971869 extends Smarty_Internal_Block
{
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

        
          <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1986792217632483460c98c3_71280312', 'hook_home', $this->tplIndex);
?>

        <?php
}
}
/* {/block 'page_content'} */
/* {block 'page_content_container'} */
class Block_2090702487632483460c50d1_21629084 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'page_content_container' => 
  array (
    0 => 'Block_2090702487632483460c50d1_21629084',
  ),
  'page_content_top' => 
  array (
    0 => 'Block_1396701702632483460c6783_24104621',
  ),
  'page_content' => 
  array (
    0 => 'Block_186635613632483460c84a6_01971869',
  ),
  'hook_home' => 
  array (
    0 => 'Block_1986792217632483460c98c3_71280312',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

      <section id="content" class="page-home">
        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1396701702632483460c6783_24104621', 'page_content_top', $this->tplIndex);
?>


        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_186635613632483460c84a6_01971869', 'page_content', $this->tplIndex);
?>

      </section>
      
    <?php
}
}
/* {/block 'page_content_container'} */
}
