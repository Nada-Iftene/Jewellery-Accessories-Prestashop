<?php

if (!defined('_PS_VERSION_')) {
	exit;
}

require_once _PS_MODULE_DIR_ . 'crazyelements/PrestaHelper.php';
require_once dirname(__FILE__) . '/classes/PseImageType.php';
require_once CRAZY_PATH . 'includes/plugin.php';
require_once CRAZY_PATH . 'classes/PseFonts.php';
require_once CRAZY_PATH . 'classes/CrazyContent.php';
require_once CRAZY_PATH . 'classes/CrazyUpdater.php';

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use CrazyElements\Plugin;
use CrazyElements\PrestaHelper;

class CrazyElements extends Module
{

	public function __construct()
	{
		$this->name          = CRAZY_MODULE_ABS_NAME;
		$this->tab           = 'administration';
		$this->version       = CRAZY_VERSION;
		$this->author        = 'ClassyDevs';
		$this->need_instance = 0;

		$this->bootstrap        = true;
		$this->displayName      = $this->l('Crazyelements Page builder');
		$this->description      = $this->l('An elementor based page builder for PrestaShop with remarkable features and super functionality which helps you create amazing websites, themes, pages, designs, sections at lowest time recorded.');
		$this->confirmUninstall = $this->l('Uninstall the module?');
		parent::__construct();		
	}

	public static function dataProcessing($request = null, $perform_action, $id_crazy_content = null)
	{

		$AdminCrazyContent = new AdminCrazyContent();
		switch ($perform_action) {
			case 'save_builder':
				return $AdminCrazyContent->save_builder($request);
				break;
			case 'get_elements_data':
				return $AdminCrazyContent->get_elements_data($id_crazy_content);
				break;
			default:
				echo '';
		}
	}


	public function install()
	{
		$langs    = Language::getLanguages();
		$tabvalue = array(
			array(
				'class_name' => 'AdminCrazyMain',
				'id_parent'  => '',
				'module'     => 'crazyelements',
				'name'       => 'Crazy Elements',
			),
		);
		foreach ($tabvalue as $tab) {
			$newtab             = new Tab();
			$newtab->class_name = $tab['class_name'];
			$newtab->module     = $tab['module'];
			$newtab->id_parent  = $tab['id_parent'];
			foreach ($langs as $l) {
				$newtab->name[$l['id_lang']] = $this->l($tab['name']);
			}
			$newtab->add(true, false);
		}
		$tabvalue = array();
		include_once dirname(__FILE__) . '/sql/install_tab.php';
		foreach ($tabvalue as $tab) {
			$newtab             = new Tab();
			$newtab->class_name = $tab['class_name'];
			$newtab->module     = $tab['module'];
			$newtab->id_parent  = $tab['id_parent'];
			foreach ($langs as $l) {
				$newtab->name[$l['id_lang']] = $this->l($tab['name']);
			}
			$newtab->add(true, false);
			if (isset($tab['icon'])) {
				Db::getInstance()->execute(' UPDATE `' . _DB_PREFIX_ . 'tab` SET `icon` = "' . $tab['icon'] . '" WHERE `id_tab` = "' . (int) $newtab->id . '"');
			}
		}
		$id_parent = Tab::getIdFromClassName('AdminCrazyEditor');
		$langs     = Language::getLanguages();
		$tabvalue  = array(
			array(
				'class_name' => 'AdminCrazyContent',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Content Any Where',
				'active'     => 1,
			),
			array(
				'class_name' => 'AdminCrazyPages',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Pages (cms)',
				'active'     => 1,
			),
			array(
				'class_name' => 'AdminCrazyProducts',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Products Description',
				'active'     => 1,
			),
			array(
				'class_name' => 'AdminCrazyCategories',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Categories Page (Pro)',
				'active'     => 1,
			),
			array(
				'class_name' => 'AdminCrazySuppliers',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Suppliers Page',
				'active'     => 1,
			),
			array(
				'class_name' => 'AdminCrazyBrands',
				'id_parent'  => $id_parent,
				'module'     => 'crazyelements',
				'name'       => 'Brands Page',
				'active'     => 1,
			),
		);
		foreach ($tabvalue as $tab) {
			$newtab             = new Tab();
			$newtab->class_name = $tab['class_name'];
			$newtab->module     = $tab['module'];
			$newtab->id_parent  = $tab['id_parent'];
			foreach ($langs as $l) {
				$newtab->name[$l['id_lang']] = $this->l($tab['name']);
			}
			$newtab->add(true, false);
		}
		include_once dirname(__FILE__) . '/sql/install_tables.php';
		$this->SetDefaults();

		return parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('displayDashboardTop') &&
			$this->registerHook('backOfficeHeader') &&
			$this->registerHook('backOfficeFooter') &&
			$this->registerHook('actionCrazyBeforeInit') &&
			$this->registerHook('actionCrazyAddCategory') &&
			$this->registerHook('actionObjectAddAfter') &&
			$this->registerHook('actionObjectUpdateAfter') &&
			$this->registerHook('actionCmsPageFormBuilderModifier') &&
			$this->registerHook('overrideLayoutTemplate');
	}

