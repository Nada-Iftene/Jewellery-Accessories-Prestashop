<?php

require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;

if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminCrazyBrandsController extends ModuleAdminController
{
    public function initContent(){
        PrestaHelper::get_lience_expired_date();
        Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminManufacturers'));
    }
}