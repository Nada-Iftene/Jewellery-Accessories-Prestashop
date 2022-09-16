<?php
require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;

if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminCrazyPagesController extends ModuleAdminController
{
    public function initContent(){
        PrestaHelper::get_lience_expired_date();

        if(_PS_VERSION_ >= "1.7.7.0"){
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminCmsContent'));
        }else{
            Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminCms'));
        }
    }
}