	public function getContent(){
		Tools::redirectAdmin(PrestaHelper::get_setting_page_url());
	}

	public function hookActionCrazyAddCategory($params)
	{
	}

	public function hookActionCrazyBeforeInit($params)
	{
	}

	public function hookActionObjectAddAfter($params)
	{
		if (Tools::getValue('controller') == 'AdminCrazyContent') {
			if (isset($params['object']->hook)) {
				$this->registerHook($params['object']->hook);
				$id_crazy_content = $params['object']->id;
				$id_shop          = $this->context->shop->id;
				$table_name       = _DB_PREFIX_ . 'crazy_content_shop';
				$shop_result      = Db::getInstance()->executeS("SELECT * FROM $table_name WHERE id_shop = " . $id_shop . ' AND id_crazy_content=' . $id_crazy_content);
				if (empty($shop_result)) {
					Db::getInstance()->insert(
						'crazy_content_shop',
						array(
							'id_crazy_content' => $id_crazy_content,
							'id_shop'          => $id_shop,
						)
					);
				}
			}
		}
	}

	public function hookActionCmsPageFormBuilderModifier(array $params)
    {
		// Canvas_issue
		$layout_types = array(
			'Default' => 'default',
			'Crazy Canvas Layout' => 'crazy_canvas',
			'Crazy Fullwidth Layout' => 'crazy_fullwidth'
		); 
        $formBuilder = $params['form_builder'];
        $formBuilder->add('crazy_page_layout', ChoiceType::class, [
			'choices' => $layout_types,
			'attr' => [
				'data-toggle' => 'select2',
				'data-minimumResultsForSearch' => '7',
			],
			'label' => 'Select Page Layout (Crazyelements PRO)',
			'help' => 'Get Crazyelements Pro from https://classydevs.com/prestashop-page-builder/pricing/'
		]);
        $params['data']['crazy_page_layout'] = "Default";
        $formBuilder->setData($params['data']);
    }

	public function hookDisplayDashboardTop()
	{
		PrestaHelper::crazy_promo();
		$api_options = array(
			'version'    => $this->version,
			'item_id'    => '38390',
			'item_title' => $this->displayName,
			'item_name'  => $this->name,
			'author'     => $this->author,
		);
		new CrazyUpdater(CRAZY__FILE__, $api_options);
	}

	public function hookDashboardTop()
	{
		return $this->hookDisplayDashboardTop();
	}
	public function hookActionObjectUpdateAfter($params)
	{
		if (Tools::getValue('controller') == 'AdminCrazyContent') {
			if (isset($params['object']->hook)) {
				$this->registerHook($params['object']->hook);
			}
		}
	}
	public function uninstall()
	{
		if (parent::uninstall()) {
			include dirname(__FILE__) . '/sql/uninstall_tables.php';
			return true;
		}
	}

