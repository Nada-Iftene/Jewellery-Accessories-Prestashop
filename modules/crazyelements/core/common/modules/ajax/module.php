<?php
namespace CrazyElements\Core\Common\Modules\Ajax;

use CrazyElements\Core\Base\Module as BaseModule;
use CrazyElements\Core\Utils\Exceptions;
use CrazyElements\Plugin;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}


class Module extends BaseModule {










	const NONCE_KEY = 'elementor_ajax';

	/**
	 * Ajax actions.
	 *
	 * Holds all the register ajax action.
	 *
	 * @since  1.0
	 * @access private
	 *
	 * @var array
	 */
	private $ajax_actions = array();

	/**
	 * Ajax requests.
	 *
	 * Holds all the register ajax requests.
	 *
	 * @since  1.0
	 * @access private
	 *
	 * @var array
	 */
	private $requests = array();

	/**
	 * Ajax response data.
	 *
	 * Holds all the response data for all the ajax requests.
	 *
	 * @since  1.0
	 * @access private
	 *
	 * @var array
	 */
	private $response_data = array();

	/**
	 * Current ajax action ID.
	 *
	 * Holds all the ID for the current ajax action.
	 *
	 * @since  1.0
	 * @access private
	 *
	 * @var string|null
	 */
	private $current_action_id = null;

