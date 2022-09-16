<?php
namespace CrazyElements\Core\Files\CSS;

use CrazyElements\Controls_Stack;
use CrazyElements\Element_Base;
use CrazyElements\Plugin;
use CrazyElements\PrestaHelper;

if (!defined('_PS_VERSION_')) {
    exit; // Exit if accessed directly.
}

/**
 * @since 1.2.0
 */
class Post extends Base
{

    /**
     * Elementor post CSS file prefix.
     */
    const FILE_PREFIX = 'post-';

    const META_KEY = '_elementor_css';
    const META_KEY_CMS = '_elementor_css_cms';
    const META_KEY_CATEGORY = '_elementor_css_category';
    const META_KEY_PRODUCT = '_elementor_css_product';
    const META_KEY_SUPPLIER = '_elementor_css_supplier';
    const META_KEY_MANUFACTURER = '_elementor_css_manufacturer';

    /**
     * Post ID.
     *
     * Holds the current post ID.
     *
     * @var int
     */
    private $post_id;
    private $post_type;
    private $lang_id;
    private $preview;

    /**
     * Post CSS file constructor.
     *
     * Initializing the CSS file of the post. Set the post ID and initiate the stylesheet.
     *
     * @since 1.2.0
     * @access public
     *
     * @param int $post_id Post ID.
     */
    public function __construct($post_id,$type)
    {
        $context = \Context::getContext();
        $this->lang_id = $context->language->id;
        $this->post_id = PrestaHelper::$id_content_global;
        $this->post_type = PrestaHelper::$hook_current;
        $elementor_library = \Tools::getValue('elementor_library');
        $preview = "";
        if ($elementor_library != '') {
            $this->preview = "_preview";
            $this->post_id =  $elementor_library;
        }
        if ($this->lang_id != '') {
            $lang_id_preview = "-".$this->lang_id;
        }

        $id_shop = '-'. PrestaHelper::$id_shop_global;

        $fr_controller = \Tools::getValue('fr_controller');

    
        if($type == 'extended'){
            $type = $fr_controller;
        }
        
        parent::__construct(self::FILE_PREFIX . $type.'-'.PrestaHelper::$id_content_global .$lang_id_preview.$id_shop. $preview . '.css');
    }

    /**
     * Get CSS file name.
     *
     * Retrieve the CSS file name.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string CSS file name.
     */
    public function get_name()
    {
        return 'post';
    }

    /**
     * Get post ID.
     *
     * Retrieve the ID of current post.
     *
     * @since 1.2.0
     * @access public
     *
     * @return int Post ID.
     */
    public function get_post_id()
    {
        return $this->post_id;
    }

     /**
     * Get post Type.
     *
     * Retrieve the Type of current post.
     *
     * @since 1.2.0
     * @access public
     *
     * @return string Post Type.
     */


    public function get_post_type()
    {
        return $this->post_type;
    }

     /**
     * Get Lang ID.
     *
     * Retrieve the ID of current Lang.
     *
     * @since 1.2.0
     * @access public
     *
     * @return int Lang ID.
     */

    public function get_lang_id()
    {
        return $this->lang_id;
    }


     /**
     * Get post Preview Mode.
     *
     * Retrieve Preview Mode.
     *
     * @since 1.2.0
     * @access public
     *
     * @return string Preview Mode.
     */

    public function get_preview_type()
    {
        return $this->preview;
    }
    /**
     * Get unique element selector.
     *
     * Retrieve the unique selector for any given element.
     *
     * @since 1.2.0
     * @access public
     *
     * @param Element_Base $element The element.
     *
     * @return string Unique element selector.
     */
    public function get_element_unique_selector(Element_Base $element)
    {
        return '.elementor-' . $this->post_id . ' .elementor-element' . $element->get_unique_selector();
    }

