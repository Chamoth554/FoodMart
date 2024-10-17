<?php
namespace Depicter\WordPress;

use Averta\WordPress\Models\WPOptions;
use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register plugin general hooks.
 */
class PluginServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$app = $container[ WPEMERGE_APPLICATION_KEY ];

		// register depicter options
		$container[ 'depicter.options' ] = function () {
			return new WPOptions('depicter_');
		};
		$app->alias( 'options', 'depicter.options' );

		$container[ 'depicter.wp.cli.service' ] = function () {
			return new WPCliService();
		};
		$app->alias( 'cli', 'depicter.wp.cli.service' );

	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		register_activation_hook(  DEPICTER_PLUGIN_FILE, [ $this, 'activate'  ] );
		register_deactivation_hook(DEPICTER_PLUGIN_FILE, [ $this, 'deactivate'] );

		add_action( 'plugins_loaded', [$this, 'loadTextDomain'] );
		add_action( 'admin_init', [ $this, 'check_plugin_upgrade_via_upload' ] );
		add_action( 'admin_init', [ $this, 'check_redirect_process' ] );
		add_filter( 'update_plugin_complete_actions', [ $this, 'add_depicter_link_after_upgrade'], 10, 1);

		if ( defined('WP_CLI') && WP_CLI ) {
			\WP_CLI::add_command( 'depicter', \Depicter::app()->cli() );
		}
	}

	/**
	 * Plugin activation.
	 *
	 * @return void
	 */
	public function activate() {
		set_transient( 'depicter_do_activation_redirect', true, 60 );
	}

	/**
	 * Plugin deactivation.
	 *
	 * @return void
	 */
	public function deactivate() {
		// Nothing to do right now.
	}

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	public function loadTextDomain() {
		load_plugin_textdomain( 'depicter', false, basename( dirname( DEPICTER_PLUGIN_FILE ) ) . DIRECTORY_SEPARATOR . 'languages' );
	}

	/**
	 * Check if plugin updated via upload or not
	 */
	public function check_plugin_upgrade_via_upload() {
		$previousVersion = \Depicter::options()->get( 'version', 0 );
		if ( version_compare( DEPICTER_VERSION, $previousVersion, '>' ) ) {
			\Depicter::options()->set( 'version_previous', $previousVersion );
			\Depicter::options()->set( 'version', DEPICTER_VERSION );
			do_action( 'depicter/plugin/updated' );
		}
	}

	/**
	 * Add go to depicter dashboard link after upgrade at bottom of upgrade page
	 *
	 * @param array $install_actions
	 * @return void
	 */
	public function add_depicter_link_after_upgrade( $install_actions ){
		$install_actions['depicter_dashboard'] = sprintf(
			'<a href="%s" target="_parent">%s</a>',
			admin_url( 'admin.php?page=depicter-dashboard' ),
			__( 'Go to Depicter', 'depicter' )
		);

		return $install_actions;
	}

	public function check_redirect_process() {
		// Check if the transient is set
		if (  get_transient('depicter_do_activation_redirect') ) {
			// Delete the transient to prevent repeated redirects
			delete_transient('depicter_do_activation_redirect');

			if ( \Depicter::client()->isNewClient() && ! wp_doing_ajax() ) {
				// Redirect to the desired page
				wp_safe_redirect( admin_url('admin.php?page=depicter-dashboard') );
				exit;
			}
		}
	}
}
