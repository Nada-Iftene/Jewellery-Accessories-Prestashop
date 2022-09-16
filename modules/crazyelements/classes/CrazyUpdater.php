<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}
use CrazyElements\PrestaHelper;
use Symfony\Component\Translation\TranslatorInterface;

class CrazyUpdater {
	private $crazy_store_url = 'https://classydevs.com/';
	private $item_name       = '';
	private $api_data        = array();

	public function __construct( $_item_file, $_api_options = null ) {
		$this->crazy_store_url = $this->crazy_store_url;
		$this->item_name       = $_item_file;
		$this->api_data        = $_api_options;
		$this->notify_update();
	}

	private function notify_update() {
		$cookie = new Cookie( 'check_update' );
		if ( ! isset( $cookie->check_update ) || $cookie->check_update == '' ) {
				$this->api_request( 'get_version' );
		} else {
			$cookie_version = $cookie->check_update;
			if ( version_compare( $this->api_data['version'], $cookie_version, '<' ) ) {
				$d_link = PrestaHelper::get_option( 'ce_new_v' );
				$this->show_notification( $cookie_version, $d_link);
			}
		}
	}


	private function api_request( $action ) {
		$data       = $this->api_data;
		$key = PrestaHelper::get_option( 'ce_licence' );
		$api_params = array(
			'edd_action' => $action,
			'license'        => $key,
			'item_id'    => isset( $data['item_id'] ) ? $data['item_id'] : false,
			'version'    => isset( $data['version'] ) ? $data['version'] : false,
			'author'     => $data['author'],
			'url'        => PrestaHelper::get_base_url(),
		);
		$url        = $this->crazy_store_url . '?' . http_build_query( $api_params );
		$response   = PrestaHelper::wp_remote_get(
			$url,
			array(
				'timeout' => 20,
				'headers' => '',
				'header'  => false,
				'json'    => true,
			)
		);

		$responsearray = Tools::jsonDecode( $response, true );
		$sections = '';
		if(isset($responsearray['sections'])){
			$sections = unserialize( $responsearray['sections'] );
			
			if(isset($sections['changelog'])){
				$changelog = trim($sections['changelog']);
				$changelog = strip_tags($changelog);
				$changelog = Tools::jsonDecode( $changelog, true );
				$changelog = Tools::jsonEncode( $changelog );
				// echo '<pre>';
				// print_r($changelog);
				// echo '</pre>';
				// echo __FILE__ . ' : ' . __LINE__;
				// die(__FILE__ . ' : ' . __LINE__);
				PrestaHelper::update_option( 'ce_new_changelog', $changelog );
			}
		}
		$cookie        = new Cookie( 'check_update' );
		$cookie->setExpire( time() + 60 * 60 * 24 );
		$cookie->check_update = $responsearray['new_version'];
		$cookie->write();
		if ( version_compare( $data['version'], $responsearray['new_version'], '<' ) ) {
			PrestaHelper::update_option( 'ce_new_v', $responsearray['package'] );
			$new_v  = $responsearray['new_version'];
			$d_link = $responsearray['package'];
			$this->show_notification( $new_v, $d_link);
		}
	}

	private function show_notification( $v, $d, $ds="" ) {
		$url = PrestaHelper::getAjaxUrl();
		$msg = 'There is a new version of Crazy Elements Page Builder is available.';
		?>
<script>
var ajax_update = '<?php echo $url; ?>';
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="update-content-area">
            <div class="update-ajax-loader" style="display:none">
                <div class="lds-dual-ring"></div>
            </div>
            <div class="update-logo-and-text">
                <img src="<?php echo CRAZY_ASSETS_URL . 'images/crazy-elements.svg'; ?>" width="50" height="50">
                <div class="update-header-text-and-version">
                    <h4 class="update_msg"><?php echo $msg; ?></h4>
                    <div class="update_vsn_wrappper">
                        <h6 class="update_vsn"><?php echo 'Version: ' . $v; ?></h6><a class="what-s-new"
                            href="https://classydevs.com/docs/crazy-elements/getting-startted/change-log-for-crazy-elements-free/?utm_source=crazyfree_boffice_chnglg&utm_medium=crazyfree_boffice_chnglg&utm_campaign=crazyfree_boffice_chnglg&utm_id=crazyfree_boffice_chnglg&utm_term=crazyfree_boffice_chnglg&utm_content=crazyfree_boffice_chnglg"
                            target="_blank">What's
                            new?</a>
                    </div>
                </div>
            </div>
            <a href="javascript:void(0)" id="crazy_update_bt" data-down_vs="<?php echo $v; ?>"
                data-down_url="<?php echo $d; ?>"
                class="btn btn-primary crazy-update-bt"><?php echo 'Update To <strong>Version ' . $v . '</strong>'; ?></a>
        </div>
    </div>
</div>
<?php
	}

}