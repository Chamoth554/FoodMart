<?php
/**
 * The header for our theme
 *
 * @package Continental Restaurant
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'continental-restaurant' ); ?></a>

    <?php
    $continental_restaurant_preloader_wrap = absint(get_theme_mod('continental_restaurant_enable_preloader', 0));
    if ($continental_restaurant_preloader_wrap === 1): ?>
        <div id="loader">
            <div class="loader-container">
                <div id="preloader" class="loader-2">
                    <div class="dot"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <header id="masthead" class="site-header">
        
        <?php $continental_restaurant_has_header_image = has_header_image();

        if ($continental_restaurant_has_header_image) {
            $continental_restaurant_header_image_url = esc_url(get_header_image());
        } else {
            $continental_restaurant_header_image_url = '';
        }
        ?>
        <div class="header-info-box" style="background-image: url('<?php echo $continental_restaurant_header_image_url; ?>');">
            <div class="container">
                <div class="flex-row">
                    <div class="header-info-left">
                        <?php if (get_theme_mod('continental_restaurant_header_info_email')): ?>
                            <span class="contact-info">
                                <span class="main-box mail">
                                    <i class="fas fa-envelope"></i><span class="main-head"><a href="mailto:<?php echo esc_attr(get_theme_mod('continental_restaurant_header_info_email', '')); ?>"><?php echo esc_html(get_theme_mod('continental_restaurant_header_info_email')); ?></a></span>
                                </span>
                            </span>
                        <?php endif; ?>
                         <?php if (get_theme_mod('continental_restaurant_header_info_phone')): ?>
                            <span class="contact-info">
                                <span class="main-box phone">
                                    <i class="fas fa-phone-volume"></i><span class="main-head"><a href="tel:<?php echo esc_attr(get_theme_mod('continental_restaurant_header_info_phone', '')); ?>"><?php echo esc_html(get_theme_mod('continental_restaurant_header_info_phone')); ?></a></span>
                                </span>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="header-info-center">
                        <div class="site-branding">
                            <?php
                            the_custom_logo();
                            if (is_front_page() && is_home()):
                                if (get_theme_mod('continental_restaurant_site_title_text', true)): ?>
                                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                                <?php endif;
                            else:
                                if (get_theme_mod('continental_restaurant_site_title_text', true)): ?>
                                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                                <?php endif;
                            endif;

                            $continental_restaurant_description = get_bloginfo('description', 'display');
                            if ($continental_restaurant_description || is_customize_preview()):
                                if (get_theme_mod('continental_restaurant_site_tagline_text', false)): ?>
                                    <p class="site-description"><?php echo esc_html($continental_restaurant_description); ?></p>
                                <?php endif;
                            endif; ?>
                        </div>
                    </div>
                    <div class="header-info-right">
                        <?php if ( get_theme_mod('continental_restaurant_facebook_link_option') ) : ?><a href="<?php echo esc_url(get_theme_mod('continental_restaurant_facebook_link_option', '')); ?>"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                        <?php if ( get_theme_mod('continental_restaurant_twitter_link_option') ) : ?><a href="<?php echo esc_url(get_theme_mod('continental_restaurant_twitter_link_option', '')); ?>"><i class="fab fa-twitter"></i></span><?php endif; ?>
                        <?php if ( get_theme_mod('continental_restaurant_google_plus_link_option') ) : ?><a href="<?php echo esc_url(get_theme_mod('continental_restaurant_google_plus_link_option', '')); ?>"><i class="fab fa-google-plus-g"></i></a><?php endif; ?>
                        <?php if ( get_theme_mod('continental_restaurant_instagram_link_option') ) : ?><a href="<?php echo esc_url(get_theme_mod('continental_restaurant_instagram_link_option', '')); ?>"><i class="fab fa-instagram"></i></a><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-menu-box">
            <div class="container">
                <div class="nav-menu-header-left">
                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'continental-restaurant'); ?></span>
                            <i class="fas fa-bars"></i>
                        </button>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    </header>
</div>