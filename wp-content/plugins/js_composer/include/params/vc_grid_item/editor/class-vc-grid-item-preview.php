<?php
/**
 * Renders grid item preview.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Grid_Item_Preview
 */
class Vc_Grid_Item_Preview {
	/**
	 * Shortcodes string.
	 *
	 * @var string
	 */
	protected $shortcodes_string = '';

	/**
	 * Post ID.
	 *
	 * @var bool|int
	 */
	protected $post_id = false;

	/**
	 * Render template item preview output.
	 */
	public function render() {
		$this->post_id = (int) vc_request_param( 'post_id' );
		$this->shortcodes_string = stripslashes( vc_request_param( 'shortcodes_string', true ) );
		require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );
		$grid_item = new Vc_Grid_Item();
		$grid_item->setIsEnd( false );
		$grid_item->setGridAttributes( [ 'element_width' => 4 ] );
		$grid_item->setTemplate( $this->shortcodes_string, $this->post_id );
		$this->enqueue();
		vc_include_template( 'params/vc_grid_item/preview.tpl.php', [
			'preview' => $this,
			'grid_item' => $grid_item,
			'shortcodes_string' => $this->shortcodes_string,
			'post' => $this->mockingPost(),
			'default_width_value' => apply_filters( 'vc_grid_item_preview_render_default_width_value', 4 ),
		] );
	}

	/**
	 * Add css background image css.
	 *
	 * @param string $css
	 * @return string
	 */
	public function addCssBackgroundImage( $css ) {
		if ( empty( $css ) ) {
			$css = 'background-image: url(' . vc_asset_url( 'vc/vc_gitem_image.png' ) . ') !important';
		}

		return $css;
	}

	/**
	 * Add image url.
	 *
	 * @param string $url
	 * @return string
	 */
	public function addImageUrl( $url ) {
		if ( empty( $url ) ) {
			$url = vc_asset_url( 'vc/vc_gitem_image.png' );
		}

		return $url;
	}

	/**
	 * Add image.
	 *
	 * @param string $img
	 * @return string
	 */
	public function addImage( $img ) {
		if ( empty( $img ) ) {
			$img = '<img src="' . esc_url( vc_asset_url( 'vc/vc_gitem_image.png' ) ) . '" alt="">';
		}

		return $img;
	}

	/**
	 * Disable content link.
	 *
	 * @param string $link
	 * @param array $atts
	 * @param string $css_class
	 * @return string
	 * @since 4.5
	 */
	public function disableContentLink( $link, $atts, $css_class ) {
		return 'a' . ( strlen( $css_class ) > 0 ? ' class="' . esc_attr( $css_class ) . '"' : '' );
	}

	/**
	 * Disable real content link.
	 *
	 * @param string $link
	 * @param array $atts
	 * @param WP_Post $post
	 * @param string $css_class
	 * @return string
	 * @since 4.5
	 */
	public function disableRealContentLink( $link, $atts, $post, $css_class ) {
		return 'a' . ( strlen( $css_class ) > 0 ? ' class="' . esc_attr( $css_class ) . '"' : '' );
	}

	/**
	 * Used for filter: vc_gitem_zone_image_block_link
	 *
	 * @param string $link
	 *
	 * @return string
	 * @since 4.5
	 */
	public function disableGitemZoneLink( $link ) {
		if ( null === $link ) {
			_deprecated_argument( __METHOD__, '7.9', '$link' );
		}
		return '';
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue() {
		wpbakery()->frontCss();
		wpbakery()->frontJsRegister();
		wp_enqueue_script( 'prettyphoto' );
		wp_enqueue_style( 'prettyphoto' );
		wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'wpb_composer_front_js' );

		if ( vc_modules_manager()->is_module_on( 'custom_css' ) ) {
			wp_enqueue_style( 'js_composer_custom_css' );
		}

		VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Basic_Grid' );

		$grid = new WPBakeryShortCode_Vc_Basic_Grid( [ 'base' => 'vc_basic_grid' ] );
		$grid->shortcodeScripts();
		$grid->enqueueScripts();
	}

	/**
	 * Set mock post values.
	 *
	 * @return array|WP_Post|null
	 */
	public function mockingPost() {
		$post = get_post( $this->post_id );
		setup_postdata( $post );
		$post->post_title = esc_html__( 'Post title', 'js_composer' );
		$post->post_content = esc_html__( 'The WordPress Excerpt is an optional summary or description of a post; in short, a post summary.', 'js_composer' );
		$post->post_excerpt = esc_html__( 'The WordPress Excerpt is an optional summary or description of a post; in short, a post summary.', 'js_composer' );
		add_filter( 'get_the_categories', [
			$this,
			'getTheCategories',
		], 10, 2 );
		$GLOBALS['post'] = $post;

		return $post;
	}

	/**
	 * Get the categories.
	 *
	 * @param array $categories
	 * @param int $post_id
	 * @return array
	 */
	public function getTheCategories( $categories, $post_id ) {
		$ret = $categories;
		if ( ! $post_id || ( $post_id && $post_id === $this->post_id ) ) {
			$cat = get_categories( 'number=5' );
			if ( empty( $ret ) && ! empty( $cat ) ) {
				$ret += $cat;
			}
		}

		return $ret;
	}

	/**
	 * Add placeholder image.
	 *
	 * @param mixed $img
	 * @return array
	 */
	public function addPlaceholderImage( $img ) {
		if ( null === $img || false === $img ) {
			$img = [
				'thumbnail' => '<img class="vc_img-placeholder vc_single_image-img" src="' . esc_url( vc_asset_url( 'vc/vc_gitem_image.png' ) ) . '" />',
			];
		}

		return $img;
	}
}
