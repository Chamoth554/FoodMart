<?php

namespace ImageOptimization\Modules\Connect\Rest;

use ImageOptimization\Modules\Connect\Classes\{
	Data,
	Route_Base,
	Service
};
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Deactivate
 */
class Deactivate extends Route_Base {
	public string $path = 'deactivate';

	public function get_methods(): array {
		return [ 'POST' ];
	}

	public function get_name(): string {
		return 'deactivate';
	}

	public function POST() {
		try {
			Service::deactivate_license();

			return $this->respond_success_json();
		} catch ( Throwable $t ) {
			Data::ensure_reset_connect();
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code' => 'internal_server_error',
			] );
		}
	}
}
