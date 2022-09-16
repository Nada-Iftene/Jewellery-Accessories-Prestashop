<?php
namespace CrazyElements;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

/**
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Scheme_Base implements Scheme_Interface {

	/**
	 * DB option name for the time when the scheme was last updated.
	 */
	const LAST_UPDATED_META = '_elementor_scheme_last_updated';

	/**
	 * System schemes.
	 *
	 * Holds the list of all the system schemes.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array System schemes.
	 */
	private $_system_schemes;

	/**
	 * Init system schemes.
	 *
	 * Initialize the system schemes.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @abstract
	 */
	abstract protected function _init_system_schemes();

	/**
	 * Get description.
	 *
	 * Retrieve the scheme description.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return string Scheme description.
	 */
	public static function get_description() {
		return '';
	}

	/**
	 * Get system schemes.
	 *
	 * Retrieve the system schemes.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string System schemes.
	 */
	final public function get_system_schemes() {
		if ( null === $this->_system_schemes ) {
			$this->_system_schemes = $this->_init_system_schemes();
		}

		return $this->_system_schemes;
	}

	/**
	 * Get scheme value.
	 *
	 * Retrieve the scheme value.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Scheme value.
	 */
	public function get_scheme_value() {
		$scheme_value = PrestaHelper::get_option( 'elementor_scheme_' . static::get_type() );

        
		if ( ! $scheme_value ) {
			$scheme_value = $this->get_default_scheme();

			PrestaHelper::update_option( 'elementor_scheme_' . static::get_type(), $scheme_value );
		}
		if(!is_array($scheme_value)){
			$scheme_value = json_decode($scheme_value,true);
		}
		
		 
		return $scheme_value;
	}

	/**
	 * Save scheme.
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $posted
	 */
	public function save_scheme( array $posted ) {
		$scheme_value = $this->get_scheme_value();

		PrestaHelper::update_option( 'elementor_scheme_' . static::get_type(), array_replace( $scheme_value, array_intersect_key( $posted, $scheme_value ) ) );

		PrestaHelper::update_option( self::LAST_UPDATED_META, time() );
	}

	/**
	 * Get scheme.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array The scheme.
	 */
	public function get_scheme() {
		$scheme = [];

		$titles = $this->get_scheme_titles();

		foreach ( $this->get_scheme_value() as $scheme_key => $scheme_value ) {
			$scheme[ $scheme_key ] = [
				'title' => isset( $titles[ $scheme_key ] ) ? $titles[ $scheme_key ] : '',
				'value' => $scheme_value,
			];
		}

		return $scheme;
	}

	/**
	 * @since 1.0.0
	 * @access public
	 */
	final public function print_template() {
		?>
		<script type="text/template" id="tmpl-elementor-panel-schemes-<?php echo static::get_type(); ?>">
			<div class="elementor-panel-scheme-buttons">
				<div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-reset">
					<button class="elementor-button">
						<i class="fa fa-undo" aria-hidden="true"></i>
						<?php echo PrestaHelper::__( 'Reset', 'elementor' ); ?>
					</button>
				</div>
				<div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
					<button class="elementor-button">
						<i class="ceicon-close" aria-hidden="true"></i>
						<?php echo PrestaHelper::__( 'Discard', 'elementor' ); ?>
					</button>
				</div>
				<div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
					<button class="elementor-button elementor-button-success" disabled><?php echo PrestaHelper::__( 'Apply', 'elementor' ); ?></button>
				</div>
			</div>
			<?php $this->print_template_content(); ?>
		</script>
		<?php
	}
}
