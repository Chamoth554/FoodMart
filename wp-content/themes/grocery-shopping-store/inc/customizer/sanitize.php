<?php
/**
* Custom Functions.
*
* @package Grocery Shopping Store
*/

if( !function_exists( 'grocery_shopping_store_sanitize_sidebar_option' ) ) :

    // Sidebar Option Sanitize.
    function grocery_shopping_store_sanitize_sidebar_option( $grocery_shopping_store_input ){

        $grocery_shopping_store_metabox_options = array( 'global-sidebar','left-sidebar','right-sidebar','no-sidebar' );
        if( in_array( $grocery_shopping_store_input,$grocery_shopping_store_metabox_options ) ){

            return $grocery_shopping_store_input;

        }

        return;

    }

endif;

if ( ! function_exists( 'grocery_shopping_store_sanitize_checkbox' ) ) :

	/**
	 * Sanitize checkbox.
	 */
	function grocery_shopping_store_sanitize_checkbox( $grocery_shopping_store_checked ) {

		return ( ( isset( $grocery_shopping_store_checked ) && true === $grocery_shopping_store_checked ) ? true : false );

	}

endif;


if ( ! function_exists( 'grocery_shopping_store_sanitize_select' ) ) :

    /**
     * Sanitize select.
     */
    function grocery_shopping_store_sanitize_select( $grocery_shopping_store_input, $grocery_shopping_store_setting ) {
        $grocery_shopping_store_input = sanitize_text_field( $grocery_shopping_store_input );
        $choices = $grocery_shopping_store_setting->manager->get_control( $grocery_shopping_store_setting->id )->choices;
        return ( array_key_exists( $grocery_shopping_store_input, $choices ) ? $grocery_shopping_store_input : $grocery_shopping_store_setting->default );
    }

endif;

/*Radio Button sanitization*/
function grocery_shopping_store_sanitize_choices( $input, $setting ) {
    global $wp_customize;
    $control = $wp_customize->get_control( $setting->id );
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}