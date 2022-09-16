<?php
require_once dirname( __FILE__ ) . '/../../classes/CrazyContent.php';


require_once dirname( __FILE__ ) . '/../../includes/template-library/classes/class-import-images.php';

require_once _PS_MODULE_DIR_ . 'crazyelements/includes/plugin.php';
use CrazyElements\PrestaHelper;
use CrazyElements\TemplateLibrary\Classes\Import_Images;

class AdminCrazyContentController extends ModuleAdminController {


	public $activeButton = true;
	public function __construct() {
		$this->table        = 'crazy_content';
		$this->className    = 'AdminCrazyContent';
		$this->lang         = true;
		$this->deleted      = false;
		$this->bootstrap    = true;
		$this->module       = 'crazyelements';
		$this->activeButton = PrestaHelper::get_option( 'ce_licence', 'false' );
		if ( Shop::isFeatureActive() ) {
			Shop::addTableAssociation( $this->table, array( 'type' => 'shop' ) );
		}
		parent::__construct();

		$this->_where = 'AND id_content_type = 0 ';

		$this->fields_list  = array(
			'id_crazy_content' => array(
				'title'   => $this->l( 'Id' ),
				'width'   => 100,
				'type'    => 'text',
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'title'            => array(
				'title'   => $this->l( 'Title' ),
				'width'   => 440,
				'type'    => 'text',
				'lang'    => true,
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'hook'             => array(
				'title' => $this->l( 'Hook' ),
				'type'  => 'text',
			),
			'active'           => array(
				'title'   => $this->l( 'Status' ),
				'width'   => '70',
				'align'   => 'center',
				'active'  => 'status',
				'type'    => 'bool',
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
		);
		$this->bulk_actions = array(
			'delete' => array(
				'text'    => $this->l( 'Delete selected' ),
				'icon'    => 'icon-trash',
				'confirm' => $this->l( 'Delete selected items?' ),
			),
		);
		parent::__construct();
	}

	public function initContent() {
		$this->context->controller->addCSS( CRAZY_ASSETS_URL . 'css/select2.min.css' );
		$this->context->controller->addJS( CRAZY_ASSETS_URL . 'js/select2.min.js' );
		$this->context->controller->addJS( CRAZY_ASSETS_URL . 'js/crazy_admin.js' );
		if ( $this->display == 'list' ) {
			$this->display = '';
		}
		if ( isset( $this->display ) && method_exists( $this, 'render' . $this->display ) ) {
			$this->content .= $this->initPageHeaderToolbar();
			$this->content .= $this->{'render' . $this->display}();
			$this->context->smarty->assign(
				array(
					'content'                   => $this->content,
					'show_page_header_toolbar'  => $this->show_page_header_toolbar,
					'page_header_toolbar_title' => $this->page_header_toolbar_title,
					'page_header_toolbar_btn'   => $this->page_header_toolbar_btn,
				)
			);
		} else {
			return parent::initContent();
		}
	}

	public function display() {
		parent::display();
	}

	public function renderForm() {
		$GetAlldisplayHooks = array();
		include_once dirname( __FILE__ ) . '/../../includes/hook.php';
		$this->fields_form = array(
			'legend'  => array(
				'title' => $this->l( 'Content Any Where' ),
			),
			'input'   => array(
				array(
					'type'     => 'text',
					'label'    => $this->l( 'Title' ),
					'name'     => 'title',
					'lang'     => true,
					'required' => true,
					'desc'     => $this->l( 'Enter Your Title' ),
				),
				array(
					'type'     => 'textarea',
					'callback' => 'gohere',
					'label'    => $this->l( 'Content' ),
					'name'     => 'resource',
					'lang'     => true,
				),
				array(
					'type'    => 'select',
					'label'   => $this->l( 'Select Display Hook' ),
					'name'    => 'hook',
					'options' => array(
						'query' => $GetAlldisplayHooks,
						'id'    => 'id',
						'name'  => 'name',
					),
					'desc'    => $this->l( 'Type to Search and Select Your Hook Position Where You Want to Show This Item. "example: hom"' ),
				),
				array(
					'type'     => 'switch',
					'label'    => $this->l( 'Status' ),
					'name'     => 'active',
					'required' => false,
					'class'    => 't',
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active',
							'value' => 1,
							'label' => $this->l( 'Enabled' ),
						),
						array(
							'id'    => 'active',
							'value' => 0,
							'label' => $this->l( 'Disabled' ),
						),
					),
				),
			),
			'submit'  => array(
				'title' => $this->l( 'Save And Close' ),
				'class' => 'btn btn-default pull-right',
			),
			'buttons' => array(
				'save-and-stay' => array(
					'name'  => 'submitAdd' . $this->table . 'AndStay',
					'type'  => 'submit',
					'title' => $this->l( 'Save And Stay' ),
					'class' => 'btn btn-default pull-right',
					'icon'  => 'process-icon-save',
				),
			),
		);

		if ( Shop::isFeatureActive() ) {
			$this->fields_form['input'][] = array(
				'type'  => 'shop',
				'label' => $this->l( 'Shop association:' ),
				'name'  => 'checkBoxShopAsso',
			);
		}

		$sql      = 'SELECT count(`id_crazy_content`) FROM `' . _DB_PREFIX_ . 'crazy_content` WHERE `hook` NOT IN("product","category","cms","supplier","manufacturer")';
		$count_content = DB::getInstance()->getValue( $sql );

		if($count_content > 3){
			$id_cr_cntnt = Tools::getValue('id_crazy_content');
			if(!$id_cr_cntnt){
				$html = "";
				$html.='<div class="panel col-lg-12">
				<div class="font-prev-wrapper" style="text-align: center;">
				<h2>You Need The Pro Version to Add More Than Three Contents to Content Anywhere. <a style="color: blue;" href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_anywhere&utm_medium=crazyfree_module_anywhere&utm_campaign=crazyfree_anywhere&utm_term=crazyfree_anywhere&utm_content=crazyfree_anywhere?utm_source=crazyfree_anywhere&utm_medium=crazyfree_module_anywhere&utm_campaign=crazyfree_anywhere&utm_term=crazyfree_anywhere&utm_content=crazyfree" target="_blank">Get PRO</a></h2><br>
				<a  href="https://classydevs.com/prestashop-page-builder/pricing/?utm_source=crazyfree_anywhere&utm_medium=crazyfree_module_anywhere&utm_campaign=crazyfree_anywhere&utm_term=crazyfree_anywhere&utm_content=crazyfree_anywhere?utm_source=crazyfree_anywhere&utm_medium=crazyfree_module_anywhere&utm_campaign=crazyfree_anywhere&utm_term=crazyfree_anywhere&utm_content=crazyfree" target="_blank"> <img src=" ' . CRAZY_ASSETS_URL . 'images/price_compare.png" width="1200"></a></div></div>';
				return $html;
			}else{
				return parent::renderForm(); 
			}
		}else{
			return parent::renderForm();
		}
	}

	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
	}

