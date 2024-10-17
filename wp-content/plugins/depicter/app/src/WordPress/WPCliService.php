<?php

namespace Depicter\WordPress;

use Averta\WordPress\Utility\JSON;
use Depicter\GuzzleHttp\Exception\GuzzleException;
use Depicter\Services\AssetsAPIService;

class WPCliService
{
	/**
	 * Add import sub command to depicter cli command
	 *
	 * @example wp depicter import --template-ids:1,2,3 --v=1 --directory=2 --site-id=5
	 * @param $args
	 * @param $assoc_args
	 *`
	 * @return void
	 */
	public function import( $args, $assoc_args ) {

		if ( empty( $assoc_args['template-ids'] ) ) {
			\WP_CLI::error( __( '--template-ids param is required.', 'depicter' ) );
			return;
		}

		if ( is_multisite() && ! empty( $assoc_args['site-id'] ) ) {
			switch_to_blog( $assoc_args['site-id'] );
		}

		\Depicter::client()->getRefreshToken();

		$templateIDs = explode( ',', $assoc_args['template-ids'] );
		$endpointVersion = !empty( $assoc_args['v'] ) ? (int) $assoc_args['v'] : 1;
		$directory = !empty( $assoc_args['directory'] ) ? (int) $assoc_args['directory'] : 2;

		foreach( $templateIDs as $templateID ){
			try {
				$result = AssetsAPIService::getDocumentTemplateData( $templateID, [ 'v' => $endpointVersion, 'directory' => $directory ] );
				if ( !empty( $result->errors ) ){
					foreach( $result->errors as $error ) {
						\WP_CLI::error( $error );
					}
				} elseif ( !empty( $result->hits ) ) {
					$editorData = JSON::encode( $result->hits );
					$editorData = preg_replace( '/"activeBreakpoint":".+?"/', '"activeBreakpoint":"default"', $editorData );
					$document   = \Depicter::documentRepository()->create();

					$updateData = [ 'content' => $editorData ];
					if( ! empty( $result->title ) ){
						$updateData['name'] = $result->title . ' ' . $document->getID();
					}
					if( ! empty( $result->image ) ){
						$imageRequest = wp_remote_get( $result->image );
						$previewImage = wp_remote_retrieve_body( $imageRequest );
						\Depicter::storage()->filesystem()->write( \Depicter::documentRepository()
						                                                    ->getPreviewImagePath( $document->getID() ), $previewImage );
					}

					\Depicter::documentRepository()->update( $document->getID(), $updateData );
					\Depicter::media()->importDocumentAssets( $editorData );

					if( \Depicter::options()->get( 'use_google_fonts', 'on' ) === 'save_locally' ){
						$documentModel = \Depicter::document()->getModel( $document->getID() )->prepare();
						\Depicter::googleFontsService()->download( $documentModel->getFontsLink() );
					}

					if ( is_multisite() && empty( $assoc_args['site-id'] ) ) {
						\WP_CLI::warning( __( 'slider imported on the main site. To import template on specific site please provide the --site-id param.', 'depicter' ) );
					}

					\WP_CLI::success(sprintf( __( 'Slider with ID of %s imported successfully', 'depicter' ), $templateID ) );
				}
			} catch( GuzzleException $e ) {
				\WP_CLI::error( $e->getMessage() );
			} catch( \Exception $e ){
				\WP_CLI::error( $e->getMessage() );
			}
		}
	}
}
