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
 * Class Authorize
 */
class Authorize extends Route_Base {
	public string $path = 'authorize';
	public const NONCE_NAME = 'wp_rest';

	public function get_methods(): array {
		return [ 'POST' ];
	}

	public function get_name(): string {
		return 'authorize';
	}

	public function POST( WP_REST_Request $request ) {
		$this->verify_nonce_and_capability(
			$request->get_param( self::NONCE_NAME ),
			self::NONCE_NAME
		);

		if ( Connect::is_connected() && Utils::is_valid_home_url() ) {
			return $this->respond_error_json( [
				'message' => esc_html__( 'You are already connected', 'image-optimization' ),
				'code' => 'forbidden',
			] );
		}

		try {
			$client_id = Data::get_client_id();

			if ( ! $client_id ) {
				$client_id = Service::register_client();
			}

			if ( ! Utils::is_valid_home_url() ) {
				if ( $request->get_param( 'update_redirect_uri' ) ) {
					Service::update_redirect_uri();
				} else {
					return $this->respond_error_json( [
						'message' => esc_html__( 'Connected domain mismatch', 'image-optimization' ),
						'code'    => 'forbidden',
					] );
				}
			}

			$authorize_url = Utils::get_authorize_url( $client_id );

			$additional_source_campaign = [];

			$image_optimization_campaign = get_transient( 'elementor_image_optimization_campaign' );

			if ( ! empty( $image_optimization_campaign['source'] ) ) {
				$additional_source_campaign['utm_source'] = $image_optimization_campaign['source'];
			}

			if ( ! empty( $image_optimization_campaign['medium'] ) ) {
				$additional_source_campaign['utm_medium'] = $image_optimization_campaign['medium'];
			}

			if ( ! empty( $image_optimization_campaign['campaign'] ) ) {
				$additional_source_campaign['utm_campaign'] = $image_optimization_campaign['campaign'];
			}

			if ( ! empty( $additional_source_campaign ) ) {
				$authorize_url = add_query_arg( $additional_source_campaign, $authorize_url );
			}

			return $this->respond_success_json( $authorize_url );
		} catch ( Throwable $t ) {
			return $this->respond_error_json( [
				'message' => $t->getMessage(),
				'code' => 'internal_server_error',
			] );
		}
	}
}
