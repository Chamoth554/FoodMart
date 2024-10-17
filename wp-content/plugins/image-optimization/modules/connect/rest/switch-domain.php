<?php

namespace ImageOptimization\Modules\Connect\Rest;

use ImageOptimization\Modules\Connect\Classes\{
	Data,
	Route_Base,
	Service,
};

use Throwable;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Switch_Domain
 */
class Switch_Domain extends Route_Base {
	public string $path = 'switch_domain';
	public const NONCE_NAME = 'wp_rest';

	public function get_methods(): array {
		return [ 'POST' ];
	}

	public function get_name(): string {
		return 'switch_domain';
	}

	public function POST( WP_REST_Request $request ) {
		$this->verify_nonce_and_capability(
			$request->get_param( self::NONCE_NAME ),
			self::NONCE_NAME
		);

		try {
			$client_id = Data::get_client_id();

			if ( ! $client_id ) {
				return $this->respond_error_json( [
					'message' => esc_html__( 'Client ID not found', 'image-optimization' ),
					'code' => 'bad_request',
				] );
			}

			Service::update_redirect_uri();

			return $this->respond_success_json( [
				'message' => esc_html__( 'Domain updated!', 'image-optimization' ),
			] );
		} catch ( Throwable $t ) {
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code' => 'internal_server_error',
			] );
		}
	}
}
