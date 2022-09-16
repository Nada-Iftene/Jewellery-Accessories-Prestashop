<?php

include_once(dirname(__FILE__) . '/classes/StaticMainClass.php');
include_once(dirname(__FILE__) . '/sql/SampleBlocks.php');

class Ht_Staticblocks extends Module {

    protected $error = false;
    private $_html;
    private $myHook = array(
        'displayNav1',
        'displayHomeTop1',
        'displayHomeTop2',
        'displayHomeTop3',
        'displayHome',
        'displayHomeBottom1',
        'displayHomeBottom2',
        'displayHomeBottom3',
        'displayFooter',
    );

    public function __construct() {
        $this->name = 'ht_staticblocks';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'Hiddentechies';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Hiddentechies Static Blocks');
        $this->description = $this->l('Adds Static Blocks to Any Hook');
        $this->confirmUninstall = $this->l('Are you sure that you want to delete your Static Blocks?');
    }

    public function install() {
        if (parent::install() == false || !$this->registerHook('header') || !$this->registerHook('actionShopDataDuplication') || !$this->registerHook('actionObjectLanguageAddAfter'))
            return false;
        
        include(dirname(__FILE__) . '/sql/install.php');
        $sample_data = new SampleBlocks();
        $base_url = _PS_BASE_URL_ . __PS_BASE_URI__;
        $sample_data->initData($base_url);
        return true;
    }

    public function uninstall() {
        include(dirname(__FILE__) . '/sql/uninstall.php');
        if (parent::uninstall() == false)
            return false;
        return true;
    }

    public function getContent() {
        if (Tools::isSubmit('changeStatus') || Tools::isSubmit('delete_cat_block')) {
            $this->_postProcess();
            $this->_html .= $this->renderList();
        } elseif (Tools::isSubmit('addblock') || Tools::isSubmit('id_ht_staticblocks') || Tools::isSubmit('saveBlock')) {
            $this->_postProcess();
            $this->renderAddForm();
        } else {
            $this->_postProcess();
            $this->_html .= $this->renderList();
        }
        /* $this->_displayAdvertising(); */
        return $this->_html;
    }

    private function _postProcess() {
        $errors = array();
        if (Tools::isSubmit('saveBlock')) {
            $block = new StaticMainClass(Tools::getValue('id_ht_staticblocks'));
            $block->copyFromPost();
            $errors = $block->validateController();
            if (count($errors))
                $this->_html .= $this->displayError(implode('<br />', $errors));
            else {
                Tools::getValue('id_ht_staticblocks') ? $block->update() : $block->add();
                $this->clearCacheBlock(Tools::getValue('hook'));
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&saveBlockConfirmation');
            }
        } elseif (Tools::isSubmit('changeStatus') && Tools::getValue('id_ht_staticblocks')) {
            $block = new StaticMainClass(Tools::getValue('id_ht_staticblocks'));
            if ($block->active == 0)
                $block->active = 1;
            else
                $block->active = 0;
            $res = $block->update();
            $this->clearCacheBlock(Tools::getValue('hook'));
            $this->html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        }
        elseif (Tools::isSubmit('delete_id_block')) {
            $block = new StaticMainClass(Tools::getValue('delete_id_block'));
            $block->delete();
            $this->clearCacheBlock(Tools::getValue('hook'));
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&deleteBlockConfirmation');
        } elseif (Tools::isSubmit('saveBlockConfirmation'))
            $this->_html = $this->displayConfirmation($this->l('Static block has been saved successfully'));
        elseif (Tools::isSubmit('deleteBlockConfirmation'))
            $this->_html = $this->displayConfirmation($this->l('Static block deleted successfully'));
    }

    private function clearCacheBlock($hook) {
        $this->_clearCache('ht_staticblocks_' . Tools::strtolower($this->getHookName($hook)) . '.tpl');
    }

