<?php
defined( '_PS_VERSION_' ) or exit;
require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';
require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;
class AdminCrazySettingController extends AdminController {


	public $dirpaths                     = array();
	public $json_file_name               = '';
	public $svg_file_name                = '';
	public $new_json                     = array();
	public $custom_icon_upload_font_name = '';
	public $text_file_name               = 'fontarray.txt';
	public $new_json_file_name           = 'fontarray.json';
	public $folder_name                  = '';
	public $first_icon_name              = '';
	public function __construct() {
		$this->context   = Context::getContext();
		$this->bootstrap = true;
		$this->table     = 'configuration';
		parent::__construct();
	}

	public function renderList() {
		$check_yes           = '';
		$check_no            = '';
		$page_title_selector = PrestaHelper::get_option( 'page_title' );
		if ( PrestaHelper::get_option( 'presta_editor_enable' ) == 'yes' ) {
			$check_yes = 'checked = checked';
			$check_no  = '';
		}
		if ( PrestaHelper::get_option( 'presta_editor_enable', 'no' ) == 'no' ) {
			$check_no  = 'checked = checked';
			$check_yes = '';
		}
		$content_check_yes = '';
		$content_check_no  = '';
		if ( PrestaHelper::get_option( 'crazy_content_disable' ) == 'yes' ) {
			$content_check_yes = 'checked = checked';
			$content_check_no  = '';
		}
		if ( PrestaHelper::get_option( 'crazy_content_disable', 'no' ) == 'no' ) {
			$content_check_no  = 'checked = checked';
			$content_check_yes = '';
		}
		$cookie = new Cookie( 'check_update' );
		$cookie_version = $cookie->check_update;
		if(!isset($cookie_version) || $cookie_version == false){
			$cookie_version = CRAZY_VERSION;
		}
		$info_msg = '<div class="col-lg-9 col-lg-offset-3 module-info"> Installed Version : ' . CRAZY_VERSION . '</div>
		<div class="col-lg-9 col-lg-offset-3 module-info"> Available Version : ' . $cookie_version . '<button type="submit" class="btn btn-default check-update-bt" name="check_update"><i class="process-icon-refresh icon-check-update"></i>Check Update
		</button></div>
		<div class="col-lg-9 col-lg-offset-3 module-info"> <a href="https://classydevs.com/docs/crazy-elements/?utm_source=crazyfree_licsec&utm_medium=crazyfree_licsec&utm_campaign=crazyfree_licsec&utm_id=crazyfree_licsec&utm_term=crazyfree_licsec&utm_content=crazyfree_licsec" target="_blank">Check Documentation</a></div>';
		$page_types = array(
			'index' => 'Homepage',
			'cms' => 'Cms',
			'product' => 'Product',
			'category' => 'Product Category',
			'supplier' => 'Supplier',
			'manufacturer' => 'Manufacturer'
		);
		$page_options = '';
		foreach($page_types as $key => $p_type){
			$page_options .= '<div class="specific-page"><input type="checkbox"> <span>' . $p_type. '</span> </div>';
		}
		$disable_ce = '<div class="form-group">
		<label class="control-label col-lg-3">Enable Crazyelements Content On (PRO Feature)</label>
		<div class="col-lg-9 specific-page-wrapper">
		'.$page_options.'
		</div>
		<div class="col-lg-9 col-lg-offset-3"> <div class="help-block"><a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_homelayout&utm_medium=crazyfree_homelayout&utm_campaign=crazyfree_homelayout&utm_id=crazyfree_homelayout&utm_term=crazyfree_homelayout&utm_content=crazyfree_homelayout">Get Crazyelements Pro</a> And Optimize Asset Loading on Specific Pages</div></div>
	</div>';
		$fromhtml = '<div class="double_section"><form action="" id="configuration_form_3" method="post" enctype="multipart/form-data" class="form-horizontal"> 
        <div class="panel ce_licence_panel" id="configuration_fieldset_license"> <div class="panel-heading"> <i class="icon-cogs"></i> Crazyelements Information
        </div><div class="form-wrapper"> <div class="form-group"> <div id="conf_id_license_data">
		'.$info_msg.'
		</div></div></div>
		<div class="panel-footer"> 
		<a class="link-social link-youtube _blank pull-right" href="https://www.youtube.com/c/ClassyDevs" title="Youtube">
		<i class="icon-youtube"></i>
		</a>
		<a class="link-social link-facebook _blank pull-right" href="https://www.facebook.com/classydevs" title="Facebook">
			<i class="icon-facebook"></i>
		</a>
        </div>
        </div>
        </form>
		<form action="" id="configuration_form_6" method="post" enctype="multipart/form-data" class="form-horizontal"> 
        <div class="panel " id="configuration_fieldset_replace"> <div class="panel-heading"> <i class="icon-cogs"></i> Homepage Settings </div><div class="form-wrapper"> 
			<div class="form-group">
				<label class="control-label col-lg-3">Select Home Layout (PRO) </label>
				<div class="col-lg-5">
					<select name="crazy_home_layout">
						<option>Default</option>
						<option>Crazy Canvas Layout</option>
						<option>Crazy Fullwidth Layout</option>
					</select>
				</div>
				<div class="col-lg-9 col-lg-offset-3"> <div class="help-block"><a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_homelayout&utm_medium=crazyfree_homelayout&utm_campaign=crazyfree_homelayout&utm_id=crazyfree_homelayout&utm_term=crazyfree_homelayout&utm_content=crazyfree_homelayout">Get Crazyelements Pro</a> And Select Layout for your Homepage</div></div>
			</div>
			<div class="form-group"> 
				<div id="conf_crazy_content_disable"> 
					<label class="control-label col-lg-3"> Clear displayHome Hook </label> 
					<div class="col-lg-9"> <span class="switch prestashop-switch fixed-width-lg"> 
					<input type="radio" name="remove_display_home_hook" id="remove_display_home_hook" value="1">
					<label for="remove_display_home_hook" class="radioCheck">Yes</label>
					<input type="radio" name="remove_display_home_hook" id="remove_display_home_hook_off" value="0" checked="checked">
					<label for="remove_display_home_hook_off" class="radioCheck">No</label> <a class="slide-button btn"></a> </span> 
					</div>
					<div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> Remove all modules from displayHome hook </div></div>
				</div>
			</div>
		</div><div class="panel-footer"> <button type="submit" class="btn btn-default pull-right" name="crazy_home_settings"><i class="process-icon-save"></i> Save </button> </div></div>
        </form>

		</div>
		<form action="" id="configuration_form_4" method="post" enctype="multipart/form-data" class="form-horizontal"> 
        <div class="panel " id="configuration_fieldset_page_title_selector">
         <div class="panel-heading"> <i class="icon-cogs"></i> General Settings </div>
         <div class="form-wrapper"> 
         <div class="form-group">
          <div id="conf_id_page_title"> <label class="control-label col-lg-3"> Page Title Selector </label> 
          <div class="col-lg-9"><input class="form-control " type="text" size="5" name="page_title" value="' . $page_title_selector . '"> </div>
          <div class="col-lg-9 col-lg-offset-3"> 
          </div>
          </div>
          </div>
        <div class="form-group"> 
            <div id="conf_presta_editor_enable"> 
                <label class="control-label col-lg-3"> Enable Presta Editor </label> 
                <div class="col-lg-9"> <span class="switch prestashop-switch fixed-width-lg"> 
                <input type="radio" name="presta_editor_enable" id="presta_editor_enable_on" value="1" ' . $check_yes . '>
                <label for="presta_editor_enable_on" class="radioCheck">Yes</label>
                <input type="radio" name="presta_editor_enable" id="presta_editor_enable_off" value="0"  ' . $check_no . '>
                <label for="presta_editor_enable_off" class="radioCheck">No</label> <a class="slide-button btn"></a> </span> 
                </div>
                <div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> Enable or disable Prestashop default editor </div></div>
            </div>
        </div>
        <div class="form-group"> 
            <div id="conf_crazy_content_disable"> 
                <label class="control-label col-lg-3"> Disable Crazyelements Content </label> 
                <div class="col-lg-9"> <span class="switch prestashop-switch fixed-width-lg"> 
                <input type="radio" name="crazy_content_disable" id="crazy_content_disable_on" value="1" ' . $content_check_yes . '>
                <label for="crazy_content_disable_on" class="radioCheck">Yes</label>
                <input type="radio" name="crazy_content_disable" id="crazy_content_disable_off" value="0"  ' . $content_check_no . '>
                <label for="crazy_content_disable_off" class="radioCheck">No</label> <a class="slide-button btn"></a> </span> 
                </div>
                <div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> Enable or disable Crazyelements content in front </div></div>
            </div>
        </div>
		'.$disable_ce.'
        <div class="panel-footer"> <button type="submit" class="btn btn-default pull-right" name="page_title_submit"><i class="process-icon-save"></i> Add Title Selector</button> </div>
          </div> 
          </div> 
        </form>
		<form action="" id="configuration_form_7" method="post" enctype="multipart/form-data" class="form-horizontal"> 
		<div class="panel " id="configuration_fieldset_replace"> <div class="panel-heading"> <i class="icon-cogs"></i> Add Custom Hooks (PRO) <a href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_customhook&utm_medium=crazyfree_customhook&utm_campaign=crazyfree_customhook&utm_id=crazyfree_customhook&utm_term=crazyfree_customhook&utm_content=crazyfree_customhook">Get Crazyelements Pro</a></div><div class="form-wrapper"> 
			</div><div class="form-wrapper"> <div class="form-group"> <div id="conf_id_hook_data"> <label class="control-label col-lg-2"> Add Hook Name </label> <div class="col-lg-3"><input class="form-control " type="text" size="5"> 
			</div><label class="control-label col-lg-2"> Add Page Rewrite </label> <div class="col-lg-3"><input class="form-control " type="text" size="5"> 
			</div></div></div>
		</div></div>
		</form>
        <form action="" id="configuration_form_1" method="post" enctype="multipart/form-data" class="form-horizontal"> 
        <div class="panel " id="configuration_fieldset_cache"> 
            <div class="panel-heading"> <i class="icon-cogs"></i> Clear Cache for Crazy</div>
            <div class="form-wrapper"> 
                <div class="form-group"> 
                    <div id="conf_id_crazy_clear_cache"> 
                        <label class="control-label col-lg-3"> Clear Cache </label> 
                        <div class="col-lg-9"> <span class="switch prestashop-switch fixed-width-lg"> <input type="radio" name="crazy_clear_cache" id="crazy_clear_cache_on" value="1"><label for="crazy_clear_cache_on" class="radioCheck">Yes</label><input type="radio" name="crazy_clear_cache" id="crazy_clear_cache_off" value="0" checked="checked"><label for="crazy_clear_cache_off" class="radioCheck">No</label> <a class="slide-button btn"></a> </span> </div>
                        <div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> If your css is not working clearing cache might help. </div></div>
                    </div>
                 </div>
            </div>
            <div class="panel-footer"> <button type="submit" class="btn btn-default pull-right" name="crazy_clear_cache_submit"><i class="process-icon-save"></i> Clear Cache</button> 
            </div>
        </div>
        </form>
        <form action="" id="configuration_form_2" method="post" enctype="multipart/form-data" class="form-horizontal"> 
        <div class="panel " id="configuration_fieldset_replace"> <div class="panel-heading"> <i class="icon-cogs"></i> Update Site Address</div><div class="form-wrapper"> <div class="form-group"> <div id="conf_id_crazy_old_url"> <label class="control-label col-lg-3"> Old Url </label> <div class="col-lg-9"><input class="form-control " type="text" size="5" name="crazy_old_url" value=""> </div><div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> Enter your old URL. </div></div></div></div><div class="form-group"> <div id="conf_id_crazy_new_url"> <label class="control-label col-lg-3"> New Url </label> <div class="col-lg-9"><input class="form-control " type="text" size="5" name="crazy_new_url" value=""> </div><div class="col-lg-9 col-lg-offset-3"> <div class="help-block"> Enter your new URL. </div></div></div></div></div><div class="panel-footer"> <button type="submit" class="btn btn-default pull-right" name="crazy_url_submit"><i class="process-icon-save"></i> Replace URL</button> </div></div>
        </form>';
		$html     = parent::renderList() . $fromhtml;
		return $html;
	}

