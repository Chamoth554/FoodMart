<?php

namespace ImageOptimization\Modules\Core\Components;

use ImageOptimization\Classes\Image\Image_Query_Builder;
use ImageOptimization\Classes\Utils;
use ImageOptimization\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Not_Connected {
	const NOT_CONNECTED_NOTICE_SLUG = 'image-optimizer-not-connected';

	public function render_not_connected_notice() {
		if ( Pointers::is_dismissed( self::NOT_CONNECTED_NOTICE_SLUG ) ) {
			return;
		}

		?>
		<div class="notice notice-info notice is-dismissible image-optimizer__notice image-optimizer__notice--pink"
			 data-notice-slug="<?php echo esc_attr( self::NOT_CONNECTED_NOTICE_SLUG ); ?>">
			<div class="image-optimizer__icon-block">
				<svg width="32" height="32" fill="none" role="presentation">
					<rect width="32" height="32" fill="#FF7BE5" rx="16"/>
					<path fill="#fff" d="M10.508 4.135a.125.125 0 0 0-.236 0l-1.183 3.42a.125.125 0 0 1-.078.078L5.553 8.8a.125.125 0 0 0 0 .237l3.458 1.166a.125.125 0 0 1 .078.078l1.183 3.42a.125.125 0 0 0 .236 0l1.182-3.42a.125.125 0 0 1 .078-.078l3.458-1.166a.125.125 0 0 0 0-.237l-3.458-1.167a.125.125 0 0 1-.078-.077l-1.182-3.421ZM17.425 12.738v3.683l-4.073 4.582L26.495 9.598a.125.125 0 0 1 .207.094v14.851a.125.125 0 0 1-.125.125H5.874a.125.125 0 0 1-.09-.212l11.427-11.805a.125.125 0 0 1 .214.087Z"/>
				</svg>
			</div>

			<p>
				<b>
					<?php esc_html_e(
						'Image Optimizer is not connected right now. To start optimizing your images, please connect your account.',
						'image-optimization'
					); ?>
				</b>

				<span>
					<a href="<?php echo admin_url( 'admin.php?page=' . \ImageOptimization\Modules\Settings\Module::SETTING_BASE_SLUG . '&action=connect' ); ?>">
						<?php esc_html_e(
							'Connect now',
							'image-optimization'
						); ?>
					</a>
				</span>
			</p>
		</div>

		<script>
			const onNotConnectedNoticeClose = () => {
				const pointer = '<?php echo esc_js( self::NOT_CONNECTED_NOTICE_SLUG ); ?>';

				return wp.ajax.post( 'image_optimizer_pointer_dismissed', {
					data: {
						pointer,
					},
					nonce: '<?php echo esc_js( wp_create_nonce( 'image-optimization-pointer-dismissed' ) ); ?>',
				} );
			}

			jQuery( document ).ready( function( $ ) {
				setTimeout(() => {
					const $closeButton = $( '[data-notice-slug="<?php echo esc_js( self::NOT_CONNECTED_NOTICE_SLUG ); ?>"] .notice-dismiss' )

					$closeButton
						.first()
						.on( 'click', onNotConnectedNoticeClose )

					$( '[data-notice-slug="<?php echo esc_js( self::NOT_CONNECTED_NOTICE_SLUG ); ?>"] a' )
						.first()
						.on( 'click', function ( e ) {
							e.preventDefault();

							onNotConnectedNoticeClose().promise().done(() => {
								window.open( $( this ).attr( 'href' ), '_blank' ).focus();

								$closeButton.click();
							});
						})
				}, 0);
			} );
		</script>
		<?php
	}

	public function add_media_menu_badge( $parent_file ) {
		global $menu;

		foreach ( $menu as &$item ) {
			if ( 'upload.php' === $item[2] ) {
				$item[0] .= ' <span class="update-plugins count-1"><span class="plugin-count">1</span></span>';
				break;
			}
		}

		return $parent_file;
	}


	public function __construct() {
		add_action('current_screen', function () {
			if ( ! Utils::user_is_admin() ) {
				return;
			}

			// @var ImageOptimizer/Modules/ConnectManager/Module
			$module = Plugin::instance()->modules_manager->get_modules( 'connect-manager' );

			if ( $module->connect_instance->is_connected() || ! $module->connect_instance->is_valid_home_url() ) {
				return;
			}

			add_filter( 'parent_file', [ $this, 'add_media_menu_badge' ] );

			if (
				Utils::is_media_page() ||
				 Utils::is_plugin_page() ||
				 Utils::is_single_attachment_page() ||
				 Utils::is_media_upload_page()
			) {
				add_action( 'admin_notices', [ $this, 'render_not_connected_notice' ] );
			}
		});
	}
}
