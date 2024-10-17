<?php

namespace Averta\Core\Utility;

class Validator
{
    /**
     * Verifies that a URL is valid.
     *
     * @param mixed $value  URL address to verify.
     *
     * @return mixed
     */
    public static function isUrl( $value ){
        return filter_var( $value, FILTER_VALIDATE_URL );
    }

    /**
     * Verifies that a phone number is valid.
     *
     * @param mixed $value  Phone number to verify.
     *
     * @return bool
     */
    public static function isTel( $value ){
        if( is_numeric( $value ) ){
            return true;
        }
        return preg_match( "/^[0-9\-\(\)\/\+\s]*$/", $value);
    }

    /**
     * Verifies that an email is valid.
     *
     * @param string $value  Email address to verify.
     *
     * @return bool
     */
    public static function isEmail( $value ) {
        try{
			return static::checkEmail( $value );
		} catch( \Exception $exception ){
			return false;
		}
    }

    /**
     * Checks if it's a valid email and throws validation error on failure
     *
     * @param string $value Email address to verify.
     *
     * @return true
     *
     * @throws \Exception
     */
    public static function checkEmail( $value ) {

        // Test for the minimum length the email can be.
        if ( strlen( $value ) < 6 ) {
            throw new \Exception('Email too short.');
        }

        // Test for an @ character after the first position.
        if ( strpos( $value, '@', 1 ) === false ) {
            throw new \Exception('Email has no @.');
        }

        // Split out the local and domain parts.
        list( $local, $domain ) = explode( '@', $value, 2 );

        // Test for invalid characters.
        if ( ! preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
            throw new \Exception('Local invalid chars.');
        }

        // DOMAIN PART. Test for sequences of periods.
        if ( preg_match( '/\.{2,}/', $domain ) ) {
            throw new \Exception('Invalid sequences of domain periods.');
        }

        // Test for leading and trailing periods and whitespace.
        if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
            throw new \Exception('Invalid domain leading or trailing periods.');
        }

        // Split the domain into subs.
        $subs = explode( '.', $domain );

        // Assume the domain will have at least two subs.
        if ( 2 > count( $subs ) ) {
            throw new \Exception('Invalid domain subs.');
        }

        // Loop through each sub.
        foreach ( $subs as $sub ) {
            // Test for leading and trailing hyphens and whitespace.
            if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) {
                throw new \Exception('Invalid sub hyphens.');
            }

            // Test for invalid characters.
            if ( ! preg_match( '/^[a-z0-9-]+$/i', $sub ) ) {
                throw new \Exception('Invalid characters.');
            }
        }

        return true;
    }

}
