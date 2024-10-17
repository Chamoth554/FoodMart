<?php

namespace ImageOptimization\Modules\Optimization\Classes;

use ImageOptimization\Classes\Async_Operation\{
	Async_Operation,
	Async_Operation_Hook,
	Async_Operation_Queue,
	Exceptions\Async_Operation_Exception,
	Queries\Image_Optimization_Operation_Query
};
use ImageOptimization\Classes\Image\{
	Exceptions\Invalid_Image_Exception,
	Image,
	Image_Meta,
	Image_Optimization_Error_Type,
	Image_Query_Builder,
	Image_Status,
	WP_Image_Meta
};
use ImageOptimization\Classes\File_System\Exceptions\File_System_Operation_Error;
use ImageOptimization\Classes\File_System\File_System;
use ImageOptimization\Classes\Logger;
use ImageOptimization\Classes\Utils;
use ImageOptimization\Modules\Oauth\Classes\Data;
use ImageOptimization\Classes\Exceptions\Quota_Exceeded_Error;
use ImageOptimization\Modules\Optimization\Classes\Exceptions\Bulk_Token_Obtaining_Error;
use ImageOptimization\Modules\Optimization\Components\Exceptions\Bulk_Optimization_Token_Not_Found_Error;
use ImageOptimization\Modules\Stats\Classes\Optimization_Stats;

use ImageOptimization\Plugin;

