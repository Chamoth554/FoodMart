<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="image-optimization-control image-optimization-control--meta-box image-optimization-control--error"
		data-image-optimization-context="meta-box"
		data-image-optimization-status="error"
		data-image-allow-retry="<?php echo esc_attr( $args['allow_retry'] ); ?>"
		data-image-optimization-action="<?php echo esc_attr( $args['action'] ); ?>"
		data-image-optimization-image-id="<?php echo esc_attr( $args['image_id'] ); ?>"
		data-image-optimization-can-be-restored="<?php echo esc_attr( $args['can_be_restored'] ); ?>">
	<p class="image-optimization-control__property">
		<?php esc_html_e( 'Status', 'image-optimization' ); ?>:

		<span class="image-optimization-control__property-value">
			<?php esc_html_e( 'Error', 'image-optimization' ); ?>
		</span>
	</p>

	<p class="image-optimization-control__property">
		<?php esc_html_e( 'Reason', 'image-optimization' ); ?>:

		<span class="image-optimization-control__property-value">
			<?php echo esc_html( $args['message'] ); ?>
		</span>
	</p>

	<?php if ( $args['allow_retry'] ) { ?>
		<div class="image-optimization-control__action-button-wrapper">
			<?php if ( isset( $args['images_left'] ) && 0 === $args['images_left'] ) { ?>
				<a class="button button-secondary button-large image-optimization-control__button"
					 href="https://go.elementor.com/io-panel-upgrade/"
					 target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Upgrade', 'image-optimization' ); ?>
				</a>
			<?php } else { ?>
				<button class="button button-secondary button-large button-link-delete image-optimization-control__button image-optimization-control__button--try-again"
								type="button">
					<?php esc_html_e( 'Try again', 'image-optimization' ); ?>
				</button>
			<?php } ?>
		</div>
	<?php } ?>
</div>
