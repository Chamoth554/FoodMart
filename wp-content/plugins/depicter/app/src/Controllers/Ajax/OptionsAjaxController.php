<?php
namespace Depicter\Controllers\Ajax;

use Averta\WordPress\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class OptionsAjaxController {

	/**
	 * update setting
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function update( RequestInterface $request, $view ) {
		$id = Sanitize::textfield( $request->body('id', '') );

        if ( $id != 'allow_unfiltered_data_upload' ) {
            return \Depicter::json([
                'errors' => [__('You only has the right to update uploading unfiltered data option', 'depicter' ) ]
            ])->withStatus(403);
        }

        $value = (bool) $request->body('value', false) ? 'on' : 'off';
        if ( \Depicter::options()->set( 'allow_unfiltered_data_upload', $value ) ) {
	        return \Depicter::json([
				'success' => true,
                'value' => $value
            ])->withStatus(200);
        }

		return \Depicter::json([
			'errors' => [ __('Option value not changed.' , 'depicter' ) ]
		])->withStatus(400);
	}
}
