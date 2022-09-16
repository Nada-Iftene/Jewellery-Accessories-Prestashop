<?php
require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;
if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminCrazyCategoriesController extends ModuleAdminController
{
    public function initContent(){
        PrestaHelper::get_lience_expired_date();
        $license_status = PrestaHelper::get_option( 'ce_licence_status', 'invalid' );
        if ($license_status == "valid") {
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminCategories'));
        }else{
            return "<div></div>" . parent::initContent();
        }
    }

    public function renderList()
    {
        $ce_licence = PrestaHelper::get_option('ce_licence', 'false');
        if ($ce_licence == "false") {
            $active_link = PrestaHelper::get_setting_page_url();
            return '<style>.need_to_active {font-size: 20px;color: #495157 !important;font-weight: bold;}.need_to_active_a {font-size: 12px;font-weight: bold;}</style><div class="panel col-lg-12"> <div class="panel-heading"> Need To Active Licence.<span class="badge"></span></div><div class="col-md-12"><div class="need_to_active">Need To Active Licence.</div><a class="need_to_active_a" href="' . $active_link . '">Click For Active.</a></div></div>';
        }
    }
}