use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bulk_Optimization_Controller {
	private const OBTAIN_TOKEN_ENDPOINT = 'image/bulk-token';

	public static function reschedule_bulk_optimization() {
		self::cancel_bulk_optimization();
		self::find_images_and_schedule_optimization();
	}

	public static function reschedule_bulk_reoptimization() {
		self::cancel_bulk_reoptimization();
		self::find_optimized_images_and_schedule_reoptimization();
	}

	/**
	 * Cancels pending bulk optimization operations.
	 *
	 * @return void
	 * @throws Async_Operation_Exception
	 */
	public static function cancel_bulk_optimization(): void {
		$query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			// It's risky to cancel in-progress operations at that point, so we cancel only the pending ones.
			->set_status( Async_Operation::OPERATION_STATUS_PENDING )
			->set_limit( -1 );

		$operations = Async_Operation::get( $query );

		foreach ( $operations as $operation ) {
			$image_id = $operation->get_args()['attachment_id'];

			Async_Operation::cancel( $operation->get_id() );

			( new Image_Meta( $image_id ) )->delete();
		}
	}

	/**
	 * Cancels pending bulk re-optimization operations.
	 *
	 * @return void
	 * @throws Async_Operation_Exception
	 */
	public static function cancel_bulk_reoptimization(): void {
		$query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::REOPTIMIZE_BULK )
			// It's risky to cancel in-progress operations at that point, so we cancel only the pending ones.
			->set_status( Async_Operation::OPERATION_STATUS_PENDING )
			->set_limit( -1 );

		$operations = Async_Operation::get( $query );

		foreach ( $operations as $operation ) {
			$image_id = $operation->get_args()['attachment_id'];

			Async_Operation::cancel( $operation->get_id() );

			( new Image_Meta( $image_id ) )->delete();
		}
	}

	/**
	 * Looks for all non-optimized images and creates a bulk operation for each of them.
	 * Also, obtains bulk token and passes it to a newly created operation.
	 *
	 * @return void
	 *
	 * @throws Quota_Exceeded_Error|Invalid_Image_Exception
	 */
	public static function find_images_and_schedule_optimization(): void {
		$images = self::find_images(
			( new Image_Query_Builder() )
				->return_not_optimized_images(),
			true
		);

		if ( ! $images['total_images_count'] ) {
			return;
		}

		$operation_id = wp_generate_password( 10, false );

		try {
			$bulk_token = self::obtain_bulk_token( $images['total_images_count'] );
			self::set_bulk_operation_token( $operation_id, $bulk_token );
		} catch ( Bulk_Token_Obtaining_Error $e ) {
			$bulk_token = null;
		}

		foreach ( $images['attachments_in_quota'] as $attachment_id ) {
			$meta = new Image_Meta( $attachment_id );

			if ( null === $bulk_token ) {
				$meta
					->set_status( Image_Status::OPTIMIZATION_FAILED )
					->save();

				continue;
			}

			try {
				Async_Operation::create(
					Async_Operation_Hook::OPTIMIZE_BULK,
					[
						'attachment_id' => $attachment_id,
						'operation_id' => $operation_id,
					],
					Async_Operation_Queue::OPTIMIZE
				);

				$meta
					->set_status( Image_Status::OPTIMIZATION_IN_PROGRESS )
					->save();
			} catch ( Async_Operation_Exception $aoe ) {
				$meta
					->set_status( Image_Status::OPTIMIZATION_FAILED )
					->save();

				continue;
			}
		}
	}

	/**
	 * Looks for already optimized images with backups and creates a bulk operation for each of them.
	 * Also, obtains bulk token and passes it to a newly created operation.
	 *
	 * @return void
	 *
	 * @throws Quota_Exceeded_Error|Invalid_Image_Exception
	 */
	public static function find_optimized_images_and_schedule_reoptimization(): void {
		$images = self::find_images(
			( new Image_Query_Builder() )
				->return_optimized_images()
		);

		if ( ! $images['total_images_count'] ) {
			return;
		}

		$operation_id = wp_generate_password( 10, false );

		try {
			$bulk_token = self::obtain_bulk_token( $images['total_images_count'] );
			self::set_bulk_operation_token( $operation_id, $bulk_token );
		} catch ( Bulk_Token_Obtaining_Error $e ) {
			$bulk_token = null;
		}

		foreach ( $images['attachments_in_quota'] as $attachment_id ) {
			$meta = new Image_Meta( $attachment_id );

			if ( null === $bulk_token ) {
				$meta
					->set_status( Image_Status::REOPTIMIZING_FAILED )
					->save();

				continue;
			}

			try {
				Async_Operation::create(
					Async_Operation_Hook::REOPTIMIZE_BULK,
					[
						'attachment_id' => $attachment_id,
						'operation_id' => $operation_id,
					],
					Async_Operation_Queue::OPTIMIZE
				);

				$meta
					->set_status( Image_Status::REOPTIMIZING_IN_PROGRESS )
					->save();
			} catch ( Async_Operation_Exception $aoe ) {
				$meta
					->set_status( Image_Status::REOPTIMIZING_FAILED )
					->save();

				continue;
			}
		}

		foreach ( $images['attachments_out_of_quota'] as $attachment_id ) {
			( new Image_Meta( $attachment_id ) )
				->set_status( Image_Status::REOPTIMIZING_FAILED )
				->set_error_type( Image_Optimization_Error_Type::QUOTA_EXCEEDED )
				->save();
		}
	}

	/**
	 * Looks for images for bulk optimization operations based on a query passed and the quota left.
	 *
	 * @param Image_Query_Builder $query Image query to execute.
	 * @param bool $limit_to_quota If true, it limits image query to the quota left.
	 * @return array{total_images_count: int, attachments_in_quota: array, attachments_out_of_quota: array}
	 *
	 * @throws Invalid_Image_Exception
	 * @throws Quota_Exceeded_Error
	 */
	private static function find_images( Image_Query_Builder $query, bool $limit_to_quota = false ): array {
		$output = [
			'total_images_count' => 0,
			'attachments_in_quota' => [],
			'attachments_out_of_quota' => [],
		];

		$images_left = Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance->images_left();

		if ( ! $images_left ) {
			throw new Quota_Exceeded_Error( __( 'Images quota exceeded', 'image-optimization' ) );
		}

		if ( $limit_to_quota ) {
			$query->set_paging_size( $images_left );
		}

		$wp_query = $query->execute();

		if ( ! $wp_query->post_count ) {
			return $output;
		}

		foreach ( $wp_query->posts as $attachment_id ) {
			try {
				Validate_Image::is_valid( $attachment_id );
				$wp_meta = new WP_Image_Meta( $attachment_id );
			} catch ( Invalid_Image_Exception | Exceptions\Image_Validation_Error $ie ) {
				continue;
			}

			$sizes_count = count( $wp_meta->get_size_keys() );

			if ( $output['total_images_count'] + $sizes_count <= $images_left ) {
				$output['total_images_count'] += $sizes_count;
				$output['attachments_in_quota'][] = $attachment_id;
			} else {
				break;
			}
		}

		$output['attachments_out_of_quota'] = array_diff( $wp_query->posts, $output['attachments_in_quota'] );

		return $output;
	}

	/**
	 * Looks for the bulk token in transients.
	 *
	 * @param string $operation_id Bulk optimization operation id
	 *
	 * @return string|null Bulk token.
	 *
	 * @throws Bulk_Optimization_Token_Not_Found_Error
	*/
	public static function get_bulk_operation_token( string $operation_id ): ?string {
		$bulk_token = get_transient( "image_optimizer_bulk_token_$operation_id" );

		if ( ! $bulk_token ) {
			throw new Bulk_Optimization_Token_Not_Found_Error( "There is no token found for the operation $operation_id" );
		}

		return $bulk_token;
	}

	/**
	 * Saves bulk optimization token to transients for a day.
	 *
	 * @param string $operation_id Bulk optimization operation id
	 * @param string $bulk_token Bulk optimization token
	 * @return void
	 */
	public static function set_bulk_operation_token( string $operation_id, string $bulk_token ): void {
		set_transient( "image_optimizer_bulk_token_$operation_id", $bulk_token, HOUR_IN_SECONDS );
	}

	/**
	 * Sends a request to the BE to obtain bulk optimization token.
	 * It prevents obtaining a token for each and every optimization operation.
	 *
	 * @return string
	 *
	 * @throws Bulk_Token_Obtaining_Error
	 */
	private static function obtain_bulk_token( int $images_count ): ?string {
		try {
			$response = Utils::get_api_client()->make_request(
				'POST',
				self::OBTAIN_TOKEN_ENDPOINT,
				[
					'images_count' => $images_count,
				]
			);
		} catch ( Throwable $t ) {
			Logger::log( Logger::LEVEL_ERROR, 'Error while sending bulk token request: ' . $t->getMessage() );

			throw new Bulk_Token_Obtaining_Error( $t->getMessage() );
		}

		return $response->token ?? null;
	}

	/**
	 * Checks if there is a bulk optimization operation in progress.
	 * If there is at least a single active bulk optimization operation it returns true, otherwise false.
	 *
	 * @return bool
	 * @throws Async_Operation_Exception
	 */
	public static function is_optimization_in_progress(): bool {
		$query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_status( [ Async_Operation::OPERATION_STATUS_PENDING, Async_Operation::OPERATION_STATUS_RUNNING ] )
			->set_limit( 1 )
			->return_ids();

		return ! empty( Async_Operation::get( $query ) );
	}

	/**
	 * Checks if there is a bulk re-optimization operation in progress.
	 * If there is at least a single active bulk re-optimization operation it returns true, otherwise false.
	 *
	 * @return bool
	 * @throws Async_Operation_Exception
	 */
	public static function is_reoptimization_in_progress(): bool {
		$query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::REOPTIMIZE_BULK )
			->set_status( [ Async_Operation::OPERATION_STATUS_PENDING, Async_Operation::OPERATION_STATUS_RUNNING ] )
			->set_limit( 1 )
			->return_ids();

		return ! empty( Async_Operation::get( $query ) );
	}

	/**
	 * Retrieves the bulk optimization process status.
	 *
	 * @return array{status: string, stats: array}
	 * @throws Async_Operation_Exception
	 */
	public static function get_status(): array {
		$stats = Optimization_Stats::get_image_stats();

		$output = [
			'status' => 'not-started',
			'percentage' => round( $stats['optimized_image_count'] / $stats['total_image_count'] * 100 ),
		];

		$active_query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_status( [ Async_Operation::OPERATION_STATUS_PENDING, Async_Operation::OPERATION_STATUS_RUNNING ] )
			->set_limit( -1 );

		if ( empty( Async_Operation::get( $active_query ) ) ) {
			return $output;
		}

		$output['status'] = 'in-progress';

		return $output;
	}

	/**
	 * Returns latest operations for the bulk optimization screen.
	 *
	 * @param string|null $operation_id
	 *
	 * @return array
	 * @throws Async_Operation_Exception
	 */
	public static function get_processed_images( string $operation_id ): array {
		$output = [];

		$query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_bulk_operation_id( $operation_id )
			->set_limit( 50 );

		$operations = Async_Operation::get( $query );

		foreach ( $operations as $operation ) {
			$image_id = $operation->get_args()['attachment_id'];
			$image = new Image( $image_id );

			try {
				$stats = Optimization_Stats::get_image_stats( $image_id );
			} catch ( Invalid_Image_Exception $iie ) {
				continue;
			} catch ( Throwable $t ) {
				$original_file_size = 0;
				$current_file_size = 0;
			}

			$output[] = [
				'id' => $operation->get_id(),
				'status' => $operation->get_status() === Async_Operation::OPERATION_STATUS_COMPLETE
					? ( new Image_Meta( $image_id ) )->get_status()
					: $operation->get_status(),
				'image_name' => $image->get_attachment_object()->post_title,
				'image_id' => $image_id,
				'thumbnail_url' => $image->get_url( 'thumbnail' ),
				'original_file_size' => $stats['initial_image_size'],
				'current_file_size' => $stats['current_image_size'],
			];
		}

		return $output;
	}
}
