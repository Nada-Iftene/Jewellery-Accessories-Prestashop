<?php
namespace CrazyElements;

use CrazyElements\Core\Base\Document;

use CrazyElements\PrestaHelper; 

if ( ! defined( '_PS_VERSION_' ) ) {
	exit; // Exit if accessed directly
}

$document_types = Plugin::$instance->documents->get_document_types();

$types = [];

$selected = get_query_var( 'elementor_library_type' );

foreach ( $document_types as $document_type ) {
	if ( $document_type::get_property( 'show_in_library' ) ) {
		/**
		 * @var Document $instance
		 */
		$instance = new $document_type();

		$types[ $instance->get_name() ] = $document_type::get_title();
	}
}

/**
 * @since 1.0.0
 *
 * @param array    $types          Types data.
 * @param Document $document_types Document types.
 */
$types = PrestaHelper::apply_filters( 'elementor/template-library/create_new_dialog_types', $types, $document_types );
?>
<script type="text/template" id="tmpl-elementor-new-template">
	<div id="elementor-new-template__description">
		<div id="elementor-new-template__description__title"><?php echo PrestaHelper::__( 'Templates Help You <span>Work Efficiently</span>', 'elementor' ); ?></div>
		<div id="elementor-new-template__description__content"><?php echo PrestaHelper::__( 'Use templates to create the different pieces of your site, and reuse them with one click whenever needed.', 'elementor' ); ?></div>
		<?php
		/*
		<div id="elementor-new-template__take_a_tour">
			<i class="ceicon-play-o"></i>
			<a href="#"><?php echo PrestaHelper::__( 'Take The Video Tour', 'elementor' ); ?></a>
		</div>
		*/
		?>
	</div>
	<form id="elementor-new-template__form" action="<?php esc_url( PrestaHelper::admin_url( '/edit.php' ) ); ?>">
		<input type="hidden" name="post_type" value="elementor_library">
		<input type="hidden" name="action" value="elementor_new_post">
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'elementor_action_new_post' ); ?>">
		<div id="elementor-new-template__form__title"><?php echo PrestaHelper::__( 'Choose Template Type', 'elementor' ); ?></div>
		<div id="elementor-new-template__form__template-type__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__template-type" class="elementor-form-field__label"><?php echo PrestaHelper::__( 'Select the type of template you want to work on', 'elementor' ); ?></label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__template-type" class="elementor-form-field__select" name="template_type" required>
					<option value=""><?php echo PrestaHelper::__( 'Select', 'elementor' ); ?>...</option>
					<?php
					foreach ( $types as $value => $type_title ) {
						printf( '<option value="%1$s" %2$s>%3$s</option>', $value, selected( $selected, $value, false ), $type_title );
					}
					?>
				</select>
			</div>
		</div>
		<?php
		/**
		 * @since 1.0.0
		 */
		PrestaHelper::do_action( 'elementor/template-library/create_new_dialog_fields' );
		?>

		<div id="elementor-new-template__form__post-title__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__post-title" class="elementor-form-field__label">
				<?php echo PrestaHelper::__( 'Name your template', 'elementor' ); ?>
			</label>
			<div class="elementor-form-field__text__wrapper">
				<input type="text" placeholder="<?php echo PrestaHelper::esc_attr__( 'Enter template name (optional)', 'elementor' ); ?>" id="elementor-new-template__form__post-title" class="elementor-form-field__text" name="post_data[post_title]">
			</div>
		</div>
		<button id="elementor-new-template__form__submit" class="elementor-button elementor-button-success"><?php echo PrestaHelper::__( 'Create Template', 'elementor' ); ?></button>
	</form>
</script>
