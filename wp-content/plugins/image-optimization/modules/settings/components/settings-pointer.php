<?php

namespace ImageOptimization\Modules\Settings\Components;

use ImageOptimization\Modules\Core\Components\Pointers;
use ImageOptimization\Modules\Settings\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Settings_Pointer {
	const CURRENT_POINTER_SLUG = 'image-optimizer-settings';

	public function admin_print_script() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->is_dismissed() ) {
			return;
		}

		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_style( 'wp-pointer' );

		$pointer_content = '<h3>' . esc_html__( 'Image Optimization settings', 'image-optimization' ) . '</h3>';
		$pointer_content .= '<p>' . esc_html__( 'Head over to the Image Optimization Settings to fine-tune how your media uploads are managed.', 'image-optimization' ) . '</p>';

		$pointer_content .= sprintf(
			'<p><a class="button button-primary image-optimization-pointer-settings-link" href="%s">%s</a></p>',
			admin_url( 'admin.php?page=' . Module::SETTING_BASE_SLUG ),
			esc_html__( 'Take me there', 'image-optimization' )
		);
		$allowed_tags = [
			'h3' => [],
			'p' => [],
			'a' => [
				'class' => [],
				'href' => [],
			],
		];
		?>
		<script>
				const onClose = () => {
					return wp.ajax.post( 'image_optimizer_pointer_dismissed', {
						data: {
							pointer: '<?php echo esc_attr( static::CURRENT_POINTER_SLUG ); ?>',
						},
						nonce: '<?php echo esc_attr( wp_create_nonce( 'image-optimization-pointer-dismissed' ) ); ?>',
					} );
				}

				jQuery( document ).ready( function( $ ) {
						$( '#menu-media' ).pointer( {
							content: '<?php echo wp_kses( $pointer_content, $allowed_tags ); ?>',
							position: {
								edge: <?php echo is_rtl() ? "'right'" : "'left'"; ?>,
								align: 'center'
							},
							close: onClose
						} ).pointer( 'open' );

					$( '.image-optimization-pointer-settings-link' ).first().on( 'click', function( e ) {
						e.preventDefault();

						$(this).attr( 'disabled', true );

						onClose().promise().done(() => {
							location = $(this).attr( 'href' );
						});
					})
				} );
		</script>
		<?php
	}

	public function is_dismissed(): bool {
		$meta = (array) get_user_meta( get_current_user_id(), Pointers::DISMISSED_POINTERS_META_KEY, true );

		return key_exists( static::CURRENT_POINTER_SLUG, $meta );
	}

	public function __construct() {
		add_action( 'in_admin_header', [ $this, 'admin_print_script' ] );
	}
}
