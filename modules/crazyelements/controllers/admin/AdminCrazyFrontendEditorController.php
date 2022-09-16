<?php

defined( '_PS_VERSION_' ) or exit;
require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
require_once _PS_MODULE_DIR_ . 'crazyelements/classes/CrazyContent.php';
use CrazyElements\PrestaHelper;
use CrazyElements\Plugin;

class AdminCrazyFrontendEditorController extends ModuleAdminController {

	public $display_header = true;
	public $content_only   = true;
	public $display_footer = false;
	public function __construct() {
		parent::__construct();
	}
	public function initContent() {
		parent::initContent();
		PrestaHelper::get_lience_expired_date();
		$this->assignData();
		$this->activateTemporary();
		$this->assignVCReqTplVars();

		PrestaHelper::$current_url = $this->get_current_url();
		
		Plugin::instance()->initForEditor();
		die();
	}
	private function get_current_url(){
		$hook    = \Tools::getValue( 'hook' );
		$id      = \Tools::getValue( 'id' );
		$exit_to_dashboard = "";
		switch ( $hook ) {
			case 'cms':
				PrestaHelper::$current_url = Context::getContext()->link->getAdminLink('AdminCmsContent', true,[], ['id_cms' => $id, 'updatecms' => true]);
				break;
			case 'product':
				$exit_to_dashboard = Context::getContext()->link->getAdminLink('AdminProducts', true,['id_product' => $id, 'updateproduct' => true]);
				break;
			case 'category':
				$exit_to_dashboard = Context::getContext()->link->getAdminLink('AdminCategories', true,[], ['id_category' => $id, 'updatecategory' => true]);
				break;
			case 'supplier':
				$exit_to_dashboard = Context::getContext()->link->getAdminLink('AdminSuppliers', true,[], ['id_supplier' => $id, 'updatesupplier' => true]);
				break;
			case 'manufacturer':
				$exit_to_dashboard = Context::getContext()->link->getAdminLink('AdminManufacturers', true,[], ['id_manufacturer' => $id, 'updatemanufacturer' => true]);
				break;
			case 'prdlayouts':
				$exit_to_dashboard = Context::getContext()->link->getAdminLink('AdminCrazyPrdlayouts', true,[], ['id_crazyprdlayouts' => $id, 'updatecrazyprdlayouts' => true]);
				break;
			default:
				break;	
		}
		return $exit_to_dashboard;
	}
	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
	}

	public function assignData() {
		PrestaHelper::$hook_current      = Tools::getValue( 'hook' );
		PrestaHelper::$id_lang_global    = Tools::getValue( 'id_lang' );
		PrestaHelper::$id_shop_global    = $this->context->shop->id;
		PrestaHelper::$id_content_global = Tools::getValue( 'id' );
		$id = PrestaHelper::getRealPostId( Tools::getValue( 'id' ) );
		

		if ( $id == null ) {
			$id_content_type                    = Tools::getValue( 'id' );
			$type                               = PrestaHelper::$hook_current;
			$table_name                         = _DB_PREFIX_ . 'crazy_content';
			$AdminCrazyContent                  = new AdminCrazyContent();
			$AdminCrazyContent->id_content_type = $id_content_type;
			$AdminCrazyContent->active          = 1;
			$AdminCrazyContent->hook            = Tools::getValue( 'hook' );
			$langs                              = Language::getLanguages();

			foreach ( $langs as $l ) {
				$pageContent = array();
				switch ( PrestaHelper::$hook_current ) {
					case 'cms':
						$pageContent = new CMS( PrestaHelper::$id_content_global, $l['id_lang'] );
						$contentData = $pageContent->content;
						$pageTitle   = $pageContent->meta_title;
						break;
					case 'product':
						$pageContent = new Product( PrestaHelper::$id_content_global, false, $l['id_lang'] );
						$contentData = $pageContent->description;
						$pageTitle   = $pageContent->name;
						break;
					case 'category':
						$pageContent = new Category( PrestaHelper::$id_content_global, $l['id_lang'] );
						$contentData = $pageContent->description;
						$pageTitle   = $pageContent->name;
						break;
					case 'supplier':
						$pageContent = new Supplier( PrestaHelper::$id_content_global, $l['id_lang'] );
						$contentData = $pageContent->description;
						$pageTitle   = $pageContent->name;
						break;
					case 'manufacturer':
						$pageContent = new Manufacturer( PrestaHelper::$id_content_global, $l['id_lang'] );
						$contentData = $pageContent->description;
						$pageTitle   = $pageContent->name;
						break;
					case 'extended':
						$pageContent = new SmartBlogPost( PrestaHelper::$id_content_global, $l['id_lang'] );
						$contentData = $pageContent->content;
						$pageTitle   = "Blog Content with Crazy";
						break;
					default:
						Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminDashboard'));
						break;
				}
				$contentData = PrestaHelper::setContent( $contentData );
				$AdminCrazyContent->title[ $l['id_lang'] ]    = $pageTitle;
				$AdminCrazyContent->resource[ $l['id_lang'] ] = json_encode( $contentData );
			}
			$AdminCrazyContent->save();
			$results = Db::getInstance()->executeS( "SELECT * FROM $table_name WHERE id_content_type = " . $id_content_type . " AND hook='$type'" );
			$id      = $results[0]['id_crazy_content'];
		}
        if(Configuration::get('PS_DISABLE_OVERRIDES') =="1"){
            PrestaHelper::SetCurrentError('Override option in your site is disabled. Please enable from performence tab.');
        }elseif(!Module::isEnabled( 'crazyelements' )){
            PrestaHelper::SetCurrentError('Please enable Crazyelements for this shop from the modules page.');
        }elseif(PrestaHelper::$hook_current == 'supplier'){
            PrestaHelper::SetCurrentError('The display of your suppliers is disabled on your store. Go to <strong>Shop Parameters > General</strong> and enable <strong>Display suppliers</strong>');
        }


		PrestaHelper::$id_content_primary_global = $id;

	}

	public function activateTemporary() {
		PrestaHelper::$hook_current      = Tools::getValue( 'hook' );
		PrestaHelper::$id_content_global = Tools::getValue( 'id' );
		switch ( PrestaHelper::$hook_current ) {
			case 'cms':
				$pageContent = new CMS( PrestaHelper::$id_content_global );
				if($pageContent->active == 0){
					$pageContent->active = 1;
					$pageContent->save();
					PrestaHelper::$disable_activity = '&disable=true';
				}
				break;
			case 'product':
				$pageContent = new Product( PrestaHelper::$id_content_global );
				if($pageContent->active == 0){
					$pageContent->active = 1;
					$pageContent->save();
					PrestaHelper::$disable_activity = '&disable=true';
				}
				break;
			case 'category':
				$pageContent = new Category( PrestaHelper::$id_content_global );
				if($pageContent->active == 0){
					$pageContent->active = 1;
					$pageContent->save();
					PrestaHelper::$disable_activity = '&disable=true';
				}
				break;
			case 'supplier':
				$pageContent = new Supplier( PrestaHelper::$id_content_global );
				if($pageContent->active == 0){
					$pageContent->active = 1;
					$pageContent->save();
					PrestaHelper::$disable_activity = '&disable=true';
				}
				break;
			case 'manufacturer':
				$pageContent = new Manufacturer( PrestaHelper::$id_content_global);
				if($pageContent->active == 0){
					$pageContent->active = 1;
					$pageContent->save();
					PrestaHelper::$disable_activity = '&disable=true';
				}
				break;
			default:
				break;
		}
	}
	public function assignVCReqTplVars() {
		$protocol_link       = ( Configuration::get( 'PS_SSL_ENABLED' ) || Tools::usingSecureMode() ) ? 'https://' : 'http://';
		$useSSL              = ( ( isset( $this->ssl ) && $this->ssl && Configuration::get( 'PS_SSL_ENABLED' ) ) || Tools::usingSecureMode() ) ? true : false;
		$protocol_content    = ( $useSSL ) ? 'https://' : 'http://';
		$link                = new Link( $protocol_link, $protocol_content );
		$this->context->link = $link;
		$currency      = Tools::setCurrency( $this->context->cookie );
		$cart          = new Cart( $this->context->cookie->id_cart );
		$languages     = Language::getLanguages( true, $this->context->shop->id );
		$meta_language = array();
		foreach ( $languages as $lang ) {
			   $meta_language[] = $lang['iso_code'];
		}
		$compared_products = array();
		if ( Configuration::get( 'PS_COMPARATOR_MAX_ITEM' ) && isset( $this->context->cookie->id_compare ) ) {
			   $compared_products = CompareProduct::getCompareProducts( $this->context->cookie->id_compare );
		}
		Product::initPricesComputation();
		Context::getContext()->customer = new Customer( 1 );
		$display_tax_label = $this->context->country->display_tax_label;
		if ( isset( $cart->{Configuration::get( 'PS_TAX_ADDRESS_TYPE' )} ) && $cart->{Configuration::get( 'PS_TAX_ADDRESS_TYPE' )} ) {
				$infos                  = Address::getCountryAndState( (int) $cart->{Configuration::get( 'PS_TAX_ADDRESS_TYPE' )} );
				$country                = new Country( (int) $infos['id_country'] );
				$this->context->country = $country;
			if ( Validate::isLoadedObject( $country ) ) {
					$display_tax_label = $country->display_tax_label;
			}
		}
		$this->context->smarty->assign(
			array(
				// Useful for layout.tpl
				'mobile_device'       => $this->context->getMobileDevice(),
				'link'                => $link,
				'cart'                => $cart,
				'currency'            => $currency,
				'currencyRate'        => method_exists( $currency, 'getConversationRate' ) ? (float) $currency->getConversationRate() : null,
				'cookie'              => $this->context->cookie,
				'page_name'           => '',
				'hide_left_column'    => true,
				'hide_right_column'   => true,
				'tabs'                => array(),
				'base_dir'            => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
				'base_dir_ssl'        => $protocol_link . Tools::getShopDomainSsl() . __PS_BASE_URI__,
				'force_ssl'           => Configuration::get( 'PS_SSL_ENABLED' ) && Configuration::get( 'PS_SSL_ENABLED_EVERYWHERE' ),
				'content_dir'         => $protocol_content . Tools::getHttpHost() . __PS_BASE_URI__,
				'base_uri'            => $protocol_content . Tools::getHttpHost() . __PS_BASE_URI__ . ( ! Configuration::get( 'PS_REWRITING_SETTINGS' ) ? 'index.php' : '' ),
				'tpl_dir'             => _PS_THEME_DIR_,
				'tpl_uri'             => _THEME_DIR_,
				'modules_dir'         => _MODULE_DIR_,
				'mail_dir'            => _MAIL_DIR_,
				'lang_iso'            => $this->context->language->iso_code,
				'lang_id'             => (int) $this->context->language->id,
				'language_code'       => $this->context->language->language_code ? $this->context->language->language_code : $this->context->language->iso_code,
				'come_from'           => Tools::getHttpHost( true, true ) . Tools::htmlentitiesUTF8( str_replace( array( '\'', '\\' ), '', urldecode( $_SERVER['REQUEST_URI'] ) ) ),
				'cart_qties'          => (int) $cart->nbProducts(),
				'currencies'          => Currency::getCurrencies(),
				'languages'           => $languages,
				'meta_language'       => implode( ',', $meta_language ),
				'priceDisplay'        => Product::getTaxCalculationMethod( (int) $this->context->cookie->id_customer ),
				'is_logged'           => true,
				'is_guest'            => false,
				'add_prod_display'    => (int) Configuration::get( 'PS_ATTRIBUTE_CATEGORY_DISPLAY' ),
				'shop_name'           => Configuration::get( 'PS_SHOP_NAME' ),
				'roundMode'           => (int) Configuration::get( 'PS_PRICE_ROUND_MODE' ),
				'use_taxes'           => (int) Configuration::get( 'PS_TAX' ),
				'show_taxes'          => (int) ( Configuration::get( 'PS_TAX_DISPLAY' ) == 1 && (int) Configuration::get( 'PS_TAX' ) ),
				'display_tax_label'   => (bool) $display_tax_label,
				'vat_management'      => (int) Configuration::get( 'VATNUMBER_MANAGEMENT' ),
				'opc'                 => (bool) Configuration::get( 'PS_ORDER_PROCESS_TYPE' ),
				'PS_CATALOG_MODE'     => (bool) Configuration::get( 'PS_CATALOG_MODE' ) || ( Group::isFeatureActive() && ! (bool) Group::getCurrent()->show_prices ),
				'b2b_enable'          => (bool) Configuration::get( 'PS_B2B_ENABLE' ),
				'request'             => $link->getPaginationLink( false, false, false, true ),
				'PS_STOCK_MANAGEMENT' => Configuration::get( 'PS_STOCK_MANAGEMENT' ),
				'quick_view'          => (bool) Configuration::get( 'PS_QUICK_VIEW' ),
				'shop_phone'          => Configuration::get( 'PS_SHOP_PHONE' ),
				'compared_products'   => is_array( $compared_products ) ? $compared_products : array(),
				'comparator_max_item' => (int) Configuration::get( 'PS_COMPARATOR_MAX_ITEM' ),
				'currencySign'        => $currency->sign, // backward compat, see global.tpl
				'currencyFormat'      => $currency->format, // backward compat
				'currencyBlank'       => $currency->blank, // backward compat
			)
		);
	}
}