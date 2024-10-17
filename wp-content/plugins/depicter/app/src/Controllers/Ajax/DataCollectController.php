<?php

namespace Depicter\Controllers\Ajax;

use Depicter\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class DataCollectController
{
	public function getPermission( RequestInterface $request, $veiw ) {
		$state = Sanitize::textfield( $request->body('state', '' ) );

		$allowedState = [ 'allow', 'deny', 'not-set' ];
		if ( in_array( $state, $allowedState ) ) {
			\Depicter::options()->set( 'data_collect_consent', $state );
			return \Depicter::json( ['success' => true ] )->withStatus(200);
		}

		return \Depicter::json( [
			'errors' => [ __( 'Not a valid state value.', 'depicter' )]
		] )->withStatus(400);
	}
}
