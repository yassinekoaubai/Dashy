<?php
/**
 * Configuration class for grid attributes.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once __DIR__ . '/vc-grids-functions.php';
if ( ! class_exists( 'VcGridsCommon' ) ) {
	/**
	 * Class VcGridsCommon.
	 */
	abstract class VcGridsCommon {

		/**
		 * Basic grid attributes.
		 *
		 * @var array
		 */
		protected static $basicGrid;
		/**
		 * Masonry grid attributes.
		 *
		 * @var array
		 */
		protected static $masonryGrid;
		/**
		 * Masonry media grid attributes.
		 *
		 * @var array
		 */
		protected static $masonryMediaGrid;
		/**
		 * Media grid attributes.
		 *
		 * @var array
		 */
		protected static $mediaGrid;
		/**
		 * Buttons params.
		 *
		 * @var array
		 */
		protected static $btn3Params;
		/**
		 * Grid columns list.
		 *
		 * @var array
		 */
		protected static $gridColsList;

		/**
		 * Set initial data.
		 */
		protected static function initData() {
			self::$btn3Params = vc_map_integrate_shortcode( 'vc_btn', 'btn_', esc_html__( 'Load More Button', 'js_composer' ), [
				'exclude' => [
					'link',
					'css',
					'i_css',
					'el_class',
					'css_animation',
				],
			], [
				'element' => 'style',
				'value' => [ 'load-more' ],
			] );
			foreach ( self::$btn3Params as $key => $value ) {
				if ( 'btn_title' === $value['param_name'] ) {
					self::$btn3Params[ $key ]['value'] = esc_html__( 'Load more', 'js_composer' );
				} elseif ( 'btn_color' === $value['param_name'] ) {
					self::$btn3Params[ $key ]['std'] = 'blue';
				} elseif ( 'btn_style' === $value['param_name'] ) {
					self::$btn3Params[ $key ]['std'] = 'flat';
				}
			}

			// Grid column list.
			self::$gridColsList = [
				[
					'label' => '6',
					'value' => 2,
				],
				[
					'label' => '4',
					'value' => 3,
				],
				[
					'label' => '3',
					'value' => 4,
				],
				[
					'label' => '2',
					'value' => 6,
				],
				[
					'label' => '1',
					'value' => 12,
				],
			];
		}

		/**
		 * Basic Grid Common Settings.
		 */
		public static function getBasicAtts() {

			if ( self::$basicGrid ) {
				return self::$basicGrid;
			}

			if ( is_null( self::$btn3Params ) && is_null( self::$gridColsList ) ) {
				self::initData();
			}

			$post_types = get_post_types();
			$post_types_list = [];
			$excluded_post_types = [
				'revision',
				'nav_menu_item',
				'vc_grid_item',
			];
			if ( is_array( $post_types ) && ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					if ( ! in_array( $post_type, $excluded_post_types, true ) ) {
						$label = ucfirst( $post_type );
						$post_types_list[] = [
							$post_type,
							$label,
						];
					}
				}
			}
			$post_types_list[] = [
				'custom',
				esc_html__( 'Custom query', 'js_composer' ),
			];
			$post_types_list[] = [
				'ids',
				esc_html__( 'List of IDs', 'js_composer' ),
			];

			$taxonomies_for_filter = [];

			if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
				$vc_taxonomies_types = vc_taxonomies_types();
				if ( is_array( $vc_taxonomies_types ) && ! empty( $vc_taxonomies_types ) ) {
					foreach ( $vc_taxonomies_types as $t => $data ) {
						if ( 'post_format' !== $t && is_object( $data ) ) {
							$taxonomies_for_filter[ $data->labels->name . '(' . $t . ')' ] = $t;
						}
					}
				}
			}

			self::$basicGrid = array_merge( [
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Data source', 'js_composer' ),
					'param_name' => 'post_type',
					'value' => $post_types_list,
					'save_always' => true,
					'description' => esc_html__( 'Select content type for your grid.', 'js_composer' ),
					'admin_label' => true,
				],
				[
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include only', 'js_composer' ),
					'param_name' => 'include',
					'description' => esc_html__( 'Add posts, pages, etc. by title.', 'js_composer' ),
					'settings' => [
						'multiple' => true,
						'sortable' => true,
						'groups' => true,
					],
					'dependency' => [
						'element' => 'post_type',
						'value' => [ 'ids' ],
					],
				],
				// Custom query tab.
				[
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Custom query', 'js_composer' ),
					'param_name' => 'custom_query',
					'description' => sprintf( esc_html__( 'Build custom query according to %1$sWordPress Codex%2$s.', 'js_composer' ), '<a href="https://codex.wordpress.org/Function_Reference/query_posts">', '</a>' ),

					'dependency' => [
						'element' => 'post_type',
						'value' => [ 'custom' ],
					],
				],
				[
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Narrow data source', 'js_composer' ),
					'param_name' => 'taxonomies',
					'settings' => [
						'multiple' => true,
						'min_length' => 1,
						'groups' => true,
						// In UI show results grouped by groups, default false.
						'unique_values' => true,
						// In UI show results except selected. NB! You should manually check values in backend, default false.
						'display_inline' => true,
						// In UI show results inline view, default false (each value in own line).
						'delay' => 500,
						// delay for search. default 500.
						'auto_focus' => true,
						// auto focus input, default true.
					],
					'param_holder_class' => 'vc_not-for-custom',
					'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'js_composer' ),
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
						'callback' => 'vcGridTaxonomiesCallBack',
					],
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Total items', 'js_composer' ),
					'param_name' => 'max_items',
					'value' => 10,
					// default value.
					'param_holder_class' => 'vc_not-for-custom',
					'description' => esc_html__( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'js_composer' ),
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
					],
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Display Style', 'js_composer' ),
					'param_name' => 'style',
					'value' => [
						esc_html__( 'Show all', 'js_composer' ) => 'all',
						esc_html__( 'Load more button', 'js_composer' ) => 'load-more',
						esc_html__( 'Lazy loading', 'js_composer' ) => 'lazy',
						esc_html__( 'Pagination', 'js_composer' ) => 'pagination',
					],
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [ 'custom' ],
					],
					'edit_field_class' => 'vc_col-sm-6',
					'description' => esc_html__( 'Select display style for grid.', 'js_composer' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Items per page', 'js_composer' ),
					'param_name' => 'items_per_page',
					'description' => esc_html__( 'Number of items to show per page.', 'js_composer' ),
					'value' => '10',
					'dependency' => [
						'element' => 'style',
						'value' => [
							'lazy',
							'load-more',
							'pagination',
						],
					],
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type' => 'checkbox',
					'heading' => esc_html__( 'Show filter', 'js_composer' ),
					'param_name' => 'show_filter',
					'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
					'description' => esc_html__( 'Append filter to grid.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Grid elements per row', 'js_composer' ),
					'param_name' => 'element_width',
					'value' => self::$gridColsList,
					'std' => '4',
					'edit_field_class' => 'vc_col-sm-6',
					'description' => esc_html__( 'Select number of single grid elements per row.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Gap', 'js_composer' ),
					'param_name' => 'gap',
					'value' => [
						'0px' => '0',
						'1px' => '1',
						'2px' => '2',
						'3px' => '3',
						'4px' => '4',
						'5px' => '5',
						'10px' => '10',
						'15px' => '15',
						'20px' => '20',
						'25px' => '25',
						'30px' => '30',
						'35px' => '35',
					],
					'std' => '30',
					'description' => esc_html__( 'Select gap between grid elements.', 'js_composer' ),
					'edit_field_class' => 'vc_col-sm-6',
				],
				// Data settings.
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order by', 'js_composer' ),
					'param_name' => 'orderby',
					'value' => [
						esc_html__( 'Date', 'js_composer' ) => 'date',
						esc_html__( 'Order by post ID', 'js_composer' ) => 'ID',
						esc_html__( 'Author', 'js_composer' ) => 'author',
						esc_html__( 'Title', 'js_composer' ) => 'title',
						esc_html__( 'Last modified date', 'js_composer' ) => 'modified',
						esc_html__( 'Post/page parent ID', 'js_composer' ) => 'parent',
						esc_html__( 'Number of comments', 'js_composer' ) => 'comment_count',
						esc_html__( 'Menu order/Page Order', 'js_composer' ) => 'menu_order',
						esc_html__( 'Meta value', 'js_composer' ) => 'meta_value',
						esc_html__( 'Meta value number', 'js_composer' ) => 'meta_value_num',
						esc_html__( 'Random order', 'js_composer' ) => 'rand',
					],
					'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
					'group' => esc_html__( 'Data Settings', 'js_composer' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
					],
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Sort order', 'js_composer' ),
					'param_name' => 'order',
					'group' => esc_html__( 'Data Settings', 'js_composer' ),
					'value' => [
						esc_html__( 'Descending', 'js_composer' ) => 'DESC',
						esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
					],
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
					],
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta key', 'js_composer' ),
					'param_name' => 'meta_key',
					'description' => esc_html__( 'Input meta key for grid ordering.', 'js_composer' ),
					'group' => esc_html__( 'Data Settings', 'js_composer' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => [
						'element' => 'orderby',
						'value' => [
							'meta_value',
							'meta_value_num',
						],
					],
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Offset', 'js_composer' ),
					'param_name' => 'offset',
					'description' => esc_html__( 'Number of grid elements to displace or pass over.', 'js_composer' ),
					'group' => esc_html__( 'Data Settings', 'js_composer' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
					],
				],
				[
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude', 'js_composer' ),
					'param_name' => 'exclude',
					'description' => esc_html__( 'Exclude posts, pages, etc. by title.', 'js_composer' ),
					'group' => esc_html__( 'Data Settings', 'js_composer' ),
					'settings' => [
						'multiple' => true,
					],
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [
							'ids',
							'custom',
						],
						'callback' => 'vc_grid_exclude_dependency_callback',
					],
				],
				// Filter tab.
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Filter by', 'js_composer' ),
					'param_name' => 'filter_source',
					'value' => $taxonomies_for_filter,
					'group' => esc_html__( 'Filter', 'js_composer' ),
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'save_always' => true,
					'description' => esc_html__( 'Select filter source.', 'js_composer' ),
				],
				[
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude from filter list', 'js_composer' ),
					'param_name' => 'exclude_filter',
					'settings' => [
						'multiple' => true,
						// is multiple values allowed? default false.
						'min_length' => 1,
						// min length to start search -> default 2.
						'groups' => true,
						// In UI show results grouped by groups, default false.
						'unique_values' => true,
						// In UI show results except selected. NB! You should manually check values in backend, default false.
						'display_inline' => true,
						// In UI show results inline view, default false (each value in own line).
						'delay' => 500,
						// delay for search. default 500.
						'auto_focus' => true,
						// auto focus input, default true.
					],
					'description' => esc_html__( 'Enter categories, tags won\'t be shown in the filters list', 'js_composer' ),
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
						'callback' => 'vcGridFilterExcludeCallBack',
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'js_composer' ),
					'param_name' => 'filter_style',
					'value' => [
						esc_html__( 'Rounded', 'js_composer' ) => 'default',
						esc_html__( 'Less Rounded', 'js_composer' ) => 'default-less-rounded',
						esc_html__( 'Border', 'js_composer' ) => 'bordered',
						esc_html__( 'Rounded Border', 'js_composer' ) => 'bordered-rounded',
						esc_html__( 'Less Rounded Border', 'js_composer' ) => 'bordered-rounded-less',
						esc_html__( 'Filled', 'js_composer' ) => 'filled',
						esc_html__( 'Rounded Filled', 'js_composer' ) => 'filled-rounded',
						esc_html__( 'Dropdown', 'js_composer' ) => 'dropdown',
					],
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
					'description' => esc_html__( 'Select filter display style.', 'js_composer' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Default title', 'js_composer' ),
					'param_name' => 'filter_default_title',
					'value' => esc_html__( 'All', 'js_composer' ),
					'description' => esc_html__( 'Enter default title for filter option display (empty: "All").', 'js_composer' ),
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Alignment', 'js_composer' ),
					'param_name' => 'filter_align',
					'value' => [
						esc_html__( 'Center', 'js_composer' ) => 'center',
						esc_html__( 'Left', 'js_composer' ) => 'left',
						esc_html__( 'Right', 'js_composer' ) => 'right',
					],
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
					'description' => esc_html__( 'Select filter alignment.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Color', 'js_composer' ),
					'param_name' => 'filter_color',
					'value' => vc_get_shared( 'colors' ),
					'std' => 'grey',
					'param_holder_class' => 'vc_colored-dropdown',
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
					'description' => esc_html__( 'Select filter color.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Filter size', 'js_composer' ),
					'param_name' => 'filter_size',
					'value' => vc_get_shared( 'sizes' ),
					'std' => 'md',
					'description' => esc_html__( 'Select filter size.', 'js_composer' ),
					'dependency' => [
						'element' => 'show_filter',
						'value' => [ 'yes' ],
					],
					'group' => esc_html__( 'Filter', 'js_composer' ),
				],
				// moved to the end.
				// Paging controls.
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows design', 'js_composer' ),
					'param_name' => 'arrows_design',
					'value' => [
						esc_html__( 'None', 'js_composer' ) => 'none',
						esc_html__( 'Simple', 'js_composer' ) => 'vc_arrow-icon-arrow_01_left',
						esc_html__( 'Simple Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_02_left',
						esc_html__( 'Simple Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_03_left',
						esc_html__( 'Simple Square', 'js_composer' ) => 'vc_arrow-icon-arrow_09_left',
						esc_html__( 'Simple Square Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_12_left',
						esc_html__( 'Simple Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_11_left',
						esc_html__( 'Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_04_left',
						esc_html__( 'Rounded Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_05_left',
						esc_html__( 'Rounded Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_06_left',
						esc_html__( 'Rounded Square', 'js_composer' ) => 'vc_arrow-icon-arrow_10_left',
						esc_html__( 'Simple Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_08_left',
						esc_html__( 'Simple Rounded Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_07_left',

					],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select design for arrows.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows position', 'js_composer' ),
					'param_name' => 'arrows_position',
					'value' => [
						esc_html__( 'Inside Wrapper', 'js_composer' ) => 'inside',
						esc_html__( 'Outside Wrapper', 'js_composer' ) => 'outside',
					],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'arrows_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Arrows will be displayed inside or outside grid.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows color', 'js_composer' ),
					'param_name' => 'arrows_color',
					'value' => vc_get_shared( 'colors' ),
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'arrows_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Select color for arrows.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination style', 'js_composer' ),
					'param_name' => 'paging_design',
					'value' => [
						esc_html__( 'None', 'js_composer' ) => 'none',
						esc_html__( 'Square Dots', 'js_composer' ) => 'square_dots',
						esc_html__( 'Radio Dots', 'js_composer' ) => 'radio_dots',
						esc_html__( 'Point Dots', 'js_composer' ) => 'point_dots',
						esc_html__( 'Fill Square Dots', 'js_composer' ) => 'fill_square_dots',
						esc_html__( 'Rounded Fill Square Dots', 'js_composer' ) => 'round_fill_square_dots',
						esc_html__( 'Pagination Default', 'js_composer' ) => 'pagination_default',
						esc_html__( 'Outline Default Dark', 'js_composer' ) => 'pagination_default_dark',
						esc_html__( 'Outline Default Light', 'js_composer' ) => 'pagination_default_light',
						esc_html__( 'Pagination Rounded', 'js_composer' ) => 'pagination_rounded',
						esc_html__( 'Outline Rounded Dark', 'js_composer' ) => 'pagination_rounded_dark',
						esc_html__( 'Outline Rounded Light', 'js_composer' ) => 'pagination_rounded_light',
						esc_html__( 'Pagination Square', 'js_composer' ) => 'pagination_square',
						esc_html__( 'Outline Square Dark', 'js_composer' ) => 'pagination_square_dark',
						esc_html__( 'Outline Square Light', 'js_composer' ) => 'pagination_square_light',
						esc_html__( 'Pagination Rounded Square', 'js_composer' ) => 'pagination_rounded_square',
						esc_html__( 'Outline Rounded Square Dark', 'js_composer' ) => 'pagination_rounded_square_dark',
						esc_html__( 'Outline Rounded Square Light', 'js_composer' ) => 'pagination_rounded_square_light',
						esc_html__( 'Stripes Dark', 'js_composer' ) => 'pagination_stripes_dark',
						esc_html__( 'Stripes Light', 'js_composer' ) => 'pagination_stripes_light',
					],
					'std' => 'radio_dots',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select pagination style.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination color', 'js_composer' ),
					'param_name' => 'paging_color',
					'value' => vc_get_shared( 'colors' ),
					'std' => 'grey',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'paging_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Select pagination color.', 'js_composer' ),
				],
				[
					'type' => 'checkbox',
					'heading' => esc_html__( 'Loop pages?', 'js_composer' ),
					'param_name' => 'loop',
					'description' => esc_html__( 'Allow items to be repeated in infinite loop (carousel).', 'js_composer' ),
					'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Autoplay delay', 'js_composer' ),
					'param_name' => 'autoplay',
					'value' => '-1',
					'description' => esc_html__( 'Enter value in seconds. Set -1 to disable autoplay.', 'js_composer' ),
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Animation In', 'js_composer' ),
					'param_name' => 'paging_animation_in',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'settings' => [
						'type' => [
							'in',
							'other',
						],
					],
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select "animation in" for page transition.', 'js_composer' ),
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Animation Out', 'js_composer' ),
					'param_name' => 'paging_animation_out',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'settings' => [
						'type' => [ 'out' ],
					],
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select "animation out" for page transition.', 'js_composer' ),
				],
				[
					'type' => 'vc_grid_item',
					'heading' => esc_html__( 'Grid element template', 'js_composer' ),
					'param_name' => 'item',
					'description' => sprintf( esc_html__( '%1$sCreate new%2$s template or %3$smodify selected%4$s. Predefined templates will be cloned.', 'js_composer' ), '<a href="' . esc_url( admin_url( 'post-new.php?post_type=vc_grid_item' ) ) . '" target="_blank">', '</a>', '<a href="#" target="_blank" data-vc-grid-item="edit_link">', '</a>' ),
					'group' => esc_html__( 'Item Design', 'js_composer' ),
					'value' => 'none',
				],
				[
					'type' => 'vc_grid_id',
					'param_name' => 'grid_id',
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Initial loading animation', 'js_composer' ),
					'param_name' => 'initial_loading_animation',
					'value' => 'fadeIn',
					'settings' => [
						'type' => [
							'in',
							'other',
						],
					],
					'description' => esc_html__( 'Select initial loading animation for grid element.', 'js_composer' ),
				],
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
						'margin-bottom' => '35px',
					],
				],

				// Load more btn.
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button style', 'js_composer' ),
					'param_name' => 'button_style',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button style.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button color', 'js_composer' ),
					'param_name' => 'button_color',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button color.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button size', 'js_composer' ),
					'param_name' => 'button_size',
					'value' => '',
					'description' => esc_html__( 'Select button size.', 'js_composer' ),
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
				],
			], self::$btn3Params );
			self::$basicGrid = array_merge( self::$basicGrid );

			return self::$basicGrid;
		}

		/**
		 * Media grid common settings
		 */
		public static function getMediaCommonAtts() {

			if ( self::$mediaGrid ) {
				return self::$mediaGrid;
			}

			if ( is_null( self::$btn3Params ) && is_null( self::$gridColsList ) ) {
				self::initData();
			}

			self::$mediaGrid = array_merge( [
				[
					'type' => 'attach_images',
					'heading' => esc_html__( 'Images', 'js_composer' ),
					'param_name' => 'include',
					'description' => esc_html__( 'Select images from media library.', 'js_composer' ),

				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Display Style', 'js_composer' ),
					'param_name' => 'style',
					'value' => [
						esc_html__( 'Show all', 'js_composer' ) => 'all',
						esc_html__( 'Load more button', 'js_composer' ) => 'load-more',
						esc_html__( 'Lazy loading', 'js_composer' ) => 'lazy',
						esc_html__( 'Pagination', 'js_composer' ) => 'pagination',
					],
					'dependency' => [
						'element' => 'post_type',
						'value_not_equal_to' => [ 'custom' ],
					],
					'edit_field_class' => 'vc_col-sm-6',
					'description' => esc_html__( 'Select display style for grid.', 'js_composer' ),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Items per page', 'js_composer' ),
					'param_name' => 'items_per_page',
					'description' => esc_html__( 'Number of items to show per page.', 'js_composer' ),
					'value' => '10',
					'dependency' => [
						'element' => 'style',
						'value' => [
							'lazy',
							'load-more',
							'pagination',
						],
					],
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Grid elements per row', 'js_composer' ),
					'param_name' => 'element_width',
					'value' => self::$gridColsList,
					'std' => '4',
					'edit_field_class' => 'vc_col-sm-6',
					'description' => esc_html__( 'Select number of single grid elements per row.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Gap', 'js_composer' ),
					'param_name' => 'gap',
					'value' => [
						'0px' => '0',
						'1px' => '1',
						'2px' => '2',
						'3px' => '3',
						'4px' => '4',
						'5px' => '5',
						'10px' => '10',
						'15px' => '15',
						'20px' => '20',
						'25px' => '25',
						'30px' => '30',
						'35px' => '35',
					],
					'std' => '5',
					'description' => esc_html__( 'Select gap between grid elements.', 'js_composer' ),
					'edit_field_class' => 'vc_col-sm-6',
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button style', 'js_composer' ),
					'param_name' => 'button_style',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button style.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button color', 'js_composer' ),
					'param_name' => 'button_color',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button color.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button size', 'js_composer' ),
					'param_name' => 'button_size',
					'value' => '',
					'description' => esc_html__( 'Select button size.', 'js_composer' ),
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows design', 'js_composer' ),
					'param_name' => 'arrows_design',
					'value' => [
						esc_html__( 'None', 'js_composer' ) => 'none',
						esc_html__( 'Simple', 'js_composer' ) => 'vc_arrow-icon-arrow_01_left',
						esc_html__( 'Simple Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_02_left',
						esc_html__( 'Simple Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_03_left',
						esc_html__( 'Simple Square', 'js_composer' ) => 'vc_arrow-icon-arrow_09_left',
						esc_html__( 'Simple Square Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_12_left',
						esc_html__( 'Simple Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_11_left',
						esc_html__( 'Rounded', 'js_composer' ) => 'vc_arrow-icon-arrow_04_left',
						esc_html__( 'Rounded Circle Border', 'js_composer' ) => 'vc_arrow-icon-arrow_05_left',
						esc_html__( 'Rounded Circle', 'js_composer' ) => 'vc_arrow-icon-arrow_06_left',
						esc_html__( 'Rounded Square', 'js_composer' ) => 'vc_arrow-icon-arrow_10_left',
						esc_html__( 'Simple Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_08_left',
						esc_html__( 'Simple Rounded Arrow', 'js_composer' ) => 'vc_arrow-icon-arrow_07_left',

					],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select design for arrows.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows position', 'js_composer' ),
					'param_name' => 'arrows_position',
					'value' => [
						esc_html__( 'Inside Wrapper', 'js_composer' ) => 'inside',
						esc_html__( 'Outside Wrapper', 'js_composer' ) => 'outside',
					],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'arrows_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Arrows will be displayed inside or outside grid.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Arrows color', 'js_composer' ),
					'param_name' => 'arrows_color',
					'value' => vc_get_shared( 'colors' ),
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'arrows_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Select color for arrows.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination style', 'js_composer' ),
					'param_name' => 'paging_design',
					'value' => [
						esc_html__( 'None', 'js_composer' ) => 'none',
						esc_html__( 'Square Dots', 'js_composer' ) => 'square_dots',
						esc_html__( 'Radio Dots', 'js_composer' ) => 'radio_dots',
						esc_html__( 'Point Dots', 'js_composer' ) => 'point_dots',
						esc_html__( 'Fill Square Dots', 'js_composer' ) => 'fill_square_dots',
						esc_html__( 'Rounded Fill Square Dots', 'js_composer' ) => 'round_fill_square_dots',
						esc_html__( 'Pagination Default', 'js_composer' ) => 'pagination_default',
						esc_html__( 'Outline Default Dark', 'js_composer' ) => 'pagination_default_dark',
						esc_html__( 'Outline Default Light', 'js_composer' ) => 'pagination_default_light',
						esc_html__( 'Pagination Rounded', 'js_composer' ) => 'pagination_rounded',
						esc_html__( 'Outline Rounded Dark', 'js_composer' ) => 'pagination_rounded_dark',
						esc_html__( 'Outline Rounded Light', 'js_composer' ) => 'pagination_rounded_light',
						esc_html__( 'Pagination Square', 'js_composer' ) => 'pagination_square',
						esc_html__( 'Outline Square Dark', 'js_composer' ) => 'pagination_square_dark',
						esc_html__( 'Outline Square Light', 'js_composer' ) => 'pagination_square_light',
						esc_html__( 'Pagination Rounded Square', 'js_composer' ) => 'pagination_rounded_square',
						esc_html__( 'Outline Rounded Square Dark', 'js_composer' ) => 'pagination_rounded_square_dark',
						esc_html__( 'Outline Rounded Square Light', 'js_composer' ) => 'pagination_rounded_square_light',
						esc_html__( 'Stripes Dark', 'js_composer' ) => 'pagination_stripes_dark',
						esc_html__( 'Stripes Light', 'js_composer' ) => 'pagination_stripes_light',
					],
					'std' => 'radio_dots',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select pagination style.', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination color', 'js_composer' ),
					'param_name' => 'paging_color',
					'value' => vc_get_shared( 'colors' ),
					'std' => 'grey',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'paging_design',
						'value_not_equal_to' => [ 'none' ],
						// New dependency.
					],
					'description' => esc_html__( 'Select pagination color.', 'js_composer' ),
				],
				[
					'type' => 'checkbox',
					'heading' => esc_html__( 'Loop pages?', 'js_composer' ),
					'param_name' => 'loop',
					'description' => esc_html__( 'Allow items to be repeated in infinite loop (carousel).', 'js_composer' ),
					'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Autoplay delay', 'js_composer' ),
					'param_name' => 'autoplay',
					'value' => '-1',
					'description' => esc_html__( 'Enter value in seconds. Set -1 to disable autoplay.', 'js_composer' ),
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Animation In', 'js_composer' ),
					'param_name' => 'paging_animation_in',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'settings' => [
						'type' => [
							'in',
							'other',
						],
					],
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select "animation in" for page transition.', 'js_composer' ),
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Animation Out', 'js_composer' ),
					'param_name' => 'paging_animation_out',
					'group' => esc_html__( 'Pagination', 'js_composer' ),
					'settings' => [
						'type' => [ 'out' ],
					],
					'dependency' => [
						'element' => 'style',
						'value' => [ 'pagination' ],
					],
					'description' => esc_html__( 'Select "animation out" for page transition.', 'js_composer' ),
				],
				[
					'type' => 'vc_grid_item',
					'heading' => esc_html__( 'Grid element template', 'js_composer' ),
					'param_name' => 'item',
					'description' => sprintf( esc_html__( '%1$sCreate new%2$s template or %3$smodify selected%4$s. Predefined templates will be cloned.', 'js_composer' ), '<a href="' . esc_url( admin_url( 'post-new.php?post_type=vc_grid_item' ) ) . '" target="_blank">', '</a>', '<a href="#" target="_blank" data-vc-grid-item="edit_link">', '</a>' ),
					'group' => esc_html__( 'Item Design', 'js_composer' ),
					'value' => 'mediaGrid_Default',
				],
				[
					'type' => 'vc_grid_id',
					'param_name' => 'grid_id',
				],
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
						'margin-bottom' => '35px',
					],
				],
			], self::$btn3Params, [
				// Load more btn bc.
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button style', 'js_composer' ),
					'param_name' => 'button_style',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button style.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button color', 'js_composer' ),
					'param_name' => 'button_color',
					'value' => '',
					'param_holder_class' => 'vc_colored-dropdown',
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
					'description' => esc_html__( 'Select button color.', 'js_composer' ),
				],
				[
					'type' => 'hidden',
					'heading' => esc_html__( 'Button size', 'js_composer' ),
					'param_name' => 'button_size',
					'value' => '',
					'description' => esc_html__( 'Select button size.', 'js_composer' ),
					'group' => esc_html__( 'Load More Button', 'js_composer' ),
					'dependency' => [
						'element' => 'style',
						'value' => [ 'load-more' ],
					],
				],
				[
					'type' => 'animation_style',
					'heading' => esc_html__( 'Initial loading animation', 'js_composer' ),
					'param_name' => 'initial_loading_animation',
					'value' => 'fadeIn',
					'settings' => [
						'type' => [
							'in',
							'other',
						],
					],
					'description' => esc_html__( 'Select initial loading animation for grid element.', 'js_composer' ),
				],
			] );

			self::$mediaGrid = array_merge( self::$mediaGrid );

			return self::$mediaGrid;
		}

		/**
		 * Get Masonry Grid Common Attributes
		 *
		 * @return array
		 */
		public static function getMasonryCommonAtts() {

			if ( self::$masonryGrid ) {
				return self::$masonryGrid;
			}

			$grid_params = self::getBasicAtts();

			self::$masonryGrid = $grid_params;
			$style = self::arraySearch( self::$masonryGrid, 'param_name', 'style' );
			unset( self::$masonryGrid[ $style ]['value'][ esc_html__( 'Pagination', 'js_composer' ) ] );

			$animation = self::arraySearch( self::$masonryGrid, 'param_name', 'initial_loading_animation' );
			$masonry_animation = [
				'type' => 'dropdown',
				'heading' => esc_html__( 'Initial loading animation', 'js_composer' ),
				'param_name' => 'initial_loading_animation',
				'value' => [
					esc_html__( 'None', 'js_composer' ) => 'none',
					esc_html__( 'Default', 'js_composer' ) => 'zoomIn',
					esc_html__( 'Fade In', 'js_composer' ) => 'fadeIn',
				],
				'std' => 'zoomIn',
				'description' => esc_html__( 'Select initial loading animation for grid element.', 'js_composer' ),
			];
			self::$masonryGrid[ $animation ] = $masonry_animation;

			$key = self::arraySearch( self::$masonryGrid, 'group', esc_html__( 'Pagination', 'js_composer' ) );
			while ( $key ) {
				unset( self::$masonryGrid[ $key ] );
				$key = self::arraySearch( self::$masonryGrid, 'group', esc_html__( 'Pagination', 'js_composer' ) );
			}

			$vc_grid_item = self::arraySearch( self::$masonryGrid, 'param_name', 'item' );
			self::$masonryGrid[ $vc_grid_item ]['value'] = 'masonryGrid_Default';

			self::$masonryGrid = array_merge( self::$masonryGrid );

			return array_merge( self::$masonryGrid );
		}

		/**
		 * Get Masonry Media Grid Common Attributes
		 *
		 * @return array
		 */
		public static function getMasonryMediaCommonAtts() {
			if ( self::$masonryMediaGrid ) {
				return self::$masonryMediaGrid;
			}

			$media_grid_params = self::getMediaCommonAtts();

			self::$masonryMediaGrid = $media_grid_params;
			$key = self::arraySearch( self::$masonryMediaGrid, 'group', esc_html__( 'Pagination', 'js_composer' ) );
			while ( $key ) {
				unset( self::$masonryMediaGrid[ $key ] );
				$key = self::arraySearch( self::$masonryMediaGrid, 'group', esc_html__( 'Pagination', 'js_composer' ) );
			}

			$vc_grid_item = self::arraySearch( self::$masonryMediaGrid, 'param_name', 'item' );
			self::$masonryMediaGrid[ $vc_grid_item ]['value'] = 'masonryMedia_Default';

			$style = self::arraySearch( self::$masonryMediaGrid, 'param_name', 'style' );

			unset( self::$masonryMediaGrid[ $style ]['value'][ esc_html__( 'Pagination', 'js_composer' ) ] );

			$animation = self::arraySearch( self::$masonryMediaGrid, 'param_name', 'initial_loading_animation' );
			$masonry_animation = [
				'type' => 'dropdown',
				'heading' => esc_html__( 'Initial loading animation', 'js_composer' ),
				'param_name' => 'initial_loading_animation',
				'value' => [
					esc_html__( 'None', 'js_composer' ) => 'none',
					esc_html__( 'Default', 'js_composer' ) => 'zoomIn',
					esc_html__( 'Fade In', 'js_composer' ) => 'fadeIn',
				],
				'std' => 'zoomIn',
				'settings' => [
					'type' => [
						'in',
						'other',
					],
				],
				'description' => esc_html__( 'Select initial loading animation for grid element.', 'js_composer' ),
			];
			self::$masonryMediaGrid[ $animation ] = $masonry_animation;

			self::$masonryMediaGrid = array_merge( self::$masonryMediaGrid );

			return array_merge( self::$masonryMediaGrid );
		}

		/**
		 * Function to search array.
		 *
		 * @param array $initial
		 * @param string $column
		 * @param string $value
		 */
		public static function arraySearch( $initial, $column, $value ) {
			if ( ! is_array( $initial ) ) {
				return false;
			}
			foreach ( $initial as $key => $inner_array ) {
				$exists = isset( $inner_array[ $column ] ) && $inner_array[ $column ] === $value;
				if ( $exists ) {
					return $key;
				}
			}

			return false;
		}
	}
}
