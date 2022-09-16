<?php
namespace CrazyElements;


use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-panel-history-page">
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
		<div id="elementor-panel-elements-navigation-history" class="elementor-panel-navigation-tab elementor-active" data-view="history"><?php  PrestaHelper::esc_attr_e( 'Actions', 'elementor' ); ?></div>
		<div id="elementor-panel-elements-navigation-revisions" class="elementor-panel-navigation-tab" data-view="revisions"><?php  PrestaHelper::esc_attr_e( 'Revisions', 'elementor' ); ?></div>
	</div>
	<div id="elementor-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-tab">
    <div class="elementor-panel-box">
		<div class="elementor-panel-box-content">
			<div id="elementor-history-list"></div>
			<div class="elementor-history-revisions-message"><?php  PrestaHelper::esc_attr_e( 'Switch to Revisions tab for older versions', 'elementor' ); ?></div>
			<div class="elementor-nerd-box-title nerd-box-pro"><?php  PrestaHelper::esc_attr_e( 'Get ', 'elementor' ); ?><a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">PRO</a><?php  PrestaHelper::esc_attr_e( ' To Use This Feature ', 'elementor' ); ?></div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-no-items">
    <i class="elementor-nerd-box-icon ceicon-nerd"></i>
	<div class="elementor-nerd-box-title"><?php  PrestaHelper::esc_attr_e( 'No History Yet', 'elementor' ); ?></div>
	<div class="elementor-nerd-box-message"><?php  PrestaHelper::esc_attr_e( 'Once you start working, you\'ll be able to redo / undo any action you make in the editor.', 'elementor' ); ?></div>
	<div class="elementor-nerd-box-message"><?php  PrestaHelper::esc_attr_e( 'Switch to Revisions tab for older versions', 'elementor' ); ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-item">
    <div class="elementor-history-item elementor-history-item-{{ status }}">
		<div class="elementor-history-item__details">
			<span class="elementor-history-item__title">{{{ title }}} </span>
			<span class="elementor-history-item__subtitle">{{{ subTitle }}} </span>
			<span class="elementor-history-item__action">{{{ action }}}</span>
		</div>
		<div class="elementor-history-item__icon">
			<span class="fa" aria-hidden="true"></span>
		</div>
	</div>
</script>