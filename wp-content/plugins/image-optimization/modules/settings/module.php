<?php

namespace ImageOptimization\Modules\Settings;

use ImageOptimization\Classes\Image\Image_Conversion_Option;
use ImageOptimization\Classes\Module_Base;
use ImageOptimization\Modules\Settings\Classes\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	const SETTING_PREFIX = 'image_optimizer_';
	const SETTING_GROUP = 'image_optimizer_settings';
	const SETTING_BASE_SLUG = 'image-optimization-settings';
	const SETTING_CAPABILITY = 'manage_options';

	public function get_name(): string {
		return 'settings';
	}

	public static function component_list() : array {
		return [
			'Settings_Pointer',
		];
	}

	public static function get_options() : array {
		return [
			'compression_level' => [ 'default' => 'lossy' ],
			'optimize_on_upload' => [
				'type' => 'boolean',
				'default' => true,
			],
			'resize_larger_images' => [
				'type' => 'boolean',
				'default' => true,
			],
			'resize_larger_images_size' => [
				'type' => 'integer',
				'default' => 1920,
			],
			'exif_metadata' => [
				'type' => 'boolean',
				'default' => true,
			],
			'original_images' => [
				'type' => 'boolean',
				'default' => true,
			],
			'convert_to_format' => [
				'type' => 'string',
				'default' => Image_Conversion_Option::WEBP,
			],
			'custom_sizes' => [
				'type' => 'string',
				'default' => 'all',
			],
		];
	}

	public function register_options() {
		$options = $this->get_options();

		foreach ( $options as $key => &$args ) {
			$args['type'] = $args['type'] ?? 'string';
			$args['show_in_rest'] = $args['show_in_rest'] ?? true;
			$args['default'] = $args['default'] ?? '';

			register_setting(
				self::SETTING_GROUP,
				self::SETTING_PREFIX . $key,
				$args
			);

			// Set defaults
			add_option( self::SETTING_PREFIX . $key, $args['default'] );
		}
	}

	public function render_app() {
		?>
		<!-- The hack required to wrap WP notifications -->
		<div class="wrap">
			<h1 style="display: none;" role="presentation"></h1>
		</div>

		<div id="image-optimization-app"></div>
		<?php
	}

	public function register_page() {
		add_media_page(
			__( 'Image Optimizer', 'image-optimization' ),
			__( 'Image Optimizer', 'image-optimization' ),
			self::SETTING_CAPABILITY,
			self::SETTING_BASE_SLUG,
			[ $this, 'render_app' ],
			6
		);
	}

	/**
	 * The handler converts an old CONVERT_TO_WEBP option to the new CONVERT_TO_FORMAT option.
	 * TODO: [Stability] Remove this fallback after all users updated
	 *
	 * @return void
	 */
	public function maybe_migrate_legacy_conversion_option() {
		$legacy_convert_to_webp = get_option( Settings::CONVERT_TO_WEBP_OPTION_NAME, null );

		if ( is_null( $legacy_convert_to_webp ) ) {
			return;
		}

		if ( '1' === $legacy_convert_to_webp ) {
			update_option( Settings::CONVERT_TO_FORMAT_OPTION_NAME, Image_Conversion_Option::WEBP, false );
		}

		if ( '0' === $legacy_convert_to_webp ) {
			update_option( Settings::CONVERT_TO_FORMAT_OPTION_NAME, Image_Conversion_Option::ORIGINAL, false );
		}

		delete_option( Settings::CONVERT_TO_WEBP_OPTION_NAME );
	}

	public function __construct() {
		$this->register_components();

		add_action( 'admin_init', [ $this, 'register_options' ] );
		add_action( 'rest_api_init', [ $this, 'register_options' ] );
		add_action( 'admin_init', [ $this, 'maybe_migrate_legacy_conversion_option' ] );
		add_action( 'admin_menu', [ $this, 'register_page' ] );
	}
}
