<?php
namespace CrazyElements\Core\Settings\Page;

use CrazyElements\Core\Files\CSS\Base;
use CrazyElements\Core\Files\CSS\Post;
use CrazyElements\Core\Files\CSS\Post_Preview;
use CrazyElements\Core\Utils\Exceptions;
use CrazyElements\Core\Settings\Manager as SettingsManager;
use CrazyElements\Core\Settings\Base\Manager as BaseManager;
use CrazyElements\Core\Settings\Base\Model as BaseModel;
use CrazyElements\DB;
use CrazyElements\Plugin;
use CrazyElements\Utils;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.0.0
 */
class Manager extends BaseManager {

	/**
	 * Meta key for the page settings.
	 */
	const META_KEY = '_elementor_page_settings';

	const META_KEY_CMS = '_elementor_cms_page_settings';
	const META_KEY_PRODUCT = '_elementor_product_settings';
	const META_KEY_SUPPLIER = '_elementor_supplier_settings';
	const META_KEY_CATEGORY = '_elementor_category_settings';
	const META_KEY_MANUFACTURER = '_elementor_manufacturer_settings';

	/**
	 * Is CPT supports custom templates.
	 *
	 * Whether the Custom Post Type supports templates.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return bool True is templates are supported, False otherwise.
	 */
	public static function is_cpt_custom_templates_supported() {
		_deprecated_function( __METHOD__, '1.0.0', 'Utils::is_cpt_custom_templates_supported()' );

		return Utils::is_cpt_custom_templates_supported();
	}

	/**
	 * Get manager name.
	 *
	 * Retrieve page settings manager name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Manager name.
	 */
	public function get_name() {
		return 'page';
	}

	/**
	 * Get model for config.
	 *
	 * Retrieve the model for settings configuration.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return BaseModel The model object.
	 */
	public function get_model_for_config() {

        $id= PrestaHelper::$id_content_global;
        
		$model = $this->get_model( $id );


		return $model;
	}

	/**
	 * Ajax before saving settings.
	 *
	 * Validate the data before saving it and updating the data in the database.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data Post data.
	 * @param int   $id   Post ID.
	 *
	 * @throws \Exception If invalid post returned using the `$id`.
	 * @throws \Exception If current user don't have permissions to edit the post.
	 */
	public function ajax_before_save_settings( array $data, $id ) {
		$post = get_post( $id );

		if ( empty( $post ) ) {
			throw new \Exception( 'Invalid post.', Exceptions::NOT_FOUND );
		}

		if ( ! current_user_can( 'edit_post', $id ) ) {
			throw new \Exception( 'Access denied.', Exceptions::FORBIDDEN );
		}

		// Avoid save empty post title.
		if ( ! empty( $data['post_title'] ) ) {
			$post->post_title = $data['post_title'];
		}

		if ( isset( $data['post_excerpt'] ) && post_type_supports( $post->post_type, 'excerpt' ) ) {
			$post->post_excerpt = $data['post_excerpt'];
		}

		if ( isset( $data['post_status'] ) ) {
			$this->save_post_status( $id, $data['post_status'] );
			unset( $post->post_status );
		}

		wp_update_post( $post );

		// Check updated status
		if ( DB::STATUS_PUBLISH === get_post_status( $id ) ) {
			$autosave = wp_get_post_autosave( $post->ID );
			if ( $autosave ) {
				wp_delete_post_revision( $autosave->ID );
			}
		}

		if ( isset( $data['post_featured_image'] ) && post_type_supports( $post->post_type, 'thumbnail' ) ) {
			if ( empty( $data['post_featured_image']['id'] ) ) {
				delete_post_thumbnail( $post->ID );
			} else {
				set_post_thumbnail( $post->ID, $data['post_featured_image']['id'] );
			}
		}

		if ( Utils::is_cpt_custom_templates_supported() ) {
			$template = get_metadata( 'post', $post->ID, '_wp_page_template', true );

			if ( isset( $data['template'] ) ) {
				$template = $data['template'];
			}

			if ( empty( $template ) ) {
				$template = 'default';
			}

			// Use `update_metadata` in order to save also for revisions.
			update_metadata( 'post', $post->ID, '_wp_page_template', $template );
		}
	}

