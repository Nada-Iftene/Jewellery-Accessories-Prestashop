<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

?>
<script type="text/template" id="tmpl-elementor-repeater-row">
	<div class="elementor-repeater-row-tools">
		<# if ( itemActions.drag_n_drop ) {  #>
			<div class="elementor-repeater-row-handle-sortable">
				<i class="ceicon-ellipsis-v" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Drag & Drop', 'elementor' ); ?></span>
			</div>
		<# } #>
		<div class="elementor-repeater-row-item-title"></div>
		<# if ( itemActions.duplicate ) {  #>
			<div class="elementor-repeater-row-tool elementor-repeater-tool-duplicate">
				<i class="ceicon-copy" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Duplicate', 'elementor' ); ?></span>
			</div>
		<# }
		if ( itemActions.remove ) {  #>
			<div class="elementor-repeater-row-tool elementor-repeater-tool-remove">
				<i class="ceicon-close" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Remove', 'elementor' ); ?></span>
			</div>
		<# } #>
	</div>
	<div class="elementor-repeater-row-controls"></div>
</script>
