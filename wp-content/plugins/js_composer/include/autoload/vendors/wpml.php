<?php
/**
 * Backward compatibility with "WPML" WordPress plugin.
 *
 * @see https://wpml.org/
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'plugins_loaded', 'vc_init_vendor_wpml' );
/**
 * Initialize WPML vendor.
 */
function vc_init_vendor_wpml() {
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		require_once vc_path_dir( 'VENDORS_DIR', 'plugins/class-vc-vendor-wpml.php' );
		$vendor = new Vc_Vendor_WPML();
		add_action( 'vc_after_set_mode', [
			$vendor,
			'load',
		] );
	}
}
