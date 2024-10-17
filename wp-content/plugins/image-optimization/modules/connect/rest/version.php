<?php

namespace ImageOptimization\Modules\Connect\Rest;

use ImageOptimization\Modules\Connect\Classes\{
	Data,
	Route_Base,
	Service,
	Utils
};

use ImageOptimization\Modules\Connect\Module as Connect;
use Throwable;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Version
 */
class Version extends Route_Base {
	public string $path = 'version';
	public const NONCE_NAME = 'wp_rest';

	public function get_methods(): array {
		return [ 'GET' ];
	}

	public function get_name(): string {
		return 'version';
	}

	public function GET( WP_REST_Request $request ) {

		try {
			return $this->respond_success_json([
				'version' => 2,
			]);
		} catch ( Throwable $t ) {
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code' => 'internal_server_error',
			] );
		}
	}
}
