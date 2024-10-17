<?php
/**
 * Header Layout
 * @package Grocery Shopping Store
 */

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$grocery_shopping_store_header_search = get_theme_mod( 'grocery_shopping_store_header_search', 
$grocery_shopping_store_default['grocery_shopping_store_header_search'] );

$grocery_shopping_store_header_layout_button = esc_html( get_theme_mod( 'grocery_shopping_store_header_layout_button',
$grocery_shopping_store_default['grocery_shopping_store_header_layout_button'] ) );

$grocery_shopping_store_header_layout_button_url = esc_url( get_theme_mod( 'grocery_shopping_store_header_layout_button_url',
$grocery_shopping_store_default['grocery_shopping_store_header_layout_button_url'] ) );

?>

<section id="center-header">
    <div class=" header-main wrapper-flex">
        <div class="header-right-box theme-header-areas">
            <header id="site-header" class="site-header-layout header-layout" role="banner">
                <div class="header-center">
                    <div class="theme-header-areas header-areas-right header-logo">
                        <div class="header-titles">
                            <?php
                                grocery_shopping_store_site_logo();
                                grocery_shopping_store_site_description();
                            ?>
                        </div>
                    </div>
                    <div class="theme-header-areas header-areas-right header-menu">
                        <div class="site-navigation">
                            <nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'grocery-shopping-store'); ?>" role="navigation">
                                <ul class="primary-menu theme-menu">
                                    <?php
                                    if (has_nav_menu('grocery-shopping-store-primary-menu')) {
                                        wp_nav_menu(
                                            array(
                                                'container' => '',
                                                'items_wrap' => '%3$s',
                                                'theme_location' => 'grocery-shopping-store-primary-menu',
                                            )
                                        );
                                    } else {
                                        wp_list_pages(
                                            array(
                                                'match_menu_classes' => true,
                                                'show_sub_menu_icons' => true,
                                                'title_li' => false,
                                                'walker' => new Grocery_Shopping_Store_Walker_Page(),
                                            )
                                        );
                                    } ?>
                                </ul>
                            </nav>
                        </div>
                        <div class="navbar-controls twp-hide-js">
                            <button type="button" class="navbar-control navbar-control-offcanvas">
                                <span class="navbar-control-trigger" tabindex="-1">
                                    <?php grocery_shopping_store_the_theme_svg('menu'); ?>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="theme-header-areas header-areas-right woo-box">
                        <?php if( $grocery_shopping_store_header_search ){ ?>
                            <span class="header-search"> 
                                <a href="#search">
                                  <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6 .1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
                                </a>
                                <!-- Search Form -->
                                <div id="search">
                                    <span class="close">X</span>
                                    <?php get_search_form(); ?>
                                </div>
                            </span>
                        <?php } ?>
                        <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                            <?php if(defined('YITH_WCWL')){ ?>
                              <a class="wishlist_view" href="<?php echo YITH_WCWL()->get_wishlist_url(); ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M462.3 62.6C407.5 15.9 326 24.3 275.7 76.2L256 96.5l-19.7-20.3C186.1 24.3 104.5 15.9 49.7 62.6c-62.8 53.6-66.1 149.8-9.9 207.9l193.5 199.8c12.5 12.9 32.8 12.9 45.3 0l193.5-199.8c56.3-58.1 53-154.3-9.8-207.9z"/></svg>
                                  <?php $wishlist_count = YITH_WCWL()->count_products(); ?>
                                <span class="wishlist-counter"><?php echo $wishlist_count; ?></span>
                              </a>
                            <?php }?>
                        <?php } ?>
                        <?php if(class_exists('woocommerce')){ ?>
                            <span class="cart_no">
                                <a href="<?php if(function_exists('wc_get_cart_url')){ echo esc_url(wc_get_cart_url()); } ?>" title="<?php esc_attr_e( 'shopping cart','grocery-shopping-store' ); ?>"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M528.1 301.3l47.3-208C578.8 78.3 567.4 64 552 64H159.2l-9.2-44.8C147.8 8 137.9 0 126.5 0H24C10.7 0 0 10.7 0 24v16c0 13.3 10.7 24 24 24h69.9l70.2 343.4C147.3 417.1 136 435.2 136 456c0 30.9 25.1 56 56 56s56-25.1 56-56c0-15.7-6.4-29.8-16.8-40h209.6C430.4 426.2 424 440.3 424 456c0 30.9 25.1 56 56 56s56-25.1 56-56c0-22.2-12.9-41.3-31.6-50.4l5.5-24.3c3.4-15-8-29.3-23.4-29.3H218.1l-6.5-32h293.1c11.2 0 20.9-7.8 23.4-18.7z"/></svg></a>
                                <span class="cart-value"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="theme-header-areas header-areas-right header-button">
                        <?php if( $grocery_shopping_store_header_layout_button || $grocery_shopping_store_header_layout_button_url ){ ?>
                            <span>
                                <a href="<?php echo esc_url( $grocery_shopping_store_header_layout_button_url ); ?>"><?php echo esc_html( $grocery_shopping_store_header_layout_button ); ?></a>
                            </span>
                        <?php } ?>
                    </div>
                </div>
            </header>
        </div>
    </div>
</section>