<?php
/**
 * Configuration file for [vc_tab] shortcode of 'Old Tab' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 * @depreacted 4.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Old Tab', 'js_composer' ),
	'base' => 'vc_tab',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'content_element' => false,
	'deprecated' => '4.6',
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'Enter title of tab.', 'js_composer' ),
		],
		[
			'type' => 'tab_id',
			'heading' => esc_html__( 'Tab ID', 'js_composer' ),
			'param_name' => 'tab_id',
		],
	],
	'js_view' => 'VcTabView',
];