    /**
     * Load meta data.
     *
     * Retrieve the post CSS file meta data.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return array Post CSS file meta data.
     */
    protected function load_meta()
    {
        

        switch ($this->post_type) {
			case 'cms':
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY_CMS.'_'.$this->lang_id, true);
			break;
			case 'product':
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY_PRODUCT.'_'.$this->lang_id, true);
			break;
			case 'supplier':
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY_SUPPLIER.'_'.$this->lang_id, true);
			break;
			case 'category':
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY_CATEGORY.'_'.$this->lang_id, true);
			break;
			case 'manufacturer':
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY_MANUFACTURER.'_'.$this->lang_id, true);
			break;
			default:
                return PrestaHelper::get_post_meta($this->post_id, static::META_KEY.'_'.$this->lang_id, true);
			break;
        } 
        
    }

    /**
     * Update meta data.
     *
     * Update the global CSS file meta data.
     *
     * @since 1.2.0
     * @access protected
     *
     * @param array $meta New meta data.
     */
    protected function update_meta($meta)
    {

        switch ($this->post_type) {
			case 'cms':
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY_CMS.'_'.$this->lang_id, $meta);
			break;
			case 'product':
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY_PRODUCT.'_'.$this->lang_id, $meta);
			break;
			case 'supplier':
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY_SUPPLIER.'_'.$this->lang_id, $meta);
			break;
			case 'category':
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY_CATEGORY.'_'.$this->lang_id, $meta);
			break;
			case 'manufacturer':
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY_MANUFACTURER.'_'.$this->lang_id, $meta);
			break;
			default:
                PrestaHelper::update_post_meta($this->post_id, static::META_KEY.'_'.$this->lang_id, $meta);
			break;
        } 
    }

    /**
     * Delete meta.
     *
     * Delete the file meta data.
     *
     * @since  2.1.0
     * @access protected
     */
    protected function delete_meta()
    {

        switch ($this->post_type) {
            case 'cms':
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY_CMS.'_'.$this->lang_id);
            break;
            case 'product':
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY_PRODUCT.'_'.$this->lang_id);
            break;
            case 'supplier':
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY_SUPPLIER.'_'.$this->lang_id);
            break;
            case 'category':
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY_CATEGORY.'_'.$this->lang_id);
            break;
            case 'manufacturer':
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY_MANUFACTURER.'_'.$this->lang_id);
            break;
            default:
                PrestaHelper::delete_post_meta($this->post_id, static::META_KEY.'_'.$this->lang_id);
            break;
        }
       
    }

    /**
     * Get post data.
     *
     * Retrieve raw post data from the database.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Post data.
     */
    protected function get_data()
    {

        $type = PrestaHelper::$hook_current;
        
        $post_id =PrestaHelper::$id_content_global;
        if($post_id !=false || $post_id != ''){
             switch ($type) {
                case 'cms': 
                case 'supplier':
                case 'manufacturer':
                case 'category':
                case 'product':
                $table_name = _DB_PREFIX_ . 'crazy_content';
				$results = \Db::getInstance()->executeS("SELECT * FROM $table_name WHERE hook ='".$type."' AND id_content_type = " . $post_id);
                if (empty($results) || $results == null || $results == false) {
                    return array();
                } else {
                    $id_crazy_content = $results[0]['id_crazy_content'];
                    $elementor_library = \Tools::getValue('elementor_library'); 
                   
                    if ($elementor_library == '') {

                        $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $id_crazy_content . "' AND id_lang='" .  $this->lang_id . "'";
                        $post = \Db::getInstance()->executeS($query);
                        if (empty($post)) {
                            return array(); // in future we will fetch the resource from previously stored content on that page
                        }else{
                            $post = $post[0];
                            return \Tools::jsonDecode($post['resource'], true);
                        }
                    } else {
                        $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_library where id_crazy_library='" . $elementor_library . "' ";
                        $post = \Db::getInstance()->executeS($query);
                        $post = $post[0]; 
                        return \Tools::jsonDecode($post['data'], true);
                    }
                }
                break;
                default:
                    $elementor_library = \Tools::getValue('elementor_library');
                    $id_lang = (int) PrestaHelper::$id_lang_global;
                    $isExtended = PrestaHelper::check_extended_frontcontroller($type);
                    if($isExtended){
                        $table_name = _DB_PREFIX_ . 'crazy_content';
                        $results = \Db::getInstance()->executeS("SELECT * FROM $table_name WHERE hook ='extended' AND id_content_type = " . $post_id);
                        if (empty($results) || $results == null || $results == false) {
                            return array();
                        } else {
                            $id_crazy_content = $results[0]['id_crazy_content'];
                            $elementor_library = \Tools::getValue('elementor_library'); 
                        
                            if ($elementor_library == '') {
        
                                $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $id_crazy_content . "' AND id_lang='" .  $this->lang_id . "'";
                                $post = \Db::getInstance()->executeS($query);
                                if (empty($post)) {
                                    return array(); // in future we will fetch the resource from previously stored content on that page
                                }else{
                                    $post = $post[0];
                                    return \Tools::jsonDecode($post['resource'], true);
                                }
                            } else {
                                $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_library where id_crazy_library='" . $elementor_library . "' ";
                                $post = \Db::getInstance()->executeS($query);
                                $post = $post[0]; 
                                return \Tools::jsonDecode($post['data'], true);
                            }
                        }
                    }else{
                        if ($elementor_library == '') {
                            $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $post_id . "' AND id_lang='" . $this->lang_id . "'";
                            $post = \Db::getInstance()->executeS($query);
    
                            $post = $post[0];
                            return \Tools::jsonDecode($post['resource'], true);
                        } else {
                            $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_library where id_crazy_library='" . $elementor_library . "' ";
                            $post = \Db::getInstance()->executeS($query);
                            $post = $post[0];
                            //print_r($post);
                            return \Tools::jsonDecode($post['data'], true);
                        }
                    }
                break;
            }

        }
                

    }

    /**
     * Render CSS.
     *
     * Parse the CSS for all the elements.
     *
     * @since 1.2.0
     * @access protected
     */
    protected function render_css()
    {

        $data = $this->get_data();

        if (!empty($data)) {
            foreach ($data as $element_data) {
                
                $element = Plugin::$instance->elements_manager->create_element_instance($element_data);

                if (!$element) {
                    continue;
                }

            
                $this->render_styles($element);
            }
        }
    }

    /**
     * Enqueue CSS.
     *
     * Enqueue the post CSS file in Elementor.
     *
     * This method ensures that the post was actually built with elementor before
     * enqueueing the post CSS file.
     *
     * @since 1.2.2
     * @access public
     */
    public function enqueue()
    {

        parent::enqueue(); //commented_line
    }

    /**
     * Add controls-stack style rules.
     *
     * Parse the CSS for all the elements inside any given controls stack.
     *
     * This method recursively renders the CSS for all the child elements in the stack.
     *
     * @since 1.6.0
     * @access public
     *
     * @param Controls_Stack $controls_stack The controls stack.
     * @param array          $controls       Controls array.
     * @param array          $values         Values array.
     * @param array          $placeholders   Placeholders.
     * @param array          $replacements   Replacements.
     * @param array          $all_controls   All controls.
     */
    public function add_controls_stack_style_rules(Controls_Stack $controls_stack, array $controls, array $values, array $placeholders, array $replacements, array $all_controls = null)
    {
        parent::add_controls_stack_style_rules($controls_stack, $controls, $values, $placeholders, $replacements, $all_controls);

        if ($controls_stack instanceof Element_Base) {
            foreach ($controls_stack->get_children() as $child_element) {
                $this->render_styles($child_element);
            }
        }
    }

    /**
     * Get enqueue dependencies.
     *
     * Retrieve the name of the stylesheet used by `PrestaHelper::wp_enqueue_style()`.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return array Name of the stylesheet.
     */
    protected function get_enqueue_dependencies()
    {
        return ['elementor-frontend'];
    }

    /**
     * Get inline dependency.
     *
     * Retrieve the name of the stylesheet used by `wp_add_inline_style()`.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string Name of the stylesheet.
     */
    protected function get_inline_dependency()
    {
        return 'elementor-frontend';
    }

    /**
     * Get file handle ID.
     *
     * Retrieve the handle ID for the post CSS file.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string CSS file handle ID.
     */
    protected function get_file_handle_id()
    {
        return 'elementor-post-'. PrestaHelper::$hook_current.'-' . $this->post_id;
    }

    /**
     * Render styles.
     *
     * Parse the CSS for any given element.
     *
     * @since 1.2.0
     * @access protected
     *
     * @param Element_Base $element The element.
     */
    protected function render_styles(Element_Base $element)
    {
        /**
         * Before element parse CSS.
         *
         * Fires before the CSS of the element is parsed.
         *
         * @since 1.2.0
         *
         * @param Post         $this    The post CSS file.
         * @param Element_Base $element The element.
         */
        PrestaHelper::do_action('elementor/element/before_parse_css', $this, $element);

        $element_settings = $element->get_settings();

        $this->add_controls_stack_style_rules($element, $element->get_style_controls(null, $element->get_parsed_dynamic_settings()), $element_settings, ['{{ID}}', '{{WRAPPER}}'], [$element->get_id(), $this->get_element_unique_selector($element)]);


        if ( $this instanceof Dynamic_CSS ) {
            return;
        }

        $css = "";
        if ( empty( $css ) ) {
            return;
        }
        $css = str_replace( 'selector', $this->get_element_unique_selector( $element ), $css );

        // Add a css comment
        $css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

        $this->get_stylesheet()->add_raw_css( $css );

        /**
         * After element parse CSS.
         *
         * Fires after the CSS of the element is parsed.
         *
         * @since 1.2.0
         *
         * @param Post         $this    The post CSS file.
         * @param Element_Base $element The element.
         */
        PrestaHelper::do_action('elementor/element/parse_css', $this, $element);
    }
}