	/**
	 * Save settings to DB.
	 *
	 * Save page settings to the database, as post meta data.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param array $settings Settings.
	 * @param int   $id       Post ID.
	 */
	protected function save_settings_to_db( array $settings, $id ) {

		$jsonsettings=pSQL(\Tools::jsonEncode($settings));
        $type = PrestaHelper::$hook_current;
        $id = pSQL($id);
        $type = pSQL($type);
		$sql="INSERT INTO "._DB_PREFIX_."crazy_setting (`post_id`, `settings`, `type`) VALUES
		('".$id."', '".$jsonsettings."','$type')";
		\DB::getInstance()->execute($sql);
		
		
		$table_name = _DB_PREFIX_ . 'crazy_options';
		$context = \Context::getContext();
        $id_lang = \Tools::getValue('id_lang',$context->language->id);
        $shop_id = $context->shop->id;

        switch ($type) {
            case 'cms':
                    $metaname=self::META_KEY_CMS;
                break;
            case 'product':
                    $metaname=self::META_KEY_PRODUCT;
                break;
            case 'supplier':
                    $metaname=self::META_KEY_SUPPLIER;
                break;
            case 'category':
                    $metaname=self::META_KEY_CATEGORY;
                break;
            case 'manufacturer':
                    $metaname=self::META_KEY_MANUFACTURER;
                break;
            default:
                    $metaname=self::META_KEY;
                break;
        }


        if ( ! empty( $settings ) ) {
                $results = \Db::getInstance()->executeS("SELECT * FROM $table_name WHERE id_shop = '" . $shop_id ."' AND id_lang='".$id_lang."' AND id='".$id."' AND option_name='".$metaname."'");
                if (empty($results)) {
                        $sql="INSERT INTO $table_name (`id_shop`, `id_lang`, `id`,`option_name`,`option_value`) VALUES ('".$shop_id."', '".$id_lang."','".$id."','".$metaname."','".$jsonsettings."')";
                }else{
                        $sql="UPDATE $table_name SET `option_value` = '".$jsonsettings."'
                                WHERE id_shop = '" . $shop_id ."' AND id_lang='".$id_lang."' AND id='".$id."' AND option_name='".$metaname."'";
                }
                 \DB::getInstance()->execute($sql);
         }else{
            $result = \Db::getInstance()->getValue("DELETE FROM $table_name WHERE option_name = '$metaname' AND id='$id' AND id_lang='$id_lang' AND id_shop='$shop_id' ");
         }
	}

	/**
	 * Get CSS file for update.
	 *
	 * Retrieve the CSS file before updating it.
	 *
	 * This method overrides the parent method to disallow updating CSS files for pages.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param int $id Post ID.
	 *
	 * @return false Disallow The updating CSS files for pages.
	 */
	protected function get_css_file_for_update( $id ) {
		return false;
	}

	/**
	 * Get saved settings.
	 *
	 * Retrieve the saved settings from the post meta.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param int $id Post ID.
	 *
	 * @return array Saved settings.
	 */
	protected function get_saved_settings( $id ) {
		

		if($id!=''){
            $type = PrestaHelper::$hook_current;
            switch ($type) {
                case 'cms':
                    $settings = PrestaHelper::get_post_meta( $id, self::META_KEY_CMS, true );
                    break;
                case 'product':
                    $settings =  PrestaHelper::get_post_meta( $id, self::META_KEY_PRODUCT, true );
                    break;
                case 'supplier':
                    $settings =  PrestaHelper::get_post_meta( $id, self::META_KEY_SUPPLIER, true );
                    break;
                case 'category':
                    $settings =  PrestaHelper::get_post_meta( $id, self::META_KEY_CATEGORY, true );
                    break;
                case 'manufacturer':
                    $settings =  PrestaHelper::get_post_meta( $id, self::META_KEY_MANUFACTURER, true );
                    break;
                default:
                    $settings =  PrestaHelper::get_post_meta( $id, self::META_KEY, true );
                    break;
            }



            if ( ! $settings ) {
				$settings = [];
			}
			$saved_template = PrestaHelper::get_post_meta( $id, '_wp_page_template', true );

			if ( $saved_template ) {
				$settings['template'] = $saved_template;
			}
		    return $settings;
		}else{
			$context = \Context::getContext();
			$url=$context->link->getAdminLink('AdminDashboard');
		 
			\Tools::redirect($url);
		}
	}

	/**
	 * Get CSS file name.
	 *
	 * Retrieve CSS file name for the page settings manager.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return string CSS file name.
	 */
	protected function get_css_file_name() {
		return 'post';
	}

	/**
	 * Get model for CSS file.
	 *
	 * Retrieve the model for the CSS file.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param Base $css_file The requested CSS file.
	 *
	 * @return BaseModel The model object.
	 */
	protected function get_model_for_css_file( Base $css_file ) {
		if ( ! $css_file instanceof Post ) {
			return null;
		}

		$post_id = $css_file->get_post_id();

		if ( $css_file instanceof Post_Preview ) {
			$autosave = Utils::get_post_autosave( $post_id );
			if ( $autosave ) {
				$post_id = $autosave->ID;
			}
		}

		return $this->get_model( $post_id );
	}

	/**
	 * Get special settings names.
	 *
	 * Retrieve the names of the special settings that are not saved as regular
	 * settings. Those settings have a separate saving process.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Special settings names.
	 */
	protected function get_special_settings_names() {
		return [
			'id',
			'post_title',
			'post_status',
			'template',
			'post_excerpt',
			'post_featured_image',
		];
	}

	/**
	 * @since 1.0.0
	 * @access public
	 *
	 * @param $post_id
	 * @param $status
	 */
	public function save_post_status( $post_id, $status ) {
		$parent_id = wp_is_post_revision( $post_id );

		if ( $parent_id ) {
			// Don't update revisions post-status
			return;
		}

		$parent_id = $post_id;

		$post = get_post( $parent_id );

		$allowed_post_statuses = get_post_statuses();

		if ( isset( $allowed_post_statuses[ $status ] ) ) {
			$post_type_object = get_post_type_object( $post->post_type );
			if ( 'publish' !== $status || current_user_can( $post_type_object->cap->publish_posts ) ) {
				$post->post_status = $status;
			}
		}

		wp_update_post( $post );
	}
}