    private function getHookName($id_hook, $name = false) {
        if (!$result = Db::getInstance()->getRow('
		SELECT `name`,`title`
		FROM `' . _DB_PREFIX_ . 'hook` 
		WHERE `id_hook` = ' . (int) ($id_hook)))
            return false;
        return $result['name'];
    }

    private function getBlocks() {
        $this->context = Context::getContext();
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
        if (!$result = Db::getInstance()->ExecuteS(
                'SELECT b.id_ht_staticblocks, b.hook, bs.active, bl.`title`, bl.`content` 
			FROM `' . _DB_PREFIX_ . 'ht_staticblocks` b 
			LEFT JOIN `' . _DB_PREFIX_ . 'ht_staticblocks_shop` bs ON (b.`id_ht_staticblocks` = bs.`id_ht_staticblocks` )
			LEFT JOIN `' . _DB_PREFIX_ . 'ht_staticblocks_lang` bl ON (b.`id_ht_staticblocks` = bl.`id_ht_staticblocks`' . ( $id_shop ? 'AND bl.`id_shop` = ' . $id_shop : ' ' ) . ') 
			WHERE bl.id_lang = ' . (int) $id_lang .
                ( $id_shop ? ' AND bs.`id_shop` = ' . $id_shop : ' ' )))
            return false;
        return $result;
    }

    public function getHookTitle($id_hook, $name = false) {
        if (!$result = Db::getInstance()->getRow('
			SELECT `name`,`title`
			FROM `' . _DB_PREFIX_ . 'hook` 
			WHERE `id_hook` = ' . (int) ($id_hook)))
            return false;
        return (($result['title'] != '' && $name) ? $result['title'] : $result['name']);
    }

    public function renderList() {
        $blocks = array();
        if ($this->getBlocks() && count($this->getBlocks()) > 0) {
            $blocks = $this->getBlocks();
        }
        $this->context->smarty->assign(
                array(
                    'link' => $this->context->link,
                    'blocks' => $blocks
                )
        );
        return $this->display(__FILE__, 'views/templates/admin/list.tpl');
    }

    public function getHookList() {
        $hooks = array();

        foreach ($this->myHook as $key => $hook) {
            $hooks[$key]['key'] = $hook;
            $hooks[$key]['name'] = $hook;
        }
        return $hooks;
    }

    public function renderAddForm() {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $hooks = $this->getHookList();
        $this->fields_form[0]['form'] = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Block item'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'lang' => true,
                    'name' => 'title',
                    'cols' => 40,
                    'rows' => 10
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook'),
                    'name' => 'hook',
                    'options' => array(
                        'query' => $hooks,
                        'id' => 'key',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('HTML'),
                    'name' => 'content',
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 40,
                    'rows' => 10
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'ht_staticblocks';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->show_cancel_button = true;
        $helper->back_url = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'saveBlock';
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' =>
            array(
                'href' => $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $id_ht_staticblocks = Tools::getValue('id_ht_staticblocks');
        if (Tools::isSubmit('id_ht_staticblocks') && $id_ht_staticblocks) {
            $block = new StaticMainClass((int) $id_ht_staticblocks);
            $this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_ht_staticblocks');
            $helper->fields_value['id_ht_staticblocks'] = (int) Tools::getValue('id_ht_staticblocks', $block->id_ht_staticblocks);
            $helper->fields_value['active'] = Tools::getValue('active', $block->active);
            $helper->fields_value['hook'] = Tools::getValue('hook', $block->hook);
        } else {
            $block = new StaticMainClass();
            $helper->fields_value['active'] = Tools::getValue('active', 1);
            $helper->fields_value['hook'] = Tools::getValue('hook', 9);
        }
        foreach (Language::getLanguages(false) as $lang) {
            $helper->fields_value['title'][(int) $lang['id_lang']] = Tools::getValue('title_' . (int) $lang['id_lang'], $block->title[(int) $lang['id_lang']]);
            $helper->fields_value['content'][(int) $lang['id_lang']] = Tools::getValue('content_' . (int) $lang['id_lang'], $block->content[(int) $lang['id_lang']]);
        }

        $this->_html .= $helper->generateForm($this->fields_form);
    }

    private function getBlockFromHook($hook_name) {
        $block_list = array();
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        $results = Db::getInstance()->ExecuteS('SELECT b.`id_ht_staticblocks`, bl.`content` FROM `' . _DB_PREFIX_ . 'ht_staticblocks` b
		LEFT JOIN `' . _DB_PREFIX_ . 'ht_staticblocks_shop` bs ON (b.id_ht_staticblocks = bs.id_ht_staticblocks)
		LEFT JOIN `' . _DB_PREFIX_ . 'ht_staticblocks_lang` bl ON (bl.id_ht_staticblocks = bs.id_ht_staticblocks)
		WHERE bs.active = 1 AND bl.id_lang = ' . (int) $id_lang . ' AND (bs.id_shop = ' . (int) $id_shop . ') AND b.`hook` = "' . $hook_name . '"');

        //$block_list = [];
        foreach ($results as $result) {
            $temp = str_replace('{static_block_url}', _PS_BASE_URL_ . __PS_BASE_URI__, $result['content']);
            $block_list[$result['id_ht_staticblocks']]['content'] = $temp;
        }
        return $block_list;
    }

    public function hookdisplayHome() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_home.tpl', $this->getCacheId('ht_staticblocks_home'))) {
            $block_list = $this->getBlockFromHook('displayHome');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_home.tpl', $this->getCacheId('ht_staticblocks_home'));
    }

    public function hookdisplayNav1() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/displayNav1.tpl', $this->getCacheId('displayNav1'))) {
            $block_list = $this->getBlockFromHook('displayNav1');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'displayNav1.tpl', $this->getCacheId('displayNav1'));
    }
    public function hookdisplayFooter() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_footer.tpl', $this->getCacheId('ht_staticblocks_footer'))) {
            $block_list = $this->getBlockFromHook('displayFooter');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_footer.tpl', $this->getCacheId('ht_staticblocks_footer'));
    }

    public function hookdisplayHomeTop1() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_hometop1.tpl', $this->getCacheId('ht_staticblocks_hometop1'))) {
            $block_list = $this->getBlockFromHook('displayHomeTop1');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_hometop1.tpl', $this->getCacheId('ht_staticblocks_hometop1'));
    }

    public function hookdisplayHomeTop2() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_hometop2.tpl', $this->getCacheId('ht_staticblocks_hometop2'))) {
            $block_list = $this->getBlockFromHook('displayHomeTop2');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_hometop2.tpl', $this->getCacheId('ht_staticblocks_hometop2'));
    }

    public function hookdisplayHomeTop3() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_hometop3.tpl', $this->getCacheId('ht_staticblocks_hometop3'))) {
            $block_list = $this->getBlockFromHook('displayHomeTop3');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_hometop3.tpl', $this->getCacheId('ht_staticblocks_hometop3'));
    }

