<?php

/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2018 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class Ht_Googleanalytics extends Module {

    public function __construct() {
        $this->name = 'ht_googleanalytics';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Hiddentechies';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Hiddentechies Google Analytics');
        $this->description = $this->l('This module enables you to track your site using the latest Google Analytics tracking code.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install() {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        return parent::install() &&
                $this->registerHook('displayFooter');
    }

    public function uninstall() {
        if (!parent::uninstall() ||
                !Configuration::deleteByName('GA_ENABLE') ||
                !Configuration::deleteByName('GA_GOOGLE_TRACKING_ID')
        )
            return false;

        return true;
    }

    public function getContent() {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $ga_enable = (string) Tools::getValue('GA_ENABLE');
            $ga_google_tracking_id = (string) Tools::getValue('GA_GOOGLE_TRACKING_ID');

            if (!$ga_google_tracking_id || empty($ga_google_tracking_id)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('GA_ENABLE', $ga_enable);
                Configuration::updateValue('GA_GOOGLE_TRACKING_ID', $ga_google_tracking_id);

                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }

        return $output . $proinfo . $this->displayForm();
    }

    public function displayForm() {
        // Get default language
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->trans(
                            'Enable Google Analytics', array(), 'Modules.Googleanalytics.Admin'
                    ),
                    'name' => 'GA_ENABLE',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'ga_yes',
                                'name' => $this->trans(
                                        'Yes', array(), 'Modules.Googleanalytics.Admin'
                                ),
                            ),
                            array(
                                'id' => 'ga_no',
                                'name' => $this->trans(
                                        'No', array(), 'Modules.Googleanalytics.Admin'
                                ),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Google Tracking ID'),
                    'name' => 'GA_GOOGLE_TRACKING_ID',
                    'size' => 20,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['GA_ENABLE'] = Configuration::get('GA_ENABLE');
        $helper->fields_value['GA_GOOGLE_TRACKING_ID'] = Configuration::get('GA_GOOGLE_TRACKING_ID');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayFooter($params) {
        $this->context->smarty->assign(
                array(
                    'ga_enable' => Configuration::get('GA_ENABLE'),
                    'ga_google_tracking_id' => Configuration::get('GA_GOOGLE_TRACKING_ID')
                )
        );
        return $this->display(__FILE__, 'ht_googleanalytics.tpl');
    }

}
