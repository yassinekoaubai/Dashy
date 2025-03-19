<?php
/**
 * WPBakery Inner Helper API.
 *
 * Helper functions that can be used by 3 party developers to simplify integration with WPBakery.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/
 *
 * @package WPBakeryPageBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * This function is alias for vc_map.
 *
 * @param array $attributes
 * @return bool
 * @throws \Exception
 */
function wpb_map( $attributes ) {
	return vc_map( $attributes );
}

/**
 * Lean map shortcodes.
 *
 * @param string $tag
 * @param null $settings_function
 * @param null $settings_file
 * @since 4.9
 */
function vc_lean_map( $tag, $settings_function = null, $settings_file = null ) {
	WPBMap::leanMap( $tag, $settings_function, $settings_file );
}

/**
 * Add your shortcode to the content elements list.
 *
 * @param array $attributes
 *
 * @return bool
 * @throws \Exception
 * @since 4.2
 */
function vc_map( $attributes ) {
	if ( ! isset( $attributes['base'] ) ) {
		throw new Exception( esc_html__( 'Wrong vc_map object. Base attribute is required', 'js_composer' ) );
	}

	return WPBMap::map( $attributes['base'], $attributes );
}

/**
 * Remove editor element, dropping shortcode of it.
 *
 * @param string $shortcode
 *
 * @since 4.2
 */
function vc_remove_element( $shortcode ) {
	WPBMap::dropShortcode( $shortcode );
}

/**
 * Add new shortcode param.
 *
 * @param string $shortcode - tag for shortcode.
 * @param array $attributes - attribute settings.
 * @throws \Exception
 * @since 4.2
 */
function vc_add_param( $shortcode, $attributes ) {
	WPBMap::addParam( $shortcode, $attributes );
}

/**
 * Mass shortcode params adding function.
 *
 * @param string $shortcode - tag for shortcode.
 * @param array $attributes - list of attributes arrays.
 * @throws \Exception
 * @since 4.3
 */
function vc_add_params( $shortcode, $attributes ) {
	if ( is_array( $attributes ) ) {
		foreach ( $attributes as $attr ) {
			vc_add_param( $shortcode, $attr );
		}
	}
}

/**
 * Shorthand function for WPBMap::modify.
 *
 * @param string $name
 * @param string $setting
 * @param string $value
 *
 * @return array|bool
 * @throws \Exception
 * @since 4.2
 */
function vc_map_update( $name = '', $setting = '', $value = '' ) {
	return WPBMap::modify( $name, $setting, $value );
}

/**
 * Shorthand function for WPBMap::mutateParam.
 *
 * @param string $name
 * @param array $attribute
 *
 * @return bool
 * @throws \Exception
 * @since 4.2
 */
function vc_update_shortcode_param( $name, $attribute = [] ) {
	return WPBMap::mutateParam( $name, $attribute );
}

/**
 * Shorthand function for WPBMap::dropParam.
 *
 * @param string $name
 * @param string $attribute_name
 *
 * @return bool
 * @since 4.2
 */
function vc_remove_param( $name = '', $attribute_name = '' ) {
	return WPBMap::dropParam( $name, $attribute_name );
}

