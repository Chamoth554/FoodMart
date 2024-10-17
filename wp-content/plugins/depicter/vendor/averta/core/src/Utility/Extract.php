<?php
namespace Averta\Core\Utility;


class Extract
{

	/**
	 * Extracts domain from absolute url
	 *
	 * @param string $url
	 *
	 * @return mixed|string
	 */
	public static function domain( $url ) {

		if ( empty( $url ) ) {
			return '';
		}

		$parsedUrl  = parse_url( $url );

		$host = '';

		if( isset( $parsedUrl['host'] ) ){
			$host = $parsedUrl['host'];

			// if it was a plain url without schema
		} elseif( isset( $parsedUrl['path'] ) ){
			$host = $parsedUrl['path'];
		}

		$hostParts = explode(".", $host);
		$domainExtensions = [];

		if( count( $hostParts ) > 2 ){
			$domainExtensions = array_slice( $hostParts, -2 );
		}

		if( count( $hostParts ) === 3 ){
			if( strlen(  implode( '', $domainExtensions ) ) > 5 ){
				$hostParts = $domainExtensions;
			}

		} elseif( count( $hostParts ) > 3 ){
			// skip ips
			if( ! is_numeric( implode( '', $hostParts ) ) ){
				// if one of the last two parts is domain
				if( strlen( implode( '', $domainExtensions ) ) > 5 ){
					$hostParts = $domainExtensions;
				} else {
					$hostParts = array_slice( $hostParts, -3 );
				}
			}
		}

		$host = implode( '.', $hostParts );
		return trim( $host, '/' );
	}

    /**
     * Extract payload of a JWT token
     *
     * @param string $jwt          JWT token
     * @param bool   $associative  Convert the payload to associative array or object
     *
     * @return false|mixed         Returns the payload in array or object, False on failure
     */
    public static function JWTPayload( $jwt, $associative = true ) {
        $tokenParts = explode('.', $jwt);
        // make sure there are header, payload, and signature
        if ( count( $tokenParts ) === 3 && !empty( $tokenParts[1] ) ) {
            $base64UrlPayload = str_replace( ['-', '_'], ['+', '/'], $tokenParts[1] );
            $jsonPayload = base64_decode( $base64UrlPayload );
            return json_decode( $jsonPayload, $associative );
        } else {
            return false;
        }
    }

}
