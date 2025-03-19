<?php
/**
 * Frontend editor template.
 *
 * @var Vc_Frontend_Editor $editor
 * @var bool $wpb_vc_status
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

global $menu, $submenu, $parent_file, $post_ID, $post, $post_type, $post_type_object, $plugin_page, $title;
$post_ID = $editor->post_id;
$post = $editor->post;
$post_type = $post->post_type;
$post_type_object = get_post_type_object( $post_type );
$post_title = trim( $post->post_title );
$nonce_action = $nonce_action = 'update-post_' . $editor->post_id;
$user_ID = isset( $editor->current_user ) && isset( $editor->current_user->ID ) ? (int) $editor->current_user->ID : 0;
$form_action = 'editpost';
$menu = [];
$plugin_page = 'js_composer';
$title = __( 'Frontend Editor', 'js_composer' );
// we use it in case to repair editor if iframe url has redirect.
$editor->setFrontendEditorTransient( $post_ID );
add_thickbox();
wp_enqueue_media( [ 'post' => $editor->post_id ] );
require_once $editor->adminFile( 'admin-header.php' );
// @since 4.8 js logic for user role access manager.
vc_include_template( 'editors/partials/access-manager-js.tpl.php' );
$custom_tag = 'script';
$modules = vc_modules_manager()->get_settings();
?>
	<div id="vc_preloader"></div>
	<div id="vc_overlay_spinner" class="vc_ui-wp-spinner vc_ui-wp-spinner-dark vc_ui-wp-spinner-lg" style="display:none;"></div>
	<<?php echo esc_attr( $custom_tag ); ?>>
		document.getElementById( 'vc_preloader' ).style.height = window.screen.availHeight;
		window.vc_mode = '<?php echo esc_js( vc_mode() ); ?>';
		window.vc_iframe_src = '<?php echo esc_js( $editor->url ); ?>';
		window.wpbGutenbergEditorUrl = '<?php echo esc_js( set_url_scheme( admin_url( 'post-new.php?post_type=wpb_gutenberg_param' ) ) ); ?>';
		window.vc_modules = <?php echo wp_json_encode( $modules ); ?>;
	</<?php echo esc_attr( $custom_tag ); ?>>
	<?php // we use vc_title-saved value to check if we should open settings when clicking to h1 tag that has value saved by us. ?>
	<input type="hidden" name="vc_post_title" id="vc_title-saved" value="<?php echo esc_attr( $post_title ); ?>"/>
	<input type="hidden" name="vc_post_id" id="vc_post-id" value="<?php echo esc_attr( $editor->post_id ); ?>"/>
<?php

// [vc_navbar frontend].
require_once vc_path_dir( 'EDITORS_DIR', 'navbar/class-vc-navbar-frontend.php' );
$nav_bar = new Vc_Navbar_Frontend( $post );
$nav_bar->render();
// [/vc_navbar frontend]
if ( vc_modules_manager()->is_module_on( 'vc-post-custom-layout' ) ) {
	$custom_layout = vc_modules_manager()->get_module( 'vc-post-custom-layout' );
	if ( $custom_layout->get_custom_layout_name() ) {
		$template_class = ' vc_post-custom-layout-selected';
	} else {
		$template_class = '';
	}
} else {
	$template_class = ' vc_post-custom-layout-selected';
}
?>
<div id="vc_no-content-helper"
	class="vc_welcome vc_select-post-custom-layout-frontend-editor vc_ui-font-open-sans <?php echo esc_attr( $template_class ); ?>">
	<?php
	vc_include_template(
		'editors/partials/start-logo.tpl.php'
	);
	vc_include_template(
		'editors/partials/start-select-layout-title.tpl.php'
	);
	if ( vc_modules_manager()->is_module_on( 'vc-post-custom-layout' ) ) {
		vc_include_template(
			'editors/partials/vc_post_custom_layout.tpl.php',
			[ 'location' => 'welcome' ]
		);
	}
	?>
</div>

<div id="vc_inline-frame-wrapper" class="<?php echo esc_attr( $template_class ); ?> vc_selected-post-custom-layout-visible-e"></div>

<?php
vc_include_template( 'editors/partials/footer.tpl.php',
	[
		'editor' => $editor,
		'post' => $post,
	]
);

// fe controls.
vc_include_template( 'editors/partials/frontend_controls.tpl.php' );

// [shortcodes presets data]
if ( vc_user_access()->part( 'presets' )->can()->get() ) {
	require_once vc_path_dir( 'AUTOLOAD_DIR', 'class-vc-settings-presets.php' );
	$vc_vendor_settings_presets = Vc_Settings_Preset::listDefaultVendorSettingsPresets();
	$vc_all_presets = Vc_Settings_Preset::listAllPresets();
} else {
	$vc_vendor_settings_presets = [];
	$vc_all_presets = [];
}
// [/shortcodes presets data]

vc_include_template(
	'editors/partials/vc_post_custom_meta.tpl.php',
	[ 'editor' => $editor ]
);
?>
<<?php echo esc_attr( $custom_tag ); ?>>
	window.vc_user_mapper = <?php echo wp_json_encode( WPBMap::getUserShortCodes() ); ?>;
	window.vc_mapper = <?php echo wp_json_encode( WPBMap::getShortCodes() ); ?>;
	window.vc_vendor_settings_presets = <?php echo wp_json_encode( $vc_vendor_settings_presets ); ?>;
	window.vc_all_presets = <?php echo wp_json_encode( $vc_all_presets ); ?>;
	window.vc_roles = [];
	window.vcAdminNonce = '<?php echo esc_js( vc_generate_nonce( 'vc-admin-nonce' ) ); ?>';
	window.wpb_js_google_fonts_save_nonce = '<?php echo esc_js( wp_create_nonce( 'wpb_js_google_fonts_save' ) ); ?>';
	window.wpb_vc_js_status = '<?php echo esc_js( wp_json_encode( $wpb_vc_status ) ); ?>';
	window.vc_post_id = <?php echo esc_js( $post_ID ); ?>;
	window.vc_auto_save = <?php echo wp_json_encode( get_option( 'wpb_js_auto_save' ) ); ?>;
</<?php echo esc_attr( $custom_tag ); ?>>

<?php vc_include_template( 'editors/partials/vc_settings-image-block.tpl.php' ); ?>
<!-- BC for older plugins 5.5 !-->
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo esc_attr( $post_ID ); ?>"/>
	<div style="height: 1px; visibility: hidden; overflow: hidden;">
		<?php
		// Disable notice in edit-form-advanced.php.
		$is_IE = false;
		wp_editor( '', 'vc-hidden-editor', [
			'editor_height' => 300,
			'tinymce' => [
				'resize' => false,
				'wp_autoresize_on' => false,
				'add_unload_trigger' => false,
				'wp_keep_scroll_position' => ! $is_IE,
			],
		] );
		// Fix: WP 4.0.
		wp_dequeue_script( 'editor-expand' );
		do_action( 'vc_frontend_editor_render_template' );
		?>
	</div>
<?php

// other admin footer files and actions.
require_once $editor->adminFile( 'admin-footer.php' );
