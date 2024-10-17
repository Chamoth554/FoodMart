<?php

namespace ImageOptimization\Modules\Stats\Classes;

use ImageOptimization\Classes\Async_Operation\{
	Async_Operation,
	Async_Operation_Hook,
	Queries\Image_Optimization_Operation_Query,
	Queries\Operation_Query,
};
use ImageOptimization\Classes\Image\Image_Query_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Stats {
	public static function calculate_global_stats(): array {
		$bulk_optimization_operation_status = self::get_bulk_optimization_status();
		$bulk_optimization_operation_id = Async_Operation::OPERATION_STATUS_RUNNING === $bulk_optimization_operation_status
			? self::get_bulk_optimization_active_operation_id()
			: null;

		return [
			'optimization_stats' => Optimization_Stats::get_image_stats(),
			'bulk_optimization_status' => $bulk_optimization_operation_status,
			'bulk_optimization_operation_id' => $bulk_optimization_operation_id,
			'bulk_restoring_status' => self::get_bulk_restoring_status(),
			'bulk_backup_removing_status' => self::get_bulk_backup_removing_status(),
			'backups_exist' => self::backups_exist(),
		];
	}

	private static function get_bulk_optimization_status(): string {
		$active_query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_status( [
				Async_Operation::OPERATION_STATUS_PENDING,
				Async_Operation::OPERATION_STATUS_RUNNING,
			] )
			->set_limit( 1 );

		$active_operations = Async_Operation::get( $active_query );

		if ( empty( $active_operations ) ) {
			return Async_Operation::OPERATION_STATUS_NOT_STARTED;
		}

		$operation_id = $active_operations[0]->get_args()['operation_id'];
		$cancelled_query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_status( Async_Operation::OPERATION_STATUS_CANCELED )
			->set_bulk_operation_id( $operation_id )
			->set_limit( 1 );

		$cancelled_operations = Async_Operation::get( $cancelled_query );

		if ( ! empty( $cancelled_operations ) ) {
			return Async_Operation::OPERATION_STATUS_CANCELED;
		}

		return Async_Operation::OPERATION_STATUS_RUNNING;
	}

	private static function get_bulk_optimization_active_operation_id(): ?string {
		$active_query = ( new Image_Optimization_Operation_Query() )
			->set_hook( Async_Operation_Hook::OPTIMIZE_BULK )
			->set_status( [
				Async_Operation::OPERATION_STATUS_PENDING,
				Async_Operation::OPERATION_STATUS_RUNNING,
			] )
			->set_limit( 1 );

		$active_operation = Async_Operation::get( $active_query );

		if ( empty( $active_operation ) ) {
			return null;
		}

		return $active_operation[0]->get_args()['operation_id'];
	}

	private static function get_bulk_restoring_status(): string {
		$active_query = ( new Operation_Query() )
			->set_hook( Async_Operation_Hook::RESTORE_MANY_IMAGES )
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

	private static function get_bulk_backup_removing_status(): string {
		$active_query = ( new Operation_Query() )
			->set_hook( Async_Operation_Hook::REMOVE_MANY_BACKUPS )
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

	private static function backups_exist(): bool {
		$query = ( new Image_Query_Builder() )
			->set_paging_size( 1 )
			->return_images_only_with_backups()
			->execute();

		return $query->post_count > 0;
	}
}
