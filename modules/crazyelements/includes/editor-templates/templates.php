<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}
?>
<script type="text/template" id="tmpl-elementor-template-library-header-actions">
    <div id="elementor-template-library-header-import" class="elementor-templates-modal__header__item">
		<i class="ceicon-upload-circle-o" aria-hidden="true" title="<?php PrestaHelper::esc_attr_e( 'Import Template', 'elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Import Template', 'elementor' ); ?></span>
	</div>
	<div id="elementor-template-library-header-sync" class="elementor-templates-modal__header__item">
		<i class="ceicon-sync" aria-hidden="true" title="<?php PrestaHelper::esc_attr_e( 'Sync Library', 'elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Sync Library', 'elementor' ); ?></span>
	</div>
	<div id="elementor-template-library-header-save" class="elementor-templates-modal__header__item">
		<i class="ceicon-save-o" aria-hidden="true" title="<?php PrestaHelper::esc_attr_e( 'Save', 'elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Save', 'elementor' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-menu">
    <# screens.forEach( ( screen ) => { #>
		<div class="elementor-template-library-menu-item" data-template-source="{{{ screen.source }}}"{{{ screen.type ? ' data-template-type="' + screen.type + '"' : '' }}}>{{{ screen.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-preview">
    <div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ elementor.templates.getLayout().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-back">
    <i class="ceicon-" aria-hidden="true"></i>
	<span><?php echo PrestaHelper::__( 'Back to Library', 'elementor' ); ?></span>
</script>

<script type="text/template" id="tmpl-elementor-template-library-loading">
    <div class="crazya-elementor-loading">
			<div class="elementor-loader-wrapper">
				<div class="elementor-loader">
					<div class="elementor-loader-boxes crazya-element-one">
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
					</div>
					<div class="elementor-loader-boxes crazya-element-two">
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
						<div class="elementor-loader-box"></div>
					</div>
				</div>
				<div class="elementor-loading-title"><?php echo PrestaHelper::__( 'Loading', 'elementor' ); ?></div>
			</div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates">
    <#
		var activeSource = elementor.templates.getFilter('source');
	#>
	<div id="elementor-template-library-toolbar">
		<# if ( 'remote' === activeSource ) {
			var activeType = elementor.templates.getFilter('type');
			#>
			<div id="elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">
				<# if ( 'page' === activeType ) { #>
					<div id="elementor-template-library-order">
						<input type="radio" id="elementor-template-library-order-new" class="elementor-template-library-order-input" name="elementor-template-library-order" value="date">
						<label for="elementor-template-library-order-new" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'New', 'elementor' ); ?></label>
						<input type="radio" id="elementor-template-library-order-trend" class="elementor-template-library-order-input" name="elementor-template-library-order" value="trendIndex">
						<label for="elementor-template-library-order-trend" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Trend', 'elementor' ); ?></label>
						<input type="radio" id="elementor-template-library-order-popular" class="elementor-template-library-order-input" name="elementor-template-library-order" value="popularityIndex">
						<label for="elementor-template-library-order-popular" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Popular', 'elementor' ); ?></label>
					</div>
				<# } else {
					var config = elementor.templates.getConfig( activeType );
					if ( config.categories ) { #>
						<div id="elementor-template-library-filter">
						</div>
					<# }
				} #>
			</div>
		<# } else { #>
			<div id="elementor-template-library-filter-toolbar-local" class="elementor-template-library-filter-toolbar"></div>
		<# } #>
		<div id="elementor-template-library-filter-text-wrapper">
			
			
		</div>
	</div>
	<# if ( 'local' === activeSource ) { #>
		<div id="elementor-template-library-order-toolbar-local">
			<div class="elementor-template-library-local-column-1">
				<input type="radio" id="elementor-template-library-order-local-title" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="title" data-default-ordering-direction="asc">
				<label for="elementor-template-library-order-local-title" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Name', 'elementor' ); ?></label>
			</div>
			<div class="elementor-template-library-local-column-2">
				<input type="radio" id="elementor-template-library-order-local-type" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="type" data-default-ordering-direction="asc">
				<label for="elementor-template-library-order-local-type" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Type', 'elementor' ); ?></label>
			</div>
			<div class="elementor-template-library-local-column-3">
				<input type="radio" id="elementor-template-library-order-local-author" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="author" data-default-ordering-direction="asc">
				<label for="elementor-template-library-order-local-author" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Created By', 'elementor' ); ?></label>
			</div>
			<div class="elementor-template-library-local-column-4">
				<input type="radio" id="elementor-template-library-order-local-date" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="date">
				<label for="elementor-template-library-order-local-date" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Creation Date', 'elementor' ); ?></label>
			</div>
			<div class="elementor-template-library-local-column-5">
				<div class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Actions', 'elementor' ); ?></div>
			</div>
		</div>
	<# } #>
	<div id="elementor-template-library-templates-container"></div>
	<# if ( 'remote' === activeSource ) { #>
		<div id="elementor-template-library-footer-banner">
			<i class="ceicon-nerd" aria-hidden="true"></i>
			<div class="elementor-excerpt"><?php echo PrestaHelper::__( 'Stay tuned! More awesome templates coming real soon.', 'elementor' ); ?></div>
		</div>
	<# } #>
</script>
<script type="text/template" id="tmpl-elementor-template-library-template-remote">
    <div class="elementor-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img src="{{ thumbnail }}">
		<# } #>
		<div class="elementor-template-library-template-preview">
			<i class="ceicon-zoom-in" aria-hidden="true"></i>
		</div>
	</div>
	<div class="elementor-template-library-template-footer">
		{{{ elementor.templates.getLayout().getTemplateActionButton( obj ) }}}
		<div class="elementor-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-local">
    <div class="elementor-template-library-template-name elementor-template-library-local-column-1">{{{ title }}}</div>
	<div class="elementor-template-library-template-meta elementor-template-library-template-type elementor-template-library-local-column-2">{{{ elementor.translate( type ) }}}</div>
	<div class="elementor-template-library-template-meta elementor-template-library-template-author elementor-template-library-local-column-3">{{{ author }}}</div>
	<div class="elementor-template-library-template-meta elementor-template-library-template-date elementor-template-library-local-column-4">{{{ human_date }}}</div>
	<div class="elementor-template-library-template-controls elementor-template-library-local-column-5">
		<div class="elementor-template-library-template-preview">
			<i class="ceicon-eye" aria-hidden="true"></i>
			<span class="elementor-template-library-template-control-title"><?php echo PrestaHelper::__( 'Preview', 'elementor' ); ?></span>
		</div>
		<button class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button elementor-button-success <?php echo (PrestaHelper::get_option( 'elementor_user_access_token' ))?'':'need_to_login'?>">
			<i class="ceicon-file-download" aria-hidden="true"></i>
			<span class="elementor-button-title"><?php echo PrestaHelper::__( 'Insert', 'elementor' ); ?></span>
		</button>
		<div class="elementor-template-library-template-more-toggle">
			<i class="ceicon-ellipsis-h" aria-hidden="true"></i>
			<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'More actions', 'elementor' ); ?></span>
		</div>
		<div class="elementor-template-library-template-more">
			<div class="elementor-template-library-template-delete">
				<i class="ceicon-trash-o" aria-hidden="true"></i>
				<span class="elementor-template-library-template-control-title"><?php echo PrestaHelper::__( 'Delete', 'elementor' ); ?></span>
			</div>
			<div class="elementor-template-library-template-export">
				<a href="{{ export_link }}">
					<i class="ceicon-sign-out" aria-hidden="true"></i>
					<span class="elementor-template-library-template-control-title"><?php echo PrestaHelper::__( 'Export', 'elementor' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-insert-button">
    <a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button <?php echo (PrestaHelper::get_option( 'elementor_user_access_token' ))?'':'need_to_login'?> ">
			<i class="ceicon-file-download" aria-hidden="true"></i>
			<span class="elementor-button-title"><?php echo PrestaHelper::__( 'Insert', 'elementor' ); ?></span>
		</a>
</script>

<script type="text/template" id="tmpl-elementor-template-library-get-pro-button">

</script>

<script type="text/template" id="tmpl-elementor-template-library-save-template">
    <div class="elementor-template-library-blank-icon">
		<i class="ceicon-library-save" aria-hidden="true"></i>
		<span class="elementor-screen-only"><?php echo PrestaHelper::__( 'Save', 'elementor' ); ?></span>
	</div>
	<div class="elementor-template-library-blank-title">{{{ title }}}</div>
	<div class="elementor-template-library-blank-message">{{{ description }}}</div>
	<form id="elementor-template-library-save-template-form">
		<input type="hidden" name="post_id" value="<?php echo PrestaHelper::get_post_id();//echo get_the_ID(); ?>">
		<input id="elementor-template-library-save-template-name" name="title" placeholder="<?php echo PrestaHelper::esc_attr__( 'Enter Template Name', 'elementor' ); ?>" required>
		<button id="elementor-template-library-save-template-submit" class="elementor-button elementor-button-success">
			<span class="elementor-state-icon">
				<i class="ceicon-loading ceicon-animation-spin" aria-hidden="true"></i>
			</span>
			<?php echo PrestaHelper::__( 'Save', 'elementor' ); ?>
		</button>
	</form>
	<div class="elementor-template-library-blank-footer">
		
	</div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-import">
    <form id="elementor-template-library-import-form">
		<div class="elementor-template-library-blank-icon">
			<i class="ceicon-library-upload" aria-hidden="true"></i>
		</div>
		<div class="elementor-template-library-blank-title"><?php echo PrestaHelper::__( 'Import Template to Your Library', 'elementor' ); ?></div>
		<div class="elementor-template-library-blank-message"><?php echo PrestaHelper::__( 'Drag & drop your .JSON or .zip template file', 'elementor' ); ?></div>
		<div id="elementor-template-library-import-form-or"><?php echo PrestaHelper::__( 'or', 'elementor' ); ?></div>
		<label for="elementor-template-library-import-form-input" id="elementor-template-library-import-form-label" class="elementor-button elementor-button-success"><?php echo PrestaHelper::__( 'Select File', 'elementor' ); ?></label>
		<input id="elementor-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
		<div class="elementor-template-library-blank-footer">
			
		</div>
	</form>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates-empty">
    <div class="elementor-template-library-blank-icon">
		<i class="ceicon-nerd" aria-hidden="true"></i>
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		
</script>

<script type="text/template" id="tmpl-elementor-template-library-preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-elementor-template-connected-user-name">
    <div id="elementor-template-library-header-import" class="elementor-templates-modal__header__item">
		
		<label for="connected_user_name" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'User Name', 'elementor' ); ?></label>
		<input  id="connected_user_name" type="text" name="username" placeholder="UserName">
	</div>
	<div id="elementor-template-library-header-import" class="elementor-templates-modal__header__item">
		
		<label for="connected_user_pass" class="elementor-template-library-order-label"><?php echo PrestaHelper::__( 'Password', 'elementor' ); ?></label>
		<input  id="connected_user_pass" type="password" name="username" placeholder="UserName">
	</div>
	
</script>
<script type="text/template" id="tmpl-elementor-template-library-connect">
    <div id="elementor-template-library-connect-logo" class="elementor-gradient-logo">
	<i class="ceicon-ce-icon"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
	</div>
	<div class="elementor-template-library-blank-title">
		
		<?php echo PrestaHelper::__( 'Connect to Crazyelements Template Library', 'elementor' ); ?>  
	
	</div>
	<div class="elementor-template-library-blank-message">
		<?php echo PrestaHelper::__( 'Create a personal account for free and access this template and our entire library.', 'elementor' ); ?>  
	</div>
	<?php $url = "https://smartdemowp.com/api/login/"; ?>
	<a id="elementor-template-library-connect__button" class="elementor-button elementor-button-success" href="<?php echo  $url ; ?>" target="_blank">
		
		<?php echo PrestaHelper::__( 'Get Started', 'elementor' ); ?>  
	
	</a>
	<?php
	$base_images_url =  CRAZY_ASSETS_URL.'/images/library-connect/';

	$images = [ 'left-1', 'left-2', 'right-1', 'right-2' ];

	foreach ( $images as $image ) : ?>
		<img id="elementor-template-library-connect__background-image-<?php echo $image; ?>" class="elementor-template-library-connect__background-image" src="<?php echo $base_images_url . $image; ?>.png" draggable="false"/>
	<?php endforeach; ?>
</script>