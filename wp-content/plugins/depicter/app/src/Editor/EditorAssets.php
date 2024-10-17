<?php
namespace Depicter\Editor;

use Averta\Core\Utility\Data;
use Averta\WordPress\Utility\Escape;
use Averta\WordPress\Utility\JSON;
use Averta\WordPress\Utility\Plugin;
use Averta\WordPress\Utility\Sanitize;
use Depicter\Security\CSRF;
use Depicter\Services\UserAPIService;

class EditorAssets
{
	public function bootstrap(){
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueAdminAssets' ] );
	}

	public function enqueueAdminAssets(){
		global $wp_version;

		// This will enqueue the Media Uploader script
		wp_enqueue_media();

		\Depicter::core()->assets()->enqueueScript(
			'depicter-admin',
			\Depicter::core()->assets()->getUrl() . '/resources/scripts/admin/index.js',
			['jquery'],
			true
		);

		wp_enqueue_style('common');

		// Enqueue scripts.
		\Depicter::core()->assets()->enqueueScript(
			'depicter-editor-vendors',
			\Depicter::core()->assets()->getUrl() . '/resources/scripts/editor/vendors-main.js',
			[],
			true
		);
		\Depicter::core()->assets()->enqueueScript(
			'depicter-editor-js',
			\Depicter::core()->assets()->getUrl() . '/resources/scripts/editor/depicter-editor.js',
			['depicter-editor-vendors'],
			true
		);

		$currentUser = wp_get_current_user();
		$documentID = ! empty( $_GET['document'] ) ? Data::cast( Sanitize::key( wp_unslash( $_GET['document'] ) ), 'int' ) : null;

		try {
			$googleClientId = UserAPIService::googleClientID()['clientId'] ?? '';
		} catch ( \Exception $e ) {
			$googleClientId = '';
		}

		$upgradeLink = add_query_arg([
			'action'   => 'upgrade-plugin',
			'plugin'   => 'depicter/depicter.php',
			'_wpnonce' => wp_create_nonce( 'upgrade-plugin_depicter/depicter.php')
		], self_admin_url('update.php') );

		$envData = [
			'wpVersion'   => $wp_version,
			'wpHomepage'  => home_url(),
			"scriptsPath" => \Depicter::core()->assets()->getUrl(). '/resources/scripts/editor/',
			'pluginAPI'   => admin_url( 'admin-ajax.php' ),
			'clientKey'   => \Depicter::auth()->getClientKey(),
			'csrfToken'   => \Depicter::csrf()->getToken( CSRF::EDITOR_ACTION ),
			'updateInfo' => [
				'from' => \Depicter::options()->get('version_previous') ?: null,
				'to'   => \Depicter::options()->get('version'),
				'url'  => $upgradeLink,
			],
			"assetsAPI"   => Escape::url('https://api.wp.depicter.com/' ),
			"wpRestAPI"   => Escape::url( get_rest_url() ),
			"dashboardURL"=> Escape::url( menu_page_url('depicter-dashboard', false) ),
			"documentId"  => $documentID,
			'documentType' => \Depicter::documentRepository()->getFieldValue( $documentID, 'type' ),
			'user' => [
				'tier'  => $this->getUserTier( $documentID ),
				'name'  => Escape::html( $currentUser->display_name ),
				'email' => Escape::html( $currentUser->user_email   ),
				'joinedNewsletter' => !! \Depicter::options()->get('has_subscribed'),
				'dataCollectionConsent' => \Depicter::options()->get('data_collect_consent', 'not-set')
			],
			'activation' => [
				'status'       => \Depicter::auth()->getActivationStatus(),
				'errorMessage' => \Depicter::options()->get('activation_error_message', ''),
				'expiresAt'    => \Depicter::options()->get('subscription_expires_at' , ''),
				'isNew'        => isset( $_GET['depicter_upgraded'] )
			],
			'subscription' => [
				'id'        => \Depicter::options()->get('subscription_id', null),
				'status'    => \Depicter::auth()->getSubscriptionStatus(),
				'overdue'   => \Depicter::auth()->isSubscriptionExpired()
			],
			'integrations' => [
				'unfilteredUploadAllowed' => \Depicter::options()->get('allow_unfiltered_data_upload' ) === 'on',
				'woocommerce' => [
					'label' => __( 'WooCommerce Plugin', 'depicter' ),
					'enabled' => Plugin::isActive( 'woocommerce/woocommerce.php' )
				],
                'googleReviews' => \Depicter::dataSource()->googlePlaces()->hasValidApiKey()
			],
			'googleClientId' => $googleClientId,
			'tokens' => [
				'idToken'      => \Depicter::cache('base')->get( 'id_token'     , null ),
				'accessToken'  => \Depicter::cache('base')->get( 'access_token' , null ),
				'refreshToken' => \Depicter::cache('base')->get( 'refresh_token', null )
			],
            'routes'=>[
                'settingPage' => Escape::url( add_query_arg( [ 'page' => 'depicter-settings', ], self_admin_url( 'admin.php' ) ) )
            ]
		];

		$useGoogleFonts = \Depicter::options()->get('use_google_fonts', 'on');
		if ( $useGoogleFonts == 'off' ) {
			$envData['disabledFontServices'] = ['googleFont'];
		} elseif ( $useGoogleFonts == 'save_locally' ) {
			$envData['hostedFonts'] = \Depicter::googleFontsService()->getListOfHostedGoogleFonts();
		}

		// Add Environment variables
		wp_add_inline_script( 'depicter-editor-vendors', 'window.depicterEnv = '. JSON::encode( $envData ), 'before' );

	}

	/**
	 * Get user tier
	 *
	 * @param $documentID
	 *
	 * @return string
	 */
	public function getUserTier( $documentID ): string{
		$tier = \Depicter::auth()->getTier();
		if ( $tier == 'free-user' ) {
			$createdAt = \Depicter::documentRepository()->getFieldValue( $documentID, 'created_at' );
			if ( strtotime( $createdAt ) < strtotime('2024-01-01') ) {
				return $tier . '+';
			}
		}

		return $tier;
	}

}
