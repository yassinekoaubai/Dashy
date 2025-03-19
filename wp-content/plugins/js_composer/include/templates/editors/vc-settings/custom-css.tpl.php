<?php
/**
 * Custom CSS template.
 *
 * @var array $value
 * @var string $field_prefix
 */

?>

<div class="vc_ui-settings-text-wrapper">
	<?php
	if ( vc_modules_manager()->is_module_on( 'vc-ai' ) ) {
		wpb_add_ai_icon_to_code_field( 'custom_css', 'wpb_css_editor' );
	}
	?>
</div>
<textarea name="<?php echo esc_attr( $field_prefix ); ?>custom_css" class="wpb_code_editor custom_code" style="display:none"><?php echo esc_textarea( $value ); ?></textarea>
<pre id="wpb_css_editor" class="wpb_content_element custom_code" data-ace-location="plugin-settings">
	<?php echo esc_textarea( $value ); ?>
</pre>
