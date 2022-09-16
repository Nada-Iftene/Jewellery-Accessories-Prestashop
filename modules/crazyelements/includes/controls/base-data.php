<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor base data control.
 *
 * An abstract class for creating new data controls in the panel.
 *
 * @since    1.0.0
 * @abstract
 */
abstract class Base_Data_Control extends Base_Control {

	/**
	 * Get data control default value.
	 *
	 * Retrieve the default value of the data control. Used to return the default
	 * values while initializing the data control.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Control default value.
	 */
	public function get_default_value() {
		return '';
	}

	/**
	 * Retrieve default control settings.
	 *
	 * Get the default settings of the control. Used to return the default
	 * settings while initializing the control.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		$default_settings = parent::get_default_settings();

		$default_settings['dynamic'] = false;

		return $default_settings;
	}

	/**
	 * Get data control value.
	 *
	 * Retrieve the value of the data control from a specific Controls_Stack settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $control  Control
	 * @param array $settings Element settings
	 *
	 * @return mixed Control values.
	 */
	public function get_value( $control, $settings ) {
		if ( ! isset( $control['default'] ) ) {
			$control['default'] = $this->get_default_value();
		}

		if ( isset( $settings[ $control['name'] ] ) ) {
			$value = $settings[ $control['name'] ];
		} else {
			$value = $control['default'];
		}
		return $value;
	}

	/**
	 * Parse dynamic tags.
	 *
	 * Iterates through all the controls and renders all the dynamic tags.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $dynamic_value    The dynamic tag text.
	 * @param array  $dynamic_settings The dynamic tag settings.
	 *
	 * @return string|string[]|mixed A string or an array of strings with the
	 *                               return value from each tag callback function.
	 */
	public function parse_tags( $dynamic_value, $dynamic_settings ) {
		$current_dynamic_settings = $this->get_settings( 'dynamic' );

		if ( is_array( $current_dynamic_settings ) ) {
			$dynamic_settings = array_merge( $current_dynamic_settings, $dynamic_settings );
		}

		return Plugin::$instance->dynamic_tags->parse_tags_text( $dynamic_value, $dynamic_settings, array( Plugin::$instance->dynamic_tags, 'get_tag_data_content' ) );
	}

	/**
	 * Get data control style value.
	 *
	 * Retrieve the style of the control. Used when adding CSS rules to the control
	 * while extracting CSS from the `selectors` data argument.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param string $css_property  CSS property.
	 * @param string $control_value Control value.
	 * @param array  $control_data  Control Data.
	 *
	 * @return string Control style value.
	 */
	public function get_style_value( $css_property, $control_value, array $control_data ) {
		if ( 'DEFAULT' === $css_property ) {
			return $control_data['default'];
		}

		return $control_value;
	}

	/**
	 * Get data control unique ID.
	 *
	 * Retrieve the unique ID of the control. Used to set a uniq CSS ID for the
	 * element.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @param string $input_type Input type. Default is 'default'.
	 *
	 * @return string Unique ID.
	 */
	protected function get_control_uid( $input_type = 'default' ) {
		return 'elementor-control-' . $input_type . '-{{{ data._cid }}}';
	}

	public function get_selected_items_by_id( $type, $ids ) {

		$result = array();
		if ( $type == 'product' ) {
			$result = $this->get_selected_products_by_id( $ids );
		} elseif ( $type == 'suppliers' ) {
			$result = $this->get_selected_suppliers_by_id( $ids );
		} elseif ( $type == 'category' ) {
			$result = $this->get_selected_categoris_by_id( $ids );
		} elseif ( $type == 'manufacturer' ) {
			$result = $this->get_selected_manufecturers_by_id( $ids );
		}

		return $result;
	}

