<?php
namespace Depicter\WordPress;

use Depicter\Services\ClientService;
use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register admin-related entities and hooks, like admin menu pages.
 */
class AdminServiceProvider implements ServiceProviderInterface {

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$app = $container[ WPEMERGE_APPLICATION_KEY ];

		$container['depicter.system.check'] = function() {
			return new SystemCheckService();
		};

		// register deactivation feedback
		$container[ 'depicter.deactivation.feedback' ] = function () {
			return new DeactivationFeedbackService();
		};
		$app->alias( 'deactivationFeedback', 'depicter.deactivation.feedback' );

		// register client service
		$container['depicter.services.client.api'] = function() {
			return new ClientService();
		};
		$app->alias( 'client', 'depicter.services.client.api' );

		// register wp file upload service
		$container['depicter.services.file.uploader'] = function() {
			return new FileUploaderService();
		};
		$app->alias( 'fileUploader', 'depicter.services.file.uploader' );

		// register wp scheduling service
		$container['depicter.services.schedule'] = function() {
			return new SchedulingService();
		};
		$app->alias( 'schedule', 'depicter.services.schedule' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {

		if ( is_admin() ){

			// Only executes in admin pages
			if( ! ( defined('DOING_AJAX') && DOING_AJAX ) ){
				\Depicter::resolve('depicter.deactivation.feedback');
				\Depicter::resolve('depicter.auto.update.check' );
				\Depicter::resolve('depicter.system.check');

				\Depicter::client()->authorize();
			}

			add_filter( 'plugin_action_links_' . DEPICTER_PLUGIN_BASENAME, [ $this, 'plugin_action_links' ] );
			add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
		}

		\Depicter::schedule()->hooks();
	}

	/**
	 * Adds action links to the plugin list table
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {

		if ( \Depicter::auth()->isPaid() ) {
			return $links;
		}

		$links['go-pro'] = sprintf( '<a href="%1$s" target="_blank" class="depicter-go-pro">%2$s</a>',
			'https://depicter.com/pricing?utm_source=depicter&utm_medium=depicter-free&utm_campaign=free-to-pro&utm_term=unlock-plugins-table',
			esc_html__( 'Upgrade to PRO', 'depicter' )
		);

		return $links;
	}

	/**
	 * Adds row meta links to the plugin list table
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( DEPICTER_PLUGIN_BASENAME === $plugin_file ) {
			$row_meta = [
				'video' => '<a href="https://www.youtube.com/@depicterApp" target="_blank">' . esc_html__( 'Video Tutorials', 'depicter' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

}
