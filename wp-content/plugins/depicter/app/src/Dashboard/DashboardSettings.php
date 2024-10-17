<?php
namespace Depicter\Dashboard;

use Depicter\Jeffreyvr\WPSettings\Error;
use Depicter\Jeffreyvr\WPSettings\Flash;
use Depicter\WordPress\Settings\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DashboardSettings {

	const PAGE_ID = 'depicter-settings';

	public $settings = null;

	public function __construct() {
		if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'depicter-settings') {
			$this->settingsPage();
			
			$this->settings->errors = new Error( $this->settings );
			$this->settings->flash = new Flash( $this->settings );
			
			add_action('admin_head', [$this->settings, 'styling']);
			add_action('admin_init', [$this->settings, 'save']);
		}
	}

	/**
	 * Settings page markup
	 *
	 * @return void
	 */
	public function settingsPage() {

		$settings = new Settings(__('Settings', 'depicter'), 'depicter-settings');
		$settings->set_option_name('depicter_options');
		$settings->set_menu_parent_slug( 'depicter-dashboard' );

		$settings->add_tab(__( 'General', 'depicter' ));
		$settings->add_section( __( 'General Settings', 'depicter' ) );

		$settings->add_option('nonce',[
			'action' => 'depicter-settings',
			'name' => '_depicter_settings_nonce'
		]);

		$settings->add_option('select', [
			'name' => 'use_google_fonts',
			'label' => __( 'Google Fonts', 'depicter' ),
			'options' => [
				'on' => __( 'Default (Enable)', 'depicter' ),
				'off' => __( 'Disable', 'depicter' ),
				'editor_only' => __( 'Load in Editor Only', 'depicter' ),
				'save_locally' => __( 'Save Locally', 'depicter' )
			],
			'description' => __( 'Enable, disable, or save Google Fonts locally on your host.', 'depicter' )
		]);

		$settings->add_option('select', [
			'name' => 'resource_preloading',
			'label' => __( 'Resource Preloading', 'depicter' ),
			'options' => [
				'on' => __( 'Default (Enable)', 'depicter' ),
				'off' => __( 'Disable', 'depicter' )
			],
			'description' => __( 'Enable or disable preloading of website resources (images and CSS) for faster page load speed.', 'depicter' )
		]);

		$settings->add_option('select', [
			'name' => 'allow_unfiltered_data_upload',
			'label' => __( 'Allow SVG & JSON Upload?', 'depicter' ),
			'options' => [
				'off' => __( 'Disable', 'depicter' ),
				'on'  => __( 'Enable', 'depicter' )
			],
			'description' => __( 'Attention! Allowing uploads of SVG or JSON files is a potential security risk.<br/>Although Depicter sanitizes such files, we recommend that you only enable this feature if you understand the security risks involved.', 'depicter' ),
		]);

		$settings->add_option('button', [
			'name' => 'regenerate_css_flush_cache',
			'label' => __( 'Regenerate CSS & Flush Cache', 'depicter' ),
			'button_text' => __( 'Regenerate CSS & Flush Cache', 'depicter' ),
			'class' => 'button button-secondary depicter-flush-cache',
			'icon' => '<span class="dashicons dashicons-update" style="line-height:28px; margin-right:8px; height:28px;"></span>'
		]);

		$settings->add_option('checkbox', [
			'name' => 'always_load_assets',
			'label' => __( 'Load assets on all pages?', 'depicter' ),
			'description' => "<br><br>". __( 'By default, Depicter will load corresponding JavaScript and CSS files on demand. but if you need to load assets on all pages, check this option. <br>(For example, if you plan to load Depicter via Ajax, you need to enable this option)', 'depicter' ),
		]);

		if ( \Depicter::auth()->isPaid() ) {

			$settings->add_tab(__( 'CAPTCHA', 'depicter' ));
			$settings->add_section( __( 'Google Recaptcha V3', 'depicter' ) );

			$settings->add_option('nonce',[
				'action' => 'depicter-settings',
				'name' => '_depicter_settings_nonce'
			]);

			$settings->add_option('text', [
				'name' => 'google_recaptcha_client_key',
				'label' => __( 'Google Recaptcha (v3) Client key', 'depicter' ),
				'description' => "",
			]);

			$settings->add_option('password', [
				'name' => 'google_recaptcha_secret_key',
				'label' => __( 'Google Recaptcha (v3) Secret key', 'depicter' ),
				'description' => "",
			]);

			$settings->add_option('number', [
				'name' => 'google_recaptcha_score_threshold',
				'label' => __( 'Score Threshold', 'depicter' ),
				'default' => 0.6,
				'description' => __( 'reCAPTCHA v3 returns a score (1.0 is very likely a good interaction, 0.0 is very likely a bot). If the score less than or equal to this threshold, the form submission will be blocked and the message below will be displayed.', 'depicter' ),
			]);

			$settings->add_option('textarea', [
				'name' => 'google_recaptcha_fail_message',
				'label' => __( 'Fail Message', 'depicter' ),
				'default' => __('Google reCAPTCHA verification failed, please try again later.', 'depicter' ),
				'description' => __( 'Displays to users who fail the verification process.', 'depicter'),
			]);

			$settings->add_tab(__( 'Integrations', 'depicter' ));
			$settings->add_section( __( '', 'depicter' ) );

			$settings->add_option('nonce',[
				'action' => 'depicter-settings',
				'name' => '_depicter_settings_nonce'
			]);

			$settings->add_option('password', [
				'name' => 'google_places_api_key',
				'label' => __( 'Google Places Api key', 'depicter' ),
				'description' => sprintf(
					__("To fetch and display reviews of a place on your website (Google Reviews), you need to provide %s a valid Google Places API key%s.", 'depicter' ),
					'<a href="https://docs.depicter.com/article/290-google-places-api-key" target="_blank">',
					'</a>'
				)
			]);
		}

		$this->settings = $settings;
	}

	public function render() {

		$this->settings->render();
	}
}