	private function get_selected_products_by_id( $prod_ids ) {

		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( ! empty( $prod_ids ) && is_array( $prod_ids ) ) {

			$prod_ids = implode( ',', $prod_ids );

			$sql = 'SELECT p.`id_product`, pl.`name`
		FROM `' . _DB_PREFIX_ . 'product` p
		' . \Shop::addSqlAssociation( 'product', 'p' ) . '
		LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
		WHERE pl.`id_lang` = ' . (int) $id_lang . '
		' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND p.`id_product` IN (' . $prod_ids . ') ' .
			'ORDER BY pl.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_product'] ] = $result['name'];
			}

			return $return_arr;
		} elseif ( $prod_ids != '' ) {

			$sql = 'SELECT p.`id_product`, pl.`name`
			FROM `' . _DB_PREFIX_ . 'product` p
			' . \Shop::addSqlAssociation( 'product', 'p' ) . '
			LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . \Shop::addSqlRestrictionOnLang( 'pl' ) . ')
			WHERE pl.`id_lang` = ' . (int) $id_lang . '
			' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND p.`id_product` IN (' . $prod_ids . ') ' .
			'ORDER BY pl.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_product'] ] = $result['name'];
			}
			return $return_arr;
		}

		return array();

	}

	private function get_selected_suppliers_by_id( $supl_ids ) {

		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( ! empty( $supl_ids ) && is_array( $supl_ids ) ) {

			$supl_ids = implode( ',', $supl_ids );

			$sql = 'SELECT s.`id_supplier`, s.`name`
			FROM `' . _DB_PREFIX_ . 'supplier` s
			WHERE s.`active` =' . 1 . '' .
			' AND s.`id_supplier` IN ("' . $supl_ids . '") ' .
			'ORDER BY s.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_supplier'] ] = $result['name'];
			}

			return $return_arr;
		} else {

			$sql = 'SELECT s.`id_supplier`, s.`name`
			FROM `' . _DB_PREFIX_ . 'supplier` s
			WHERE s.`active` =' . 1 . '' .
			' AND s.`id_supplier` IN (' . $supl_ids . ') ' .
			'ORDER BY s.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_supplier'] ] = $result['name'];
			}

			return $return_arr;
		}

		return array();

	}

	private function get_selected_categoris_by_id( $catg_ids ) {

		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( ! empty( $catg_ids ) && is_array( $catg_ids ) ) {

			$catg_ids = implode( ',', $catg_ids );

			$sql = 'SELECT c.`id_category`, cl.`name`
                FROM `' . _DB_PREFIX_ . 'category` c
                ' . \Shop::addSqlAssociation( 'category', 'c' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` ' . \Shop::addSqlRestrictionOnLang( 'cl' ) . ')
                WHERE cl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND c.`active` = 1' : '' ) .
			' AND c.`id_category` IN(' . $catg_ids . ')' .
			'ORDER BY cl.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_category'] ] = $result['name'];
			}

			return $return_arr;
		} else {

			$sql = 'SELECT c.`id_category`, cl.`name`
                FROM `' . _DB_PREFIX_ . 'category` c
                ' . \Shop::addSqlAssociation( 'category', 'c' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` ' . \Shop::addSqlRestrictionOnLang( 'cl' ) . ')
                WHERE cl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND c.`active` = 1' : '' ) .
			' AND c.`id_category` IN(' . $catg_ids . ')' .
			'ORDER BY cl.`name`';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_category'] ] = $result['name'];
			}

			return $return_arr;
		}

		return array();

	}

	private function get_selected_manufecturers_by_id( $man_ids ) {

		if($man_ids != ''){
		$context = \Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( ! empty( $man_ids ) && is_array( $man_ids ) ) {

			$man_ids = implode( ',', $man_ids );

			$sql = 'SELECT m.`id_manufacturer`, m.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` m
                ' . \Shop::addSqlAssociation( 'manufacturer', 'm' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer_lang` mn ON (m.`id_manufacturer` = mn.`id_manufacturer`)
                WHERE mn.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND m.`active` = 1' : '' ) .
			' AND m.`id_manufacturer` IN(' . $man_ids . ')';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_manufacturer'] ] = $result['name'];
			}

			return $return_arr;
		} else {

			$sql = 'SELECT m.`id_manufacturer`, m.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` m
                ' . \Shop::addSqlAssociation( 'manufacturer', 'm' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer_lang` mn ON (m.`id_manufacturer` = mn.`id_manufacturer`)
                WHERE mn.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND m.`active` = 1' : '' ) .
			' AND m.`id_manufacturer` IN(' . $man_ids . ')';

			$results    = \Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
			$return_arr = array();
			foreach ( $results as $result ) {
				$return_arr[ $result['id_manufacturer'] ] = $result['name'];
			}

			return $return_arr;
		}
		}
		

		return array();

	}
}
