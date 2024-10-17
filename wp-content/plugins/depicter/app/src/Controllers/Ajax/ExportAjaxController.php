<?php
namespace Depicter\Controllers\Ajax;

use Averta\WordPress\File\FileSystem;
use Depicter\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class ExportAjaxController
{
	protected $namePrefix = DEPICTER_PLUGIN_ID;

	/**
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface|void
	 */
	public function pack( RequestInterface $request, $view ) {
		if ( ! apply_filters( 'depicter/can/export', true ) ) {
			return \Depicter::json([
				'errors' => [ 'Export service is disabled.' ]
			]);
		}

		$documentID = Sanitize::textfield( $request->query('id') );

		try {
			if ( ! $documentID = Sanitize::textfield( $request->query('id') ) ) {
				throw new \Exception( __( 'Document ID is required', 'depicter' ) );
			}

			$zip = \Depicter::exportService()->pack( $documentID );
			if ( $zip ) {
				$outputName = "{$this->namePrefix}-{$documentID}-" . gmdate("mdHis"). ".zip";
				header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="'. $outputName .'"');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($zip));
				echo \Depicter::storage()->filesystem()->read($zip);
				exit;
			}

		} catch ( \Exception  $exception ) {
			return \Depicter::json([
				'errors' => [ $exception->getMessage() ]
			]);
		}
	}
}
