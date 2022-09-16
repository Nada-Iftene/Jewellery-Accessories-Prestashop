<?php
/* Smarty version 3.1.43, created on 2022-09-16 16:08:12
  from 'C:\xampp-v7\htdocs\prestashop\modules\ht_googleanalytics\views\templates\hook\ht_googleanalytics.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.43',
  'unifunc' => 'content_6324834c0e40a8_78863102',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e4f1dd5cba8f6a6f820063977875a2ee207bf075' => 
    array (
      0 => 'C:\\xampp-v7\\htdocs\\prestashop\\modules\\ht_googleanalytics\\views\\templates\\hook\\ht_googleanalytics.tpl',
      1 => 1663266851,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6324834c0e40a8_78863102 (Smarty_Internal_Template $_smarty_tpl) {
if ((isset($_smarty_tpl->tpl_vars['ga_enable']->value)) && $_smarty_tpl->tpl_vars['ga_enable']->value == 'ga_yes') {?>
    <?php echo '<script'; ?>
>
        
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        
            ga('create', '<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['ga_google_tracking_id']->value, ENT_QUOTES, 'UTF-8');?>
', 'auto');
            ga('send', 'pageview');
    <?php echo '</script'; ?>
>
<?php }
}
}