if ( ! function_exists( 'vc_set_as_theme' ) ) {
	/**
	 * Sets plugin as theme plugin.
	 *
	 * @internal param bool $disable_updater - If value is true disables auto updater options.
	 *
	 * @since 4.2
	 */
	function vc_set_as_theme() {
		vc_manager()->setIsAsTheme( true );
	}
}
if ( ! function_exists( 'vc_is_as_theme' ) ) {
	/**
	 * Is VC as-theme-plugin.
	 *
	 * @return bool
	 * @since 4.2
	 */
	function vc_is_as_theme() {
		return vc_manager()->isAsTheme();
	}
}
if ( ! function_exists( 'vc_is_updater_disabled' ) ) {
	/**
	 * Check if plugin updater is disabled.
	 *
	 * @return bool
	 * @since 4.2
	 */
	function vc_is_updater_disabled() {
		return vc_manager()->isUpdaterDisabled();
	}
}
if ( ! function_exists( 'vc_default_editor_post_types' ) ) {
	/**
	 * Returns list of default post type.
	 *
	 * @return array
	 * @since 4.2
	 */
	function vc_default_editor_post_types() {
		return vc_manager()->editorDefaultPostTypes();
	}
}
if ( ! function_exists( 'vc_set_default_editor_post_types' ) ) {
	/**
	 * Set post types for VC editor.
	 *
	 * @param array $type_list - list of valid post types to set.
	 * @since 4.2
	 */
	function vc_set_default_editor_post_types( array $type_list ) {
		vc_manager()->setEditorDefaultPostTypes( $type_list );
	}
}
if ( ! function_exists( ( 'vc_editor_post_types' ) ) ) {
	/**
	 * Returns list of post types where VC editor is enabled.
	 *
	 * @return array
	 * @since 4.2
	 */
	function vc_editor_post_types() {
		return vc_manager()->editorPostTypes();
	}
}
if ( ! function_exists( ( 'vc_editor_set_post_types' ) ) ) {
	/**
	 * Set list of post types where VC editor is enabled.
	 *
	 * @param array $post_types
	 * @throws \Exception
	 * @since 4.4
	 */
	function vc_editor_set_post_types( array $post_types ) {
		vc_manager()->setEditorPostTypes( $post_types );
	}
}
if ( ! function_exists( 'vc_mode' ) ) {
	/**
	 * Return current VC mode.
	 *
	 * @return string
	 * @see Vc_Mapper::$mode
	 * @since 4.2
	 */
	function vc_mode() {
		return vc_manager()->mode();
	}
}
if ( ! function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
	/**
	 * Sets directory where WPBakery Page Builder should look for template files for content elements.
	 *
	 * @param string $dir - full directory path to new template directory with trailing slash.
	 * @since 4.2
	 */
	function vc_set_shortcodes_templates_dir( $dir ) {
		vc_manager()->setCustomUserShortcodesTemplateDir( $dir );
	}
}
if ( ! function_exists( 'vc_shortcodes_theme_templates_dir' ) ) {
	/**
	 * Get custom theme template path.
	 *
	 * @param string $template - filename for template.
	 *
	 * @return string
	 * @since 4.2
	 */
	function vc_shortcodes_theme_templates_dir( $template ) {
		return vc_manager()->getShortcodesTemplateDir( $template );
	}
}

/**
 * Set inline mode.
 *
 * @param bool $value
 *
 * @todo check usage.
 *
 * @since 4.3
 */
function set_vc_is_inline( $value = true ) {
	_deprecated_function( 'set_vc_is_inline', '5.2 (will be removed in 5.3)' );
	global $vc_is_inline;
	$vc_is_inline = $value;
}

/**
 * Disable frontend editor for VC.
 *
 * @param bool $disable
 * @since 4.3
 */
function vc_disable_frontend( $disable = true ) {
	vc_frontend_editor()->disableInline( $disable );
}

/**
 * Check is front end enabled.
 *
 * @return bool
 * @throws \Exception
 * @since 4.3
 */
function vc_enabled_frontend() {
	return vc_frontend_editor()->frontendEditorEnabled();
}

if ( ! function_exists( 'vc_add_default_templates' ) ) {
	/**
	 * Add custom template in default templates list.
	 *
	 * @param array $data | template data (name, content, custom_class, image_path).
	 *
	 * @return bool
	 * @since 4.3
	 */
	function vc_add_default_templates( $data ) {
		return wpbakery()->templatesPanelEditor()->addDefaultTemplates( $data );
	}
}

/**
 * Get element shortcode map include/exclude some param fields.
 *
 * @param array $shortcode
 * @param string $field_prefix
 * @param string $group_prefix
 * @param null|array $change_fields
 * @param null|array $dependency
 * @return array
 * @throws \Exception
 */