	public function renderList() {
		$this->addRowAction( 'edit' );
		$this->addRowAction( 'delete' );
		if ( class_exists( 'ZipArchive' ) ) {
			$this->addRowAction( 'ceexport' );
		} else {
			$this->displayInformation( $this->l( 'Zip Archive Extension Not Enabled.' ) );
		}
		return parent::renderList();
	}

	public function initProcess() {
		if ( Tools::getIsset( 'ceexport' . $this->table ) ) {
			$this->action = 'ceexport';
		}
		if ( ! $this->action ) {
			parent::initProcess();
		} else {
			$this->id_object = (int) Tools::getValue( $this->identifier );
		}
	}

	public function initPageHeaderToolbar() {
		parent::initPageHeaderToolbar();
		if ( class_exists( 'ZipArchive' ) ) {
			if ( ! isset( $this->display ) ) {
				if ( $this->activeButton == 'false' ) {
					return '';
				}
				$this->page_header_toolbar_btn['import_ceimport'] = array(
					'href' => self::$currentIndex . '&action=importcontent&token=' . $this->token,
					'desc' => $this->l( 'Import', null, null, false ),
					'icon' => 'process-icon-import',
				);
			}
		}
	}

	public function renderImportContent() {
		$toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l( 'Save' ),
		);

