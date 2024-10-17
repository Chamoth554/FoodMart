<?php

namespace ImageOptimization\Classes\Client;

use ImageOptimization\Classes\Exceptions\Client_Exception;
use ImageOptimization\Classes\File_Utils;
use ImageOptimization\Classes\Image\Image;
use ImageOptimization\Modules\Stats\Classes\Optimization_Stats;
use WP_Error;

use ImageOptimization\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Client
 */
class Client {
	const BASE_URL = 'https://my.elementor.com/api/v2/image-optimizer/';
	const STATUS_CHECK = 'status/check';

	private bool $refreshed = false;

	public static ?Client $instance = null;

	/**
	 * get_instance
	 * @return Client|null
	 */
	public static function get_instance(): ?Client {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public static function get_site_info(): array {
		return [
			// Which API version is used.
			'app_version' => IMAGE_OPTIMIZATION_VERSION,
			// Which language to return.
			'site_lang' => get_bloginfo( 'language' ),
			// site to connect
			'site_url' => trailingslashit( home_url() ),
			// current user
			'local_id' => get_current_user_id(),
			// Media library stats
			'media_data' => base64_encode( wp_json_encode( self::get_request_stats() ) ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		];
	}

	private static function get_request_stats(): array {
		$optimization_stats = Optimization_Stats::get_image_stats();
		$image_sizes = [];

		foreach ( wp_get_registered_image_subsizes() as $image_size_key => $image_size_data ) {
			$image_sizes[] = [
				'label' => $image_size_key,
				'size' => "{$image_size_data['width']}x{$image_size_data['height']}",
			];
		}

		return [
			'not_optimized_images' => $optimization_stats['total_image_count'] - $optimization_stats['optimized_image_count'],
			'optimized_images' => $optimization_stats['optimized_image_count'],
			'images_sizes' => $image_sizes,
		];
	}

	public function make_request( $method, $endpoint, $body = [], array $headers = [], $file = false, $file_name = '' ) {
		$headers = array_replace_recursive([
			'x-elementor-image-optimizer' => IMAGE_OPTIMIZATION_VERSION,
		], $headers);

		$headers = array_replace_recursive(
			$headers,
			$this->is_connected() ? $this->generate_authentication_headers( $endpoint ) : []
		);

		$body = array_replace_recursive( $body, $this->get_site_info() );

		try {
			if ( $file ) {
				$boundary = wp_generate_password( 24, false );
				$body = $this->get_upload_request_body( $body, $file, $boundary, $file_name );
				// add content type header
				$headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;
			}
		} catch ( Client_Exception $ce ) {
			return new WP_Error( 500, $ce->getMessage() );
		}

		$response = $this->request(
			$method,
			$endpoint,
			[
				'timeout' => 100,
				'headers' => $headers,
				'body' => $body,
			]
		);

		return ( new Client_Response( $response ) )->handle();
	}

	private static function get_remote_url( $endpoint ): string {
		return self::BASE_URL . $endpoint;
	}

	protected function is_connected(): bool {
		return Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance->is_connected();
	}

	protected function generate_authentication_headers( $endpoint ): array {

		$connect_instance = Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance;

		if ( ! $connect_instance->get_is_connect_on_fly() ) {
			$headers = [
				'data' => base64_encode(  // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					wp_json_encode( [ 'app' => 'library' ] )
				),
				'access_token' => $connect_instance->get_access_token() ?? '',
				'client_id' => $connect_instance->get_client_id() ?? '',
			];

			if ( $connect_instance->is_activated() ) {
				$headers['key'] = $connect_instance->get_activation_state() ?? '';
			}
		} else {
			$headers = $this->add_bearer_token([
				'x-elementor-apps-connect' => true,
			]);
		}

		$headers['endpoint'] = $endpoint;
		$headers['x-elementor-apps'] = 'image-optimizer';

		return $headers;
	}

	protected function request( $method, $endpoint, $args = [] ) {
		$args['method'] = $method;

		$response = wp_remote_request(
			self::get_remote_url( $endpoint ),
			$args
		);

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();

			return new WP_Error(
				$response->get_error_code(),
				is_array( $message ) ? join( ', ', $message ) : $message
			);
		}

		$body = wp_remote_retrieve_body( $response );
		$response_code = (int) wp_remote_retrieve_response_code( $response );

		if ( ! $response_code ) {
			return new WP_Error( 500, 'No Response' );
		}

		// Server sent a success message without content.
		if ( 'null' === $body ) {
			$body = true;
		}

		$body = json_decode( $body );

		if ( false === $body ) {
			return new WP_Error( 422, 'Wrong Server Response' );
		}

		if ( Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance->get_is_connect_on_fly() ) {
			// If the token is invalid, refresh it and try again once only.
			if ( ! $this->refreshed && ! empty( $body->message ) && ( false !== strpos( $body->message, 'Invalid Token' ) ) ) {
				Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance->refresh_token();
				$this->refreshed = true;
				$args['headers'] = $this->add_bearer_token( $args['headers'] );
				return $this->request( $method, $endpoint, $args );
			}
		}

		if ( 200 !== $response_code ) {
			// In case $as_array = true.
			$message = $body->message ?? wp_remote_retrieve_response_message( $response );
			$message = is_array( $message ) ? join( ', ', $message ) : $message;
			$code = isset( $body->code ) ? (int) $body->code : $response_code;

			return new WP_Error( $code, $message );
		}

		return $body;
	}

	public function add_bearer_token( $headers ) {
		if ( $this->is_connected() ) {
			$headers['Authorization'] = 'Bearer ' . Plugin::instance()->modules_manager->get_modules( 'connect-manager' )->connect_instance->get_access_token();
		}
		return $headers;
	}

	/**
	 * get_upload_request_body
	 *
	 * @param array $body
	 * @param $file
	 * @param string $boundary
	 * @param string $file_name
	 *
	 * @return string
	 * @throws Client_Exception
*/
	private function get_upload_request_body( array $body, $file, string $boundary, string $file_name = '' ): string {
		$payload = '';
		// add all body fields as standard POST fields:
		foreach ( $body as $name => $value ) {
			$payload .= '--' . $boundary;
			$payload .= "\r\n";
			$payload .= 'Content-Disposition: form-data; name="' . esc_attr( $name ) . '"' . "\r\n\r\n";
			$payload .= $value;
			$payload .= "\r\n";
		}

		if ( is_array( $file ) ) {
			foreach ( $file as $key => $file_data ) {
				$payload .= $this->get_file_payload( $file_data['name'], $file_data['type'], $file_data['path'], $boundary );
			}
		} else {
			$image_mime = image_type_to_mime_type( exif_imagetype( $file ) );

			if (
				! in_array( $image_mime, Image::get_supported_mime_types(), true ) &&
				( 'application/octet-stream' === $image_mime && 'avif' !== File_Utils::get_extension( $file ) )
			) {
				throw new Client_Exception( "Unsupported mime type `$image_mime`" );
			}

			if ( empty( $file_name ) ) {
				$file_name = basename( $file );
			}

			$payload .= $this->get_file_payload( $file_name, $image_mime, $file, $boundary );
		}

		$payload .= '--' . $boundary . '--';

		return $payload;
	}

	/**
	 * get_file_payload
	 * @param string $filename
	 * @param string $file_type
	 * @param string $file_path
	 * @param string $boundary
	 * @return string
	 */
	private function get_file_payload( string $filename, string $file_type, string $file_path, string $boundary ): string {
		$name = $filename ?? basename( $file_path );
		$mine_type = 'image' === $file_type ? image_type_to_mime_type( exif_imagetype( $file_path ) ) : $file_type;
		$payload = '';
		// Upload the file
		$payload .= '--' . $boundary;
		$payload .= "\r\n";
		$payload .= 'Content-Disposition: form-data; name="' . esc_attr( $name ) . '"; filename="' . esc_attr( $name ) . '"' . "\r\n";
		$payload .= 'Content-Type: ' . $mine_type . "\r\n";
		$payload .= "\r\n";
		$payload .= file_get_contents( $file_path );
		$payload .= "\r\n";

		return $payload;
	}
}