function vc_map_integrate_shortcode( $shortcode, $field_prefix = '', $group_prefix = '', $change_fields = null, $dependency = null ) { // phpcs:ignore:Generic.Metrics.CyclomaticComplexity.TooHigh
	if ( is_string( $shortcode ) ) {
		$shortcode_data = WPBMap::getShortCode( $shortcode );
	} else {
		$shortcode_data = $shortcode;
	}
	if ( is_array( $shortcode_data ) && ! empty( $shortcode_data ) ) {
		// WPBakeryShortCodeFishBones $shortcode - base shortcode.
		$params = isset( $shortcode_data['params'] ) && ! empty( $shortcode_data['params'] ) ? $shortcode_data['params'] : false;
		if ( is_array( $params ) && ! empty( $params ) ) {
			$keys = array_keys( $params );
			$count = count( $keys );
			for ( $i = 0; $i < $count; $i++ ) {
				$param = &$params[ $keys[ $i ] ]; // Note! passed by reference to automatically update data.
				if ( isset( $change_fields ) ) {
					$param = vc_map_integrate_include_exclude_fields( $param, $change_fields );
					if ( empty( $param ) ) {
						continue;
					}
				}
				if ( ! empty( $group_prefix ) ) {
					if ( isset( $param['group'] ) ) {
						$param['group'] = $group_prefix . ': ' . $param['group'];
					} else {
						$param['group'] = $group_prefix;
					}
				}
				if ( ! empty( $field_prefix ) && isset( $param['param_name'] ) ) {
					$param['param_name'] = $field_prefix . $param['param_name'];
					if ( isset( $param['dependency'] ) && is_array( $param['dependency'] ) && isset( $param['dependency']['element'] ) ) {
						$param['dependency']['element'] = $field_prefix . $param['dependency']['element'];
					}
					$param = vc_map_integrate_add_dependency( $param, $dependency );

				} elseif ( ! empty( $dependency ) ) {
					$param = vc_map_integrate_add_dependency( $param, $dependency );
				}
				$param['integrated_shortcode'] = is_array( $shortcode ) ? $shortcode['base'] : $shortcode;
				$param['integrated_shortcode_field'] = $field_prefix;
			}
		}

		return is_array( $params ) ? array_filter( $params ) : [];
	}

	return [];
}

/**
 * Used to filter params (include/exclude).
 *
 * @param array $param
 * @param array $change_fields
 *
 * @return array|null
 * @internal
 */
function vc_map_integrate_include_exclude_fields( $param, $change_fields ) {
	if ( ! is_array( $change_fields ) || ! isset( $param['param_name'] ) ) {
		return $param;
	}
	$param_name = $param['param_name'];

	if ( isset( $change_fields['exclude'] ) ) {
		$param = in_array( $param_name, $change_fields['exclude'], true ) ? null : $param;
	} elseif ( isset( $change_fields['exclude_regex'] ) ) {
		$param = vc_map_check_param_field_against_regex( $param, $change_fields['exclude_regex'], 'exclude' );
	}

	if ( isset( $change_fields['include_only'] ) ) {
		$param = ! in_array( $param_name, $change_fields['include_only'], true ) ? null : $param;
	} elseif ( isset( $change_fields['include_only_regex'] ) ) {
		$param = vc_map_check_param_field_against_regex( $param, $change_fields['include_only_regex'], 'include' );
	}

	return $param;
}


if ( ! function_exists( 'vc_map_check_param_field_against_regex' ) ) {
	/**
	 * Check shortcode param against regex.
	 *
	 * @param array $param
	 * @param string|array $regex_list
	 * @param string $condition
	 *
	 * @since 7.8
	 * @return array
	 */
	function vc_map_check_param_field_against_regex( $param, $regex_list, $condition ) { // phpcs:ignore:Generic.Metrics.CyclomaticComplexity.TooHigh
		$check_against = 'exclude' === $condition ? 1 : 0;

		if ( is_array( $regex_list ) && ! empty( $regex_list ) ) {
			$break_foreach = false;

			foreach ( $regex_list as $regex ) {
				if ( wpb_is_regex_valid( $regex ) ) {
					if ( preg_match( $regex, $param['param_name'] ) === $check_against ) {
						$param = null;
						$break_foreach = true;
					}
				}
				if ( $break_foreach ) {
					break;
				}
			}
			if ( $break_foreach ) {
				return $param; // to prevent group adding to $param.
			}
		} elseif ( is_string( $regex_list ) && strlen( $regex_list ) > 0 ) {
			$regex = $regex_list;
			if ( wpb_is_regex_valid( $regex ) ) {
				if ( preg_match( $regex, $param['param_name'] ) === $check_against ) {
					return null; // to prevent group adding to $param.
				}
			}
		}

		return $param;
	}
}