	public function hookOverrideLayoutTemplate($params)
	{
		$token = Tools::getValue('token');
		if (!$token) {
			$crazy_content_disable = PrestaHelper::get_option('crazy_content_disable', 'no');
			if ($crazy_content_disable == 'yes') {
				return;
			}
		}

		$controller                   = Tools::getValue('controller');
		PrestaHelper::$id_lang_global = $this->context->language->id;
		PrestaHelper::$id_shop_global = $this->context->shop->id;
		$id_lang                      = Tools::getValue('id_lang', $this->context->language->id);

		$id_lang = pSQL($id_lang);
		switch ($controller) {
			case 'cms':
				PrestaHelper::$hook_current = $controller;
				if (isset($this->context->smarty->tpl_vars['cms']->value['id'])) {
					$id_cms                                  = $this->context->smarty->tpl_vars['cms']->value['id'];
					PrestaHelper::$id_content_global         = $id_cms;
					PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_cms, $controller);
					PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
					$content                                 = &$this->context->smarty->tpl_vars['cms']->value['content'];
					$body_classes = $this->context->smarty->tpl_vars['page']->value['body_classes'];
					$this->context->smarty->tpl_vars['page']->value['body_classes'] = PrestaHelper::crazy_body_classes($body_classes);

				}
				if (!empty($id_cms)) {
					$results         = array();
					$table_name      = _DB_PREFIX_ . 'crazy_content';
					$table_name_lang = _DB_PREFIX_ . 'crazy_content_lang';
					$results         = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'cms' AND id_content_type = " . $id_cms);
					if (empty($results)) {
						$element_data = null;
						ob_start();
						Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
						$output = ob_get_contents();
						ob_end_clean();
					} else {
						$element_data = $results[0]['resource'];
						ob_start();
						Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
						$output = ob_get_contents();
						ob_end_clean();
						$content = $output;
					}
				}
				if (Tools::getValue('disable') == 'true') {
					$pageContent         = new CMS($id_cms);
					$pageContent->active = 0;
					$pageContent->save();
				}
				break;
			case 'product':
				PrestaHelper::$hook_current = $controller;
				if (isset($this->context->smarty->tpl_vars['product']->value['id'])) {
					$output                                  = '';
					$id_product                              = $this->context->smarty->tpl_vars['product']->value['id'];
					PrestaHelper::$id_content_global         = $id_product;
					PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_product, $controller);
					PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
					$product_var                             = $this->context->smarty->tpl_vars['product'];
					$product_var_place                       = &$this->context->smarty->tpl_vars['product'];
					if (!empty($id_product)) {
						$results         = array();
						$table_name      = _DB_PREFIX_ . 'crazy_content';
						$table_name_lang = _DB_PREFIX_ . 'crazy_content_lang';
						$results         = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'product' AND id_content_type = " . $id_product);
						if (empty($results)) {
							$element_data = null;
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
						} else {
							$element_data = $results[0]['resource'];
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
							$product_var->value['description'] = $output;
						}

						$product_var_place                 = $product_var;
						if (Tools::getValue('disable') == 'true') {
							$pageContent         = new Product($id_product);
							$pageContent->active = 0;
							$pageContent->save();
						}
					}
				}
				break;
			case 'category':
				break;
			case 'manufacturer':
				PrestaHelper::$hook_current = $controller;
				if (isset($this->context->smarty->tpl_vars['manufacturer']->value['id'])) {
					$output                 = '';
					$id_manufacturer        = $this->context->smarty->tpl_vars['manufacturer']->value['id'];
					$manufacturer_var       = $this->context->smarty->tpl_vars['manufacturer'];
					$manufacturer_var_place = &$this->context->smarty->tpl_vars['manufacturer'];
					if (!empty($id_manufacturer)) {
						$results                                 = array();
						PrestaHelper::$id_content_global         = $id_manufacturer;
						PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_manufacturer, $controller);
						PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
						$table_name                              = _DB_PREFIX_ . 'crazy_content';
						$table_name_lang                         = _DB_PREFIX_ . 'crazy_content_lang';
						$results                                 = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'manufacturer' AND id_content_type = " . $id_manufacturer);
						$body_classes = $this->context->smarty->tpl_vars['page']->value['body_classes'];
						$this->context->smarty->tpl_vars['page']->value['body_classes'] = PrestaHelper::crazy_body_classes($body_classes);
						if (empty($results)) {
							$element_data = null;
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
						} else {
							$element_data = $results[0]['resource'];
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
						}
						$manufacturer_var->value['description'] = $output;
						$manufacturer_var_place                 = $manufacturer_var;
						if (Tools::getValue('disable') == 'true') {
							$pageContent         = new Manufacturer($id_manufacturer);
							$pageContent->active = 0;
							$pageContent->save();
						}
					}
				}
				break;
			case 'supplier':
				PrestaHelper::$hook_current = $controller;
				// This is a default error for suppliers page. if there is not error this will not show.

				if (isset($this->context->smarty->tpl_vars['supplier']->value['id'])) {
					$output             = '';
					$id_supplier        = $this->context->smarty->tpl_vars['supplier']->value['id'];
					$supplier_var       = $this->context->smarty->tpl_vars['supplier'];
					$supplier_var_place = &$this->context->smarty->tpl_vars['supplier'];
					if (!empty($id_supplier)) {
						$results                                 = array();
						PrestaHelper::$id_content_global         = $id_supplier;
						PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_supplier, $controller);
						PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
						$table_name                              = _DB_PREFIX_ . 'crazy_content';
						$table_name_lang                         = _DB_PREFIX_ . 'crazy_content_lang';
						$body_classes = $this->context->smarty->tpl_vars['page']->value['body_classes'];
						$this->context->smarty->tpl_vars['page']->value['body_classes'] = PrestaHelper::crazy_body_classes($body_classes);
						$results                                 = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'supplier' AND id_content_type = " . $id_supplier);
						if (empty($results)) {
							$element_data = null;
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
						} else {
							$element_data = $results[0]['resource'];
							ob_start();
							Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
							$output = ob_get_contents();
							ob_end_clean();
						}
						$supplier_var->value['description'] = $output;
						$supplier_var_place                 = $supplier_var;
						if (Tools::getValue('disable') == 'true') {
							$pageContent         = new Supplier($id_supplier);
							$pageContent->active = 0;
							$pageContent->save();
						}
					}
				}
				break;
			default:
				$isExtended = PrestaHelper::check_extended_frontcontroller($controller);
				if ($isExtended) {
					PrestaHelper::$hook_current = $controller;
					// This is a default error for suppliers page. if there is not error this will not show.

					if (isset($this->context->smarty->tpl_vars['post']->value['content'])) {
						$output             = '';
						$id_supplier        = $this->context->smarty->tpl_vars['post']->value['id_post'];
						$supplier_var       = $this->context->smarty->tpl_vars['post'];
						$supplier_var_place = &$this->context->smarty->tpl_vars['post'];
						if (!empty($id_supplier)) {
							$results                                 = array();
							PrestaHelper::$id_content_global         = $id_supplier;
							PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_supplier, $controller);
							PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
							$table_name                              = _DB_PREFIX_ . 'crazy_content';
							$table_name_lang                         = _DB_PREFIX_ . 'crazy_content_lang';
							$results                                 = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'extended' AND id_content_type = " . $id_supplier);
							if (empty($results)) {
								$element_data = null;
								ob_start();
								Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
								$output = $this->context->smarty->tpl_vars['post']->value['content'];
								ob_end_clean();
							} else {
								$element_data = $results[0]['resource'];
								ob_start();
								Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
								$output = ob_get_contents();
								ob_end_clean();
							}
							$supplier_var->value['content'] = $output;
							$supplier_var_place                 = $supplier_var;
							if (Tools::getValue('disable') == 'true') {
								$pageContent         = new Supplier($id_supplier);
								$pageContent->active = 0;
								$pageContent->save();
							}
						}
					}
				} else {
					if($controller == 'index'){
						$body_classes = $this->context->smarty->tpl_vars['page']->value['body_classes'];
						$this->context->smarty->tpl_vars['page']->value['body_classes'] = PrestaHelper::crazy_body_classes($body_classes);
					}
					echo '';
				}
				break;
		}
	}

	public function hookdisplayCMSDisputeInformation()
	{
		$controller                   = Tools::getValue('controller');
		PrestaHelper::$id_lang_global = $this->context->language->id;
		PrestaHelper::$id_shop_global = $this->context->shop->id;
		$id_lang                      = Tools::getValue('id_lang', $this->context->language->id);

		$id_lang = pSQL($id_lang);
		if (isset($this->context->smarty->tpl_vars['cms']->value['id'])) {
			$id_cms                                  = $this->context->smarty->tpl_vars['cms']->value['id'];
			PrestaHelper::$id_content_global         = $id_cms;
			PrestaHelper::$id_content_primary_global = PrestaHelper::getRealPostId($id_cms, $controller);
			PrestaHelper::$id_editor_global          = PrestaHelper::$id_content_primary_global;
			$content                                 = &$this->context->smarty->tpl_vars['cms']->value['content'];
		}
		if (!empty($id_cms)) {
			$results         = array();
			$table_name      = _DB_PREFIX_ . 'crazy_content';
			$table_name_lang = _DB_PREFIX_ . 'crazy_content_lang';
			$results         = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_lang WHERE $table_name.id_crazy_content = $table_name_lang.id_crazy_content AND id_lang =$id_lang AND hook = 'cms' AND id_content_type = " . $id_cms);
			if (empty($results)) {
				$element_data = null;
				ob_start();
				Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
				$output = ob_get_contents();
				ob_end_clean();
				$content = $output;
			} else {
				$element_data = $results[0]['resource'];
				ob_start();
				Plugin::instance()->loadElementsForTemplate(json_decode($element_data, true));
				$output = ob_get_contents();
				ob_end_clean();
				$content = $output;
			}
		}
		echo $content;
	}

	public function __call($hookName, $arguments = array())
	{
		$token = Tools::getValue('token');
		if (!$token) {
			$crazy_content_disable = PrestaHelper::get_option('crazy_content_disable', 'no');
			if ($crazy_content_disable == 'yes') {
				return;
			}
		}


		PrestaHelper::$id_lang_global = $this->context->language->id;
		PrestaHelper::$id_shop_global = $this->context->shop->id;
		if (strpos($hookName, 'hook') !== false) {
			$hook_actual_name           = str_replace('hook', '', $hookName);

			PrestaHelper::$hook_current = $hook_actual_name;
			$post_id                    = \Tools::getValue('id');  // Must not be deleted. this is for checking if its an editor.
			$id_lang                    = Tools::getValue('id_lang', $this->context->language->id);

			$table_name      = _DB_PREFIX_ . 'crazy_content';
			$table_name_shop = _DB_PREFIX_ . 'crazy_content_shop';
			$table_name_lang = _DB_PREFIX_ . 'crazy_content_lang';
			$content         = '';
			if (PrestaHelper::isHookType()) {
				PrestaHelper::$id_editor_global = $post_id;
			}
			$results_frontend = Db::getInstance()->executeS("SELECT * FROM $table_name,$table_name_shop WHERE hook = '" . $hook_actual_name . "' AND $table_name.id_crazy_content = 
			$table_name_shop.id_crazy_content AND $table_name_shop.id_shop = " . PrestaHelper::$id_shop_global);

			if (empty($results_frontend)) {
				PrestaHelper::SetCurrentError('Content is not enabled for this shop');
			}
			foreach ($results_frontend as $result) {
				PrestaHelper::$id_content_global         = $result['id_crazy_content'];
				PrestaHelper::$id_content_primary_global = $result['id_crazy_content'];
				ob_start();
				Plugin::instance()->loadElements($result['id_crazy_content']);
				$output = ob_get_contents();
				if ($result['active'] == 0 && $post_id == false) {
					$output = '';
				}
				ob_end_clean();
				$content .= $output;
			}
			return $content;
		}
	}


	public static function getAdminId()
	{

		$controller     = Tools::getValue('controller');
		$actual_link    = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$symphony_style = false;
		if (strpos($actual_link, '/edit') !== false) {
			$symphony_style = true;
		};
		$id_admin = 0;

		switch ($controller) {
			case 'AdminCategories':
				break;
			case 'AdminProducts':
				if (!Tools::getValue('id_product') && $symphony_style) {
					$link_array = explode('products/', $actual_link);
					$link_array = explode('/edit', $link_array[1]);
					$id_admin   = (int) $link_array[0];
				} elseif (!Tools::getValue('id_product') && !$symphony_style) {
					$link_array   = explode('products/', $actual_link);
					$link_array   = explode('?_token', $link_array[1]);
					$id_admin     = (int) $link_array[0];
					$checkProduct = new Product($id_admin);
					if ($checkProduct->state == '0') {
						$id_admin = 0;
					}
				} else {
					$id_admin = Tools::getValue('id_product ', 0);
				}
				break;
			case 'AdminManufacturers':
				if (!Tools::getValue('id_manufacturer') && $symphony_style) {
					$link_array = explode('brands/', $actual_link);
					$link_array = explode('/edit', $link_array[1]);
					$id_admin   = (int) $link_array[0];
				} else {
					$id_admin = Tools::getValue('id_manufacturer', 0);
				}
				break;
			case 'AdminSuppliers':
				if (!Tools::getValue('id_supplier') && $symphony_style) {
					$link_array = explode('supplier/', $actual_link);
					$link_array = explode('/edit', $link_array[1]);
					$id_admin   = (int) $link_array[0];
				} else {
					$id_admin = Tools::getValue('id_supplier', 0);
				}

				break;
			case 'AdminCmsContent':
				if (!Tools::getValue('id_cms') && $symphony_style) {
					$link_array = explode('cms-pages/', $actual_link);
					$link_array = explode('/edit', $link_array[1]);
					$id_admin   = (int) $link_array[0];
				} else {
					$id_admin = Tools::getValue('id_cms', 0);
				}
				break;
		}
		return $id_admin;
	}

	public function hookDisplayBackOfficeHeader()
	{

		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/button.css');
		$this->context->controller->addJS(CRAZY_ASSETS_URL . 'js/update_crazy.js');
		$currentController   = Tools::getValue('controller');
		$DONT_EDIT           = 'true';
		$ALLOW_PRESTA_EDITOR = PrestaHelper::get_option('presta_editor_enable', 'no');
		if (
			$currentController == 'AdminCmsContent'
			|| $currentController == 'AdminCategories'
			|| $currentController == 'AdminProducts'
			|| $currentController == 'AdminManufacturers'
			|| $currentController == 'AdminSuppliers'
			|| $currentController == 'AdminCrazyContent'
		) {
			$this->context->controller->addJS(CRAZY_ASSETS_URL . 'js/button.js');
			$id = self::getAdminId();
			if ($id != 0) {
				$DONT_EDIT = 'false';
			}
			switch ($currentController) {
				case 'AdminCategories':
					break;
				case 'AdminProducts':
					$hook = 'product';
					break;
				case 'AdminManufacturers':
					$hook = 'manufacturer';
					break;
				case 'AdminSuppliers':
					$hook = 'supplier';
					break;
				case 'AdminCmsContent':
					$hook = 'cms';
					break;
				default:
					$hook = 'index';
					$id      = (int) Tools::getValue('id_crazy_content');
					$context = Context::getContext();
					$shop_id = $context->shop->id;
					$id_lang = '';
					if (isset($_REQUEST['id_lang'])) {
						$id_lang = $_REQUEST['id_lang'];
					}
					$AdminCrazyContent = new AdminCrazyContent($id, $id_lang, $shop_id);
					$hook              = $AdminCrazyContent->hook;
					if (Tools::getValue('id_crazy_content')) {
						$DONT_EDIT = 'false';
					}
					break;
			}
			$this->context->smarty->assign(
				array(
					'proper_href'  => $this->context->link->getAdminLink('AdminCrazyFrontendEditor') . '&hook=' . $hook . '&id=' . $id . '&id_lang=', // id_lang will be empty because it will be set dynamically
					'_PS_VERSION_' => _PS_VERSION_,
					'icon_url'     => CRAZY_ASSETS_URL . 'images/logo-icon.svg',
				)
			);
			$DONT_EDIT_MESSAGE = 'Please save first to edit with Crazyelements';
			Media::addJsDef(
				array(
					'IS_CUSTOM' => 'false',
					'DONT_EDIT'           => $DONT_EDIT,
					'DONT_EDIT_MESSAGE'   => $DONT_EDIT_MESSAGE,
					'ALLOW_PRESTA_EDITOR' => $ALLOW_PRESTA_EDITOR,
				)
			);
			return $this->context->smarty->fetch(CRAZY_PATH . 'views/templates/front/button.tpl');
		} else {
			$isExtended = $this->check_extended_module($currentController);
			if ($isExtended) {
				if (Tools::getValue($isExtended['extended_item_key'])) {
					$this->context->controller->addJS(CRAZY_ASSETS_URL . 'js/button.js');
					$id = Tools::getValue($isExtended['extended_item_key']);
					if ($id != 0) {
						$DONT_EDIT = 'false';
					}
					$hook = 'extended';
					$ext_comtroller = $isExtended['controller_name'];
					$fr_controller = $isExtended['front_controller_name'];
					$mod_name = $isExtended['module_name'];
					$ext_field_name = $isExtended['field_name'];
					$ext_class_name = $this->context->controller->className;
					$this->context->smarty->assign(
						array(
							'proper_href'  => $this->context->link->getAdminLink('AdminCrazyFrontendEditor') . '&hook=' . $hook . '&ext_controller=' . $ext_comtroller . '&fr_controller=' . $fr_controller . '&mod_name=' . $mod_name  . '&ext_class_name=' . $ext_class_name . '&id=' . $id . '&id_lang=', // id_lang will be empty because it will be set dynamically
							'_PS_VERSION_' => _PS_VERSION_,
							'icon_url'     => CRAZY_ASSETS_URL . 'images/logo-icon.svg',
						)
					);
					$DONT_EDIT_MESSAGE = 'Please save first to edit with Crazyelements';
					Media::addJsDef(
						array(
							'IS_CUSTOM' => 'true',
							'FIELD_NAME' => $isExtended['field_name'],
							'DONT_EDIT'           => $DONT_EDIT,
							'DONT_EDIT_MESSAGE'   => $DONT_EDIT_MESSAGE,
						)
					);
					return $this->context->smarty->fetch(CRAZY_PATH . 'views/templates/front/button.tpl');
				}
			}
		}
	}


	public function check_extended_module($controller)
	{
		$id_lang   		= Tools::getValue('id_lang', $this->context->language->id);
		$table_name  	= _DB_PREFIX_ . 'crazy_extended_modules';
		$havetable     	= Db::getInstance()->executeS( "SHOW TABLES LIKE '{$table_name}'" );
		if(empty($havetable)){
			return false;
		}
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('crazy_extended_modules', 'c');
		$sql->where('c.controller_name = "' . $controller . '"');
		$result = Db::getInstance()->executeS($sql);

		if (isset($result) && !empty($result)) {
			return $result[0];
		}
		return false;
	}

	public function hookBackOfficeHeader()
	{
		return $this->hookDisplayBackOfficeHeader();
	}

	public function loadCss()
	{
		$post_id                    = \Tools::getValue('id');
        if($post_id != false){
            $this->context->controller->addCSS( CRAZY_PATH . 'assets/css/widgetonload/products_skin.css' );
			$this->context->controller->addCSS( _THEME_CSS_DIR_ . 'product.css' );
			$this->context->controller->addCSS( _THEME_CSS_DIR_ . 'product_list.css' );
			$this->context->controller->addCSS( _THEME_CSS_DIR_ . 'print.css', 'print' );
			$this->context->controller->addJqueryPlugin( array( 'fancybox', 'idTabs', 'scrollTo', 'serialScroll', 'bxslider' ) );
			$this->context->controller->addJqueryUI(array('ui.spinner'));
			$this->context->controller->addJS(
				array(
					_THEME_JS_DIR_ . 'tools.js',
				)
			);
        }  
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/frontend/css/global.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'lib/ceicons/css/ce-icons.min.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'lib/animations/animations.min.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/animate.min.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/morphext.css');

		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/frontend.min.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'lib/e-select2/css/e-select2.min.css');
		$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'css/editor-preview.min.css');
		$this->context->controller->registerStylesheet('crazy-font-awesome', 'modules/crazyelements/assets/lib/font-awesome/css/font-awesome.min.css', ['media' => 'all', 'priority' => 10]);
		$this->context->controller->registerStylesheet('crazy-fontawesome', 'modules/crazyelements/assets/lib/font-awesome/css/fontawesome.min.css', ['media' => 'all', 'priority' => 10]);
		$this->context->controller->registerStylesheet('crazy-regular', 'modules/crazyelements/assets/lib/font-awesome/css/regular.min.css', ['media' => 'all', 'priority' => 10]);
		$this->context->controller->registerStylesheet('crazy-solid', 'modules/crazyelements/assets/lib/font-awesome/css/solid.min.css', ['media' => 'all', 'priority' => 10]);
		$this->context->controller->registerStylesheet('crazy-brand', 'modules/crazyelements/assets/lib/font-awesome/css/brands.min.css', ['media' => 'all', 'priority' => 10]);
		$fontsoption = PrestaHelper::get_option('custom_icon_upload_fonts');
		$fontsoption = \Tools::jsonDecode($fontsoption, true);
		$returnicons = array();
		if (!empty($fontsoption)) {
			foreach ($fontsoption as $key => $font) {
				if (file_exists($font['maindir'] . 'fontarray.json')) {
					$this->context->controller->addCSS($font['mainurl'] . 'style.css');
				}
			}
		}
		$customfont = PseFonts::get_data_font();
		foreach ($customfont as $key => $fontData) {
			$this->context->controller->addCSS(CRAZY_ASSETS_URL . 'fonts/' . $fontData['fontname'] . '/' . $fontData['fontname'] . '.css');
		}
	}

	public function loadJs()
	{
		$token     = \Tools::getValue('token');
		$edit      = false;
		$wpPreview = true;
		if ($token) {
			$edit = true;
		}
		$empty_object = (object) array();
		Media::addJsDef(
			array(
				'elementorFrontendConfig' => array(
					'environmentMode' => array(
						'edit'      => $edit,
						'wpPreview' => true,
					),
					'is_rtl'          => '',
					'breakpoints'     => array(
						'lg'  => '1025',
						'md'  => '768',
						'sm'  => '480',
						'xl'  => '1440',
						'xs'  => '0',
						'xxl' => '1600',
					),
					'version'         => CRAZY_VERSION,
					'urls'            => array(
						'assets' => CRAZY_ASSETS_URL,
					),
					'page'            => array(),
					'general'         => array(
						'elementor_global_image_lightbox' => 'yes',
						'elementor_enable_lightbox_in_editor' => 'yes',
					),
					'general'         => array(
						'id'      => '0',
						'title'   => 'yes',
						'excerpt' => 'yes',
					),
					'test45'          => array(
						'id'      => '0',
						'title'   => 'yes',
						'excerpt' => 'yes',
					),
					'elements'        => array(
						'data'         => $empty_object,
						'editSettings' => $empty_object,
						'keys'         => $empty_object,
					),
				),
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements1',
			'modules/' . $this->name . '/assets/lib/slick/slick.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements2',
			'modules/' . $this->name . '/assets/lib/jquery-numerator/jquery-numerator.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements3',
			'modules/' . $this->name . '/assets/js/frontend-modules.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements4',
			'modules/' . $this->name . '/assets/lib/inline-editor/js/inline-editor.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements5',
			'modules/' . $this->name . '/assets/lib/dialog/dialog.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements6',
			'modules/' . $this->name . '/assets/lib/waypoints/waypoints.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);

		$this->context->controller->registerJavascript(
			'modules-crazyelements7',
			'modules/' . $this->name . '/assets/lib/swiper/swiper.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);

		$this->context->controller->registerJavascript(
			'modules-crazyelementsmorph',
			'modules/' . $this->name . '/assets/js/morphext.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);

		$this->context->controller->registerJavascript(
			'modules-crazyelementstyped',
			'modules/' . $this->name . '/assets/js/typed.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);

		$this->context->controller->registerJavascript(
			'modules-crazyelementsmorph',
			'modules/' . $this->name . '/assets/js/morphext.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);

		$this->context->controller->registerJavascript(
			'modules-crazyelementstyped',
			'modules/' . $this->name . '/assets/js/typed.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-crazyelements8',
			'modules/' . $this->name . '/assets/js/frontend.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-countdown',
			'modules/' . $this->name . '/assets/js/jquery.counteverest.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
		$this->context->controller->registerJavascript(
			'modules-tooltipstar',
			'modules/' . $this->name . '/assets/js/tooltipster.main.min.js',
			array(
				'position' => 'bottom',
				'priority' => 100,
			)
		);
	}
	public function hookDisplayHeader()
	{
		$this->loadCss();
		$this->loadJs();
	}

	public function hookHeader()
	{
		return $this->hookDisplayHeader();
	}
}