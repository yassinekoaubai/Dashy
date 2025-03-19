<?php
/**
 * Notice param template.
 *
 * @var array $notice
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$link = empty( $notice['link'] ) ? '' : $notice['link'];
?>
<div id="wpb-notice-<?php esc_attr_e( $notice['id'] ); ?>" class="updated wpb-notice">
	<?php if ( ! empty( $notice['image'] ) ) : ?>
        <?php // phpcs:ignore ?>
		<div class="wpb-notice-image"  data-notice-link="<?php esc_attr_e( $link ); ?>" style="<?php echo empty( $notice['link'] ) ?: 'cursor: pointer' ?>">
			<img src="<?php esc_attr_e( $notice['image'] ); ?>" alt="<?php empty( $notice['title'] ) ? esc_attr_e( 'wpbakery notice', 'js_composer' ) : esc_attr_e( $notice['title'] ); ?>">
		</div>
	<?php endif; ?>
	<div class="wpb-notice-text">
		<?php if ( ! empty( $notice['title'] ) ) : ?>
			<p class="title">
				<?php esc_html_e( $notice['title'] ); ?>
			</p>
		<?php endif; ?>
		<?php if ( ! empty( $notice['description'] ) ) : ?>
			<div class="wpb-notice-context">
				<?php esc_html_e( $notice['description'] ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $notice['button_text'] ) ) : ?>
			<button type="button" class="button button-primary wpb-notice-button" data-notice-link="<?php esc_attr_e( $link ); ?>">
				<?php esc_html_e( $notice['button_text'] ); ?>
			</button>
		<?php endif; ?>
	</div>

	<button type="button" class="notice-dismiss wpb-notice-dismiss">
		<span class="screen-reader-text"><?php esc_attr_e( 'Dismiss this notice', 'js_composer' ); ?></span>
	</button>
</div>
