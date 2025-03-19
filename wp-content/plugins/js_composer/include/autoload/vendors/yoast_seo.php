<?php
/**
 * Backward compatibility with "Yoast" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/wordpress-seo
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// 16 is required to be called after WPSEO_Admin_Init constructor. @since 4.9
add_action( 'plugins_loaded', 'vc_init_vendor_yoast', 16 );
/**
 * Init Yoast SEO vendor.
 */
function vc_init_vendor_yoast() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php'; // Require class-vc-wxr-parser-plugin.php to use is_plugin_active() below.
	if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || class_exists( 'WPSEO_Metabox' ) ) {
		require_once vc_path_dir( 'VENDORS_DIR', 'plugins/class-vc-vendor-yoast_seo.php' );
		$vendor = new Vc_Vendor_YoastSeo();
		if ( defined( 'WPSEO_VERSION' ) && version_compare( WPSEO_VERSION, '3.0.0' ) === - 1 ) {
			add_action( 'vc_after_set_mode', [
				$vendor,
				'load',
			] );
		} elseif ( is_admin() && 'vc_inline' === vc_action() ) {
			$vendor->frontendEditorBuild();
		}
	}
}
