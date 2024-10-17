<?php

namespace ImageOptimization\Modules\Stats\Classes;

use ImageOptimization\Classes\Image\{
	Image,
	Image_Meta,
	Image_Query_Builder,
	WP_Image_Meta,
	Exceptions\Invalid_Image_Exception
};
use ImageOptimization\Classes\Async_Operation\{
	Async_Operation,
	Async_Operation_Hook,
	Async_Operation_Queue,
	Exceptions\Async_Operation_Exception,
	Queries\Operation_Query,
};
use ImageOptimization\Classes\File_System\{
	Exceptions\File_System_Operation_Error,
	File_System,
};
use ImageOptimization\Classes\Logger;
use ImageOptimization\Modules\Optimization\Classes\Exceptions\Image_Validation_Error;
use ImageOptimization\Modules\Optimization\Classes\Validate_Image;
use ImageOptimization\Modules\Settings\Classes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Optimization_Stats {
	const PAGING_SIZE = 25000;
	const STATS_CALCULATION_DELAY = 60 * 15;

	/**
	 * Returns image stats.
	 * If the library is too big, it queries images in chunks.
	 *
	 * @return array{total_image_count: int, optimized_image_count: int, current_image_size: int, initial_image_size:
	 *     int}
	 */
	public static function get_image_stats( ?int $image_id = null, ?bool $no_cache = false ): array {
		// No caching needed if we check a specific image.
		if ( $image_id ) {
			$output = self::get_image_stats_chunk( 1, $image_id );

			unset( $output['pages'] );

			return $output;
		}

		// Look at the stored data first. If it's still valid -- use it.
		$stats = self::get_stored_stats();

		if ( ! $no_cache && ( time() - $stats['updated_at'] ) <= self::STATS_CALCULATION_DELAY ) {
			unset( $stats['updated_at'] );

			return $stats;
		}

		// Otherwise, recalculate the stats.
		$output = self::get_image_stats_chunk( 1, $image_id );
		$pages_count = $output['pages'];

		if ( $pages_count <= 1 ) {
			unset( $output['pages'] );

			return $output;
		}

		if ( $pages_count > 1 && Async_Operation::OPERATION_STATUS_NOT_STARTED === self::get_stats_calculation_status() ) {
			try {
				Async_Operation::create(
					Async_Operation_Hook::CALCULATE_OPTIMIZATION_STATS,
					[
						'page' => 2,
						'pages_count' => $pages_count,
						'output' => $output,
					],
					Async_Operation_Queue::STATS
				);
			} catch ( Async_Operation_Exception $aoe ) {
				Logger::log( Logger::LEVEL_ERROR, 'Error while creating a stats calculation task: ' . $aoe->getMessage() );
			}
		}

		unset( $stats['updated_at'] );

		return $stats;
	}

	/**
	 * @return array{pages: int, total_image_count: int, optimized_image_count: int, current_image_size: int,
	 *     initial_image_size: int}
	 */
	public static function get_image_stats_chunk( int $paged = 1, ?int $image_id = null ): array {
		$output = [
			'pages' => 1,
			'total_image_count' => 0,
			'optimized_image_count' => 0,
			'current_image_size' => 0,
			'initial_image_size' => 0,
		];

		$query = ( new Image_Query_Builder() )
			->set_paging_size( self::PAGING_SIZE )
			->set_current_page( $paged );

		if ( $image_id ) {
			$query->set_image_ids( [ $image_id ] );
		}

		$query = $query->execute();

		$output['pages'] = (int) $query->max_num_pages;

		foreach ( $query->posts as $attachment_id ) {
			try {
				Validate_Image::is_valid( $attachment_id );
				$wp_meta = new WP_Image_Meta( $attachment_id );
			} catch ( Invalid_Image_Exception | Image_Validation_Error $ie ) {
				continue;
			}

			$meta = new Image_Meta( $attachment_id );
			$image_sizes = $wp_meta->get_size_keys();

			$current_sizes = self::filter_only_enabled_sizes( $image_sizes );
			$optimized_sizes = self::filter_only_enabled_sizes( $meta->get_optimized_sizes() );

			$output['total_image_count'] += count( $current_sizes );
			$output['optimized_image_count'] += count( $optimized_sizes );

			foreach ( $image_sizes as $image_size ) {
				$output['current_image_size'] += self::calculate_current_image_file_size( $attachment_id, $wp_meta, $image_size );
				$output['initial_image_size'] += self::calculate_initial_image_file_size( $attachment_id, $meta, $wp_meta, $image_size );
			}
		}

		return $output;
	}

	private static function calculate_current_image_file_size( int $image_id, WP_Image_Meta $wp_meta, string $image_size ): int {
		$size_from_meta = $wp_meta->get_file_size( $image_size );

		if ( $size_from_meta ) {
			return $size_from_meta;
		}

		try {
			return File_System::size( ( new Image( $image_id ) )->get_file_path( $image_size ) );
		} catch ( File_System_Operation_Error $e ) {
			return 0;
		}
	}

	private static function calculate_initial_image_file_size( int $image_id, Image_Meta $meta, WP_Image_Meta $wp_meta, string $image_size ): int {
		$size_from_meta = $meta->get_original_file_size( $image_size ) ?? $wp_meta->get_file_size( $image_size );

		if ( $size_from_meta ) {
			return $size_from_meta;
		}

		try {
			return File_System::size( ( new Image( $image_id ) )->get_file_path( $image_size ) );
		} catch ( File_System_Operation_Error $e ) {
			return 0;
		}
	}

	private static function filter_only_enabled_sizes( array $size_keys ): array {
		$enabled_sizes = Settings::get( Settings::CUSTOM_SIZES_OPTION_NAME );

		if ( 'all' === $enabled_sizes ) {
			return array_filter( $size_keys, fn( string $size_key ) => ! str_starts_with( $size_key, 'elementor_' ) );
		}

		return array_filter($size_keys, function( string $size ) use ( $enabled_sizes ) {
			if ( Image::SIZE_FULL === $size ) {
				return true;
			}

			if ( in_array( $size, $enabled_sizes, true ) ) {
				return true;
			}

			return false;
		});
	}

	/**
	 * Retrieves stats data.
	 *
	 * @return array{total_image_count: ?int, optimized_image_count: ?int, current_image_size: ?int, initial_image_size:
	 *      ?int, updated_at: ?int}
	 */
	public static function get_stored_stats(): array {
		$default = [
			'total_image_count' => null,
			'optimized_image_count' => null,
			'current_image_size' => null,
			'initial_image_size' => null,
			'updated_at' => null,
		];

		return json_decode( get_option( 'image_optimizer_optimization_stats', json_encode( $default ) ), ARRAY_A );
	}

	/**
	 * Updates the optimization stats with fresh values.
	 *
	 * @param array $stats
	 *
	 * @return bool
	 */
	public static function set_stored_stats( array $stats ) {
		$stats['updated_at'] = time();

		return update_option( 'image_optimizer_optimization_stats', json_encode( $stats ) );
	}

	private static function get_stats_calculation_status(): string {
		$active_query = ( new Operation_Query() )
			->set_hook( Async_Operation_Hook::CALCULATE_OPTIMIZATION_STATS )
			->set_status( [
				Async_Operation::OPERATION_STATUS_PENDING,
				Async_Operation::OPERATION_STATUS_RUNNING,
			] )
			->set_limit( 1 );

		$active_operations = Async_Operation::get( $active_query );

		return ! empty( $active_operations )
			? Async_Operation::OPERATION_STATUS_RUNNING
			: Async_Operation::OPERATION_STATUS_NOT_STARTED;
	}
}
