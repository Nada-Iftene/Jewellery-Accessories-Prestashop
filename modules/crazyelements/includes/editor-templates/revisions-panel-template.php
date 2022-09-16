<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-panel-revisions">
    <div class="elementor-panel-box">
	<div class="elementor-panel-scheme-buttons">
			<div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
				<button class="elementor-button" disabled>
					<i class="fa fa-times" aria-hidden="true"></i>
					<?php echo PrestaHelper::__( 'Discard', 'elementor' ); ?>
				</button>
			</div>
			<div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
				<button class="elementor-button elementor-button-success" disabled>
					<?php echo PrestaHelper::__( 'Apply', 'elementor' ); ?>
				</button>
			</div>
		</div>
	</div>

	<div class="elementor-panel-box">
		<div class="elementor-panel-heading">
			<div class="elementor-panel-heading-title"><?php echo PrestaHelper::__( 'Revisions', 'elementor' ); ?></div>
		</div>
		<div id="elementor-revisions-list" class="elementor-panel-box-content"></div>
		<div class="elementor-nerd-box-title nerd-box-pro"><?php  PrestaHelper::esc_attr_e( 'Get ', 'elementor' ); ?><a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree&utm_medium=crazyfree_module&utm_campaign=crazyfree&utm_term=crazyfree&utm_content=crazyfree" target="_blank">PRO</a><?php  PrestaHelper::esc_attr_e( ' To Use This Feature ', 'elementor' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-no-revisions">
    <i class="elementor-nerd-box-icon ceicon-nerd" aria-hidden="true"></i>
	<div class="elementor-nerd-box-title"><?php echo PrestaHelper::__( 'No Revisions Saved Yet', 'elementor' ); ?></div>
	<div class="elementor-nerd-box-message">{{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_1' : 'revisions_disabled_1' ) }}}</div>
	<div class="elementor-nerd-box-message">{{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_2' : '' ) }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-loading">
    <i class="ceicon-loading ceicon-animation-spin" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-revision-item">
    <div class="elementor-revision-item__wrapper {{ type }}">
		<div class="elementor-revision-item__gravatar">{{{ gravatar }}}</div>
		<div class="elementor-revision-item__details">
			<div class="elementor-revision-date">{{{ date }}}</div>
			<div class="elementor-revision-meta"><span>{{{ elementor.translate( type ) }}}</span> <?php echo PrestaHelper::__( 'By', 'elementor' ); ?> {{{ author }}}</div>
		</div>
		<div class="elementor-revision-item__tools">
			<# if ( 'current' === type ) { #>
				<i class="elementor-revision-item__tools-current fa fa-star" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Current', 'elementor' ); ?></span>
			<# } else { #>
				<i class="elementor-revision-item__tools-delete fa fa-times" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Delete', 'elementor' ); ?></span>
			<# } #>

			<i class="elementor-revision-item__tools-spinner fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
		</div>
	</div>
</script>