/**
 * Adds a dependency to a parameter if it does not already have one.
 *
 * @param array $param
 * @param mixed $dependency
 *
 * @return array
 * @internal used to add dependency to existed param.
 */
function vc_map_integrate_add_dependency( $param, $dependency ) {
	// activator must be used for all elements who doesn't have 'dependency'.
	if ( ! empty( $dependency ) && ( ! isset( $param['dependency'] ) || empty( $param['dependency'] ) ) ) {
		if ( is_array( $dependency ) ) {
			$param['dependency'] = $dependency;
		}
	}

	return $param;
}

/**
 * Retrieves parameters of a given base shortcode that are associated with a specified integrated shortcode.
 *
 * @param string $base_shortcode
 * @param string $integrated_shortcode
 * @param string $field_prefix
 * @return array
 * @throws \Exception
 */
function vc_map_integrate_get_params( $base_shortcode, $integrated_shortcode, $field_prefix = '' ) {
	$shortcode_data = WPBMap::getShortCode( $base_shortcode );
	$params = [];
	if ( is_array( $shortcode_data ) && is_array( $shortcode_data['params'] ) && ! empty( $shortcode_data['params'] ) ) {
		foreach ( $shortcode_data['params'] as $param ) {
			if ( is_array( $param ) && isset( $param['integrated_shortcode'] ) && $integrated_shortcode === $param['integrated_shortcode'] ) {
				if ( ! empty( $field_prefix ) ) {
					if ( isset( $param['integrated_shortcode_field'] ) && $field_prefix === $param['integrated_shortcode_field'] ) {
						$params[] = $param;
					}
				} else {
					$params[] = $param;
				}
			}
		}
	}

	return $params;
}

/**
 * Retrieves and processes default attributes for integrated shortcodes.
 *
 * This function fetches the parameters for a base shortcode and an integrated shortcode,
 * then processes these parameters to generate a default set of attributes.
 * The resulting associative array of attributes is returned.
 *
 * @param string $base_shortcode
 * @param string $integrated_shortcode
 * @param string $field_prefix
 * @return array
 * @throws \Exception
 */
function vc_map_integrate_get_atts( $base_shortcode, $integrated_shortcode, $field_prefix = '' ) {
	$params = vc_map_integrate_get_params( $base_shortcode, $integrated_shortcode, $field_prefix );
	$atts = [];
	if ( is_array( $params ) && ! empty( $params ) ) {
		foreach ( $params as $param ) {
			$value = '';
			if ( isset( $param['value'] ) ) {
				if ( isset( $param['std'] ) ) {
					$value = $param['std'];
				} elseif ( is_array( $param['value'] ) ) {
					reset( $param['value'] );
					$value = current( $param['value'] );
				} else {
					$value = $param['value'];
				}
			}
			$atts[ $param['param_name'] ] = $value;
		}
	}

	return $atts;
}

/**
 * Parses and integrates attributes between two shortcodes.
 *
 * This function retrieves parameters for a base shortcode and an integrated shortcode,
 * then processes the provided attributes (`$atts`) based on these parameters. It maps
 * the attribute values, and returns an associative array of the processed attributes.
 *
 * @param string $base_shortcode
 * @param string $integrated_shortcode
 * @param array $atts
 * @param string $field_prefix
 * @return array
 * @throws \Exception
 */
function vc_map_integrate_parse_atts( $base_shortcode, $integrated_shortcode, $atts, $field_prefix = '' ) {
	$params = vc_map_integrate_get_params( $base_shortcode, $integrated_shortcode, $field_prefix );
	$data = [];
	if ( is_array( $params ) && ! empty( $params ) ) {
		foreach ( $params as $param ) {
			$value = '';
			if ( isset( $atts[ $param['param_name'] ] ) ) {
				$value = $atts[ $param['param_name'] ];
			}
			if ( isset( $value ) ) {
				$key = $param['param_name'];
				if ( strlen( $field_prefix ) > 0 ) {
					$key = substr( $key, strlen( $field_prefix ) );
				}
				$data[ $key ] = $value;
			}
		}
	}

	return $data;
}