    public function hookdisplayHomeBottom1() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_homebottom1.tpl', $this->getCacheId('ht_staticblocks_homebottom1'))) {
            $block_list = $this->getBlockFromHook('displayHomeBottom1');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_homebottom1.tpl', $this->getCacheId('ht_staticblocks_homebottom1'));
    }

    public function hookdisplayHomeBottom2() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_homebottom2.tpl', $this->getCacheId('ht_staticblocks_homebottom2'))) {
            $block_list = $this->getBlockFromHook('displayHomeBottom2');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_homebottom2.tpl', $this->getCacheId('ht_staticblocks_homebottom2'));
    }

    public function hookdisplayHomeBottom3() {
        if (!$this->isCached('module:ht_staticblocks/views/templates/hook/ht_staticblocks_homebottom3.tpl', $this->getCacheId('ht_staticblocks_homebottom3'))) {
            $block_list = $this->getBlockFromHook('displayHomeBottom3');
            $this->context->smarty->assign(array(
                'block_list' => $block_list,
                'page_name' => $this->context->controller->php_self
            ));
        }
        return $this->display(__FILE__, 'ht_staticblocks_homebottom3.tpl', $this->getCacheId('ht_staticblocks_homebottom3'));
    }

    public function hookHeader($params) {
        $this->context->controller->addCSS($this->_path . 'views/css/ht_staticblocks.css');
    }

    public function hookActionShopDataDuplication($params) {
        Db::getInstance()->execute('
		INSERT IGNORE INTO ' . _DB_PREFIX_ . 'ht_staticblocks_shop (id_ht_staticblocks, id_shop, active)
		SELECT id_ht_staticblocks, ' . (int) $params['new_id_shop'] . ', active
		FROM ' . _DB_PREFIX_ . 'ht_staticblocks_shop
		WHERE id_shop = ' . (int) $params['old_id_shop']);

        Db::getInstance()->execute('
		INSERT IGNORE INTO ' . _DB_PREFIX_ . 'ht_staticblocks_lang (id_ht_staticblocks, id_lang, id_shop, title, content)
		SELECT id_ht_staticblocks, id_lang, ' . (int) $params['new_id_shop'] . ', title, content
		FROM ' . _DB_PREFIX_ . 'ht_staticblocks_lang
		WHERE id_shop = ' . (int) $params['old_id_shop']);
    }

    public function hookActionObjectLanguageAddAfter($params) {
        Db::getInstance()->execute('
		INSERT IGNORE INTO ' . _DB_PREFIX_ . 'ht_staticblocks_lang (id_ht_staticblocks, id_lang, id_shop, title, content)
		SELECT id_ht_staticblocks, ' . (int) $params['object']->id . ', id_shop, title, content
		FROM ' . _DB_PREFIX_ . 'ht_staticblocks_lang
		WHERE id_lang = ' . (int) Configuration::get('PS_LANG_DEFAULT'));
    }

}

?>
