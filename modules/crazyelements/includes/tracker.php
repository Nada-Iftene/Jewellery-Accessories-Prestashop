<?php
namespace CrazyElements;

use CrazyElements\Core\Base\Document;

use CrazyElements\PrestaHelper;

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Tracker {


	private static $_api_url     = 'https://classydevs.com/docs/crazy-elements/';
	private static $notice_shown = false;

	public static function init() {
		PrestaHelper::add_action( 'elementor/tracker/send_event', array( __CLASS__, 'send_tracking_data' ) );
		PrestaHelper::add_action( 'admin_init', array( __CLASS__, 'handle_tracker_actions' ) );
		PrestaHelper::add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	public static function check_for_settings_optin( $new_value ) {
		$old_value = PrestaHelper::get_option( 'elementor_allow_tracking', 'no' );
		if ( $old_value !== $new_value && 'yes' === $new_value ) {
			self::send_tracking_data( true );
		}
		if ( empty( $new_value ) ) {
			$new_value = 'no';
		}
		return $new_value;
	}

	public static function send_tracking_data( $override = false ) {
		// Don't trigger this on AJAX Requests.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( ! self::is_allow_track() ) {
			return;
		}
		$last_send = self::get_last_send_time();
		$override  = PrestaHelper::apply_filters( 'elementor/tracker/send_override', $override );
		if ( ! $override ) {
			$last_send_interval = strtotime( '-1 week' );
			$last_send_interval = PrestaHelper::apply_filters( 'elementor/tracker/last_send_interval', $last_send_interval );
			// Send a maximum of once per week by default.
			if ( $last_send && $last_send > $last_send_interval ) {
				return;
			}
		} else {
			// Make sure there is at least a 1 hour delay between override sends, we dont want duplicate calls due to double clicking links.
			if ( $last_send && $last_send > strtotime( '-1 hours' ) ) {
				return;
			}
		}
		// Update time first before sending to ensure it is set.
		PrestaHelper::update_option( 'elementor_tracker_last_send', time() );
		// Send here..
		$params = array(
			'system'        => self::get_system_reports_data(),
			'site_lang'     => get_bloginfo( 'language' ),
			'email'         => PrestaHelper::get_option( 'admin_email' ),
			'usages'        => array(
				'posts'    => self::get_posts_usage(),
				'elements' => self::get_elements_usage(),
				'library'  => self::get_library_usage(),
			),
			'is_first_time' => empty( $last_send ),
		);
		$params = PrestaHelper::apply_filters( 'elementor/tracker/send_tracking_data_params', $params );
		PrestaHelper::add_filter( 'https_ssl_verify', '__return_false' );
		wp_safe_remote_post(
			self::$_api_url,
			array(
				'timeout'  => 25,
				'blocking' => false,
				'body'     => array(
					'data' => json_encode( $params ),
				),
			)
		);
	}

	public static function is_allow_track() {
		return 'yes' === PrestaHelper::get_option( 'elementor_allow_tracking', 'no' );
	}

	public static function handle_tracker_actions() {
		if ( ! isset( $_GET['elementor_tracker'] ) ) {
			return;
		}
		if ( 'opt_into' === $_GET['elementor_tracker'] ) {
			check_admin_referer( 'opt_into' );
			PrestaHelper::update_option( 'elementor_allow_tracking', 'yes' );
			self::send_tracking_data( true );
		}
		if ( 'opt_out' === $_GET['elementor_tracker'] ) {
			check_admin_referer( 'opt_out' );
			PrestaHelper::update_option( 'elementor_allow_tracking', 'no' );
			PrestaHelper::update_option( 'elementor_tracker_notice', '1' );
		}

		exit;
	}

	/**
	 * Admin notices.
	 *
	 * Add Elementor notices to WordPress admin screen to show tracker notice.
	 *
	 * Fired by `admin_notices` action.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function admin_notices() {
		// Show tracker notice after 24 hours from installed time.
		if ( Plugin::$instance->get_install_time() > strtotime( '-24 hours' ) ) {
			return;
		}
		if ( '1' === PrestaHelper::get_option( 'elementor_tracker_notice' ) ) {
			return;
		}
		if ( self::is_allow_track() ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$elementor_pages = new \WP_Query(
			array(
				'post_type'              => 'any',
				'post_status'            => 'publish',
				'fields'                 => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_key'               => '_elementor_edit_mode',
				'meta_value'             => 'builder',
			)
		);
		if ( 2 > $elementor_pages->post_count ) {
			return;
		}
		self::$notice_shown       = true;
		$optin_url                = wp_nonce_url( add_query_arg( 'elementor_tracker', 'opt_into' ), 'opt_into' );
		$optout_url               = wp_nonce_url( add_query_arg( 'elementor_tracker', 'opt_out' ), 'opt_out' );
		$tracker_description_text = PrestaHelper::__( 'Love using Crazy Elements? Become a super contributor by opting in to our anonymous plugin data collection and to our updates. We guarantee no sensitive data is collected.', 'elementor' );
		$tracker_description_text = PrestaHelper::apply_filters( 'elementor/tracker/admin_description_text', $tracker_description_text );
		?>
		<div class="notice updated elementor-message">
			<div class="elementor-message-inner">
				<div class="elementor-message-icon">
					<div class="e-logo-wrapper">
						<i class="ceicon-ce-icon" aria-hidden="true"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
					</div>
				</div>
				<div class="elementor-message-content">
					<p><?php echo PrestaHelper::esc_html( $tracker_description_text ); ?> <a href="https://classydevs.com/docs/crazy-elements/" target="_blank"><?php echo PrestaHelper::__( 'Learn more.', 'elementor' ); ?></a></p>
					<p class="elementor-message-actions">
						<a href="<?php echo $optin_url; ?>" class="button button-primary"><?php echo PrestaHelper::__( 'Sure! I\'d love to help', 'elementor' ); ?></a>&nbsp;<a href="<?php echo $optout_url; ?>" class="button-secondary"><?php echo PrestaHelper::__( 'No thanks', 'elementor' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}


	public static function is_notice_shown() {
		return self::$notice_shown;
	}


	private static function get_system_reports_data() {
		$reports        = Plugin::$instance->system_info->load_reports( System_Info\Main::get_allowed_reports() );
		$system_reports = array();
		foreach ( $reports as $report_key => $report_details ) {
			$system_reports[ $report_key ] = array();
			foreach ( $report_details['report'] as $sub_report_key => $sub_report_details ) {
				$system_reports[ $report_key ][ $sub_report_key ] = $sub_report_details['value'];
			}
		}
		return $system_reports;
	}

	private static function get_last_send_time() {
		$last_send_time = PrestaHelper::get_option( 'elementor_tracker_last_send', false );
		$last_send_time = PrestaHelper::apply_filters( 'elementor/tracker/last_send_time', $last_send_time );
		return $last_send_time;
	}

	private static function get_posts_usage() {
		global $wpdb;
		$usage   = array();
		$results = $wpdb->get_results(
			"SELECT `post_type`, `post_status`, COUNT(`ID`) `hits`
				FROM {$wpdb->posts} `p`
				LEFT JOIN {$wpdb->postmeta} `pm` ON(`p`.`ID` = `pm`.`post_id`)
				WHERE `post_type` != 'elementor_library'
					AND `meta_key` = '_elementor_edit_mode' AND `meta_value` = 'builder'
				GROUP BY `post_type`, `post_status`;"
		);
		if ( $results ) {
			foreach ( $results as $result ) {
				$usage[ $result->post_type ][ $result->post_status ] = $result->hits;
			}
		}
		return $usage;
	}

	private static function get_elements_usage() {
		return PrestaHelper::get_option( Document::ELEMENTS_USAGE_OPTION_NAME );

	}

	private static function get_library_usage() {
		global $wpdb;
		$usage   = array();
		$results = $wpdb->get_results(
			"SELECT `meta_value`, COUNT(`ID`) `hits`
				FROM {$wpdb->posts} `p`
				LEFT JOIN {$wpdb->postmeta} `pm` ON(`p`.`ID` = `pm`.`post_id`)
				WHERE `post_type` = 'elementor_library'
					AND `meta_key` = '_elementor_template_type'
				GROUP BY `post_type`, `meta_value`;"
		);
		if ( $results ) {
			foreach ( $results as $result ) {
				$usage[ $result->meta_value ] = $result->hits;
			}
		}
		return $usage;
	}
}
