<?php
namespace ImageOptimization\Modules\ConnectManager\Components;

use ImageOptimization\Modules\Connect\{
	Module as ConnectModule,
	Classes\Data,
	Classes\Service,
	Classes\Utils as ConnectUtils,
	Rest\Authorize,
	Rest\Version
};

use ImageOptimization\Classes\Logger;
use ImageOptimization\Classes\Utils;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if accessed directly
}

class Connect implements Connect_Interface {

	const STATUS_CHECK_TRANSIENT = 'image_optimizer_status_check';

	public function is_connected() : bool {
		return ConnectModule::is_connected();
	}

	public function is_activated() : bool {
		return ConnectModule::is_connected();
	}

	public function is_valid_home_url() : bool {
		return ConnectUtils::is_valid_home_url();
	}

	public function get_connect_status() {
		if ( ! $this->is_connected() ) {
			Logger::log( Logger::LEVEL_INFO, 'Status check error. Reason: User is not connected' );
			return null;
		}

		$cached_status = get_transient( self::STATUS_CHECK_TRANSIENT );

		if ( $cached_status ) {
			return $cached_status;
		}

		try {
			$response = Utils::get_api_client()->make_request(
				'POST',
				'status/check'
			);
		} catch ( Throwable $t ) {
			Logger::log(
				Logger::LEVEL_ERROR,
				'Status check error. Reason: ' . $t->getMessage()
			);

			return null;
		}

		if ( ! isset( $response->status ) ) {
			Logger::log( Logger::LEVEL_ERROR, 'Invalid response from server' );

			return null;
		}

		if ( ! empty( $response->site_url ) && Data::get_home_url() !== $response->site_url ) {
			Data::set_home_url( $response->site_url );
		}

		set_transient( self::STATUS_CHECK_TRANSIENT, $response, MINUTE_IN_SECONDS * 5 );

		return $response;
	}

	public function get_connect_data( bool $force = false ): array {
		$data = get_transient( self::STATUS_CHECK_TRANSIENT );

		$user = [];

		// Return empty array if transient does not exist or is expired.
		if ( ! $data ) {
			return $user;
		}

		// Return if user property does not exist in the data object.
		if ( ! property_exists( $data, 'user' ) ) {
			return $user;
		}

		if ( $data->user->email ) {
			$user = [
				'user' => [
					'email' => $data->user->email,
				],
			];
		}

		return $user;
	}

	public function update_usage_data( $new_usage_data ): void {
		$connect_status = $this->get_connect_status();

		if ( ! isset( $new_usage_data->allowed ) || ! isset( $new_usage_data->used ) ) {
			return;
		}

		if ( 0 === $new_usage_data->allowed - $new_usage_data->used ) {
			$connect_status->status = 'expired';
		}

		$connect_status->quota = $new_usage_data->allowed;
		$connect_status->used_quota = $new_usage_data->used;

		set_transient( self::STATUS_CHECK_TRANSIENT, $connect_status, MINUTE_IN_SECONDS * 5 );
	}

	public function get_activation_state() : string {
		/**
		 * Returning true because the license key is
		 * not used for deactivation in connect.
		 */
		return true;
	}

	public function get_access_token() {
		return Data::get_access_token();

	}

	public function get_client_id(): string {
		return Data::get_client_id();
	}

	public function images_left(): int {
		$plan_data = $this->get_connect_status();

		if ( empty( $plan_data ) ) {
			return 0;
		}

		$quota = $plan_data->quota;
		$used_quota = $plan_data->used_quota;

		return max( $quota - $used_quota, 0 );
	}

	public function user_is_subscription_owner(): bool {
		return Data::user_is_subscription_owner();
	}

	// Nonces.
	public function connect_init_nonce(): string {
		return Authorize::NONCE_NAME;
	}

	public function disconnect_nonce(): string {
		return Authorize::NONCE_NAME;
	}

	public function deactivate_nonce(): string {
		return Authorize::NONCE_NAME;
	}

	public function get_subscriptions_nonce(): string {
		return Authorize::NONCE_NAME;
	}

	public function activate_nonce(): string {
		return Authorize::NONCE_NAME;
	}

	public function version_nonce(): string {
		return Version::NONCE_NAME;
	}

	public function get_is_connect_on_fly(): bool {
		return true;
	}

	public function refresh_token(): void {
		Service::refresh_token();
	}
}
