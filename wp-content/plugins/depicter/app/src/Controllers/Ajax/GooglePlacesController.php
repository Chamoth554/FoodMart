<?php

namespace Depicter\Controllers\Ajax;

use Depicter\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class GooglePlacesController
{

	/**
	 * search for places
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function search( RequestInterface $request, $view ) {

        $search = Sanitize::textfield( $request->query('s', '') );

        if ( empty( $search ) ) {
            return \Depicter::json([
                'errors' => [ __( "Search parameter required.", 'depicter' ) ]
            ])->withStatus(400);
        }

        $places = \Depicter::dataSource()->googlePlaces()->searchPlaces( $search );
		if ( $places['success'] ) {
			unset( $places['success'] );
			return \Depicter::json( $places )->withStatus(200);
		}

		return \Depicter::json([
			'errors' => $places['errors']
		])->withStatus(400);
    }

	/**
	 * Get reviews for a place
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function reviews( RequestInterface $request, $view ) {

		$args = [
			'id' => Sanitize::textfield( $request->query('id', '') ),
			'minRating' => Sanitize::float( $request->query('minRating', 0) ),
			'dateStart' => Sanitize::textfield( $request->query('dateStart', '') ),
			'dateEnd' => Sanitize::textfield( $request->query('dateEnd', '') )
		];

        if ( empty( $args['id'] ) ) {
            return \Depicter::json([
                'errors' => [ __( "Place ID is required.", 'depicter' ) ]
            ])->withStatus(400);
        }

        $reviews = \Depicter::dataSource()->googlePlaces()->previewRecords( $args );

		if ( $reviews['success'] ) {
			return \Depicter::json([
				'hits' => $reviews['hits']
			])->withStatus(200);
		}

		return \Depicter::json([
			'errors' => $reviews['errors']
		])->withStatus(400);
    }
}
