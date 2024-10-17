<?php
namespace ImageOptimization\Modules\Optimization;

use ImageOptimization\Classes\Module_Base;
use ImageOptimization\Modules\Backups\Rest\Restore_Single;
use ImageOptimization\Modules\Optimization\Rest\Optimize_Single_Image;
use Throwable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Module extends Module_Base {
	public function get_name(): string {
		return 'optimization';
	}

	public static function routes_list() : array {
		return [
			'Optimize_Single_Image',
			'Optimize_Bulk',
			'Get_Bulk_Optimization_Images',
			'Optimization_Status',
			'Cancel_Bulk_Optimization',
		];
	}

	public static function component_list() : array {
		return [
			'Avif_Compatibility',
			'Media_Control',
			'Single_Optimization',
			'Upload_Optimization',
			'Bulk_Optimization',
			'List_View_Pointer',
			'Admin_Bulk_Actions',
			'Admin_Filter',
		];
	}

	/**
	 * Enqueue styles and scripts
	 */
	public function enqueue_scripts() {
		$asset_file = include IMAGE_OPTIMIZATION_ASSETS_PATH . 'build/control.asset.php';

		foreach ( $asset_file['dependencies'] as $style ) {
			wp_enqueue_style( $style );
		}

		wp_enqueue_style(
			'image-optimization-control',
			$this->get_css_assets_url( 'control' ),
			[],
			IMAGE_OPTIMIZATION_VERSION,
		);

		wp_enqueue_script(
			'image-optimization-control',
			$this->get_js_assets_url( 'control' ),
			$asset_file['dependencies'],
			IMAGE_OPTIMIZATION_VERSION,
			true
		);

		wp_set_script_translations( 'image-optimization-control', 'image-optimization' );

		wp_localize_script(
			'image-optimization-control',
			'imageOptimizerControlSettings',
			[
				'optimizeSingleImageNonce' => wp_create_nonce( Optimize_Single_Image::NONCE_NAME ),
				'restoreSingleImageNonce' => wp_create_nonce( Restore_Single::NONCE_NAME ),
			]
		);
	}

	public static function load_template( $path, $name, $args = [] ): bool {
		$templates_path = sprintf(
			'%s/templates/%s/%s.php',
			dirname( __FILE__ ),
			$path,
			$name
		);

		try {
			load_template( $templates_path, false, $args );
		} catch ( Throwable $t ) {
			return false;
		}

		return true;
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {
		$this->register_components();
		$this->register_routes();

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
}
