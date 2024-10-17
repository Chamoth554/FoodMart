<?php

namespace Depicter\Services;

use Averta\WordPress\Utility\JSON;
use Depicter\GuzzleHttp\Exception\GuzzleException;
use Depicter\Utility\Sanitize;

class GoogleRecaptchaV3
{
	public function verify( $token ) {

		$secret = \Depicter::options()->get( 'google_recaptcha_secret_key', '' );
		if ( empty( $secret ) ) {
			return false ;
		}

		$options = [
			'form_params' => [
				'secret' => $secret,
				'response' => $token
			]
		];

		try{
			$response = \Depicter::remote()->post( 'https://www.google.com/recaptcha/api/siteverify', $options );
			if ( $response->getStatusCode() != 200 ) {
				return [
					'success' => false,
					'data'    => [],
					'message' => __( 'Could not contact google to verify the request', 'depicter' )
				];
			}
			$content = $response->getBody()->getContents();
			$result  = JSON::decode( $content, true );

			$scoreThreshold = \Depicter::options()->get( 'google_recaptcha_score_threshold', 0.6 );

			if ( !empty( $result['score'] ) && ( $result['score'] > Sanitize::float( $scoreThreshold ) ) ) {
				return [
					'success' => true,
					'data'    => [
						'host'  => $result['hostname'] ?? ''
					],
					'message' => sprintf( __( 'Recaptcha challenge passed successfully. [%s]', 'depicter' ), $result['score'] )
				];

			} else {
				$failMessage = \Depicter::options()->get( 'google_recaptcha_fail_message','' ) . "[" .( $result['score'] ?? '0' ) . "]";

				return [
					'success' => false,
					'data'    => [
						'score' => $result['score'],
						'host'  => $result['hostname'] ?? ''
					],
					'message' => $failMessage
				];
			}
		} catch( GuzzleException $e ){
			return [
				'success' => false,
				'message' => $e->getMessage()
			];
		}

	}
}
