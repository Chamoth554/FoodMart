<?php
/**
 * Plugin Name: Image Optimizer - Compress, Resize and Optimize Images
 * Description: Automatically resize, optimize, and convert images to WebP and AVIF. Compress images in bulk or on upload to boost your WordPress site performance.
 * Plugin URI: https://go.elementor.com/wp-repo-description-tab-io-product-page/
 * Version: 1.5.3
 * Author: Elementor.com
 * Author URI: https://go.elementor.com/author-uri-io/
 * Text Domain: image-optimization
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'IMAGE_OPTIMIZATION_VERSION', '1.5.3' );
define( 'IMAGE_OPTIMIZATION_PATH', plugin_dir_path( __FILE__ ) );
define( 'IMAGE_OPTIMIZATION_URL', plugins_url( '/', __FILE__ ) );
define( 'IMAGE_OPTIMIZATION_ASSETS_PATH', IMAGE_OPTIMIZATION_PATH . 'assets/' );
define( 'IMAGE_OPTIMIZATION_ASSETS_URL', IMAGE_OPTIMIZATION_URL . 'assets/' );
define( 'IMAGE_OPTIMIZATION_PLUGIN_FILE', basename( __FILE__ ) );

/**
 * ImageOptimization Class
 */
final class ImageOptimization {
	private $requirements_errors = [];
	const REQUIRED_EXTENSIONS = [
		'exif',
		'fileinfo',
		'gd',
	];

	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct() {
		// Load translation
		add_action( 'plugins_loaded', [ $this, 'i18n' ] );

		// Init Plugin
		add_action( 'plugins_loaded', [ $this, 'init' ], -11 );
	}

	/**
	 * Load plugin localization files.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'image-optimization' );
	}

	/**
	 * Checks if all requirements met to safely start the plugin and renders admin notices
	 * if something is not right.
	 *
	 * @access public
	 * @return bool
	 */
	public function plugin_can_start(): bool {
		$can_start = true;

		if ( ! version_compare( PHP_VERSION, '7.4', '>=' ) ) {
			$this->requirements_errors[] = sprintf(
				'%1$s <a href="https://go.elementor.com/wp-dash-update-php/" target="_blank">%2$s</a>',
				sprintf(
					/* translators: %s: PHP version. */
					esc_html__( 'Update to PHP version %s and get back to optimizing!', 'image-optimization' ),
					'7.4',
				),
				esc_html__( 'Show me how', 'image-optimization' )
			);

			$can_start = false;
		}

		if ( count( $this->get_missing_extensions_list() ) ) {
			$missed_extensions = $this->get_missing_extensions_list();

			$this->requirements_errors[] = sprintf(
				esc_html__( 'The following required PHP extensions are missing: %s', 'image-optimization' ),
				implode( ', ', $missed_extensions )
			);

			$can_start = false;
		}

		if ( ! $this->is_db_json_supported() ) {
			$this->requirements_errors[] = sprintf(
				/* translators: 1: MySQL minimum version. 2: MariaDB minimum version. */
				esc_html__(
					'The database server version is outdated. Update to MySQL version %1$s or MariaDB version %2$s.',
					'image-optimization'
				),
				'5.7',
				'10.2'
			);

			$can_start = false;
		}

		$upload_dir = wp_upload_dir();

		if ( ! wp_is_writable( $upload_dir['basedir'] ) ) {
			$this->requirements_errors[] = esc_html__(
				'Your site doesn’t have the necessary read/write permissions for your file system to use this plugin. Please contact your hosting provider to resolve this matter.',
				'image-optimization',
			);

			$can_start = false;
		}

		return $can_start;
	}

	/**
	 * Retrieve an array of missing extensions mentioned in self::REQUIRED_EXTENSIONS.
	 *
	 * @access private
	 * @return array Missing extensions.
	 */
	private function get_missing_extensions_list(): array {
		$output = [];

		foreach ( self::REQUIRED_EXTENSIONS as $extension ) {
			if ( ! extension_loaded( $extension ) ) {
				$output[] = $extension;
			}
		}

		return $output;
	}

	public function is_db_json_supported(): bool {
		global $wpdb;

		$result = $wpdb->query("
			SELECT JSON_EXTRACT('[1, 2]', '$[1]');
		");

		return false !== $result;
	}

	/**
	 * Renders an admin notice if the setup did not meet requirements.
	 *
	 * Fired by `admin_notices` action hook.
	 *
	 * @access public
	 * @return void
	 */
	public function add_requirements_error() {
		$message = sprintf(
			'<h3>%s</h3>',
			esc_html__( 'Image Optimizer isn’t running because:', 'image-optimization' )
		);

		$message .= '<ul>';

		foreach ( $this->requirements_errors as $error ) {
			$message .= sprintf(
				'%s%s%s',
				'<li>',
				$error,
				'</li>'
			);
		}

		$message .= '</ul>';

		$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );

		echo wp_kses_post( $html_message );
	}

	/**
	 * Initialize the plugin.
	 *
	 * Validates that PHP version is sufficient. Checks for basic plugin requirements,
	 * if one check fail don't continue, if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @access public
	 */
	public function init() {
		if ( ! $this->plugin_can_start() ) {
			add_action( 'admin_notices', [ $this, 'add_requirements_error' ] );
		} else {
			// Once we get here, We have passed all validation checks, so we can safely include our plugin
			require_once 'plugin.php';
		}
	}
}

// Instantiate ImageOptimization.
new ImageOptimization();
