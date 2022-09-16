<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-elementor-navigator">
    <div id="elementor-navigator__header">
		<i id="elementor-navigator__toggle-all" class="ceicon-expand" data-elementor-action="expand"></i>
		<div id="elementor-navigator__header__title"><?php echo PrestaHelper::__( 'Navigator', 'elementor' ); ?> <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">PRO</a></div>
		<i id="elementor-navigator__close" class="ceicon-close"></i>
	</div>
	<div id="elementor-navigator__elements"></div>
	<div id="elementor-navigator__footer">
		<i class="ceicon-ellipsis-h"></i>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__elements">
    <# if ( obj.elType ) { #>
		<div class="elementor-navigator__item">
			<div class="elementor-navigator__element__list-toggle">
				<i class="ceicon-sort-down"></i>
			</div>
			<#
			if ( icon ) { #>
				<div class="elementor-navigator__element__element-type">
					<i class="{{{ icon }}}"></i>
				</div>
			<# } #>
			<div class="elementor-navigator__element__title">
				<span class="elementor-navigator__element__title__text">{{{ title }}}</span>
			</div>
			<div class="elementor-navigator__element__toggle">
				<i class="ceicon-eye"></i>
			</div>
			<div class="elementor-navigator__element__indicators"></div>
		</div>
	<# } #>
	<div class="elementor-navigator__elements"></div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__elements--empty">
    <div class="elementor-empty-view__title"><?php echo PrestaHelper::__( 'Empty', 'elementor' ); ?></div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__root--empty">
    <i class="elementor-nerd-box-icon ceicon-nerd" aria-hidden="true"></i>
	<div class="elementor-nerd-box-title"><?php echo PrestaHelper::__( 'Easy Navigation is Here!', 'elementor' ); ?></div>
	<div class="elementor-nerd-box-message"><?php echo PrestaHelper::__( 'Once you fill your page with content, this window will give you an overview display of all the page elements. This way, you can easily move around any section, column, or widget.', 'elementor' ); ?></div>
</script>