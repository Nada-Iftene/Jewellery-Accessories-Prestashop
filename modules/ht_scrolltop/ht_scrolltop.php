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

class Ht_Scrolltop extends Module
{

    public function __construct()
    {
        $this->name = 'ht_scrolltop';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Hiddentechies';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Hiddentechies Scroll To Top');
        $this->description = $this->l('This module allows the visitor to easily scroll back to the top of the page, with fully customizable options and image.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        Configuration::updateValue('SCROLL_TEXT_OR_ICON', 'scroll_text');
        Configuration::updateValue('SCROLL_TEXT_VALUE', 'Top');
        Configuration::updateValue('SCROLL_ICON_VALUE', 'fa-chevron-up');
        Configuration::updateValue('SCROLL_POSITION', 'scroll_right');
        Configuration::updateValue('SCROLL_SHAPE', 'scroll_square');
        Configuration::updateValue('SCROLL_TEXT_COLOR', '#ffffff');
        Configuration::updateValue('SCROLL_TEXT_HOVER_COLOR', '#ffffff');
        Configuration::updateValue('SCROLL_BACKGROUND_COLOR', '#333333');
        Configuration::updateValue('SCROLL_BACKGROUND_HOVER_COLOR', '#666666');
        Configuration::updateValue('SCROLL_BORDER_COLOR', '#333333');
        Configuration::updateValue('SCROLL_BORDER_HOVER_COLOR', '#666666');
        Configuration::updateValue('SCROLL_TEXT_SIZE', '13px');

        return parent::install() &&
                $this->registerHook('displayFooter') &&
                $this->registerHook('header');
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
                !Configuration::deleteByName('SCROLL_TEXT_OR_ICON') ||
                !Configuration::deleteByName('SCROLL_TEXT_VALUE') ||
                !Configuration::deleteByName('SCROLL_ICON_VALUE') ||
                !Configuration::deleteByName('SCROLL_POSITION') ||
                !Configuration::deleteByName('SCROLL_SHAPE') ||
                !Configuration::deleteByName('SCROLL_TEXT_COLOR') ||
                !Configuration::deleteByName('SCROLL_TEXT_HOVER_COLOR') ||
                !Configuration::deleteByName('SCROLL_BACKGROUND_COLOR') ||
                !Configuration::deleteByName('SCROLL_BACKGROUND_HOVER_COLOR') ||
                !Configuration::deleteByName('SCROLL_BORDER_COLOR') ||
                !Configuration::deleteByName('SCROLL_BORDER_HOVER_COLOR') ||
                !Configuration::deleteByName('SCROLL_TEXT_SIZE')
        )
            return false;

        return true;
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            $scrollTextIcon = (string) Tools::getValue('SCROLL_TEXT_OR_ICON');
            $textValue = (string) Tools::getValue('SCROLL_TEXT_VALUE');
            $iconValue = (string) Tools::getValue('SCROLL_ICON_VALUE');
            $position = (string) Tools::getValue('SCROLL_POSITION');
            $shape = (string) Tools::getValue('SCROLL_SHAPE');
            $textColor = (string) Tools::getValue('SCROLL_TEXT_COLOR');
            $textHoverColor = (string) Tools::getValue('SCROLL_TEXT_HOVER_COLOR');
            $backColor = (string) Tools::getValue('SCROLL_BACKGROUND_COLOR');
            $backHoverColor = (string) Tools::getValue('SCROLL_BACKGROUND_HOVER_COLOR');
            $borderColor = (string) Tools::getValue('SCROLL_BORDER_COLOR');
            $borderHoverColor = (string) Tools::getValue('SCROLL_BORDER_HOVER_COLOR');
            $textSize = (string) Tools::getValue('SCROLL_TEXT_SIZE');

