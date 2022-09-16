<?php
namespace CrazyElements\Core\DynamicTags;

use CrazyElements\Controls_Stack;
use CrazyElements\Core\Files\CSS\Post;
use CrazyElements\Element_Base;
use CrazyElements\PrestaHelper;

if (!defined('_PS_VERSION_')) {
    exit; // Exit if accessed directly.
}

class Dynamic_CSS extends Post
{

    protected $post_id_for_data;
    /**
     * Dynamic_CSS constructor.
     *
     * @since 1.0.0
     * @access public
     * @param int $post_id Post ID
     * @param int $post_id_for_data
     */
    public function __construct($post_id, $post_id_for_data,$type)
    {
        $this->post_id_for_data = $post_id_for_data;

        parent::__construct($post_id,$type);
    }

    /**
     * @since 1.0.0
     * @access public
     */
    public function get_name()
    {
        return 'dynamic';
    }

    /**
     * @since 1.0.0
     * @access protected
     */
    protected function use_external_file()
    {
        return false;
    }

    /**
     * @since 1.0.0
     * @access protected
     */
    protected function get_file_handle_id()
    {
        return 'elementor-post-dynamic-' . $this->post_id_for_data;
    }

    /**
     * @since 1.0.0
     * @access protected
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
                $results = \Db::getInstance()->executeS("SELECT * FROM $table_name WHERE hook='".$type."' AND id_content_type = " . $post_id);
                if (empty($results)) {
                        return array();
                } else {
                        $id_crazy_content = $results[0]['id_crazy_content'];
                        $elementor_library = \Tools::getValue('elementor_library');
                        $id_lang = (int) PrestaHelper::$id_lang_global;
                        if ($elementor_library == '') { 
                            $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $id_crazy_content . "' AND id_lang='" . $id_lang . "'";
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
                        $results = \Db::getInstance()->executeS("SELECT * FROM $table_name WHERE hook='extended' AND id_content_type = " . $post_id);
                        if (empty($results)) {
                            return array();
                        } else {
                            $id_crazy_content = $results[0]['id_crazy_content'];
                            $elementor_library = \Tools::getValue('elementor_library');
                            $id_lang = (int) PrestaHelper::$id_lang_global;
                            if ($elementor_library == '') { 
                                $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $id_crazy_content . "' AND id_lang='" . $id_lang . "'";
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
                            $post_id = PrestaHelper::$id_content_global;
                            $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_content_lang where id_crazy_content='" . $post_id . "' AND id_lang='" . $id_lang . "'";
                            $post = \Db::getInstance()->executeS($query);
                            $post = $post[0];
                            return \Tools::jsonDecode($post['resource'], true);
                        } else {
                            $query = "SELECT * FROM " . _DB_PREFIX_ . "crazy_library where id_crazy_library='" . $elementor_library . "' ";
                            $post = \Db::getInstance()->executeS($query);
                            $post = $post[0];
                            return \Tools::jsonDecode($post['data'], true);
                        }
                    }
                    break;
            }
        }
    }

    /**
     * @since 1.0.0
     * @access public
     */
    public function get_meta($property = null)
    {
        // Parse CSS first, to get the fonts list.
        $css = $this->get_content();

        
        $meta = [
            'status' => $css ? self::CSS_STATUS_INLINE : self::CSS_STATUS_EMPTY,
            'fonts' => $this->get_fonts(),
            'css' => $css,
        ];

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }

    /**
     * @since 1.0.0
     * @access public
     */
    public function add_controls_stack_style_rules(Controls_Stack $controls_stack, array $controls, array $values, array $placeholders, array $replacements, array $all_controls = null)
    {
        $dynamic_settings = $controls_stack->get_settings('__dynamic__');
        if (!empty($dynamic_settings)) {
            $controls = array_intersect_key($controls, $dynamic_settings);

            $all_controls = $controls_stack->get_controls();

            $parsed_dynamic_settings = $controls_stack->parse_dynamic_settings($values, $controls);

            foreach ($controls as $control) {
                if (!empty($control['style_fields'])) {
                    $this->add_repeater_control_style_rules($controls_stack, $control, $values[$control['name']], $placeholders, $replacements);
                }

                if (empty($control['selectors'])) {
                    continue;
                }

                $this->add_control_style_rules($control, $parsed_dynamic_settings, $all_controls, $placeholders, $replacements);
            }
        }

        if ($controls_stack instanceof Element_Base) {
            foreach ($controls_stack->get_children() as $child_element) {
                $this->render_styles($child_element);
            }
        }
    }
}
