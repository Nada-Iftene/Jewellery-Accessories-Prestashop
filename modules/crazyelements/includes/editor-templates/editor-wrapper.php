<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo PrestaHelper::__( 'Elementor', 'elementor' ) . ' | ' . 'Presta Elementor Editor'; ?></title>
	<?php PrestaHelper::do_action( 'wp_head' ); ?>
	<script>
		 var ajaxurl = '<?php echo PrestaHelper::getAjaxUrl(); ?>';
	</script>
</head>
<body class="elementor-device-desktop elementor-controls-ready <?php echo 'elementor-editor-active elementor-page-' . \Tools::getValue( 'id' ); ?>">
<div id="elementor-editor-wrapper">
	<div id="elementor-panel" class="elementor-panel"></div>
	<div id="elementor-preview">
		
		<div id="elementor-loading" class="crazya-elementor-loading">
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
		<div id="elementor-preview-responsive-wrapper" class="elementor-device-desktop elementor-device-rotate-portrait">
			<div id="elementor-preview-loading">
				<i class="ceicon-loading ceicon-animation-spin" aria-hidden="true"></i>
			</div>
			<?php
			$notice = null;
			if ( $notice ) {
				?>
				<div id="elementor-notice-bar">
					<i class="ceicon-crazy-elements">><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
					<div id="elementor-notice-bar__message"><?php echo sprintf( $notice['message'], $notice['action_url'] ); ?></div>
					<div id="elementor-notice-bar__action"><a href="<?php echo $notice['action_url']; ?>" target="_blank"><?php echo $notice['action_title']; ?></a></div>
					<i id="elementor-notice-bar__close" class="ceicon-close"></i>
				</div>
			<?php } ?>
		</div>
	</div>
	<div id="elementor-navigator"></div>
</div>
<?php
	PrestaHelper::do_action( 'wp_footer' );
	PrestaHelper::wp_print_footer_scripts();
	PrestaHelper::do_action( 'admin_print_footer_scripts' );
?>
</body>
</html>
