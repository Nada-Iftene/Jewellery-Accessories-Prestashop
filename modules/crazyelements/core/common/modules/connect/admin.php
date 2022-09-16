<?php
namespace CrazyElements\Core\Common\Modules\Connect;

use CrazyElements\Plugin;
use CrazyElements\Settings;
use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Admin {

	const PAGE_ID = 'elementor-connect';

	public static $url = '';

	/**
	 * @since 1.0
	 * @access public
	 */

	public function register_admin_menu() {
		$submenu_page = add_submenu_page(
			Settings::PAGE_ID,
			PrestaHelper::__( 'Connect', 'elementor' ),
			PrestaHelper::__( 'Connect', 'elementor' ),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'render_page' ]
		);
		PrestaHelper::add_action( 'load-' . $submenu_page, [ $this, 'on_load_page' ] );
	}

	/**
	 * @since 1.0
	 * @access public
	 */

	public function hide_menu_item() {
		remove_submenu_page( Settings::PAGE_ID, self::PAGE_ID );
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	
	public function on_load_page() {
		if ( isset( $_GET['action'], $_GET['app'] ) ) {
			$manager = Plugin::$instance->common->get_component( 'connect' );
			$app_slug = $_GET['app'];
			$app = $manager->get_app( $app_slug );
			$nonce_action = $_GET['app'] . $_GET['action'];
			if ( ! $app ) {
				wp_die( 'Unknown app: ' . $app_slug );
			}
			if ( empty( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], $nonce_action ) ) {
				wp_die( 'Invalid Nonce', 'Invalid Nonce', [
					'back_link' => true,
				] );
			}
			$method = 'action_' . $_GET['action'];
			if ( method_exists( $app, $method ) ) {
				call_user_func( [ $app, $method ] );
			}
		}
	}

	/**
	 * @since 1.0
	 * @access public
	 */

	public function render_page() {
		$apps = Plugin::$instance->common->get_component( 'connect' )->get_apps();
		?>
		<style>
			.elementor-connect-app-wrapper{ margin-bottom: 50px; overflow: hidden; }
		</style>
		<div class="wrap">
			<?php
			foreach ( $apps as $app ) {
				echo '<div class="elementor-connect-app-wrapper">';
				$app->render_admin_widget();
				echo '</div>';
			}
			?>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		self::$url = PrestaHelper::admin_url( 'admin.php?page=' . self::PAGE_ID );

		PrestaHelper::add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 206 );
		PrestaHelper::add_action( 'admin_head', [ $this, 'hide_menu_item' ] );
	}
}