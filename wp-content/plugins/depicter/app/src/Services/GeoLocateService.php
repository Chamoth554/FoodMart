<?php

namespace Depicter\Services;

use Averta\Core\Utility\JSON;
use Depicter\GuzzleHttp\Exception\GuzzleException;

class GeoLocateService
{

	/**
	 * API endpoints for geolocating an IP address
	 *
	 * @var array
	 */
	private $geoipApies = array(
//		'ipinfo.io'  => 'https://ipinfo.io/%s/json',
		'ip-api.com' => 'http://ip-api.com/json/%s',
		'geoplugin.net' => 'http://www.geoplugin.net/json.gp?ip=%s',
	);

	public function getCountry(){
		$ip = $this->getIP();
		if ( ! $country_code = \Depicter::cache()->get( 'country_of_' . $ip ) ) {
			$geoipServicesKeys = array_keys( $this->geoipApies );

			shuffle( $geoipServicesKeys );

			foreach ( $geoipServicesKeys as $serviceName ) {
				$serviceEndpoint = $this->geoipApies[ $serviceName ];
				try {
					$response = \Depicter::remote()->get( sprintf( $serviceEndpoint, $ip ) );
					$responseBody = $response->getBody()->getContents();
					if ( JSON::isJson( $responseBody ) ) {
						$data = JSON::decode( $responseBody );

						switch ( $serviceName ) {
							case 'ipinfo.io':
								$country_code = $data->country ?? '';
								break;
							case 'ip-api.com':
								$country_code = $data->countryCode ?? ''; // @codingStandardsIgnoreLine
								break;
							case 'geoplugin.net':
								$country_code = $data->geoplugin_countryCode ?? ''; // @codingStandardsIgnoreLine
								break;
							default:
								$country_code = '';
								break;
						}

						$country_code = sanitize_text_field( strtoupper( $country_code ) );

						if ( $country_code ) {
							\Depicter::cache()->set( 'country_of_' . $ip, $country_code, DAY_IN_SECONDS );
							break;
						}
					}
				} catch( GuzzleException $e ){
				}

			}
		}

		return $country_code;
	}

	/**
	 * Get ip address
	 *
	 * @return mixed
	 */
	public function getIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}
