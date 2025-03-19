<?php
/**
 * WPBakery Page Builder shortcode attributes fields
 *
 * @package WPBakeryPageBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Edit form fields builder for shortcode attributes.
 *
 * @since 4.4
 */
class Vc_Edit_Form_Fields {
	/**
	 * Shortcode tag used to identify the shortcode type.
	 *
	 * @since 4.4
	 * @var bool
	 */
	protected $tag = false;
	/**
	 * Array of attributes assigned to the shortcode.
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $atts = [];
	/**
	 * Configuration settings for the shortcode.
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $settings = [];
	/**
	 * Post ID.
	 *
	 * @since 4.4
	 * @var bool
	 */
	protected $post_id = false;

	/**
	 * Construct Form fields.
	 *
	 * @param string $tag - shortcode tag.
	 * @param array $atts - list of attribute assign to the shortcode.
	 * @throws \Exception
	 * @since 4.4
	 */
	public function __construct( $tag, $atts ) {
		$this->tag = $tag;
		$this->atts = apply_filters( 'vc_edit_form_fields_attributes_' . $this->tag, $atts );
		$this->setSettings( WPBMap::getShortCode( $this->tag ) );
	}

	/**
	 * Get settings
	 *
	 * @param string $key
	 *
	 * @return null
	 * @since 4.4
	 */
	public function setting( $key ) {
		return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : null;
	}