	/**
	 * Ajax manager constructor.
	 *
	 * Initializing Elementor ajax manager.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function __construct() {
		PrestaHelper::add_action( 'wp_ajax_elementor_ajax', array( $this, 'handle_ajax_request' ) );
		PrestaHelper::add_action( 'wp_ajax_wp-link-ajax', array( $this, 'get_dynamic_link' ) );
		PrestaHelper::add_action( 'wp_ajax_crazy_ajax_get_items', array( $this, 'ajax_get_items' ) );
		PrestaHelper::add_action( 'wp_ajax_ajax_search_func', array( $this, 'funtion_ajax_search' ) );
		PrestaHelper::add_action( 'wp_ajax_ajax_down_func', array( $this, 'funtion_down_file' ) );
		PrestaHelper::add_action( 'wp_ajax_ajax_close_ad', array( $this, 'funtion_close_ad' ) );
	}

	public function get_dynamic_link(){
		$search = $_POST['search'];
		$context      = \Context::getContext();
		$id_lang      = (int) $context->language->id;
		$id_shop     = $context->shop->id;
		$cms_ids = $this->get_cms_by_name($search, $id_lang, $id_shop);
		$results = array();
		foreach($cms_ids as $cms_id){
			$link = $context->link->getCMSLink( $cms_id['id_cms'], null, true, $id_lang );
			$results[] = array(
				'ID'        => $cms_id['id_cms'],
				'title'     => $cms_id['meta_title'],
				'permalink' => $link,
				'info'      => 'cms'
			);
		}
		$prd_ids = $this->get_products_by_name($search, $id_lang, $id_shop);
		foreach($prd_ids as $prd_id){
			$link = $context->link->getProductLink( $prd_id['id_product'], null, null, null, $id_lang );
			$results[] = array(
				'ID'        => $prd_id['id_product'],
				'title'     => $prd_id['name'],
				'permalink' => $link,
				'info'      => 'product'
			);
		}
		$catg_ids = $this->get_categories_by_name($search, $id_lang, $id_shop);

		foreach($catg_ids as $catg_id){
			$link = $context->link->getCategoryLink($catg_id['id_category'], null, $id_lang);
			$results[] = array(
				'ID'        => $catg_id['id_category'],
				'title'     => $catg_id['name'],
				'permalink' => $link,
				'info'      => 'category'
			);
		}
		echo json_encode($results);
		die();

	}

	public function get_categories_by_name($search, $id_lang, $id_shop){
		$sql    = 'SELECT p.`id_category`, pl.`name`
		FROM `' . _DB_PREFIX_ . 'category` p
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` pl ON (p.`id_category` = pl.`id_category` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
		WHERE pl.`id_lang` = ' . (int) $id_lang . '
		 AND pl.`name` LIKE "%' . pSQL( $search ) . '%" LIMIT 5';
		$results = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		return $results;
	}

	public function get_products_by_name($search, $id_lang, $id_shop){
		$front = true;
		$sql  = 'SELECT p.`id_product`, pl.`name`
				FROM `' . _DB_PREFIX_ . 'product` p
				' . \Shop::addSqlAssociation( 'product', 'p' ) . '
				LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
				WHERE pl.`id_lang` = ' . (int) $id_lang . '
				' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND pl.`name` LIKE "%' . pSQL( $search ) . '%" LIMIT 5';
		$results = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		return $results;
	}

	public function get_cms_by_name($search, $id_lang, $id_shop){

		$sql = 'SELECT cl.`meta_title`, cl.`id_cms`
                FROM `' . _DB_PREFIX_ . 'cms_lang` cl
                WHERE cl.`meta_title` LIKE "%' . $search . '%" AND cl.`id_lang` = '.$id_lang.' AND cl.`id_shop` = ' . $id_shop . ' LIMIT 5';

		$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		return $results;
	}

	public function funtion_close_ad(){
		$today           = date( 'Y-m-d' );
		PrestaHelper::update_option( 'ce_ad_closed', $today );
		die();
	}

	public function ajax_get_items() {
		$rs        = array();
		$item_type = \Tools::getValue( 'item_type' );
		if ( $item_type == 'product' ) {
			$rs = $this->getProductsByName();
		} elseif ( $item_type == 'suppliers' ) {
			$rs = $this->getSuppliersByName();
		} elseif ( $item_type == 'category' ) {
			$rs = $this->getCategoriesByName();
		} elseif ( $item_type == 'manufacturer' ) {
			$rs = $this->getManufecturerByName();
		}
		echo json_encode( $rs );
		die();
	}

	public function funtion_down_file() {
		$down_url  = \Tools::getValue( 'down_url' );
		$down_v    = \Tools::getValue( 'down_v' );
		$down_path = _PS_MODULE_DIR_;
		$newfile   = $down_path . '/crazyelements.zip';
		$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $down_url );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, $ua );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 20 );
		$result = curl_exec( $ch );
		file_put_contents( $newfile, $result );
		$last = curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
		if ( curl_errno( $ch ) ) {
			echo 'Update Failed';
		} else {
			$zip = new \ZipArchive();
			if ( $zip->open( $newfile ) === true ) {
				$zip->extractTo( _PS_MODULE_DIR_ );
				$zip->close();
				@unlink( $newfile );
				PrestaHelper::update_option( 'ce_new_v', '' );
				$cookie = new \Cookie( 'check_update' );
				$cookie->setExpire( time() + 60 * 60 * 24 );
				$cookie->check_update = $down_v;
				$cookie->write();
			}
		}
		curl_close( $ch );
		die();
	}

	public function funtion_ajax_search() {
		$type = \Tools::getValue( 'type' );
		if ( $type == 'products' ) {
			$rs = $this->getProductNameImage();
			echo json_encode( $rs );
		} elseif ( $type == 'category' ) {
			$rs = $this->getCategoryNameImage();
			echo json_encode( $rs );
		} elseif ( $type == 'manufacturers' ) {
			$rs = $this->getBrandNameImage();
			echo json_encode( $rs );
		} elseif ( $type == 'suppliers' ) {
			$rs = $this->getSupplierNameImage();
			echo json_encode( $rs );
		}
		die();
	}

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'elementor_ajax';
	}

	/**
	 * Register ajax action.
	 *
	 * Add new actions for a specific ajax request and the callback function to
	 * be handle the response.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param string   $tag      Ajax request name/tag.
	 * @param callable $callback The callback function.
	 */
	public function register_ajax_action( $tag, $callback ) {

		$this->ajax_actions[ $tag ] = compact( 'tag', 'callback' );
	}

	/**
	 * Handle ajax request.
	 *
	 * Verify ajax nonce, and run all the registered actions for this request.
	 *
	 * @since  1.0
	 * @access public
	 */
	public function handle_ajax_request() {
		$editor_post_id = 0;
		if ( ! empty( $_REQUEST['editor_post_id'] ) ) {
			$editor_post_id = (int) $_REQUEST['editor_post_id'];
		}

		/**
		 * Register ajax actions.
		 *
		 * Fires when an ajax request is received and verified.
		 *
		 * Used to register new ajax action handles.
		 *
		 * @since 1.0
		 *
		 * @param self $this An instance of ajax manager.
		 */
		PrestaHelper::do_action( 'elementor/ajax/register_actions', $this );
		$this->requests = \Tools::jsonDecode( $_REQUEST['actions'], true );
		if ( is_array( $this->requests ) ) {
			foreach ( $this->requests as $id => $action_data ) {
				$this->current_action_id = $id;
				if ( ! isset( $this->ajax_actions[ $action_data['action'] ] ) ) {
					$this->add_response_data( false, PrestaHelper::__( 'Action not found.', 'elementor' ), Exceptions::BAD_REQUEST );
					continue;
				}

				if ( $editor_post_id ) {
					$action_data['data']['editor_post_id'] = $editor_post_id;
				}
				try {
					$results = call_user_func( $this->ajax_actions[ $action_data['action'] ]['callback'], $action_data['data'], $this );

					if ( false === $results ) {
						$this->add_response_data( false );
					} else {
						$this->add_response_data( true, $results );
					}
				} catch ( \Exception $e ) {
					$this->add_response_data( false, $e->getMessage(), $e->getCode() );
				}
			}
		}

		$this->current_action_id = null;
		$this->send_success();
	}

