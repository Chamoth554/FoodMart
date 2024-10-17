<?php
namespace Depicter\Document;

use Depicter\Document\Migrations\DocumentMigration;
use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Load document data manager.
 */
class ServiceProvider implements ServiceProviderInterface {

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		$app = $container[ WPEMERGE_APPLICATION_KEY ];

		$container[ 'depicter.document.mapper' ] = function () {
			return new Mapper();
		};
		$container[ 'depicter.document.manager' ] = function () {
			return new Manager();
		};

		$container[ 'depicter.document.data.migration' ] = function () {
			return new DocumentMigration();
		};

		$app->alias( 'document', 'depicter.document.manager' );
		$app->alias( 'documentMigration', 'depicter.document.data.migration' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		\Depicter::document()->bootstrap();
	}

}
