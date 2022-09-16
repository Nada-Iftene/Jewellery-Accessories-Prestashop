<?php
namespace CrazyElements;

use CrazyElements\Core\Files\Assets\Svg\Svg_Handler;
use CrazyElements\Modules\DynamicTags\Module as TagsModule;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly.
}

class Control_Icons extends Control_Base_Multiple {

	/**
	 * Get media control type.
	 *
	 * Retrieve the control type, in this case `media`.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return string Control type.
	 */
	public function get_type() {
		return 'icons';
	}

	/**
	 * Get Icons control default values.
	 *
	 * Retrieve the default value of the Icons control. Used to return the default
	 * values while initializing the Icons control.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return array Control default value.
	 */
	public function get_default_value() {
		return [
			'value'   => '',
			'library' => '',
		];
	}

	/**
	 * Render Icons control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field elementor-control-media">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper elementor-aspect-ratio-219">
				<div class="elementor-control-media__content elementor-control-tag-area elementor-control-preview-area elementor-fit-aspect-ratio">
					<div class="elementor-control-media-upload-button elementor-fit-aspect-ratio">
						<i class="ceicon-plus-circle" aria-hidden="true"></i>
					</div>
					<div class="elementor-control-media-area elementor-fit-aspect-ratio">
						<div class="elementor-control-media__remove" title="<?php echo PrestaHelper::__( 'Remove', 'elementor' ); ?>">
							<i class="ceicon-trash"></i>
						</div>
						<div class="elementor-control-media__preview elementor-fit-aspect-ratio"></div>
					</div>
					<input type="hidden" id="elementor-control-media__preview__icon__{{ data._cid }}" value="{{ data.controlValue.url }}">
					<div class="elementor-control-media__tools">
						<div class="elementor-control-icon-picker elementor-control-media__tool"><?php echo PrestaHelper::__( 'Icon Library', 'elementor' ); ?></div>
						<div class="elementor-control-svg-uploader elementor-control-media__tool"><?php echo PrestaHelper::__( 'Upload SVG', 'elementor' ); ?></div>
					</div>
				</div>
			</div>
			<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
			<# } #>
			<input type="hidden" data-setting="{{ data.name }}"/>
		</div>
		<?php
	}

	/**
	 * Get Icons control default settings.
	 *
	 * Retrieve the default settings of the Icons control. Used to return the default
	 * settings while initializing the Icons control.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'search_bar' => true,
			'recommended' => false,
			'is_svg_enabled' => Svg_Handler::is_enabled(),
		];
	}
	public function on_import( $settings ) {
		if ( empty( $settings['library'] ) || 'svg' !== $settings['library'] || empty( $settings['value']['url'] ) ) {
			return $settings;
		}
		$imported = Plugin::$instance->templates_manager->get_import_images_instance()->import( $settings['value'] );
		if ( ! $imported ) {
			$settings['value'] = '';
			$settings['library'] = '';
		} else {
			$settings['value'] = $imported;
		}
		return $settings;
	}
}