	/**
	 * Get current action data.
	 *
	 * Retrieve the data for the current ajax request.
	 *
	 * @since  2.0.1
	 * @access public
	 *
	 * @return bool|mixed Ajax request data if action exist, False otherwise.
	 */
	public function get_current_action_data() {
		if ( ! $this->current_action_id ) {
			return false;
		}
		return $this->requests[ $this->current_action_id ];
	}

	/**
	 * Create nonce.
	 *
	 * Creates a cryptographic token to
	 * give the user an access to Elementor ajax actions.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return string The nonce token.
	 */
	public function create_nonce() {
		return wp_create_nonce( self::NONCE_KEY );
	}

	/**
	 * Verify request nonce.
	 *
	 * Whether the request nonce verified or not.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @return bool True if request nonce verified, False otherwise.
	 */
	public function verify_request_nonce() {
		return ! empty( $_REQUEST['_nonce'] ) && wp_verify_nonce( $_REQUEST['_nonce'], self::NONCE_KEY );
	}

	protected function get_init_settings() {
		return array(
			'url'   => PrestaHelper::getAjaxUrl(),
			'nonce' => '',
		);
	}

	/**
	 * Ajax success response.
	 *
	 * Send a JSON response data back to the ajax request, indicating success.
	 *
	 * @since  1.0
	 * @access protected
	 */
	private function send_success() {
		$response = array(
			'success' => true,
			'data'    => array(
				'responses' => $this->response_data,
			),
		);

		$json = json_encode( $response );

		// Temp removed GZIP support.
		if ( false && function_exists( 'gzencode' ) ) {
			$response = gzencode( $json );

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Encoding: gzip' );
			header( 'Content-Length: ' . strlen( $response ) );

			echo $response;
		} else {
			echo $json;
		}
		die();
	}

	/**
	 * Ajax failure response.
	 *
	 * Send a JSON response data back to the ajax request, indicating failure.
	 *
	 * @since  1.0
	 * @access protected
	 *
	 * @param null $code
	 */
	private function send_error( $code = null ) {
		wp_send_json_error(
			array(
				'responses' => $this->response_data,
			),
			$code
		);
	}

	/**
	 * Add response data.
	 *
	 * Add new response data to the array of all the ajax requests.
	 *
	 * @since  1.0
	 * @access protected
	 *
	 * @param bool  $success True if the requests returned successfully, False
	 *                       otherwise.
	 * @param mixed $data    Optional. Response data. Default is null.
	 *
	 * @param int   $code    Optional. Response code. Default is 200.
	 *
	 * @return Module An instance of ajax manager.
	 */
	private function add_response_data( $success, $data = null, $code = 200 ) {
		$this->response_data[ $this->current_action_id ] = array(
			'success' => $success,
			'code'    => $code,
			'data'    => $data,
		);
		return $this;
	}