	public function initContent() {
		
		PrestaHelper::get_lience_expired_date();
		if ( Tools::isSubmit( 'check_update' ) ) {
			$cookie = new Cookie( 'check_update' );
			if ( isset( $cookie->check_update ) ) {
				unset($cookie->check_update);
			}
			
			Tools::redirectAdmin( $this->context->link->getAdminLink( 'AdminCrazySetting' ) );
		}
		if ( Tools::isSubmit( 'crazy_home_settings' ) ) {
			$remove_display_home_hook = Tools::getValue( 'remove_display_home_hook' );
			if ( $remove_display_home_hook == '1' ) {
				$hookid = Hook::getIdByName('displayHome');
				$moduleslist = Hook::getModulesFromHook($hookid);
				
				foreach($moduleslist as $module){
					$mod_ins = Module::getInstanceByName( trim($module['name']) );
					$mod_ins->unregisterHook('displayHome');
				}
			}
			Tools::redirectAdmin( $this->context->link->getAdminLink( 'AdminCrazySetting' ) );
		}
		if ( Tools::isSubmit( 'page_title' ) ) {
			$page_title = Tools::getValue( 'page_title' );
			PrestaHelper::update_option( 'page_title', $page_title );
			$presta_editor_enable = Tools::getValue( 'presta_editor_enable' );
			if ( $presta_editor_enable == '1' ) {
				$presta_editor_enable = 'yes';
			} else {
				$presta_editor_enable = 'no';
			}
			PrestaHelper::update_option( 'presta_editor_enable', $presta_editor_enable );
			$crazy_content_disable = Tools::getValue( 'crazy_content_disable' );
			if ( $crazy_content_disable == '1' ) {
				$crazy_content_disable = 'yes';
			} else {
				$crazy_content_disable = 'no';
			}
			PrestaHelper::update_option( 'crazy_content_disable', $crazy_content_disable );
			Tools::redirectAdmin( $this->context->link->getAdminLink( 'AdminCrazySetting' ) );
		}
		if ( Tools::isSubmit( 'mailchimp_data' ) ) {
			$mailchimp_data = Tools::getValue( 'mailchimp_data' );
			PrestaHelper::update_option( 'mailchimp_data', $mailchimp_data );
			Tools::redirectAdmin( $this->context->link->getAdminLink( 'AdminCrazySetting' ) );
		}
		if ( Tools::isSubmit( 'crazy_clear_cache' ) ) {
			$crazy_clear_cache = Tools::getValue( 'crazy_clear_cache' );
			if ( $crazy_clear_cache ) {
				$this->clear_cache();
				Tools::redirectAdmin( $this->context->link->getAdminLink( 'AdminCrazySetting' ) );
			}
		}
		if ( Tools::isSubmit( 'crazy_old_url' ) && Tools::isSubmit( 'crazy_new_url' ) ) {
			$from = ! empty( Tools::getValue( 'crazy_old_url' ) ) ? Tools::getValue( 'crazy_old_url' ) : '';
			$to   = ! empty( Tools::getValue( 'crazy_new_url' ) ) ? Tools::getValue( 'crazy_new_url' ) : '';
			$this->replace_urls( $from, $to );
			$this->clear_cache();
		}
		parent::initContent();
	}