	/**
	 * Set settings data
	 *
	 * @param array $settings
	 * @since 4.4
	 */
	public function setSettings( array $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Shortcode Post ID getter.
	 * If post id isn't set try to get from get_the_ID function.
	 *
	 * @return int|bool;
	 * @since 4.4
	 */
	public function postId() {
		if ( ! $this->post_id ) {
			$this->post_id = get_the_ID();
		}

		return $this->post_id;
	}

	/**
	 * Shortcode Post ID setter.
	 *
	 * @param int $post_id - value in post_id.
	 * @since 4.4
	 */
	public function setPostId( $post_id ) {
		$this->post_id = (int) $post_id;
	}

	/**
	 * Get shortcode attribute value.
	 *
	 * This function checks if value isn't set then it uses std or value fields in param settings.
	 *
	 * @param array $param_settings
	 * @param mixed $value
	 *
	 * @since 4.4
	 * @return string
	 */
	protected function parseShortcodeAttributeValue( $param_settings, $value ) {
		if ( is_null( $value ) ) { // If value doesn't exists.
			if ( isset( $param_settings['std'] ) ) {
				$value = $param_settings['std'];
			} elseif ( isset( $param_settings['value'] ) && is_array( $param_settings['value'] ) && ! empty( $param_settings['type'] ) && 'checkbox' !== $param_settings['type'] ) {
				$first_key = key( $param_settings['value'] );
				$value = $first_key ? $param_settings['value'][ $first_key ] : '';
			} elseif ( isset( $param_settings['value'] ) && ! is_array( $param_settings['value'] ) ) {
				$value = $param_settings['value'];
			}
		} elseif ( 'css' == $param_settings['param_name'] && isset( $param_settings['value'] ) && '.vc_custom_' !== substr( $value, 0, 11 ) ) {
			// check if string value is default or modified (modified starts with a class name .vc_custom_[timestamp]).
			$css_values = $param_settings['value'];
			$value = wp_json_encode( $css_values );
		}

		return $value;
	}

	/**
	 * Enqueue js scripts for attributes types.
	 *
	 * @return string
	 * @since 4.4
	 */
	public function enqueueScripts() {
		$output = '';
		$scripts = apply_filters( 'vc_edit_form_enqueue_script', WpbakeryShortcodeParams::getScripts() );
		if ( is_array( $scripts ) ) {
			foreach ( $scripts as $script ) {
				$custom_tag = 'script';
				// @todo Check posibility to use wp_add_inline_script
                // @codingStandardsIgnoreLine
                $output .= '<' . $custom_tag . ' src="' . esc_url( $script ) . '"></' . $custom_tag . '>';
			}
		}

		return $output;
	}

	/**
	 * Render grouped fields.
	 *
	 * @param array $groups
	 * @param array $groups_content
	 *
	 * @return string
	 * @since 4.4
	 */
	protected function renderGroupedFields( $groups, $groups_content ) {
		$output = '';
		if ( count( $groups ) > 1 || ( count( $groups ) >= 1 && empty( $groups_content['_general'] ) ) ) {
			$output .= '<div class="vc_panel-tabs" id="vc_edit-form-tabs">';
			$output .= '<ul class="vc_general vc_ui-tabs-line" data-vc-ui-element="panel-tabs-controls">';
			$key = 0;
			foreach ( $groups as $g ) {
				$output .= '<li class="vc_edit-form-tab-control" data-tab-index="' . esc_attr( $key ) . '"><button data-vc-ui-element-target="#vc_edit-form-tab-' . ( $key++ ) . '" class="vc_ui-tabs-line-trigger" data-vc-ui-element="panel-tab-control">' . ( '_general' === $g ? esc_html__( 'General', 'js_composer' ) : $g ) . '</button></li>';
			}
			$output .= '<li class="vc_ui-tabs-line-dropdown-toggle" data-vc-action="dropdown"
							data-vc-content=".vc_ui-tabs-line-dropdown" data-vc-ui-element="panel-tabs-line-toggle">
							<span class="vc_ui-tabs-line-trigger" data-vc-accordion
									data-vc-container=".vc_ui-tabs-line-dropdown-toggle"
									data-vc-target=".vc_ui-tabs-line-dropdown"> </span>
							<ul class="vc_ui-tabs-line-dropdown" data-vc-ui-element="panel-tabs-line-dropdown">
							</ul>
					</ul>';

			$key = 0;
			foreach ( $groups as $g ) {
				$output .= '<form id="vc_edit-form-tab-' . ( $key++ ) . '" class="vc_edit-form-tab vc_row vc_ui-flex-row" data-vc-ui-element="panel-edit-element-tab">';
				$output .= $groups_content[ $g ];
				$output .= '</form>';
			}
			$output .= '</div>';
		} elseif ( ! empty( $groups_content['_general'] ) ) {
			$output .= '<form class="vc_edit-form-tab vc_row vc_ui-flex-row vc_active" data-vc-ui-element="panel-edit-element-tab">' . $groups_content['_general'] . '</form>';
		}

		return $output;
	}

	/**
	 * Render fields html and output it.
	 *
	 * @since 4.4
	 * vc_filter: vc_edit_form_class - filter to override editor_css_classes array
	 */
	public function render() {
		$this->loadDefaultParams();
		$output = $el_position = '';
		$groups_content = $groups = [];
		$params = $this->setting( 'params' );
		$editor_css_classes = apply_filters( 'vc_edit_form_class', [
			'wpb_edit_form_elements',
			'vc_edit_form_elements',
		], $this->atts, $params );
		$deprecated = $this->setting( 'deprecated' );
		require_once vc_path_dir( 'AUTOLOAD_DIR', 'class-vc-settings-presets.php' );
		$show_settings = false;

		$save_as_template_elements = apply_filters( 'vc_popup_save_as_template_elements', [
			'vc_row',
			'vc_section',
		] );

		$show_presets = ! in_array( $this->tag, $save_as_template_elements, true ) && vc_user_access()->part( 'presets' )->checkStateAny( true, null )->get();

		if ( in_array( $this->tag, $save_as_template_elements, true ) && vc_user_access()->part( 'templates' )->checkStateAny( true, null )->get() ) {
			$show_settings = true;
		}
		$custom_tag = 'script';
		$output .= sprintf( '<' . $custom_tag . '>window.vc_presets_show=%s;</' . $custom_tag . '>', $show_presets ? 'true' : 'false' );
		$output .= sprintf( '<' . $custom_tag . '>window.vc_settings_show=%s;</' . $custom_tag . '>', $show_presets || $show_settings ? 'true' : 'false' );

		if ( ! empty( $deprecated ) ) {
			$output .= '<div class="vc_row vc_ui-flex-row vc_shortcode-edit-form-deprecated-message"><div class="vc_col-sm-12 wpb_element_wrapper">' . vc_message_warning( sprintf( esc_html__( 'You are using outdated element, it is deprecated since version %s.', 'js_composer' ), $this->setting( 'deprecated' ) ) ) . '</div></div>';
		}
		$output .= '<div class="' . implode( ' ', $editor_css_classes ) . '" data-title="' . esc_attr__( 'Edit', 'js_composer' ) . ' ' . esc_attr( $this->setting( 'name' ) ) . '">';
		if ( is_array( $params ) ) {
			foreach ( $params as $param ) {
				$name = isset( $param['param_name'] ) ? $param['param_name'] : null;
				if ( ! is_null( $name ) ) {
					$value = isset( $this->atts[ $name ] ) ? $this->atts[ $name ] : null;
					$value = $this->parseShortcodeAttributeValue( $param, $value );
					$group = isset( $param['group'] ) && '' !== $param['group'] ? $param['group'] : '_general';
					if ( ! isset( $groups_content[ $group ] ) ) {
						$groups[] = $group;
						$groups_content[ $group ] = '';
					}
					$groups_content[ $group ] .= $this->renderField( $param, $value );
				}
			}
		}
		$output .= $this->renderGroupedFields( $groups, $groups_content );
		$output .= '</div>';
		$output .= $this->enqueueScripts();

        // @codingStandardsIgnoreLine
        echo $output;
		do_action( 'vc_edit_form_fields_after_render' );
	}

	/**
	 * Generate html for shortcode attribute.
	 *
	 * @see vc_filter: vc_single_param_edit - hook to edit any shortode param
	 * @see vc_filter: vc_form_fields_render_field_{shortcode_name}_{param_name}_param_value - hook to edit shortcode param
	 *     value vc_filter: vc_form_fields_render_field_{shortcode_name}_{param_name}_param - hook to edit shortcode
	 *     param attributes vc_filter: vc_single_param_edit_holder_output - hook to edit output of this method
	 *
	 * @param array $param
	 *
	 * @return mixed
	 * @since 4.4
	 */
	public function handleHeading( $param ) {
		$heading = '';
		if ( isset( $param['heading'] ) ) {
			$heading .= '<div class="wpb-param-heading"><div class="wpb_element_label">' . $param['heading'] . '</div>';
			$heading_open = true;
		} else {
			$heading_open = false;
		}

		if ( isset( $param['description'] ) ) {
			$heading .= vc_get_template( 'editors/partials/param-info.tpl.php', [ 'description' => $param['description'] ] );
		}

		if ( $heading_open ) {
			$heading .= '</div>'; // Close the heading div if it was opened.
		}

		return $heading;
	}

	/**
	 * Render field.
	 *
	 * @param array $param
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function renderField( $param, $value ) {
		$param['vc_single_param_edit_holder_class'] = [
			'wpb_el_type_' . $param['type'],
			'vc_wrapper-param-type-' . $param['type'],
			'vc_shortcode-param',
			'vc_column',
		];

		if ( ! empty( $param['param_holder_class'] ) ) {
			$param['vc_single_param_edit_holder_class'][] = $param['param_holder_class'];
		}

		$param = apply_filters( 'vc_single_param_edit', $param, $value );
		$output = '<div class="' . implode( ' ', $param['vc_single_param_edit_holder_class'] ) . '" data-vc-ui-element="panel-shortcode-param" data-vc-shortcode-param-name="' . esc_attr( $param['param_name'] ) . '" data-param_type="' . esc_attr( $param['type'] ) . '" data-param_settings="' . htmlentities( wp_json_encode( $param ) ) . '">';
		$output .= $this->handleHeading( $param );
		$output .= '<div class="edit_form_line">';
		$value = apply_filters( 'vc_form_fields_render_field_' . $this->setting( 'base' ) . '_' . $param['param_name'] . '_param_value', $value, $param, $this->settings, $this->atts );
		$param = apply_filters( 'vc_form_fields_render_field_' . $this->setting( 'base' ) . '_' . $param['param_name'] . '_param', $param, $value, $this->settings, $this->atts );
		$output = apply_filters( 'vc_edit_form_fields_render_field_' . $param['type'] . '_before', $output );
		$output .= vc_do_shortcode_param_settings_field( $param['type'], $param, $value, $this->setting( 'base' ) );
		$output_after = '';
		$output_after .= '</div></div>';
		$output .= apply_filters( 'vc_edit_form_fields_render_field_' . $param['type'] . '_after', $output_after );

		return apply_filters( 'vc_single_param_edit_holder_output', $output, $param, $value, $this->settings, $this->atts );
	}

	/**
	 * Create default shortcode params
	 *
	 * List of params stored in global variable $vc_params_list.
	 * Please check include/params/load.php for default params list.
	 *
	 * @return bool
	 * @since 4.4
	 */
	public function loadDefaultParams() {
		global $vc_params_list;
		if ( empty( $vc_params_list ) ) {
			return false;
		}
		$script_url = vc_asset_url( 'js/dist/edit-form.min.js' );
		foreach ( $vc_params_list as $param ) {
			vc_add_shortcode_param( $param, 'vc_' . $param . '_form_field', $script_url );
		}
		do_action( 'vc_load_default_params' );

		return true;
	}
}
