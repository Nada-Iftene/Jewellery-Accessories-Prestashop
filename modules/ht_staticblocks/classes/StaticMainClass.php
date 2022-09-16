<?php

/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2018 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
class StaticMainClass extends ObjectModel {

    public $id_ht_staticblocks;
    public $title;
    public $content;
    public $active;
    public $hook;
    public $temp_url = '{static_block_url}';
    public static $definition = array(
        'table' => 'ht_staticblocks',
        'primary' => 'id_ht_staticblocks',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => array(
            'hook' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
            'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
            'content' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString', 'required' => true),
            'active' => array('type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt'),
        )
    );

    public function __construct($id_tab = null, $id_lang = null, $id_shop = null, Context $context = null) {
        parent::__construct($id_tab, $id_lang, $id_shop);
        Shop::addTableAssociation('ht_staticblocks', array('type' => 'shop'));
        Shop::addTableAssociation('ht_staticblocks_lang', array('type' => 'fk_shop'));
        if ($this->id) {
            $this->active = $this->getFieldShop('active');
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                if (isset($this->content[(int) ($language['id_lang'])]) && !empty($this->content[(int) ($language['id_lang'])])) {
                    $temp = str_replace($this->temp_url, _PS_BASE_URL_ . __PS_BASE_URI__, $this->content[(int) ($language['id_lang'])]);
                    $this->content[(int) ($language['id_lang'])] = $temp;
                }
            }
        }
    }

    public function getFieldShop($field) {
        $id_shop = (int) Context::getContext()->shop->id;
        $sql = 'SELECT bs.' . $field . ' FROM ' . _DB_PREFIX_ . 'ht_staticblocks b
		LEFT JOIN ' . _DB_PREFIX_ . 'ht_staticblocks_shop bs ON (b.id_ht_staticblocks = bs.id_ht_staticblocks)
		WHERE b.id_ht_staticblocks = ' . $this->id . ' AND bs.id_shop = ' . $id_shop . '';
        $result = Db::getInstance()->getValue($sql);
        return $result;
    }

    public function copyFromPost() {
        $this->hook = Tools::getValue('hook');
        $this->active = Tools::getValue('active');

        if (count($this->fieldsValidateLang)) {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language)
                foreach ($this->fieldsValidateLang as $field => $validation) {
                    $temp = Tools::getValue($field . '_' . (int) ($language['id_lang']));
                    if (isset($temp)) {
                        //$temp = str_replace(_PS_BASE_URL_.__PS_BASE_URI__, $this->temp_url, $temp);
                        $this->{$field}[(int) ($language['id_lang'])] = $temp;
                    }
                }
        }
    }

    public function validateController($htmlentities = true, $copy_post = false) {
        $errors = array();
        $defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
        $field = 'title';
        if (!$this->{$field} || !count($this->{$field}) || ($this->{$field}[$defaultLanguage] !== '0' && empty($this->{$field}[$defaultLanguage])))
            $errors[] = '<b>Title</b> ' . Tools::displayError('is empty for default language.');
        $validate = new Validate();
        foreach ($this->fieldsValidateLang as $fieldArray => $method) {
            if (!is_array($this->{$fieldArray}))
                continue;
            foreach ($this->{$fieldArray} as $k => $value)
                if (!method_exists($validate, $method))
                    die(Tools::displayError('Validation function not found.') . ' ' . Tools::safeOutput($method));
                elseif (!empty($value) && !call_user_func(array('Validate', $method), $value))
                    $errors[] = Tools::displayError('The following field is invalid according to the validate method ') . '<b>' . $method . '</b>:<br/> (' . self::displayFieldName($fieldArray, get_class($this), $htmlentities) . ' = ' . $value . ' ' . Tools::displayError('for language') . ' ' . $k;
        }
        return $errors;
    }

}

?>