	public function initHeader() {
		parent::initHeader();
	}

	public function clear_cache() {
		$files = glob( _PS_MODULE_DIR_ . 'crazyelements/assets/css/frontend/css/*' ); // get all file names
		foreach ( $files as $file ) { // iterate files
			if ( is_file( $file ) ) {
				unlink( $file ); // delete file
			}
		}
		Db::getInstance()->delete( 'crazy_options', "option_name like '_elementor_css%'  OR option_name ='_elementor_global_css'" );
		Db::getInstance()->delete( 'crazy_options', "option_name ='elementor_remote_info_library'" );
		$admincontroller = Tools::getValue( 'controller' );
		$token           = Tools::getValue( 'token' );
		Configuration::updateValue( 'crazy_clear_cache', 0 );
	}

	public function replace_urls( $from, $to ) {
		if ( $from === $to ) {
			throw new \Exception( PrestaHelper::__( 'The `from` and `to` URL\'s must be different', 'elementor' ) );
		}
		$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
		if ( ! $is_valid_urls ) {
			throw new \Exception( PrestaHelper::__( 'The `from` and `to` URL\'s must be valid URL\'s', 'elementor' ) );
		}
		Db::getInstance()->update(
			'crazy_content_lang',
			array(
				'resource' => array(
					'type'  => 'sql',
					'value' => "REPLACE(`resource`, '" . str_replace(
						'/',
						'\\\/',
						$from
					) . "','" . str_replace(
						'/',
						'\\\/',
						$to
					) . "')",
				),
			),
			"`resource` LIKE '[%' "
		);
	}
}