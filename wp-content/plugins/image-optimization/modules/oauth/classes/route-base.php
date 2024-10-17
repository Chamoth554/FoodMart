<?php

namespace ImageOptimization\Modules\Oauth\Classes;

use ImageOptimization\Classes\Route;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Route_Base extends Route {
	const SITE_URL = 'https://my.elementor.com/connect/v1/';

	protected $auth = true;
	protected string $path = '';
	public function get_methods(): array {
		return [];
	}

	public function get_endpoint(): string {
		return 'connect/' . $this->get_path();
	}

	public function get_path(): string {
		return $this->path;
	}

	public function get_name(): string {
		return '';
	}

	public function get_permission_callback( \WP_REST_Request $request ): bool {
		$valid = $this->permission_callback( $request );

		return $valid && user_can( $this->current_user_id, 'manage_options' );
	}

	public function verify_nonce( $nonce = '', $name = '' ) {
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $nonce ) ), $name ) ) {
			wp_die( 'Invalid nonce', 'image-optimization' );
		}
	}

	public function verify_nonce_and_capability( $nonce = '', $name = '', $capability = 'manage_options' ) {
		$this->verify_nonce( $nonce, $name );

		if ( ! current_user_can( $capability ) ) {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}
	}
}