/**
 * Get css animation for shortcode params.
 *
 * @param bool $label
 * @return mixed|void
 */
function vc_map_add_css_animation( $label = true ) {
	$data = [
		'type' => 'animation_style',
		'heading' => esc_html__( 'CSS Animation', 'js_composer' ),
		'param_name' => 'css_animation',
		'admin_label' => $label,
		'value' => '',
		'settings' => [
			'type' => 'in',
			'custom' => [
				[
					'label' => esc_html__( 'Default', 'js_composer' ),
					'values' => [
						esc_html__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
						esc_html__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
						esc_html__( 'Left to right', 'js_composer' ) => 'left-to-right',
						esc_html__( 'Right to left', 'js_composer' ) => 'right-to-left',
						esc_html__( 'Appear from center', 'js_composer' ) => 'appear',
					],
				],
			],
		],
		'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'js_composer' ),
	];

	return apply_filters( 'vc_map_add_css_animation', $data, $label );
}

/**
 * Get settings of the mapped shortcode.
 *
 * @param string $tag
 *
 * @return array|null - settings or null if shortcode not mapped.
 * @throws \Exception
 * @since 4.4.3
 */
function vc_get_shortcode( $tag ) {
	return WPBMap::getShortCode( $tag );
}

/**
 * Remove all mapped shortcodes and the moment when function is called.
 *
 * @since 4.5
 */
function vc_remove_all_elements() {
	WPBMap::dropAllShortcodes();
}

/**
 * Function to get defaults values for shortcode.
 *
 * @param string $tag - shortcode tag.
 * @return array - list of param=>default_value.
 * @throws \Exception
 * @since 4.6
 */
function vc_map_get_defaults( $tag ) {
	$shortcode = vc_get_shortcode( $tag );
	$params = [];
	if ( is_array( $shortcode ) && isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
		$params = vc_map_get_params_defaults( $shortcode['params'] );
	}

	return $params;
}

/**
 * Use it when you have modified shortcode params and need to get defaults.
 *
 * @param array $params
 *
 * @return array
 * @since 4.12
 */
function vc_map_get_params_defaults( $params ) {
	$result_params = [];
	foreach ( $params as $param ) {
		if ( isset( $param['param_name'] ) && 'content' !== $param['param_name'] ) {
			$value = '';
			if ( isset( $param['std'] ) ) {
				$value = $param['std'];
			} elseif ( isset( $param['value'] ) ) {
				if ( is_array( $param['value'] ) ) {
					$value = current( $param['value'] );
					if ( is_array( $value ) ) {
						// in case if two-dimensional array provided (vc_basic_grid).
						$value = current( $value );
					}
					// return first value from array (by default).
				} else {
					$value = $param['value'];
				}
			}
			$result_params[ $param['param_name'] ] = apply_filters( 'vc_map_get_param_defaults', $value, $param );
		}
	}

	return $result_params;
}

/**
 * Get attributes for shortcode.
 *
 * @param string $tag - shortcode tag.
 * @param array $atts - shortcode attributes.
 *
 * @return array - return merged values with provided attributes (
 *     'a'=>1,'b'=>2 + 'b'=>3,'c'=>4 --> 'a'=>1,'b'=>3 )
 *
 * @throws \Exception
 * @see vc_shortcode_attribute_parse - return union of provided attributes (
 *     'a'=>1,'b'=>2 + 'b'=>3,'c'=>4 --> 'a'=>1,
 *     'b'=>3, 'c'=>4 )
 */
function vc_map_get_attributes( $tag, $atts = [] ) {
	$atts = shortcode_atts( vc_map_get_defaults( $tag ), $atts, $tag );

	return apply_filters( 'vc_map_get_attributes', $atts, $tag );
}

/**
 * Convert color name to hex.
 *
 * @param string $name
 * @return mixed|string
 */