            if (!$scrollTextIcon || empty($scrollTextIcon)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('SCROLL_TEXT_OR_ICON', $scrollTextIcon);

                Configuration::updateValue('SCROLL_TEXT_VALUE', $textValue);
                Configuration::updateValue('SCROLL_ICON_VALUE', $iconValue);
                Configuration::updateValue('SCROLL_POSITION', $position);
                Configuration::updateValue('SCROLL_SHAPE', $shape);
                Configuration::updateValue('SCROLL_TEXT_COLOR', $textColor);
                Configuration::updateValue('SCROLL_TEXT_HOVER_COLOR', $textHoverColor);
                Configuration::updateValue('SCROLL_BACKGROUND_COLOR', $backColor);
                Configuration::updateValue('SCROLL_BACKGROUND_HOVER_COLOR', $backHoverColor);
                Configuration::updateValue('SCROLL_BORDER_COLOR', $borderColor);
                Configuration::updateValue('SCROLL_BORDER_HOVER_COLOR', $borderHoverColor);
                Configuration::updateValue('SCROLL_TEXT_SIZE', $textSize);

                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
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
                            'Text Or Icon ?', array(), 'Modules.Scrolltop.Admin'
                    ),
                    'name' => 'SCROLL_TEXT_OR_ICON',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'scroll_text',
                                'name' => $this->trans(
                                        'Text', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                            array(
                                'id' => 'scroll_icon',
                                'name' => $this->trans(
                                        'Icon', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text Value'),
                    'name' => 'SCROLL_TEXT_VALUE',
                    'size' => 20,
                    'required' => false
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Icon Value'),
                    'name' => 'SCROLL_ICON_VALUE',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('Use Fontawesome icon class. i.e fa-chevron-up')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans(
                            'Position', array(), 'Modules.Scrolltop.Admin'
                    ),
                    'name' => 'SCROLL_POSITION',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'scroll_right',
                                'name' => $this->trans(
                                        'Right', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                            array(
                                'id' => 'scroll_left',
                                'name' => $this->trans(
                                        'Left', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans(
                            'Shape', array(), 'Modules.Scrolltop.Admin'
                    ),
                    'name' => 'SCROLL_SHAPE',
                    'required' => true,
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'scroll_square',
                                'name' => $this->trans(
                                        'Square', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                            array(
                                'id' => 'scroll_round',
                                'name' => $this->trans(
                                        'Round', array(), 'Modules.Scrolltop.Admin'
                                ),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text/Icon Color'),
                    'name' => 'SCROLL_TEXT_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #ffffff')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text/Icon Hover Color'),
                    'name' => 'SCROLL_TEXT_HOVER_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #ffffff')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Background Color'),
                    'name' => 'SCROLL_BACKGROUND_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #333333')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Background Hover Color'),
                    'name' => 'SCROLL_BACKGROUND_HOVER_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #666666')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Border Color'),
                    'name' => 'SCROLL_BORDER_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #333333')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Border Hover Color'),
                    'name' => 'SCROLL_BORDER_HOVER_COLOR',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e #666666')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Text Size'),
                    'name' => 'SCROLL_TEXT_SIZE',
                    'size' => 20,
                    'required' => false,
                    'hint' => $this->l('i.e 13px')
                ),
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
        $helper->fields_value['SCROLL_TEXT_OR_ICON'] = Configuration::get('SCROLL_TEXT_OR_ICON');
        $helper->fields_value['SCROLL_TEXT_VALUE'] = Configuration::get('SCROLL_TEXT_VALUE');
        $helper->fields_value['SCROLL_ICON_VALUE'] = Configuration::get('SCROLL_ICON_VALUE');
        $helper->fields_value['SCROLL_POSITION'] = Configuration::get('SCROLL_POSITION');
        $helper->fields_value['SCROLL_SHAPE'] = Configuration::get('SCROLL_SHAPE');
        $helper->fields_value['SCROLL_TEXT_COLOR'] = Configuration::get('SCROLL_TEXT_COLOR');
        $helper->fields_value['SCROLL_TEXT_HOVER_COLOR'] = Configuration::get('SCROLL_TEXT_HOVER_COLOR');
        $helper->fields_value['SCROLL_BACKGROUND_COLOR'] = Configuration::get('SCROLL_BACKGROUND_COLOR');
        $helper->fields_value['SCROLL_BACKGROUND_HOVER_COLOR'] = Configuration::get('SCROLL_BACKGROUND_HOVER_COLOR');
        $helper->fields_value['SCROLL_BORDER_COLOR'] = Configuration::get('SCROLL_BORDER_COLOR');
        $helper->fields_value['SCROLL_BORDER_HOVER_COLOR'] = Configuration::get('SCROLL_BORDER_HOVER_COLOR');
        $helper->fields_value['SCROLL_TEXT_SIZE'] = Configuration::get('SCROLL_TEXT_SIZE');

        return $helper->generateForm($fields_form);
    }

    public function hookDisplayFooter($params)
    {
        $this->context->smarty->assign(
                array(
                    'scroll_text_icon' => Configuration::get('SCROLL_TEXT_OR_ICON'),
                    'scroll_text_value' => Configuration::get('SCROLL_TEXT_VALUE'),
                    'scroll_icon_value' => Configuration::get('SCROLL_ICON_VALUE'),
                    'scroll_position' => Configuration::get('SCROLL_POSITION'),
                    'scroll_shape' => Configuration::get('SCROLL_SHAPE'),
                    'scroll_text_color' => Configuration::get('SCROLL_TEXT_COLOR'),
                    'scroll_text_hover_color' => Configuration::get('SCROLL_TEXT_HOVER_COLOR'),
                    'scroll_back_color' => Configuration::get('SCROLL_BACKGROUND_COLOR'),
                    'scroll_back_hover_color' => Configuration::get('SCROLL_BACKGROUND_HOVER_COLOR'),
                    'scroll_border_color' => Configuration::get('SCROLL_BORDER_COLOR'),
                    'scroll_border_hover_color' => Configuration::get('SCROLL_BORDER_HOVER_COLOR'),
                    'scroll_text_size' => Configuration::get('SCROLL_TEXT_SIZE'),
                )
        );
        return $this->display(__FILE__, 'scrolltop.tpl');
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/font-awesome.min.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/scrolltop.js', 'all');
    }

}
