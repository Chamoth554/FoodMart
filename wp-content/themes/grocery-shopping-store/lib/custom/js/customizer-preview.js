/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
    // Site title and description.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.header-titles .custom-logo-name').text(to);
        });
    });
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    // Header text color.
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' === to) {
                $('.header-titles .custom-logo-name, .site-description').css({
                    clip: 'rect(1px, 1px, 1px, 1px)',
                    position: 'absolute',
                    display: 'none',
                });
            } else {
                $('.header-titles .custom-logo-name, .site-description').css({
                    clip: 'auto',
                    position: 'relative',
                    display: 'block',
                });
                $('.header-titles .custom-logo-name:not(:hover):not(:focus), .site-description').css({
                    color: to,
                });
            }
        });
    });

}(jQuery));