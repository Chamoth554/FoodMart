<?php

namespace ImageOptimization\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Logger {
	public const LEVEL_ERROR = 'error';
	public const LEVEL_WARN = 'warn';
	public const LEVEL_INFO = 'info';

	public static function log( string $log_level, $message ): void {
		$backtrace = debug_backtrace(); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace

		$class = $backtrace[1]['class'] ?? null;
		$type = $backtrace[1]['type'] ?? null;
		$function = $backtrace[1]['function'];

		if ( $class ) {
			$message = '[Image Optimizer]: ' . $log_level . ' in ' . "$class$type$function()" . ': ' . $message;
		} else {
			$message = '[Image Optimizer]: ' . $log_level . ' in ' . "$function()" . ': ' . $message;
		}

		error_log( $message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
