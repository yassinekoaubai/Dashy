<?php
/**
 * Autoload hooks for [vc_progress_bar] shortcode of 'Progress Bar' element.
 *
 * @note we require our autoload files everytime and everywhere after plugin load.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
	VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Progress_Bar' );

	add_filter( 'vc_edit_form_fields_attributes_vc_progress_bar', [
		'WPBakeryShortCode_Vc_Progress_Bar',
		'convertAttributesToNewProgressBar',
	] );
}
