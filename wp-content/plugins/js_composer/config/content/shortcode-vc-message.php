<?php
/**
 * Configuration file for [vc_message] shortcode of 'Message Box' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$pixel_icons = vc_pixel_icons();
$custom_colors = [
	esc_html__( 'Informational', 'js_composer' ) => 'info',
	esc_html__( 'Warning', 'js_composer' ) => 'warning',
	esc_html__( 'Success', 'js_composer' ) => 'success',
	esc_html__( 'Error', 'js_composer' ) => 'danger',
	esc_html__( 'Informational Classic', 'js_composer' ) => 'alert-info',
	esc_html__( 'Warning Classic', 'js_composer' ) => 'alert-warning',
	esc_html__( 'Success Classic', 'js_composer' ) => 'alert-success',
	esc_html__( 'Error Classic', 'js_composer' ) => 'alert-danger',
];

return [
	'name' => esc_html__( 'Message Box', 'js_composer' ),
	'base' => 'vc_message',
	'icon' => 'icon-wpb-information-white',
	'element_default_class' => 'vc_do_message',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Notification box', 'js_composer' ),
	'params' => [
		[
			'type' => 'params_preset',
			'heading' => esc_html__( 'Message Box Presets', 'js_composer' ),
			'param_name' => 'color',
			// due to backward compatibility, really it is message_box_type.
			'value' => '',
			'options' => [
				[
					'label' => esc_html__( 'Custom', 'js_composer' ),
					'value' => '',
					'params' => [],
				],
				[
					'label' => esc_html__( 'Informational', 'js_composer' ),
					'value' => 'info',
					'params' => [
						'message_box_color' => 'info',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fa fa-solid fa-circle-info',
					],
				],
				[
					'label' => esc_html__( 'Warning', 'js_composer' ),
					'value' => 'warning',
					'params' => [
						'message_box_color' => 'warning',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fas fa-exclamation-triangle',
					],
				],
				[
					'label' => esc_html__( 'Success', 'js_composer' ),
					'value' => 'success',
					'params' => [
						'message_box_color' => 'success',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fas fa-check',
					],
				],
				[
					'label' => esc_html__( 'Error', 'js_composer' ),
					'value' => 'danger',
					'params' => [
						'message_box_color' => 'danger',
						'icon_type' => 'fontawesome',
						'icon_fontawesome' => 'fas fa-times',
					],
				],
				[
					'label' => esc_html__( 'Informational Classic', 'js_composer' ),
					'value' => 'alert-info',
					// due to backward compatibility.
					'params' => [
						'message_box_color' => 'alert-info',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-info',
					],
				],
				[
					'label' => esc_html__( 'Warning Classic', 'js_composer' ),
					'value' => 'alert-warning',
					// due to backward compatibility.
					'params' => [
						'message_box_color' => 'alert-warning',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-alert',
					],
				],
				[
					'label' => esc_html__( 'Success Classic', 'js_composer' ),
					'value' => 'alert-success',
					// due to backward compatibility.
					'params' => [
						'message_box_color' => 'alert-success',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-tick',
					],
				],
				[
					'label' => esc_html__( 'Error Classic', 'js_composer' ),
					'value' => 'alert-danger',
					// due to backward compatibility.
					'params' => [
						'message_box_color' => 'alert-danger',
						'icon_type' => 'pixelicons',
						'icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-explanation',
					],
				],
			],
			'description' => esc_html__( 'Select predefined message box design or choose "Custom" for custom styling.', 'js_composer' ),
			'param_holder_class' => 'vc_message-type vc_colored-dropdown',
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Style', 'js_composer' ),
			'param_name' => 'message_box_style',
			'value' => vc_get_shared( 'message_box_styles' ),
			'description' => esc_html__( 'Select message box design style.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Shape', 'js_composer' ),
			'param_name' => 'style',
			// due to backward compatibility message_box_shape.
			'std' => 'rounded',
			'value' => [
				esc_html__( 'Square', 'js_composer' ) => 'square',
				esc_html__( 'Rounded', 'js_composer' ) => 'rounded',
				esc_html__( 'Round', 'js_composer' ) => 'round',
			],
			'description' => esc_html__( 'Select message box shape.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Color', 'js_composer' ),
			'param_name' => 'message_box_color',
			'value' => $custom_colors + vc_get_shared( 'colors' ),
			'description' => esc_html__( 'Select message box color.', 'js_composer' ),
			'param_holder_class' => 'vc_message-type vc_colored-dropdown',
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Icon library', 'js_composer' ),
			'value' => [
				esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
				esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
				esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
				esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
				esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
				esc_html__( 'Pixel', 'js_composer' ) => 'pixelicons',
				esc_html__( 'Mono Social', 'js_composer' ) => 'monosocial',
			],
			'param_name' => 'icon_type',
			'description' => esc_html__( 'Choose icon library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_fontawesome',
			'value' => 'fa fa-solid fa-circle-info',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'iconsPerPage' => 500,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'fontawesome',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_openiconic',
			'value' => 'vc-oi vc-oi-dial',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'openiconic',
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'openiconic',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_typicons',
			'value' => 'typcn typcn-adjust-brightness',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'typicons',
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'typicons',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_entypo',
			'value' => 'entypo-icon entypo-icon-note',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'entypo',
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'entypo',
			],
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_linecons',
			'value' => 'vc_li vc_li-heart',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'linecons',
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'linecons',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_pixelicons',
			'value' => 'vc_pixel_icon vc_pixel_icon-alert',
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'pixelicons',
				'source' => $pixel_icons,
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'pixelicons',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_monosocial',
			'value' => 'vc-mono vc-mono-fivehundredpx',
			// default value to backend editor admin_label.
			'settings' => [
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon.
				'type' => 'monosocial',
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display.
			],
			'dependency' => [
				'element' => 'icon_type',
				'value' => 'monosocial',
			],
			'description' => esc_html__( 'Choose icon from library.', 'js_composer' ),
		],
		[
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => 'messagebox_text',
			'heading' => esc_html__( 'Message text', 'js_composer' ),
			'param_name' => 'content',
			'value' => '<p>' . esc_html__( 'I am message box. Click edit button to change this text.', 'js_composer' ) . '</p>',
		],
		vc_map_add_css_animation( false ),
		[
			'type' => 'el_id',
			'heading' => esc_html__( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		],
		[
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design Options', 'js_composer' ),
			'value' => [
				'padding-top' => '1em',
				'padding-bottom' => '1em',
				'padding-right' => '1em',
				'padding-left' => '4em',
				'margin-bottom' => '22px',
			],
		],
	],
	'js_view' => 'VcMessageView_Backend',
];
