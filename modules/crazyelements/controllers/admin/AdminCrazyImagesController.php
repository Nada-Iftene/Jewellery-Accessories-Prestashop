<?php
require_once (dirname(__FILE__) . '/../../classes/PseImageType.php');

class AdminCrazyImagesController extends ModuleAdminController
{

    protected $start_time = 0;
    protected $max_execution_time = 7200;
    protected $display_move;
    public $vc;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'pse_image_type';
        $this->className = 'PseImageType';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->imageTable = _DB_PREFIX_.'pse_media';
        parent::__construct();
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->fields_list = array(
            'id_image_type' => array('title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'),
            'name' => array('title' => $this->l('Name')),
            'width' => array('title' => $this->l('Width'), 'suffix' => ' px'),
            'height' => array('title' => $this->l('Height'), 'suffix' => ' px'),
            'active' => array('title' => $this->l('Active'), 'align' => 'center', 'type' => 'bool', 'callback' => 'printEntityActiveIcon', 'orderby' => false),
        );

        // No need to display the old image system migration tool except if product images are in _PS_PROD_IMG_DIR_
        $this->display_move = false;
        $dir = $upload_dir = Context::getContext()->shop->getBaseURI().'img/cms/'; // path from base_url to base of upload folder (with start and final /) 
        if (is_dir($dir))
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false && $this->display_move == false)
                    if (!is_dir($dir . DIRECTORY_SEPARATOR . $file) && $file[0] != '.' && is_numeric($file[0]))
                        $this->display_move = true;
                closedir($dh);
            }
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Image type'),
                'icon' => 'icon-picture'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name for the image type'),
                    'name' => 'name',
                    'required' => true,
                    'hint' => $this->l('Letters, underscores and hyphens only (e.g. "small_custom", "cart_medium", "large", "thickbox_extra-large").')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Width'),
                    'name' => 'width',
                    'required' => true,
                    'maxlength' => 5,
                    'suffix' => $this->l('pixels'),
                    'hint' => $this->l('Maximum image width in pixels.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Height'),
                    'name' => 'height',
                    'required' => true,
                    'maxlength' => 5,
                    'suffix' => $this->l('pixels'),
                    'hint' => $this->l('Maximum image height in pixels.')
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'hint' => $this->l('This will activate/deactive the size.'),
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
                        ),
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save')
            )
        );
    }

    protected function _deleteImagesOfType($id)
    {
        $dir = $this->vc->composer_settings['UPLOADS_DIR'];
        if (!is_dir($dir))
            return false;
        $images = jscomposer::get_uploaded_files_result();
        $type = VcImageType::getImageTypeById($id);
        $errors = false;
        if (empty($images)) {
            $this->errors[] = Tools::displayError('Failed to remove images. No image has found.');
            $errors = true;
        } else {
            foreach ($images as $image) {
                $filename = substr($image, 0, strrpos($image, '.'));
                $ext = substr($image, strrpos($image, '.') + 1);
                foreach ($type as $imageType) {
                    $newfilename = "{$filename}-{$imageType['name']}";
                    if (file_exists($dir . $newfilename . ".{$ext}")) {
                        @chmod($dir . $newfilename . ".{$ext}", 0777); // NT ?
                        if (!@unlink($dir . $newfilename . ".{$ext}")) {
                            $errors = true;
                            $this->errors[] = sprintf(Tools::displayError('%s file would not be removed'), $dir . $image);
                        }
                    }
                }
            }
        }
        return $errors;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitRegenerate' . $this->table)) {
            if ($this->_regenerateThumbnails(Tools::getValue('format'), Tools::getValue('erase')))
                Tools::redirectAdmin(self::$currentIndex . '&conf=9' . '&token=' . $this->token);
        } else
            return parent::postProcess();
    }

    public static function printEntityActiveIcon($value, $object)
    {
        return ($value ? '<span class="list-action-enable action-enabled"><i class="icon-check"></i></span>' : '<span class="list-action-enable action-disabled"><i class="icon-remove"></i></span>');
    }

    protected function _childValidation()
    {
        if (!Tools::getValue('id_vc_image_type') && Validate::isImageTypeName($typeName = Tools::getValue('name')) && VcImageType::typeAlreadyExists($typeName))
            $this->errors[] = Tools::displayError('This name already exists.');
    }

    /**
     * Init display for the thumbnails regeneration block
     */
    public function initRegenerate()
    {
        $formats = VcImageType::getImagesTypes('active');
        $this->context->smarty->assign(array(
            'formats' => $formats,
        ));
    }

    /**
     * Delete resized image then regenerate new one with updated settings
     */
    protected function _deleteOldImages($rootdir, $type)
    {
        if (!is_dir($rootdir))
            return false;
        $images = Db::getInstance()->executeS("SELECT * FROM {$this->imageTable} ORDER BY `id_vc_media` DESC");
        $errors = false;
        if (empty($images)) {
            $this->errors[] = Tools::displayError('Failed to remove images. No image has found.');
            $errors = true;
        } else {
            foreach ($images as $imagedata) {
                $dir = $rootdir.$imagedata['subdir'];
                $image = $imagedata['file_name'];
                $filename = substr($image, 0, strrpos($image, '.'));
                $ext = substr($image, strrpos($image, '.') + 1);
                foreach ($type as $k => $imageType) {
                    $newfilename = "{$filename}-{$imageType['name']}";
                    if (file_exists($dir . $newfilename . ".{$ext}")) {
                        @chmod($dir . $newfilename . ".{$ext}", 0777); // NT ?
                        if (!@unlink($dir . $newfilename . ".{$ext}")) {
                            $errors = true;
                            $this->errors[] = sprintf(Tools::displayError('%s file would not removed'), $dir . $image);
                        }
                    }
                }
            }
        }
        return $errors;
    }

    /**
     * Regenerate images
     *
     * @param $dir
     * @param $type
     * @param bool $productsImages
     * @return bool|string
     */
    public function _regenerateNewImages($rootdir, $type, $productsImages = false)
    {
        if (!is_dir($rootdir))
            return false;
        $images = Db::getInstance()->executeS("SELECT * FROM {$this->imageTable} ORDER BY `id_vc_media` DESC");
        $errors = false;
        if (empty($images)) {
            $this->errors[] = Tools::displayError('Failed to resize images. No image has found.');
            $errors = true;
        } else {
            foreach ($images as $imagedata) {
                $dir = $rootdir.$imagedata['subdir'];
                $image = $imagedata['file_name'];
                $filename = substr($image, 0, strrpos($image, '.'));
                $ext = substr($image, strrpos($image, '.') + 1);
                foreach ($type as $k => $imageType) {
                    $newfilename = "{$filename}-{$imageType['name']}";
                    if (!file_exists($dir . $newfilename . ".{$ext}")) {
                        if (!file_exists($dir . $image) || !filesize($dir . $image)) {
                            $errors = true;
                            $this->errors[] = sprintf('Source file does not exist or is empty (%s)', $dir . $image);
                        } elseif (!ImageManager::resize($dir . $image, $dir . $newfilename . ".{$ext}", (int) $imageType['width'], (int) $imageType['height'])) {
                            $errors = true;
                            $this->errors[] = sprintf(Tools::displayError('Failed to resize image file (%s)'), $dir . $image);
                        }
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Regenerate no-pictures images
     *
     * @param $dir
     * @param $type
     * @param $languages
     * @return bool
     */
    protected function _regenerateNoPictureImages($dir, $type, $languages)
    {
        $errors = false;
        foreach ($type as $image_type)
            foreach ($languages as $language) {
                $file = $dir . $language['iso_code'] . '.jpg';
                if (!file_exists($file))
                    $file = _PS_PROD_IMG_DIR_ . Language::getIsoById((int) Configuration::get('PS_LANG_DEFAULT')) . '.jpg';
                if (!file_exists($dir . $language['iso_code'] . '-default-' . stripslashes($image_type['name']) . '.jpg'))
                    if (!ImageManager::resize($file, $dir . $language['iso_code'] . '-default-' . stripslashes($image_type['name']) . '.jpg', (int) $image_type['width'], (int) $image_type['height']))
                        $errors = true;
            }
        return $errors;
    }
    /* Hook watermark optimization */

    protected function _regenerateWatermark($dir)
    {
        $result = Db::getInstance()->executeS('
		SELECT m.`name` FROM `' . _DB_PREFIX_ . 'module` m
		LEFT JOIN `' . _DB_PREFIX_ . 'hook_module` hm ON hm.`id_module` = m.`id_module`
		LEFT JOIN `' . _DB_PREFIX_ . 'hook` h ON hm.`id_hook` = h.`id_hook`
		WHERE h.`name` = \'actionWatermark\' AND m.`active` = 1');
        if ($result && count($result)) {
            $productsImages = Image::getAllImages();
            foreach ($productsImages as $image) {
                $imageObj = new Image($image['id_image']);
                if (file_exists($dir . $imageObj->getExistingImgPath() . '.jpg')) {
                    foreach ($result as $module) {
                        $moduleInstance = Module::getInstanceByName($module['name']);
                        if ($moduleInstance && is_callable(array($moduleInstance, 'hookActionWatermark')))
                            call_user_func(array($moduleInstance, 'hookActionWatermark'), array('id_image' => $imageObj->id, 'id_product' => $imageObj->id_product));
                        if (time() - $this->start_time > $this->max_execution_time - 4) // stop 4 seconds before the tiemout, just enough time to process the end of the page on a slow server
                            return 'timeout';
                    }
                }
            }
        }
    }

    protected function _regenerateThumbnails($format = 'all', $deleteOldImages = false)
    {
        $this->start_time = time();
        $dir = $this->vc->composer_settings['UPLOADS_DIR'];
        ini_set('max_execution_time', $this->max_execution_time); // ini_set may be disabled, we need the real value
        $this->max_execution_time = (int) ini_get('max_execution_time');
        $formats = VcImageType::getImagesTypes('active');
        if ($format != 'all') {
            foreach ($formats as $k => $form)
                if ($form['id_vc_image_type'] != $format)
                    unset($formats[$k]);
        }
        if ($deleteOldImages)
            $this->_deleteOldImages($dir, $formats);
        if (($return = $this->_regenerateNewImages($dir, $formats)) === true)
            return $return;
        return false;
    }

    /**
     * Init display for move images block
     */
    public function initMoveImages()
    {
        $this->context->smarty->assign(array(
            'safe_mode' => Tools::getSafeModeStatus(),
            'link_ppreferences' => 'index.php?tab=AdminPPreferences&token=' . Tools::getAdminTokenLite('AdminPPreferences') . '#PS_LEGACY_IMAGES_on',
        ));
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display))
            $this->page_header_toolbar_btn['new_image_type'] = array(
                'href' => self::$currentIndex . '&addvc_image_type&token=' . $this->token,
                'desc' => $this->l('Add new image type', null, null, false),
                'icon' => 'process-icon-new'
            );

        parent::initPageHeaderToolbar();
    }

    /**
     * Move product images to the new filesystem
     */
    protected function _moveImagesToNewFileSystem()
    {
        if (!Image::testFileSystem())
            $this->errors[] = Tools::displayError('Error: Your server configuration is not compatible with the new image system. No images were moved.');
        else {
            ini_set('max_execution_time', $this->max_execution_time); // ini_set may be disabled, we need the real value
            $this->max_execution_time = (int) ini_get('max_execution_time');
            $result = Image::moveToNewFileSystem($this->max_execution_time);
            if ($result === 'timeout')
                $this->errors[] = Tools::displayError('Not all images have been moved. The server timed out before finishing. Click on "Move images" again to resume the moving process.');
            else if ($result === false)
                $this->errors[] = Tools::displayError('Error: Some -- or all -- images cannot be moved.');
        }
        return (count($this->errors) > 0 ? false : true);
    }

    public function initContent()
    {
        if ($this->display != 'edit' && $this->display != 'add') {
            $this->initRegenerate();
            $this->initMoveImages();
            $this->context->smarty->assign(array(
                'display_regenerate' => true,
                'display_move' => $this->display_move
            ));
        }

        if ($this->display == 'edit')
            $this->warnings[] = $this->l('After modification, do not forget to regenerate thumbnails');

        parent::initContent();
    }
}