		$fields_form[0]                = array(
			'form' => array(
				'tinymce' => false,
				'legend'  => array(
					'title' => $this->l( 'Import from your computer' ),
					'icon'  => 'icon-picture',
				),
				'input'   => array(
					array(
						'type'  => 'file',
						'label' => $this->l( 'Zip file' ),
						'desc'  => $this->l( 'Browse your computer files and select the Zip file for your new theme.' ),
						'name'  => 'ceimportbtn',
					),
				),
				'submit'  => array(
					'id'    => 'zip',
					'title' => $this->l( 'Save' ),
				),
			),
		);
		$helper                        = new HelperForm();
		$helper->currentIndex          = $this->context->link->getAdminLink( 'AdminCrazyContent', false ) . '&action=importcontent';
		$helper->token                 = Tools::getAdminTokenLite( 'AdminCrazyContent' );
		$helper->show_toolbar          = true;
		$helper->toolbar_btn           = $toolbar_btn;
		$helper->multiple_fieldsets    = true;
		$helper->override_folder       = $this->tpl_folder;
		$helper->languages             = $this->getLanguages();
		$helper->default_form_language = (int) $this->context->language->id;
		return $helper->generateForm( $fields_form );
	}

	public function processImportContent() {
		if ( ! class_exists( 'ZipArchive' ) ) {
			return false;
		}
		$this->display = 'importcontent';
		if ( isset( $_FILES['ceimportbtn'] ) ) {
			$name       = $_FILES['ceimportbtn']['name'];
			$target_dir = _PS_MODULE_DIR_ . 'crazyelements/ipmortdata/';
			if ( $target_dir && ! file_exists( $target_dir ) ) {
				mkdir( $target_dir, 0777, true );
			}
			$target_file    = $target_dir . basename( $_FILES['ceimportbtn']['name'] );
			$imageFileType  = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
			$extensions_arr = array( 'zip' );
			if ( in_array( $imageFileType, $extensions_arr ) ) {
				if ( move_uploaded_file( $_FILES['ceimportbtn']['tmp_name'], $target_dir . $name ) ) {
					$zip = new ZipArchive();
					$res = $zip->open( $target_dir . $name );
					if ( $res === true ) {
						$zip->extractTo( $target_dir );
						$zip->close();
					}
					$attachment = array();
					$jsondata   = file_get_contents( $target_dir . 'export.json' );
					$result     = json_decode( $jsondata, true );

					$i      = 0;
					$lastid = '';
					foreach ( $result as $res ) {
						preg_match_all( '/"url"\:\"([^\"]+)\"/', $res['resource'], $image_array );
						foreach ( $image_array[1] as $image ) {
							$file_info = pathinfo( $image );
							if ( ! isset( $file_info['extension'] ) ) {

							} else {
								$attachment['url'] = stripslashes( $image );
								$addimage          = Import_Images::import( $attachment );
								$res['resource']   = str_replace( $image, addslashes( $addimage['url'] ), $res['resource'] );
							}
						}
						if ( $i == 0 ) {
							$sql = 'INSERT INTO ' . _DB_PREFIX_ . $this->table . " (`id_content_type`, `hook`, `active`) VALUES ('" . pSQL( $res['id_content_type'] ) . "', '" . pSQL( $res['hook'] ) . "','" . pSQL( $res['active'] ) . "')";
							DB::getInstance()->execute( $sql );
							$lastid      = (int) Db::getInstance()->Insert_ID();
							$id_shop     = $this->context->shop->id;
							$table_name  = _DB_PREFIX_ . 'crazy_content_shop';
							$shop_result = Db::getInstance()->executeS( "SELECT * FROM $table_name WHERE id_shop = " . $id_shop . ' AND id_crazy_content=' . $lastid );
							if ( empty( $shop_result ) ) {
								Db::getInstance()->insert(
									'crazy_content_shop',
									array(
										'id_crazy_content' => $lastid,
										'id_shop'          => $id_shop,
									)
								);
							}
						}
						$sqlnext = 'INSERT INTO ' . _DB_PREFIX_ . $this->table . "_lang (`id_crazy_content`, `id_lang`, `title`, `resource`, `id_shop`) VALUES ('" . pSQL( $lastid ) . "', '" . pSQL( $res['id_lang'] ) . "','" . pSQL( $res['title'] ) . "', '" . addslashes( $res['resource'] ) . "', '" . pSQL( $res['id_shop'] ) . "')";
						DB::getInstance()->execute( $sqlnext );
						$lastidlang = (int) Db::getInstance()->Insert_ID();
						$i++;
					}
				}
			}
			$this->rmdir_recursive( $target_dir );
			$rurl = Context::getContext()->link->getAdminLink( 'AdminCrazyContent', true );
			Tools::redirect( $rurl );
		}
	}

	public function processCeexport() {
		if ( ! class_exists( 'ZipArchive' ) ) {
			return false;
		}
		$id        = Tools::getValue( 'id_crazy_content' );
		$sql       = 'SELECT a.*,b.*  from  ' . _DB_PREFIX_ . $this->table . ' as a join ' . _DB_PREFIX_ . $this->table . '_lang as b ON a.id_' . $this->table . '= b.id_' . $this->table . ' WHERE a.id_' . $this->table . '=' . $id;
		$result    = Db::getInstance()->executeS( $sql, true, false );
		$path      = _PS_MODULE_DIR_ . 'crazyelements/exportdata/';
		$imagepath = _PS_MODULE_DIR_ . 'crazyelements/exportdata/images' . $id . '/';
		foreach ( $result as $res ) {
			if ( $res['resource'] != '' ) {
				preg_match_all( '/"url"\:\"([^\"]+)\"/', $res['resource'], $image_array );
				if ( $path && ! file_exists( $path ) ) {
					mkdir( $path, 0777, true );
				}
				if ( $imagepath && ! file_exists( $imagepath ) ) {
					mkdir( $imagepath, 0777, true );
				}
				foreach ( $image_array[1] as $image ) {
					$file_info = pathinfo( $image );
					if ( ! isset( $file_info['extension'] ) ) {
					} else {
						$content = file_get_contents( stripslashes( $image ) );
						$fp      = fopen( $imagepath . basename( $image ), 'w' );
						fwrite( $fp, $content );
						fclose( $fp );

					}
				}
			}
		}
		$fp = fopen( $path . 'export.json', 'w' );
		fwrite( $fp, json_encode( $result ) );
		fclose( $fp );
		$zip            = new ZipArchive();
		$fulltargetpath = _PS_MODULE_DIR_ . 'crazyelements/export' . $id . '.zip';
		if ( file_exists( $fulltargetpath ) ) {
			unlink( $fulltargetpath );
		}
		if ( $zip->open( $fulltargetpath, ZipArchive::CREATE ) != true ) {
			return array(
				'success' => false,
				'massage' => 'Could not open archive',
			);
		}
		$files = $this->ce_get_Dir_Contents( $path );
		foreach ( $files as $file ) {
			$relativePath = substr( $file, strlen( $path ) );
			$zip->addFile( $file, $relativePath );
		}
		$zip->close();
		$this->rmdir_recursive( $path );
		$zipurl = CRAZY_URL . 'export' . $id . '.zip';
		Tools::redirect( $zipurl );
	}


	function rmdir_recursive( $dir ) {
		foreach ( scandir( $dir ) as $file ) {
			if ( '.' === $file || '..' === $file ) {
				continue;
			}
			if ( is_dir( "$dir/$file" ) ) {
				$this->rmdir_recursive( "$dir/$file" );
			} else {
				unlink( "$dir/$file" );
			}
		}
		rmdir( $dir );
	}


	public function ce_get_Dir_Contents( $dir, &$results = array() ) {
		$files = scandir( $dir );
		foreach ( $files as $key => $value ) {
			$path = realpath( $dir . '/' . $value );
			if ( ! is_dir( $path ) ) {
				$results[] = $path;
			} elseif ( $value != '.' && $value != '..' ) {
				$this->ce_get_Dir_Contents( $path, $results );
			}
		}
		return $results;
	}


	public function displayCeimportLink( $token, $id, $name = null ) {
		if ( $this->activeButton == 'false' ) {
			return '';
		}
		$href = self::$currentIndex = 'index.php?controller=AdminCrazyContent' . '&id_crazy_content=' . $id . '&ceimport' . $this->table . '&token=' . ( $token != null ? $token : $this->token );
		return "<a href='" . $href . "'>" . $this->l( 'Import Page' ) . '</a>';
	}


	public function displayCeexportLink( $token, $id, $name = null ) {
		if ( $this->activeButton == 'false' ) {
			return '';
		}
		$href = self::$currentIndex = 'index.php?controller=AdminCrazyContent' . '&id_crazy_content=' . $id . '&ceexport' . $this->table . '&token=' . ( $token != null ? $token : $this->token );
		return "<a href='" . $href . "'><i class='icon-download'></i> " . $this->l( 'Export Page' ) . '</a>';
	}
	public function initToolbar() {
		parent::initToolbar();
	}
}