function vc_convert_vc_color( $name ) {
	$colors = [
		'blue' => '#5472d2',
		'turquoise' => '#00c1cf',
		'pink' => '#fe6c61',
		'violet' => '#8d6dc4',
		'peacoc' => '#4cadc9',
		'chino' => '#cec2ab',
		'mulled-wine' => '#50485b',
		'vista-blue' => '#75d69c',
		'orange' => '#f7be68',
		'sky' => '#5aa1e3',
		'green' => '#6dab3c',
		'juicy-pink' => '#f4524d',
		'sandy-brown' => '#f79468',
		'purple' => '#b97ebb',
		'black' => '#2a2a2a',
		'grey' => '#ebebeb',
		'white' => '#ffffff',
	];
	$name = str_replace( '_', '-', $name );
	if ( isset( $colors[ $name ] ) ) {
		return $colors[ $name ];
	}

	return '';
}

/**
 * Extract width/height from string
 *
 * @param string $dimensions WxH.
 *
 * @return mixed array(width, height) or false
 * @since 4.7
 */
function vc_extract_dimensions( $dimensions ) {
	$dimensions = str_replace( ' ', '', $dimensions );
	$matches = null;

	if ( preg_match( '/(\d+)x(\d+)/', $dimensions, $matches ) ) {
		return [
			$matches[1],
			$matches[2],
		];
	}

	return false;
}

/**
 * Get shared library for a specific asset.
 *
 * @param string $asset
 *
 * @return array|string
 */
function vc_get_shared( $asset = '' ) { // phpcs:ignore:Generic.Metrics.CyclomaticComplexity.TooHigh
	switch ( $asset ) {
		case 'colors':
			$asset = VcSharedLibrary::getColors();
			break;

		case 'colors-dashed':
			$asset = VcSharedLibrary::getColorsDashed();
			break;

		case 'icons':
			$asset = VcSharedLibrary::getIcons();
			break;

		case 'sizes':
			$asset = VcSharedLibrary::getSizes();
			break;

		case 'button styles':
		case 'alert styles':
			$asset = VcSharedLibrary::getButtonStyles();
			break;
		case 'message_box_styles':
			$asset = VcSharedLibrary::getMessageBoxStyles();
			break;
		case 'cta styles':
			$asset = VcSharedLibrary::getCtaStyles();
			break;

		case 'text align':
			$asset = VcSharedLibrary::getTextAlign();
			break;

		case 'cta widths':
		case 'separator widths':
			$asset = VcSharedLibrary::getElementWidths();
			break;

		case 'separator styles':
			$asset = VcSharedLibrary::getSeparatorStyles();
			break;

		case 'separator border widths':
			$asset = VcSharedLibrary::getBorderWidths();
			break;

		case 'single image styles':
			$asset = VcSharedLibrary::getBoxStyles();
			break;

		case 'single image external styles':
			$asset = VcSharedLibrary::getBoxStyles( [
				'default',
				'round',
			] );
			break;

		case 'toggle styles':
			$asset = VcSharedLibrary::getToggleStyles();
			break;

		case 'animation styles':
			$asset = VcSharedLibrary::getAnimationStyles();
			break;
	}

	return $asset;
}

/**
 * Helper function to register new shortcode attribute hook.
 *
 * @param string $name - attribute name.
 * @param callable $form_field_callback - hook, will be called when settings form is shown and attribute added to shortcode
 *     param list.
 * @param string $script_url - javascript file url which will be attached at the end of settings form.
 *
 * @return bool
 * @since 4.4
 */
function vc_add_shortcode_param( $name, $form_field_callback, $script_url = null ) {
	return WpbakeryShortcodeParams::addField( $name, $form_field_callback, $script_url );
}

/**
 * Call hook for attribute.
 *
 * @param string $name - attribute name.
 * @param array $param_settings - attribute settings from shortcode.
 * @param mixed $param_value - attribute value.
 * @param string $tag - attribute tag.
 *
 * @return mixed|string - returns html which will be render in hook
 * @since 4.4
 */
function vc_do_shortcode_param_settings_field( $name, $param_settings, $param_value, $tag ) {
	return WpbakeryShortcodeParams::renderSettingsField( $name, $param_settings, $param_value, $tag );
}
