<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="image-optimization-control image-optimization-control--list-view image-optimization-control--error"
		data-image-optimization-context="list-view"
		data-image-optimization-status="error"
		data-image-allow-retry="<?php echo esc_attr( $args['allow_retry'] ); ?>"
		data-image-optimization-action="<?php echo esc_attr( $args['action'] ); ?>"
		data-image-optimization-image-id="<?php echo esc_attr( $args['image_id'] ); ?>"
		data-image-optimization-can-be-restored="<?php echo esc_attr( $args['can_be_restored'] ); ?>">

	<?php
	$message = esc_html( $args['message'] );

	if ( false === $args['allow_retry'] ) {
		$message_chunks = explode( '. ', $message, 2 );

		$message = "<span class='image-optimization-control__error-title'>{$message_chunks[0]}</span>";

		if ( isset( $message_chunks[1] ) ) {
			$message .= "<span class='image-optimization-control__error-subtitle'>{$message_chunks[1]}</span>";
		}
	}
	?>

	<span class='image-optimization-control__error-message'><?php echo $message; ?></span>

	<?php if ( $args['allow_retry'] ) { ?>
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
	<?php } ?>
</div>
