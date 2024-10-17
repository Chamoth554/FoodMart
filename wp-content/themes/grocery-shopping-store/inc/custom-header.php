<?php
/**
 * Sample implementation of the Custom Header feature
 * @package Grocery Shopping Store
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses grocery_shopping_store_header_style()
 */
function grocery_shopping_store_custom_header_setup()
{
    add_theme_support('custom-header',
        apply_filters('grocery_shopping_store_custom_header_args', array(
            'default-image' => '',
            'default-text-color' => '000000',
            'width' => 1920,
            'height' => 400,
            'flex-height' => true,
            'flex-width' => true,
            'wp-head-callback' => 'grocery_shopping_store_header_style',
        )));
}

add_action('after_setup_theme', 'grocery_shopping_store_custom_header_setup');

if (!function_exists('grocery_shopping_store_header_style')) :
    /**
     * Styles the header image and text displayed on the blog
     *
     * @see grocery_shopping_store_custom_header_setup().
     */
    function grocery_shopping_store_header_style()
    {
        $grocery_shopping_store_header_text_color = get_header_textcolor();

        if (get_theme_support('custom-header', 'default-text-color') === $grocery_shopping_store_header_text_color) {
            return;
        }

        ?>
        <style type="text/css">
            <?php
                if ( 'blank' == $grocery_shopping_store_header_text_color ) :
            ?>
            .header-titles .custom-logo-name,
            .site-description {
                display: none;
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }

            <?php
                else :
            ?>
            .header-titles .custom-logo-name:not(:hover):not(:focus),
            .site-description {
                color: #<?php echo esc_attr( $grocery_shopping_store_header_text_color ); ?>;
            }

            <?php endif; ?>
        </style>
        <?php
    }
endif;