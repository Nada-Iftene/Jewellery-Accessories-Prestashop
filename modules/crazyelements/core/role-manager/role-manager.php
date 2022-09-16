<?php
namespace CrazyElements\Core\RoleManager;

use CrazyElements\Settings_Page;
use CrazyElements\Settings;
use CrazyElements\Utils;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Role_Manager extends Settings_Page {

	const PAGE_ID = 'elementor-role-manager';

	const ROLE_MANAGER_OPTION_NAME = 'exclude_user_roles';

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_role_manager_options() {
		return PrestaHelper::get_option( 'elementor_' . self::ROLE_MANAGER_OPTION_NAME, [] );
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_page_title() {
		return PrestaHelper::__( 'Role Manager', 'elementor' );
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		add_submenu_page(
			Settings::PAGE_ID,
			$this->get_page_title(),
			$this->get_page_title(),
			'manage_options',
			self::PAGE_ID,
			[ $this, 'display_settings_page' ]
		);
	}

	/**
	 * @since 1.0.0
	 * @access protected
	 */
	protected function create_tabs() {
		$validation_class = 'Elementor\Settings_Validations';
		return [
			'general' => [
				'label' => PrestaHelper::__( 'General', 'elementor' ),
				'sections' => [
					'tools' => [
						'fields' => [
							'exclude_user_roles' => [
								'label' => PrestaHelper::__( 'Exclude Roles', 'elementor' ),
								'field_args' => [
									'type' => 'checkbox_list_roles',
									'exclude' => [ 'super_admin', 'administrator' ],
								],
								'setting_args' => [
									'sanitize_callback' => [ $validation_class, 'checkbox_list' ],
								],
							],
						],
					],
				],
			],
		];
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function display_settings_page() {
		$this->get_tabs();
		?>
		<div class="wrap">
			<h1><?php echo PrestaHelper::esc_html( $this->get_page_title() ); ?></h1>

			<div id="elementor-role-manager">
				<h3><?php echo PrestaHelper::__( 'Manage What Your Users Can Edit In Elementor', 'elementor' ); ?></h3>
				<form id="elementor-settings-form" method="post" action="options.php">
					<?php
					settings_fields( static::PAGE_ID );
					echo '<div class="elementor-settings-form-page elementor-active">';
					foreach ( get_editable_roles() as $role_slug => $role_data ) {
						if ( 'administrator' === $role_slug ) {
							continue;
						}
						$this->display_role_controls( $role_slug, $role_data );
					}
					submit_button();
					?>
				</form>
			</div>
		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $role_slug The role slug.
	 * @param array  $role_data An array with role data.
	 */
	private function display_role_controls( $role_slug, $role_data ) {
		static $excluded_options = false;
		if ( false === $excluded_options ) {
			$excluded_options = $this->get_role_manager_options();
		}

		?>
		<div class="elementor-role-row <?php echo PrestaHelper::esc_attr( $role_slug ); ?>">
			<div class="elementor-role-label">
				<span class="elementor-role-name"><?php echo PrestaHelper::esc_html( $role_data['name'] ); ?></span>
				<span data-excluded-label="<?php PrestaHelper::esc_attr_e( 'Role Excluded', 'elementor' ); ?>" class="elementor-role-excluded-indicator"></span>
				<span class="elementor-role-toggle"><span class="dashicons dashicons-arrow-down"></span></span>
			</div>
			<div class="elementor-role-controls hidden">
				<div class="elementor-role-control">
					<label>
						<input type="checkbox" name="elementor_exclude_user_roles[]" value="<?php echo PrestaHelper::esc_attr( $role_slug ); ?>"<?php checked( in_array( $role_slug, $excluded_options, true ), true ); ?>>
						<?php echo PrestaHelper::__( 'No access to editor', 'elementor' ); ?>
					</label>
				</div>
				<div>
					<?php
					/**
					 * @since 1.0.0
					 *
					 * @param string $role_slug The role slug.
					 * @param array  $role_data An array with role data.
					 */
					PrestaHelper::do_action( 'elementor/role/restrictions/controls', $role_slug, $role_data );
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_go_pro_link_html() {
		$pro_link = Utils::get_pro_link( 'https://classydevs.com/pricing/' );
		?>
		<div class="elementor-role-go-pro">
			<div class="elementor-role-go-pro__desc"><?php echo PrestaHelper::__( 'Want to give access only to content?', 'elementor' ); ?></div>
			<div class="elementor-role-go-pro__link"><a class="elementor-button elementor-button-default elementor-button-go-pro" target="_blank" href="<?php echo esc_url( $pro_link ); ?>"><?php echo PrestaHelper::__( 'Extend Your License', 'elementor' ); ?></a></div>
		</div>
		<?php
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function get_user_restrictions_array() {
		$user  = wp_get_current_user();
		$user_roles = $user->roles;
		$options = $this->get_user_restrictions();
		$restrictions = [];
		if ( empty( $options ) ) {
			return $restrictions;
		}

		foreach ( $user_roles as $role ) {
			if ( ! isset( $options[ $role ] ) ) {
				continue;
			}
			$restrictions = array_merge( $restrictions, $options[ $role ] );
		}
		return array_unique( $restrictions );
	}

	/**
	 * @since 1.0.0
	 * @access private
	 */
	private function get_user_restrictions() {
		static $restrictions = false;
		if ( ! $restrictions ) {
			$restrictions = [];

			/**
			 * Editor user restrictions.
			 *
			 * Filters the user restrictions in the editor.
			 *
			 * @since 1.0.0
			 *
			 * @param array $restrictions User restrictions.
			 */
			$restrictions = PrestaHelper::apply_filters( 'elementor/editor/user/restrictions', $restrictions );
		}
		return $restrictions;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $capability
	 *
	 * @return bool
	 */
	public function user_can( $capability ) {
		$options = $this->get_user_restrictions_array();

		if ( in_array( $capability, $options, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct();

		PrestaHelper::add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 100 );
		PrestaHelper::add_action( 'elementor/role/restrictions/controls', [ $this, 'get_go_pro_link_html' ] );
	}
}