	public function getSupplierNameImage() {
		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}
		$result       = array();
		$value_search = \Tools::getValue( 'data' );
		if ( isset( $value_search ) && $value_search != '' ) {

			$sql = 'SELECT p.`id_supplier` as `key`, p.`name`
					FROM `' . _DB_PREFIX_ . 'supplier` p
						' . \Shop::addSqlAssociation( 'supplier', 'p' ) . '                
					WHERE  p.`name` LIKE "%' . pSQL( $value_search ) . '%" AND p.`active` = 1 ' .
			'ORDER BY p.`name`';

			$result    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$resultarr = array();
			$i         = 0;
			$link      = new \Link();
			foreach ( $result as $value ) {
				$image_url                = $link->getSupplierImageLink( (int) $value['key'], 'small_default' );
				$protocol_link            = ( \Configuration::get( 'PS_SSL_ENABLED' ) ) ? 'https://' : 'http://';
				$image_url                = $protocol_link . $image_url;
				$url                      = $link->getSupplierLink( $value['key'], null, $id_lang );
				$resultarr[ $i ]['name']  = $value['name'];
				$resultarr[ $i ]['thumb'] = $image_url;
				$resultarr[ $i ]['link']  = $url;
				$i++;
			}
			return $resultarr;
		}
		return $result;
	}

	public function getBrandNameImage() {
		$context      = \Context::getContext();
		$id_lang      = (int) $context->language->id;
		$result       = array();
		$value_search = \Tools::getValue( 'data' );
		if ( isset( $value_search ) && $value_search != '' ) {
			$sql       = 'SELECT p.`id_manufacturer` as `key`, p.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` p                
                WHERE 
                  p.`name` LIKE "%' . pSQL( $value_search ) . '%" AND p.`active` = 1 ' .
			'ORDER BY p.`name`';
			$result    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$resultarr = array();
			$i         = 0;
			$link      = new \Link();
			foreach ( $result as $value ) {
				$image_url                = $link->getManufacturerImageLink( (int) $value['key'], 'small_default' );
				$protocol_link            = ( \Configuration::get( 'PS_SSL_ENABLED' ) ) ? 'https://' : 'http://';
				$image_url                = $protocol_link . $image_url;
				$url                      = $link->getManufacturerLink( $value['key'], null, $id_lang );
				$resultarr[ $i ]['name']  = $value['name'];
				$resultarr[ $i ]['thumb'] = $image_url;
				$resultarr[ $i ]['link']  = $url;
				$i++;
			}
			return $resultarr;
		}
		return $result;
	}

	public function getCategoryNameImage() {
		$context      = \Context::getContext();
		$id_lang      = (int) $context->language->id;
		$result       = array();
		$value_search = \Tools::getValue( 'data' );
		if ( isset( $value_search ) && $value_search != '' ) {
			$sql       = 'SELECT p.`id_category` as `key`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'category` p
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` pl ON (p.`id_category` = pl.`id_category` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                 AND pl.`name` LIKE "%' . pSQL( $value_search ) . '%" ' .
			'ORDER BY pl.`name`';
			$result    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$resultarr = array();
			$i         = 0;
			$link      = new \Link();
			foreach ( $result as $value ) {
				$category = new \Category( (int) $value['key'] );
				$id_image = $category->id_image;
				if ( $id_image ) {
					$protocol_link = ( \Configuration::get( 'PS_SSL_ENABLED' ) ) ? 'https://' : 'http://';
					$image_url     = $link->getCatImageLink( $category->link_rewrite, $value['key'] );
					$image_url     = $protocol_link . $image_url;
				} else {
					$image_url = '';
				}
				$url                      = $category->getLink();
				$resultarr[ $i ]['name']  = $value['name'];
				$resultarr[ $i ]['thumb'] = $image_url;
				$resultarr[ $i ]['link']  = $url;
				$i++;
			}
			return $resultarr;
		}
		return $result;
	}

	public function getProductNameImage() {

		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}
		$result       = array();
		$value_search = \Tools::getValue( 'data' );
		$value_search = pSQL( $value_search );
		if ( isset( $value_search ) && $value_search != '' ) {
			$current_category = \Tools::getValue( 'is_current' );
			if ( $current_category != 'no' ) {

				if ( $current_category ) {
					$current_category = ' AND cp.`id_category` = ' . $current_category;
				} else {
					$current_category = '';
				}
			} else {
				$current_category = '';
			}

			$sql = 'SELECT p.`id_product` as `key`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                    ON p.`id_product` = cp.`id_product`
                ' . \Shop::addSqlAssociation( 'product', 'p' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND pl.`name` LIKE "%' . pSQL( $value_search ) . '%" ' .
			$current_category . '
			ORDER BY pl.`name`';

			$result    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$resultarr = array();
			$i         = 0;
			foreach ( $result as $value ) {
				$product  = new \Product( (int) $value['key'] );
				$link     = new \Link();
				$id_image = $product->getCover( $value['key'] );
				$url      = $context->link->getProductLink( $value['key'], null, null, null, $id_lang );
				if ( count( $id_image ) > 0 ) {
					$image     = new \Image( $id_image['id_image'] );
					$image_url = _PS_BASE_URL_SSL_ . _THEME_PROD_DIR_ . $image->getExistingImgPath() . '-small_default' . '.jpg';
				}
				$resultarr[ $i ]['name']  = $value['name'];
				$resultarr[ $i ]['thumb'] = $image_url;
				$resultarr[ $i ]['link']  = $url;
				$i++;
			}
			return $resultarr;

		}
		return $result;
	}

	protected function getIdFromTitle($ids) {
		$str='';
		$ids=explode(',',$ids);
		foreach($ids as $id){
			$exp=explode('_',$id);
			$str.=$exp[0].",";
		}
		$str=rtrim($str,",");
		return $str;

	}

	public function getProductsByName() {
		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}
		$result          = array();
		$value_search    = \Tools::getValue( 'data' );
		$selected_values = \Tools::getValue( 'selected_values' );
		$value_search    = pSQL( $value_search );
		$selected_values = $this->getIdFromTitle( $selected_values );
		$selected_values = pSQL( $selected_values );
		if ( isset( $value_search ) && $value_search != '' ) {
			$exSql = '';
			if ( $selected_values != '' ) {
				$exSql .= ' AND p.`id_product` NOT IN(';
				$exSql .= $selected_values;
				$exSql .= ') ';
			}
			$sql    = 'SELECT p.`id_product` as `key`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . \Shop::addSqlAssociation( 'product', 'p' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND pl.`name` LIKE "%' . pSQL( $value_search ) . '%" ' . $exSql .
			'ORDER BY pl.`name`';
			$result = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		}
		$newarray=[];
		foreach($result as $key=> $res){
			$reskey=$res['key']."_".$res['name'];
			$newarray[$key]['key']=$reskey;
			$newarray[$key]['name']=$reskey;
		}

		return $newarray;
	}

	public function getSuppliersByName() {
		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}
		$result          = array();
		$value_search    = \Tools::getValue( 'data' );
		$selected_values = \Tools::getValue( 'selected_values' );
		$selected_values = $this->getIdFromTitle( $selected_values );
		if ( isset( $value_search ) && $value_search != '' ) {
			$exSql = '';
			if ( $selected_values != '' ) {
				$exSql .= ' AND p.`id_supplier` NOT IN(';
				$exSql .= $selected_values;
				$exSql .= ') ';
			}
			$sql    = 'SELECT p.`id_supplier` as `key`, p.`name`
					FROM `' . _DB_PREFIX_ . 'supplier` p
						' . \Shop::addSqlAssociation( 'supplier', 'p' ) . '                
					WHERE 
					  p.`name` LIKE "%' . pSQL( $value_search ) . '%" ' . $exSql .
			'ORDER BY p.`name`';
			$result = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		}
		$newarray=[];
		foreach($result as $key=> $res){
			$reskey=$res['key']."_".$res['name'];
			$newarray[$key]['key']=$reskey;
			$newarray[$key]['name']=$reskey;
		}

		return $newarray;
	}

	public function getCategoriesByName() {
		$context         = \Context::getContext();
		$id_lang         = (int) $context->language->id;
		$result          = array();
		$value_search    = \Tools::getValue( 'data' );
		$selected_values = \Tools::getValue( 'selected_values' );
		if ( isset( $value_search ) && $value_search != '' ) {
			$exSql = '';
			if ( ! empty( $selected_values ) ) {
				$exSql .= ' AND p.`id_category` NOT IN(';
				$exSql .= $selected_values;
				$exSql .= ') ';
			}
			$sql    = 'SELECT p.`id_category` as `key`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'category` p
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` pl ON (p.`id_category` = pl.`id_category` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                 AND pl.`name` LIKE "%' . pSQL( $value_search ) . '%" ' . $exSql .
			'ORDER BY pl.`name`';
			$result = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		}
		$newarray=[];
		foreach($result as $key=> $res){
			$reskey=$res['key']."_".$res['name'];
			$newarray[$key]['key']=$reskey;
			$newarray[$key]['name']=$reskey;
		}

		return $newarray;
	}

	public function getManufecturerByName() {
		$result          = array();
		$value_search    = \Tools::getValue( 'data' );
		$selected_values = \Tools::getValue( 'selected_values' );
		if ( isset( $value_search ) && $value_search != '' ) {
			$exSql = '';
			if ( ! empty( $selected_values ) ) {
				$exSql .= ' AND p.`id_manufacturer` NOT IN(';
				$exSql .= $selected_values;
				$exSql .= ') ';
			}
			$sql    = 'SELECT p.`id_manufacturer` as `key`, p.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` p                
                WHERE 
                  p.`name` LIKE "%' . pSQL( $value_search ) . '%" ' . $exSql .
			'ORDER BY p.`name`';
			$result = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		}
		$newarray=[];
		foreach($result as $key=> $res){
			$reskey=$res['key']."_".$res['name'];
			$newarray[$key]['key']=$reskey;
			$newarray[$key]['name']=$reskey;
		}

		return $newarray;
	}
}