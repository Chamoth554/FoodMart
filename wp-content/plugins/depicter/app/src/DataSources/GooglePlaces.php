<?php
namespace Depicter\DataSources;

use Averta\Core\Utility\Str;
use Averta\WordPress\Utility\JSON;
use Depicter\GuzzleHttp\Client;
use Depicter\GuzzleHttp\Exception\GuzzleException;

class GooglePlaces extends DataSourceBase implements DataSourceInterface
{

    /**
     * DataSource name
     *
     * @var string
     */
    protected string $type = 'googlePlaces';

    /**
     * DataSource properties
     *
     * @var array
     */
    protected array $properties = [];

    /**
     * Default input params for retrieving dataSource records
     *
     * @var array
     */
    protected array $defaultInputParams = [
        'id'        => null,
        'minRating' => 0,
        'dateStart' => '',
        'dateEnd'   => ''
    ];

    /**
     * Google places api endpoint
     *
     * @var string
     */
    protected string $api = "https://places.googleapis.com/v1/places";


    /**
     * Asset groups of this DataSource
     *
     * @var array
     */
    protected array $assetGroupNames = ['googlePlace', 'googlePlaceReview'];


    /**
     * Get list of datasheets and corresponding required arguments
     *
     * @param  array  $args
     *
     * @return array
     */
    public function getDataSheetArgs( array $args = [] )
    {
        return $this->getRecords( $this->prepare( $args ) );
    }

    /**
     * Retrieves the list of records based on query params
     *
     * @param $args
     *
     * @return array
     */
    protected function getRecords( $args )
    {
        if ( empty( $args['id'] ) ) {
            return [];
        }

		return $this->getReviews( $args );
    }

    /**
     * Renders preview for query params
     *
     * @param  array  $args
     *
     * @return array
     */
    public function previewRecords( array $args = [] )
    {
        $args = $this->prepare( $args );

        return $this->getRecords( $args );
    }

    /**
     * Checks if Google Places API Key is set
     *
     * @return string
     */
    public function hasValidApiKey()
    {
        return ! empty( $this->getApiKey() );
    }

    /**
     * Retrieves Google Places API Key
     *
     * @return string
     */
    private function getApiKey()
    {
        return \Depicter::options()->get( 'google_places_api_key', '' );
    }

    /**
     * Searches in places by a keyword
     *
     * @param  string  $search  Search keyword
     *
     * @return array
     */
    public function searchPlaces( $search )
    {
        if ( ! $apiKey = $this->getApiKey() ) {
            return [
                'success' => false,
                'errors'  => [
                    __( 'Google Places Api Key not found.', 'depicter' )
                ]
            ];
        }

        $client = new Client();

        try{
            $response = $client->post( $this->api . ":searchText", [
                'headers' => [
                    'Content-Type'     => 'application/json',
                    'X-Goog-Api-Key'   => $apiKey,
                    'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress'
                ],
                'json'    => [
                    'textQuery' => $search
                ]
            ] );

            if ( $response->getStatusCode() == 200 ) {
                $placesInfo = JSON::decode( $response->getBody()->getContents(), true );
                $hits       = array_map( function( $place ) {
                    return [
                        'id'       => $place['id'],
                        'label'    => $place['displayName']['text'],
                        'address'  => $place['formattedAddress'],
                        'language' => $place['displayName']['languageCode']
                    ];
                }, $placesInfo['places'] ?? [] );

                return [
                    'success' => true,
                    'hits'    => $hits
                ];
            }
        } catch ( GuzzleException $e ){
            $message = $e->getMessage();
            // extract error message from google api response
            if ( preg_match( '/"message"\s*:\s*"([^"]+)"/', $e->getMessage(), $matches ) ) {
                $message = $matches[1] ?? '';
            }

            return [
                'success' => false,
                'errors'  => [$message]
            ];
        }

        return [
            'success' => false,
            'errors'  => [__( 'Something goes wrong. Please try again later.', 'depicter' )]
        ];
    }

    /**
     * Retrieves a place details and reviews from Google Places API
     *
     * @param $args
     *
     * @return array
     */
    private function getReviews( $args = [] )
    {
        if ( ! $apiKey = $this->getApiKey() ) {
            return [
                'success' => false,
                'errors'  => [
                    __( 'Google Places Api Key not found.', 'depicter' )
                ]
            ];
        }

	    $cacheKey = 'g_place_reviews_' . $args['id'];
		if ( ! $reviews = \Depicter::cache( 'base' )->get( $cacheKey ) ) {
			$client = new Client();

			try{
				$response = $client->get( $this->api . '/' . $args['id'], [
					'headers' => [
						'Content-Type'     => 'application/json',
						'X-Goog-Api-Key'   => $apiKey,
						'X-Goog-FieldMask' => 'displayName,formattedAddress,reviews,userRatingCount,rating'
					]
				] );

				if ( $response->getStatusCode() == 200 ) {
					$place = JSON::decode( $response->getBody()->getContents(), true );

					$reviews = $place['reviews'] ?? [];
					\Depicter::cache( 'base' )->set( $cacheKey, $reviews, DAY_IN_SECONDS );
				} else {
					return [
						'success' => false,
						'errors'  => [__( 'Something goes wrong. Please try again later.', 'depicter' )]
					];
				}
			} catch ( GuzzleException $e ){
				$message = $e->getMessage();
				// extract error message from google api response
				if ( preg_match( '/"message"\s*:\s*"([^"]+)"/', $e->getMessage(), $matches ) ) {
					$message = $matches[1] ?? '';
				}

				return [
					'success' => false,
					'errors'  => [$message]
				];
			}
		}

	    $hits    = [];
	    foreach ( $reviews as $review ) {
		    // skip the review if rating is less than minRating
		    $reviewRating = (float) ( $review['rating'] ?? 0 );
		    if ( ! empty( $args['minRating'] ) && ( $args['minRating'] > $reviewRating ) ) {
			    continue;
		    }

		    if ( ! empty( $args['dateStart'] ) && ( strtotime( $args['dateStart'] ) > strtotime( $review['publishTime'] ) ) ) {
			    continue;
		    }

		    if ( ! empty( $args['dateEnd'] ) && ( strtotime( $args['dateEnd'] ) < strtotime( $review['publishTime'] ) ) ) {
			    continue;
		    }

		    $authorSrc = explode( '=', $review['authorAttribution']['photoUri'] )['0'] ?? '';
		    $authorSrc .= '=s400';

		    $hits[] = [
			    'id'                 => explode( '/', $review['name'] )['3'] ?? '', // extract the review ID
			    'placeName'          => $place['displayName']['text'],
			    'placeAverageRating' => $place['rating'] ?? '',
			    'placeRatingCount'   => $place['userRatingCount'] ?? '',
			    'relativeDate'       => $review['relativePublishTimeDescription'],
			    'date'               => $review['publishTime'] ?? '',
			    'rating'             => $review['rating'] ?? '',
			    'excerpt'            => Str::trimByChars( $review['originalText']['text'], 235 ),
			    'content'            => $review['originalText']['text'],
			    'authorPhoto'        => $authorSrc,
			    'author'             => [
				    'name' => $review['authorAttribution']['displayName'],
				    'url'  => $review['authorAttribution']['uri'],
				    'src'  => $authorSrc
			    ]
		    ];
	    }

	    return [
		    'success' => true,
		    'hits'    => $hits
	    ];
    }
}
