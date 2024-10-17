<?php
namespace Depicter\Dashboard;

use Averta\Core\Utility\Data;
use Averta\Core\Utility\Extract;
use Averta\WordPress\Utility\Escape;
use Averta\WordPress\Utility\JSON;
use Averta\WordPress\Utility\Plugin;
use Depicter\GuzzleHttp\Exception\GuzzleException;
use Depicter\Security\CSRF;
use Depicter\Services\UserAPIService;

class DashboardPage
{

	const HOOK_SUFFIX = 'toplevel_page_depicter-dashboard';
	const PAGE_ID = 'depicter-dashboard';

	/**
	 * @var string
	 */
	var $hook_suffix = '';

	public function bootstrap(){
		add_action( 'admin_menu', [ $this, 'registerPage' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );
		add_action( 'admin_head', array( $this, 'disable_admin_notices' ) );
		add_action( 'admin_init', [ $this, 'externalPageRedirect' ] );
	}

	/**
	 * Register admin pages.
	 *
	 * @return void
	 */
	public function registerPage() {
		$this->hook_suffix = add_menu_page(
			__('Depicter', 'depicter'),
			__('Depicter', 'depicter'),
			'access_depicter',
			self::PAGE_ID,
			[ $this, 'render' ], // called to output the content for this page
			\Depicter::core()->assets()->getUrl() . '/resources/images/svg/wp-logo.svg'
		);

		add_submenu_page(
			self::PAGE_ID,
			__( 'Dashboard', 'depicter' ),
			__( 'Dashboard', 'depicter' ),
			'access_depicter',
			self::PAGE_ID
		);

		add_submenu_page(
			self::PAGE_ID,
			__( 'Settings', 'depicter' ),
			__( 'Settings', 'depicter' ),
			'access_depicter',
			'depicter-settings',
			[ $this, 'printSettingsPage' ]
		);

		add_submenu_page(
			self::PAGE_ID,
			__( 'Support', 'depicter' ),
			__( 'Support', 'depicter' ),
			'access_depicter',
			self::PAGE_ID . '-goto-support',
			[ $this, 'externalPageRedirect' ]
		);

		if ( ! \Depicter::auth()->isPaid() && empty( $_GET['depicter_upgraded'] ) ) {
			add_submenu_page(
				self::PAGE_ID,
				__( 'Upgrade to PRO', 'depicter' ),
				__( 'Upgrade to PRO', 'depicter' ),
				'access_depicter',
				self::PAGE_ID . '-goto-pro',
				[ $this, 'externalPageRedirect' ]
			);
		}

		add_action( 'admin_print_scripts-' . $this->hook_suffix, [ $this, 'printScripts' ] );
	}

	/**
	 * Process redirect after clicking on Depicter admin menu
	 *
	 * @return void
	 */
	public function externalPageRedirect(){
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		if ( self::PAGE_ID . '-goto-support' === $_GET['page'] ) {
			wp_redirect( 'https://wordpress.org/support/plugin/depicter/' );
			die;
		}

		if ( self::PAGE_ID . '-goto-pro' === $_GET['page'] ) {
			wp_redirect( 'https://depicter.com/pricing?utm_source=depicter&utm_medium=depicter-free&utm_campaign=free-to-pro&utm_term=unlock-submenu' );
			die;
		}
	}

	/**
	 * Disable all admin notices in dashboard page
	 *
	 * @return void
	 */
	public function disable_admin_notices() {
		$screen = get_current_screen();
		if ( $screen->id == $this->hook_suffix ) {
			remove_all_actions( 'admin_notices' );
		}
	}

	public function render(){
		$this->renewTokens();
		echo Escape::content( \Depicter::view( 'admin/dashboard/index.php' )->toString() );
	}

	/**
	 * Load dashboard scripts
	 *
	 * @param string $hook_suffix
	 */
	public function enqueueScripts( $hook_suffix = '' ){

		if( $hook_suffix !== $this->hook_suffix ){

			if ( !empty( $_GET['page'] ) && $_GET['page'] == 'depicter-settings' ) {
				\Depicter::core()->assets()->enqueueScript(
					'depicter-admin',
					\Depicter::core()->assets()->getUrl() . '/resources/scripts/admin/index.js',
					['jquery'],
					true
				);

				wp_localize_script( 'depicter-admin', 'depicterParams', [
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'token' => \Depicter::csrf()->getToken( \Depicter\Security\CSRF::DASHBOARD_ACTION ),
				]);
			}

			return;
		}

		// Enqueue scripts.
		\Depicter::core()->assets()->enqueueScript(
			'depicter--dashboard',
			\Depicter::core()->assets()->getUrl() . '/resources/scripts/dashboard/depicter-dashboard.js',
			[],
			true
		);

		// Enqueue styles.
		\Depicter::core()->assets()->enqueueStyle(
			'depicter-dashboard',
			\Depicter::core()->assets()->getUrl() . '/resources/styles/dashboard/index.css'
		);
	}

    /**
     * Print required scripts in Dashboard page
     *
     * @return void
     * @throws GuzzleException
     */
	public function printScripts()
	{
		global $wp_version;
		$currentUser = wp_get_current_user();

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

		// retrieve refresh token
		$refreshToken = \Depicter::cache('base')->get( 'refresh_token', null );
		$refreshTokenPayload = Extract::JWTPayload( $refreshToken );
		$displayReviewNotice = !empty( $refreshTokenPayload['ict'] ) && ( time() - Data::cast( $refreshTokenPayload['ict'], 'int' ) > 5 * DAY_IN_SECONDS );

		wp_add_inline_script('depicter--dashboard', 'window.depicterEnv = '. JSON::encode(
		    [
				'wpVersion'   => $wp_version,
				"scriptsPath" => \Depicter::core()->assets()->getUrl(). '/resources/scripts/dashboard/',
				'clientKey'   => \Depicter::auth()->getClientKey(),
				'csrfToken'   => \Depicter::csrf()->getToken( CSRF::DASHBOARD_ACTION ),
				'updateInfo' => [
					'from' => \Depicter::options()->get('version_previous') ?: null,
					'to'   => \Depicter::options()->get('version'),
					'url'  => $upgradeLink,
				],
				"assetsAPI"   => Escape::url('https://wp-api.depicter.com/' ),
				"wpRestApi"   => Escape::url( get_rest_url() ),
				"pluginAPI"   => admin_url( 'admin-ajax.php' ),
				"editorPath"  => \Depicter::editor()->getEditUrl( '__id__' ),
				"documentPreviewPath" => \Depicter::editor()->getEditUrl( '__id__' ),
				'user' => [
					'tier'  => \Depicter::auth()->getTier(),
					'name'  => Escape::html( $currentUser->display_name ),
					'email' => Escape::html( $currentUser->user_email   ),
					'onboarding' => \Depicter::client()->isNewClient(),
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
			    'AIWizard' =>  [
					'introVideoSrc' => 'https://www.youtube.com/embed/kdR9Jw0yWjU?rel=0'
				],
				'googleClientId' => $googleClientId,
				'tokens' => [
					'idToken'      => \Depicter::cache('base')->get( 'id_token'     , null ),
					'accessToken'  => \Depicter::cache('base')->get( 'access_token' , null ),
					'refreshToken' => $refreshToken
				],
				'display' => [
                    'reviewNotice' => $displayReviewNotice
                ],
                'routes'=>[
                    'settingPage' => Escape::url( add_query_arg( [ 'page' => 'depicter-settings', ], self_admin_url( 'admin.php' ) ) )
                ]
			]
		), 'before' );

	}

	/**
	 * Renew member tokens before expire date
	 *
	 * @return void
	 */
	public function renewTokens() {
		if ( false === \Depicter::cache('base')->get( 'access_token' ) ) {
			UserAPIService::renewTokens();
		}
	}

	public function printSettingsPage() {
		\Depicter::resolve('depicter.dashboard.settings')->render();
	}

}
