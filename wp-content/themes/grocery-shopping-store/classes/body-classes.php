<?php
/**
* Body Classes.
* @package Grocery Shopping Store
*/
 
 if (!function_exists('grocery_shopping_store_body_classes')) :

    function grocery_shopping_store_body_classes($classes) {

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        global $post;
        // Adds a class of hfeed to non-singular pages.
        if ( !is_singular() ) {
            $classes[] = 'hfeed';
        }

        // Adds a class of no-sidebar when there is no sidebar present.
        if ( !is_active_sidebar( 'sidebar-1' ) ) {
            $classes[] = 'no-sidebar';
        }

        $grocery_shopping_store_global_sidebar_layout = esc_html( get_theme_mod( 'grocery_shopping_store_global_sidebar_layout',$grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'] ) );

        if ( is_active_sidebar( 'sidebar-1' ) ) {
            if( is_single() || is_page() ){
                $grocery_shopping_store_post_sidebar = esc_html( get_post_meta( $post->ID, 'grocery_shopping_store_post_sidebar_option', true ) );
                if (empty($grocery_shopping_store_post_sidebar) || ($grocery_shopping_store_post_sidebar == 'global-sidebar')) {
                    $classes[] = esc_attr( $grocery_shopping_store_global_sidebar_layout );
                } else{
                    $classes[] = esc_attr( $grocery_shopping_store_post_sidebar );
                }
            }else{
                $classes[] = esc_attr( $grocery_shopping_store_global_sidebar_layout );
            }
            
        }
        
        return $classes;
    }

endif;

add_filter('body_class', 'grocery_shopping_store_body_classes');