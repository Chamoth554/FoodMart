<?php

namespace Depicter\Controllers\Ajax;

use Depicter\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class GoogleRecaptchaController
{

	/**
	 * form submit spam checker
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function verify( RequestInterface $request, $view ) {
		$token = Sanitize::textfield( $request->body( 'g-recaptcha-response', '' ) );

		if ( empty( $token ) ) {
			return \Depicter::json([
				'errors' => [ __( 'google recaptcha response parameter required.', 'depicter' ) ]
			])->withStatus( 400 );
		}

		$isValid = \Depicter::recaptcha()->verify( $token );
		if ( $isValid['success'] ) {
			return \Depicter::json( [ 'success' => true ] )->withStatus( 200 );
		}

		return \Depicter::json([
			'errors' => [ $isValid['message'] ]
		])->withStatus( 400 );
	}
}
