<?php
namespace CrazyElements;

use CrazyElements\TemplateLibrary\Source_Local;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Maintenance_Mode {

	/**
	 * The options prefix.
	 */
	const OPTION_PREFIX = 'elementor_maintenance_mode_';

	/**
	 * The maintenance mode.
	 */
	const MODE_MAINTENANCE = 'maintenance';

	/**
	 * The coming soon mode.
	 */
	const MODE_COMING_SOON = 'coming_soon';

	public static function get( $option, $default = false ) {
		return PrestaHelper::get_option( self::OPTION_PREFIX . $option, $default );
	}

	public static function set( $option, $value ) {
		return PrestaHelper::update_option( self::OPTION_PREFIX . $option, $value );
	}

	public function body_class( $classes ) {
		$classes[] = 'elementor-maintenance-mode';

		return $classes;
	}

	public function template_redirect() {
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			return;
		}
		$user = wp_get_current_user();
		$exclude_mode = self::get( 'exclude_mode', [] );
		$is_login_page = PrestaHelper::apply_filters( 'elementor/maintenance_mode/is_login_page', false );
		if ( $is_login_page ) {
			return;
		}
		if ( 'logged_in' === $exclude_mode && is_user_logged_in() ) {
			return;
		}
		if ( 'custom' === $exclude_mode ) {
			$exclude_roles = self::get( 'exclude_roles', [] );
			$user_roles = $user->roles;
			if ( is_multisite() && is_super_admin() ) {
				$user_roles[] = 'super_admin';
			}
			$compare_roles = array_intersect( $user_roles, $exclude_roles );
			if ( ! empty( $compare_roles ) ) {
				return;
			}
		}
		PrestaHelper::add_filter( 'body_class', [ $this, 'body_class' ] );
		if ( 'maintenance' === self::get( 'mode' ) ) {
			$protocol = wp_get_server_protocol();
			header( "$protocol 503 Service Unavailable", true, 503 );
			header( 'Content-Type: text/html; charset=utf-8' );
			header( 'Retry-After: 600' );
		}
		$GLOBALS['post'] = get_post( self::get( 'template_id' ) ); // WPCS: override ok.
		query_posts( [
			'p' => self::get( 'template_id' ),
			'post_type' => Source_Local::CPT,
		] );
	}

	public function register_settings_fields( Tools $tools ) {
		$templates = Plugin::$instance->templates_manager->get_source( 'local' )->get_items( [
			'type' => 'page',
		] );
		$templates_options = [];
		foreach ( $templates as $template ) {
			$templates_options[ $template['template_id'] ] = $template['title'];
		}
		ob_start();
		$this->print_template_description();
		$template_description = ob_get_clean();
		$tools->add_tab(
			'maintenance_mode', [
				'label' => PrestaHelper::__( 'Maintenance Mode', 'elementor' ),
				'sections' => [
					'maintenance_mode' => [
						'callback' => function() {
							echo '<h2>' . PrestaHelper::esc_html__( 'Maintenance Mode', 'elementor' ) . '</h2>';
							echo '<div>' . PrestaHelper::__( 'Set your entire website as MAINTENANCE MODE, meaning the site is offline temporarily for maintenance, or set it as COMING SOON mode, meaning the site is offline until it is ready to be launched.', 'elementor' ) . '</div>';
						},
						'fields' => [
							'maintenance_mode_mode' => [
								'label' => PrestaHelper::__( 'Choose Mode', 'elementor' ),
								'field_args' => [
									'type' => 'select',
									'options' => [
										'' => PrestaHelper::__( 'Disabled', 'elementor' ),
										self::MODE_COMING_SOON => PrestaHelper::__( 'Coming Soon', 'elementor' ),
										self::MODE_MAINTENANCE => PrestaHelper::__( 'Maintenance', 'elementor' ),
									],
									'desc' => '<div class="elementor-maintenance-mode-description" data-value="" style="display: none">' .
											  PrestaHelper::__( 'Choose between Coming Soon mode (returning HTTP 200 code) or Maintenance Mode (returning HTTP 503 code).', 'elementor' ) .
											  '</div>' .
											  '<div class="elementor-maintenance-mode-description" data-value="maintenance" style="display: none">' .
											  PrestaHelper::__( 'Maintenance Mode returns HTTP 503 code, so search engines know to come back a short time later. It is not recommended to use this mode for more than a couple of days.', 'elementor' ) .
											  '</div>' .
											  '<div class="elementor-maintenance-mode-description" data-value="coming_soon" style="display: none">' .
											  PrestaHelper::__( 'Coming Soon returns HTTP 200 code, meaning the site is ready to be indexed.', 'elementor' ) .
											  '</div>',
								],
							],
							'maintenance_mode_exclude_mode' => [
								'label' => PrestaHelper::__( 'Who Can Access', 'elementor' ),
								'field_args' => [
									'class' => 'elementor-default-hide',
									'type' => 'select',
									'std' => 'logged_in',
									'options' => [
										'logged_in' => PrestaHelper::__( 'Logged In', 'elementor' ),
										'custom' => PrestaHelper::__( 'Custom', 'elementor' ),
									],
								],
							],
							'maintenance_mode_exclude_roles' => [
								'label' => PrestaHelper::__( 'Roles', 'elementor' ),
								'field_args' => [
									'class' => 'elementor-default-hide',
									'type' => 'checkbox_list_roles',
								],
								'setting_args' => [ __NAMESPACE__ . '\Settings_Validations', 'checkbox_list' ],
							],
							'maintenance_mode_template_id' => [
								'label' => PrestaHelper::__( 'Choose Template', 'elementor' ),
								'field_args' => [
									'class' => 'elementor-default-hide',
									'type' => 'select',
									'show_select' => true,
									'options' => $templates_options,
									'desc' => $template_description,
								],
							],
						],
					],
				],
			]
		);
	}

	
	public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->add_node( [
			'id' => 'elementor-maintenance-on',
			'title' => PrestaHelper::__( 'Maintenance Mode ON', 'elementor' ),
			'href' => Tools::get_url() . '#tab-maintenance_mode',
		] );
		$document = Plugin::$instance->documents->get( self::get( 'template_id' ) );
		$wp_admin_bar->add_node( [
			'id' => 'elementor-maintenance-edit',
			'parent' => 'elementor-maintenance-on',
			'title' => PrestaHelper::__( 'Edit Template', 'elementor' ),
			'href' => $document ? $document->get_edit_url() : '',
		] );
	}


	public function print_style() {
		?>
		<style>#wp-admin-bar-elementor-maintenance-on > a { background-color: #dc3232; }
			#wp-admin-bar-elementor-maintenance-on > .ab-item:before { content: "\f160"; top: 2px; }</style>
		<?php
	}

	public function on_update_mode( $old_value, $value ) {
		if ( $old_value !== $value ) {
			PrestaHelper::do_action( 'elementor/maintenance_mode/mode_changed', $old_value, $value );
		}
	}

	public function __construct() {
		PrestaHelper::add_action( 'update_option_elementor_maintenance_mode_mode', [ $this, 'on_update_mode' ], 10, 2 );

		$is_enabled = (bool) self::get( 'mode' ) && (bool) self::get( 'template_id' );
		if ( ! $is_enabled ) {
			return;
		}
		PrestaHelper::add_action( 'admin_bar_menu', [ $this, 'add_menu_in_admin_bar' ], 300 );
		PrestaHelper::add_action( 'admin_head', [ $this, 'print_style' ] );
		PrestaHelper::add_action( 'wp_head', [ $this, 'print_style' ] );
		PrestaHelper::add_action( 'template_redirect', [ $this, 'template_redirect' ], 11 );
	}

	private function print_template_description() {
		$template_id = self::get( 'template_id' );
		$edit_url = '';
		if ( $template_id && get_post( $template_id ) ) {
			$edit_url = Plugin::$instance->documents->get( $template_id )->get_edit_url();
		}
		?>
		<a target="_blank" class="elementor-edit-template" style="display: none" href="<?php echo $edit_url; ?>"><?php echo PrestaHelper::__( 'Edit Template', 'elementor' ); ?></a>
		<div class="elementor-maintenance-mode-error"><?php echo PrestaHelper::__( 'To enable maintenance mode you have to set a template for the maintenance mode page.', 'elementor' ); ?></div>
		<div class="elementor-maintenance-mode-error"><?php echo sprintf( PrestaHelper::__( 'Select one or go ahead and <a target="_blank" href="%s">create one</a> now.', 'elementor' ), PrestaHelper::admin_url( 'post-new.php?post_type=' . Source_Local::CPT ) ); ?></div>
		<?php
	}
}