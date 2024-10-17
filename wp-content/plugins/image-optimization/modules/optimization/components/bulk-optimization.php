<?php

namespace ImageOptimization\Modules\Optimization\Components;

use ImageOptimization\Classes\Async_Operation\Async_Operation_Hook;
use ImageOptimization\Classes\Image\{
	Image,
	Image_Meta,
	Image_Optimization_Error_Type,
	Image_Restore,
	Image_Status
};
use ImageOptimization\Classes\Async_Operation\Exceptions\Async_Operation_Exception;
use ImageOptimization\Classes\Logger;
use ImageOptimization\Classes\Utils;
use ImageOptimization\Classes\Exceptions\Quota_Exceeded_Error;
use ImageOptimization\Modules\Optimization\{
	Classes\Exceptions\Bulk_Token_Expired_Error,
	Classes\Exceptions\Image_File_Already_Exists_Error,
	Classes\Optimize_Image,
	Classes\Bulk_Optimization_Controller,
	Components\Exceptions\Bulk_Optimization_Token_Not_Found_Error,
};

use ImageOptimization\Modules\Stats\Classes\Optimization_Stats;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bulk_Optimization {
	const BULK_OPTIMIZATION_BASE_SLUG = 'image-optimization-bulk-optimization';
	const BULK_OPTIMIZATION_CAPABILITY = 'manage_options';

	public function render_app() {
		?>
		<!-- The hack required to wrap WP notifications -->
		<div class="wrap">
			<h1 style="display: none;" role="presentation"></h1>
		</div>

		<div id="image-optimization-app"></div>
		<?php
	}

	public function register_page() {
		add_media_page(
			__( 'Bulk Optimization', 'image-optimization' ),
			__( 'Bulk Optimization', 'image-optimization' ),
			self::BULK_OPTIMIZATION_CAPABILITY,
			self::BULK_OPTIMIZATION_BASE_SLUG,
			[ $this, 'render_app' ],
			7
		);
	}

	/** @async */
	public function optimize_bulk( int $image_id, string $operation_id ) {
		try {
			$bulk_token = Bulk_Optimization_Controller::get_bulk_operation_token( $operation_id );

			$oi = new Optimize_Image(
				$image_id,
				'bulk',
				$bulk_token
			);

			$oi->optimize();
		} catch ( Quota_Exceeded_Error $qe ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::OPTIMIZATION_FAILED )
				->set_error_type( Image_Optimization_Error_Type::QUOTA_EXCEEDED )
				->save();
		} catch ( Image_File_Already_Exists_Error $fe ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::OPTIMIZATION_FAILED )
				->set_error_type( Image_Optimization_Error_Type::FILE_ALREADY_EXISTS )
				->save();
		} catch ( Bulk_Token_Expired_Error | Bulk_Optimization_Token_Not_Found_Error $bte ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::NOT_OPTIMIZED )
				->save();

			Bulk_Optimization_Controller::reschedule_bulk_optimization();
		} catch ( Throwable $t ) {
			Logger::log( Logger::LEVEL_ERROR, 'Optimization error. Reason: ' . $t->getMessage() );

			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::OPTIMIZATION_FAILED )
				->set_error_type( Image_Optimization_Error_Type::GENERIC )
				->save();
		} finally {
			Optimization_Stats::get_image_stats( null, true );
		}
	}

	/** @async */
	public function reoptimize_bulk( int $image_id, string $operation_id ) {
		try {
			$image = new Image( $image_id );

			if ( $image->can_be_restored() ) {
				Image_Restore::restore( $image_id, true );
			}

			$bulk_token = Bulk_Optimization_Controller::get_bulk_operation_token( $operation_id );

			$oi = new Optimize_Image(
				$image_id,
				'bulk',
				$bulk_token,
				true
			);

			$oi->optimize();
		} catch ( Quota_Exceeded_Error $qe ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::REOPTIMIZING_FAILED )
				->set_error_type( Image_Optimization_Error_Type::QUOTA_EXCEEDED )
				->save();
		} catch ( Image_File_Already_Exists_Error $fe ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::REOPTIMIZING_FAILED )
				->set_error_type( Image_Optimization_Error_Type::FILE_ALREADY_EXISTS )
				->save();
		} catch ( Bulk_Token_Expired_Error | Bulk_Optimization_Token_Not_Found_Error $bte ) {
			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::NOT_OPTIMIZED )
				->save();

			Bulk_Optimization_Controller::reschedule_bulk_reoptimization();
		} catch ( Throwable $t ) {
			Logger::log( Logger::LEVEL_ERROR, 'Reoptimization error. Reason: ' . $t->getMessage() );

			( new Image_Meta( $image_id ) )
				->set_status( Image_Status::REOPTIMIZING_FAILED )
				->set_error_type( Image_Optimization_Error_Type::GENERIC )
				->save();
		} finally {
			Optimization_Stats::get_image_stats( null, true );
		}
	}

	/**
	 * Renders the bulk optimization notice
	 *
	 * @return void
	 */
	public function render_bulk_optimization_notice() {
		try {
			$is_in_progress = Bulk_Optimization_Controller::is_optimization_in_progress();
		} catch ( Async_Operation_Exception $aoe ) {
			$is_in_progress = false;
		}
		?>
		<div class="notice notice-info notice image-optimizer__notice image-optimizer__notice--info image-optimizer__notice--bulk-tip"
				style="display: <?php echo $is_in_progress ? 'block' : 'none'; ?>">
			<p>
				<b>
					<?php esc_html_e(
						'Heads up!',
						'image-optimization'
					); ?>
				</b>

				<span>
					<?php esc_html_e(
						'Bulk optimizing may take a lot of processing and server time, depending on the number of images. Your site will still work smoothly until the processing is all done, without any downtime.',
						'image-optimization'
					); ?>
				</span>
			</p>
		</div>
		<?php
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_page' ] );

		add_action( Async_Operation_Hook::OPTIMIZE_BULK, [ $this, 'optimize_bulk' ], 10, 2 );
		add_action( Async_Operation_Hook::REOPTIMIZE_BULK, [ $this, 'reoptimize_bulk' ], 10, 2 );

		add_action('current_screen', function () {
			if ( Utils::is_bulk_optimization_page() ) {
				add_filter( 'admin_footer_text', [ $this, 'render_bulk_optimization_notice' ] );
			}
		});
	}
}
