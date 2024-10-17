<?php
/**
 * Custom Functions.
 *
 * @package Grocery Shopping Store
 */

if( !function_exists( 'grocery_shopping_store_fonts_url' ) ) :

    //Google Fonts URL
    function grocery_shopping_store_fonts_url(){

        $font_families = array(
            'Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900',
            'Noto+Sans:ital,wght@0,100..900;1,100..900',
        );

        $fonts_url = add_query_arg( array(
            'family' => implode( '&family=', $font_families ),
            'display' => 'swap',
        ), 'https://fonts.googleapis.com/css2' );

        return esc_url_raw($fonts_url);
    }

endif;

if ( ! function_exists( 'grocery_shopping_store_sub_menu_toggle_button' ) ) :

    function grocery_shopping_store_sub_menu_toggle_button( $args, $item, $depth ) {

        // Add sub menu toggles to the main menu with toggles
        if ( $args->theme_location == 'grocery-shopping-store-primary-menu' && isset( $args->show_toggles ) ) {
            
            // Wrap the menu item link contents in a div, used for positioning
            $args->before = '<div class="submenu-wrapper">';
            $args->after  = '';

            // Add a toggle to items with children
            if ( in_array( 'menu-item-has-children', $item->classes ) ) {

                $toggle_target_string = '.menu-item.menu-item-' . $item->ID . ' > .sub-menu';

                // Add the sub menu toggle
                $args->after .= '<button type="button" class="theme-aria-button submenu-toggle" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="250" aria-expanded="false"><span class="btn__content" tabindex="-1"><span class="screen-reader-text">' . esc_html__( 'Show sub menu', 'grocery-shopping-store' ) . '</span>' . grocery_shopping_store_get_theme_svg( 'chevron-down' ) . '</span></button>';

            }

            // Close the wrapper
            $args->after .= '</div><!-- .submenu-wrapper -->';
            // Add sub menu icons to the main menu without toggles (the fallback menu)

        }elseif( $args->theme_location == 'grocery-shopping-store-primary-menu' ) {

            if ( in_array( 'menu-item-has-children', $item->classes ) ) {

                $args->before = '<div class="link-icon-wrapper">';
                $args->after  = grocery_shopping_store_get_theme_svg( 'chevron-down' ) . '</div>';

            } else {

                $args->before = '';
                $args->after  = '';

            }

        }

        return $args;

    }

endif;

add_filter( 'nav_menu_item_args', 'grocery_shopping_store_sub_menu_toggle_button', 10, 3 );

if ( ! function_exists( 'grocery_shopping_store_the_theme_svg' ) ):
    
    function grocery_shopping_store_the_theme_svg( $svg_name, $return = false ) {

        if( $return ){

            return grocery_shopping_store_get_theme_svg( $svg_name ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in grocery_shopping_store_get_theme_svg();.

        }else{

            echo grocery_shopping_store_get_theme_svg( $svg_name ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in grocery_shopping_store_get_theme_svg();.

        }
    }

endif;

if ( ! function_exists( 'grocery_shopping_store_get_theme_svg' ) ):

    function grocery_shopping_store_get_theme_svg( $svg_name ) {

        // Make sure that only our allowed tags and attributes are included.
        $svg = wp_kses(
            Grocery_Shopping_Store_SVG_Icons::get_svg( $svg_name ),
            array(
                'svg'     => array(
                    'class'       => true,
                    'xmlns'       => true,
                    'width'       => true,
                    'height'      => true,
                    'viewbox'     => true,
                    'aria-hidden' => true,
                    'role'        => true,
                    'focusable'   => true,
                ),
                'path'    => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'd'         => true,
                    'transform' => true,
                ),
                'polygon' => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'points'    => true,
                    'transform' => true,
                    'focusable' => true,
                ),
                'polyline' => array(
                    'fill'      => true,
                    'points'    => true,
                ),
                'line' => array(
                    'fill'      => true,
                    'x1'      => true,
                    'x2' => true,
                    'y1'    => true,
                    'y2' => true,
                ),
            )
        );
        if ( ! $svg ) {
            return false;
        }
        return $svg;

    }

endif;

if( !function_exists( 'grocery_shopping_store_post_category_list' ) ) :

    // Post Category List.
    function grocery_shopping_store_post_category_list( $select_cat = true ){

        $post_cat_lists = get_categories(
            array(
                'hide_empty' => '0',
                'exclude' => '1',
            )
        );

        $post_cat_cat_array = array();
        if( $select_cat ){

            $post_cat_cat_array[''] = esc_html__( '-- Select Category --','grocery-shopping-store' );

        }

        foreach ( $post_cat_lists as $post_cat_list ) {

            $post_cat_cat_array[$post_cat_list->slug] = $post_cat_list->name;

        }

        return $post_cat_cat_array;
    }

endif;

if( !function_exists('grocery_shopping_store_single_post_navigation') ):

    function grocery_shopping_store_single_post_navigation(){

        $grocery_shopping_store_footer_column_layout = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_twp_navigation_type = esc_attr( get_post_meta( get_the_ID(), 'grocery_shopping_store_twp_disable_ajax_load_next_post', true ) );
        $current_id = '';
        $article_wrap_class = '';
        global $post;
        $current_id = $post->ID;
        if( $grocery_shopping_store_twp_navigation_type == '' || $grocery_shopping_store_twp_navigation_type == 'global-layout' ){
            $grocery_shopping_store_twp_navigation_type = get_theme_mod('grocery_shopping_store_twp_navigation_type', $grocery_shopping_store_footer_column_layout['grocery_shopping_store_twp_navigation_type']);
        }

        if( $grocery_shopping_store_twp_navigation_type != 'no-navigation' && 'post' === get_post_type() ){

            if( $grocery_shopping_store_twp_navigation_type == 'theme-normal-navigation' ){ ?>

                <div class="navigation-wrapper">
                    <?php
                    // Previous/next post navigation.
                    the_post_navigation(array(
                        'prev_text' => '<span class="arrow" aria-hidden="true">' . grocery_shopping_store_the_theme_svg('arrow-left',$return = true ) . '</span><span class="screen-reader-text">' . esc_html__('Previous post:', 'grocery-shopping-store') . '</span><span class="post-title">%title</span>',
                        'next_text' => '<span class="arrow" aria-hidden="true">' . grocery_shopping_store_the_theme_svg('arrow-right',$return = true ) . '</span><span class="screen-reader-text">' . esc_html__('Next post:', 'grocery-shopping-store') . '</span><span class="post-title">%title</span>',
                    )); ?>
                </div>
                <?php

            }else{

                $next_post = get_next_post();
                if( isset( $next_post->ID ) ){

                    $next_post_id = $next_post->ID;
                    echo '<div loop-count="1" next-post="' . absint( $next_post_id ) . '" class="twp-single-infinity"></div>';

                }
            }

        }

    }

endif;

add_action( 'grocery_shopping_store_navigation_action','grocery_shopping_store_single_post_navigation',30 );

if( !function_exists('grocery_shopping_store_content_offcanvas') ):

    // Offcanvas Contents
    function grocery_shopping_store_content_offcanvas(){ ?>

        <div id="offcanvas-menu">
            <div class="offcanvas-wraper">
                <div class="close-offcanvas-menu">
                    <div class="offcanvas-close">
                        <a href="javascript:void(0)" class="skip-link-menu-start"></a>
                        <button type="button" class="button-offcanvas-close">
                            <span class="offcanvas-close-label">
                                <?php echo esc_html('Close', 'grocery-shopping-store'); ?>
                            </span>
                        </button>
                    </div>
                </div>
                <div id="primary-nav-offcanvas" class="offcanvas-item offcanvas-main-navigation">
                    <nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'grocery-shopping-store'); ?>" role="navigation">
                        <ul class="primary-menu theme-menu">
                            <?php
                            if (has_nav_menu('grocery-shopping-store-primary-menu')) {
                                wp_nav_menu(
                                    array(
                                        'container' => '',
                                        'items_wrap' => '%3$s',
                                        'theme_location' => 'grocery-shopping-store-primary-menu',
                                        'show_toggles' => true,
                                    )
                                );
                            }else{

                                wp_list_pages(
                                    array(
                                        'match_menu_classes' => true,
                                        'show_sub_menu_icons' => true,
                                        'title_li' => false,
                                        'show_toggles' => true,
                                        'walker' => new Grocery_Shopping_Store_Walker_Page(),
                                    )
                                );
                            }
                            ?>
                        </ul>
                    </nav><!-- .primary-menu-wrapper -->
                </div>
                <a href="javascript:void(0)" class="skip-link-menu-end"></a>
            </div>
        </div>

    <?php
    }

endif;

add_action( 'grocery_shopping_store_before_footer_content_action','grocery_shopping_store_content_offcanvas',30 );

if( !function_exists('grocery_shopping_store_footer_content_widget') ):

    function grocery_shopping_store_footer_content_widget(){

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        
            $grocery_shopping_store_grocery_shopping_store_footer_column_layout = absint(get_theme_mod('grocery_shopping_store_footer_column_layout', $grocery_shopping_store_default['grocery_shopping_store_footer_column_layout']));
            $grocery_shopping_store_footer_sidebar_class = 12;
            if($grocery_shopping_store_grocery_shopping_store_footer_column_layout == 2) {
                $grocery_shopping_store_footer_sidebar_class = 6;
            }
            if($grocery_shopping_store_grocery_shopping_store_footer_column_layout == 3) {
                $grocery_shopping_store_footer_sidebar_class = 4;
            }
            ?>
           
            <?php if ( get_theme_mod('grocery_shopping_store_display_footer', true) == true ) : ?>
                <div class="footer-widgetarea">
                    <div class="wrapper">
                        <div class="column-row">

                            <?php for ($i=0; $i < $grocery_shopping_store_grocery_shopping_store_footer_column_layout; $i++) {
                                ?>
                                <div class="column <?php echo 'column-' . absint($grocery_shopping_store_footer_sidebar_class); ?> column-sm-12">
                                    <?php dynamic_sidebar('grocery-shopping-store-footer-widget-' . $i); ?>
                                </div>
                           <?php } ?>

                        </div>
                    </div>
                </div>
             <?php endif; ?>

        <?php

    }

endif;

add_action( 'grocery_shopping_store_footer_content_action','grocery_shopping_store_footer_content_widget',10 );

if( !function_exists('grocery_shopping_store_footer_content_info') ):

    /**
     * Footer Copyright Area
    **/
    function grocery_shopping_store_footer_content_info(){

        $grocery_shopping_store_footer_column_layout = grocery_shopping_store_get_default_theme_options(); ?>
        <div class="site-info">
            <div class="wrapper">
                <div class="column-row">
                    <div class="column column-9">
                        <div class="footer-credits">
                            <div class="footer-copyright">
                                <?php
                                $grocery_shopping_store_footer_copyright_text = wp_kses_post( get_theme_mod( 'grocery_shopping_store_footer_copyright_text', $grocery_shopping_store_footer_column_layout['grocery_shopping_store_footer_copyright_text'] ) );
                                    echo esc_html( $grocery_shopping_store_footer_copyright_text );
                                    echo '<br>';
                                    echo esc_html__('Theme: ', 'grocery-shopping-store') . 'Grocery Shopping Store ' . esc_html__('By ', 'grocery-shopping-store') . '  <span>' . esc_html__('OMEGA ', 'grocery-shopping-store') . '</span>';
                                    echo esc_html__('Powered by ', 'grocery-shopping-store') . '<a href="' . esc_url('https://wordpress.org') . '" title="' . esc_attr__('WordPress', 'grocery-shopping-store') . '" target="_blank"><span>' . esc_html__('WordPress.', 'grocery-shopping-store') . '</span></a>';
                                 ?>
                            </div>
                        </div>
                    </div>
                    <div class="column column-3 align-text-right">
                        <a class="to-the-top" href="#site-header">
                            <span class="to-the-top-long">
                                <?php
                                printf( esc_html__( 'To the Top %s', 'grocery-shopping-store' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
                                ?>
                            </span>
                            <span class="to-the-top-short">
                                <?php
                                printf( esc_html__( 'Up %s', 'grocery-shopping-store' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
                                ?>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

endif;

add_action( 'grocery_shopping_store_footer_content_action','grocery_shopping_store_footer_content_info',20 );


if( !function_exists( 'grocery_shopping_store_main_slider' ) ) :

    function grocery_shopping_store_main_slider(){

        $grocery_shopping_store_defaults = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_header_slider = get_theme_mod( 'grocery_shopping_store_header_slider', $grocery_shopping_store_defaults['grocery_shopping_store_header_slider'] );

        $grocery_shopping_store_banner_right_image_1 = get_theme_mod( 'grocery_shopping_store_banner_right_image_1' );

        $grocery_shopping_store_banner_right_image_2 = get_theme_mod( 'grocery_shopping_store_banner_right_image_2' );

        $grocery_shopping_store_banner_right_image_3 = get_theme_mod( 'grocery_shopping_store_banner_right_image_3' );

        $grocery_shopping_store_slider_section_small_title = esc_html( get_theme_mod( 'grocery_shopping_store_slider_section_small_title',
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_small_title'] ) );

        $grocery_shopping_store_slider_section_sub_title = esc_html( get_theme_mod( 'grocery_shopping_store_slider_section_sub_title',
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_sub_title'] ) );

        $grocery_shopping_store_slider_section_content = esc_html( get_theme_mod( 'grocery_shopping_store_slider_section_content',
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_content'] ) );

        $grocery_shopping_store_slider_section_button_url = esc_url( get_theme_mod( 'grocery_shopping_store_slider_section_button_url',
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button_url'] ) );

        $grocery_shopping_store_slider_section_button = esc_html( get_theme_mod( 'grocery_shopping_store_slider_section_button',
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button'] ) );

        if( $grocery_shopping_store_header_slider ){ ?>
                <div id="site-content" class="slider-box">
                    <div class="wrapper-flex">
                        <div class="slider-main">
                            <div class="left-box">
                                <div class="slide-heading-main">
                                    <h4 class="slide-title">
                                        <?php if( $grocery_shopping_store_slider_section_small_title ){ ?>
                                            <?php echo esc_html($grocery_shopping_store_slider_section_small_title) ?>
                                        <?php } ?>
                                    </h4>
                                    <h3 class="slide-sub-title">
                                        <?php if( $grocery_shopping_store_slider_section_sub_title ){ ?>
                                            <?php echo esc_html($grocery_shopping_store_slider_section_sub_title) ?>
                                        <?php } ?>
                                    </h3>
                                    <p class="slide-content">
                                        <?php if( $grocery_shopping_store_slider_section_content ){ ?>
                                            <?php echo esc_html($grocery_shopping_store_slider_section_content) ?>
                                        <?php } ?>
                                    </p>
                                    <?php if( $grocery_shopping_store_slider_section_button_url || $grocery_shopping_store_slider_section_button ){ ?>
                                        <span class="slide-button">
                                            <a href="<?php echo $grocery_shopping_store_slider_section_button_url  ?>"><?php echo esc_html($grocery_shopping_store_slider_section_button) ?></a>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="right-box">
                                <div class="image-main-box">
                                    <div class="imagebox1">
                                        <div class="entry-thumbnail">
                                            <?php if( $grocery_shopping_store_banner_right_image_1 ){ ?>
                                                <img src="<?php echo esc_url( $grocery_shopping_store_banner_right_image_1 ); ?>" alt="Banner Right Image">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
    }

endif;

    if( !function_exists( 'grocery_shopping_store_product_section' ) ) :

    function grocery_shopping_store_product_section(){ 

        $grocery_shopping_store_defaults = grocery_shopping_store_get_default_theme_options();

        $grocery_shopping_store_product_section_title = esc_html( get_theme_mod( 'grocery_shopping_store_product_section_title',
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_title'] ) );

        $grocery_shopping_store_product_section_button_url = esc_html( get_theme_mod( 'grocery_shopping_store_product_section_button_url',
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button_url'] ) );

        $grocery_shopping_store_product_section_button = esc_html( get_theme_mod( 'grocery_shopping_store_product_section_button',
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button'] ) );

        $grocery_shopping_store_catData = get_theme_mod('grocery_shopping_store_featured_product_category','');
          
        if ( class_exists( 'WooCommerce' ) ) {
            $grocery_shopping_store_args = array(
                'post_type' => 'product',
                'posts_per_page' => 100,
                'product_cat' => $grocery_shopping_store_catData,
                'order' => 'ASC'
            ); ?>
        
            <div class="theme-product-block">
                <div class="wrapper">
                    <div class="shop-heading">
                        <?php if( $grocery_shopping_store_product_section_title ){ ?>
                            <h3><?php echo esc_html( $grocery_shopping_store_product_section_title ); ?></h3>
                        <?php } ?>
                        <?php if( $grocery_shopping_store_product_section_button_url || $grocery_shopping_store_product_section_button ){ ?>
                            <span class="product-button">
                                <a href="<?php echo $grocery_shopping_store_product_section_button_url?>"><?php echo esc_html($grocery_shopping_store_product_section_button) ?></a>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="owl-carousel" role="listbox">
                        <?php 
                        $loop = new WP_Query( $grocery_shopping_store_args );
                        while ( $loop->have_posts() ) : $loop->the_post(); 
                            global $product; 
                            $product_id = $product->get_id(); // Get product ID dynamically
                        ?>
                            <div class="grid-product">
                                <figure>
                                    <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(wc_placeholder_img_src()).'" />'; ?>
                                    <div class="product-cart">
                                        <?php if( $product->is_type( 'simple' ) ) { woocommerce_template_loop_add_to_cart(  $loop->post, $product );} ?>
                                    </div>
                                </figure>
                                <div class="product-text-box">
                                    <h5 class="product-text"><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h5>
                                    <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?> "><?php echo $product->get_price_html(); ?></p>
                                </div>
                            </div>
                        <?php endwhile; 
                        wp_reset_query();
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php }

endif;

if (!function_exists('grocery_shopping_store_post_format_icon')):

    // Post Format Icon.
    function grocery_shopping_store_post_format_icon() {

        $grocery_shopping_store_format = get_post_format(get_the_ID()) ?: 'standard';
        $grocery_shopping_store_icon = '';
        $grocery_shopping_store_title = '';
        if( $grocery_shopping_store_format == 'video' ){
            $grocery_shopping_store_icon = grocery_shopping_store_get_theme_svg( 'video' );
            $grocery_shopping_store_title = esc_html__('Video','grocery-shopping-store');
        }elseif( $grocery_shopping_store_format == 'audio' ){
            $grocery_shopping_store_icon = grocery_shopping_store_get_theme_svg( 'audio' );
            $grocery_shopping_store_title = esc_html__('Audio','grocery-shopping-store');
        }elseif( $grocery_shopping_store_format == 'gallery' ){
            $grocery_shopping_store_icon = grocery_shopping_store_get_theme_svg( 'gallery' );
            $grocery_shopping_store_title = esc_html__('Gallery','grocery-shopping-store');
        }elseif( $grocery_shopping_store_format == 'quote' ){
            $grocery_shopping_store_icon = grocery_shopping_store_get_theme_svg( 'quote' );
            $grocery_shopping_store_title = esc_html__('Quote','grocery-shopping-store');
        }elseif( $grocery_shopping_store_format == 'image' ){
            $grocery_shopping_store_icon = grocery_shopping_store_get_theme_svg( 'image' );
            $grocery_shopping_store_title = esc_html__('Image','grocery-shopping-store');
        }
        
        if (!empty($grocery_shopping_store_icon)) { ?>
            <div class="theme-post-format">
                <span class="post-format-icom"><?php echo grocery_shopping_store_svg_escape($grocery_shopping_store_icon); ?></span>
                <?php if( $grocery_shopping_store_title ){ echo '<span class="post-format-label">'.esc_html( $grocery_shopping_store_title ).'</span>'; } ?>
            </div>
        <?php }
    }

endif;

if ( ! function_exists( 'grocery_shopping_store_svg_escape' ) ):

    /**
     * Get information about the SVG icon.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function grocery_shopping_store_svg_escape( $input ) {

        // Make sure that only our allowed tags and attributes are included.
        $svg = wp_kses(
            $input,
            array(
                'svg'     => array(
                    'class'       => true,
                    'xmlns'       => true,
                    'width'       => true,
                    'height'      => true,
                    'viewbox'     => true,
                    'aria-hidden' => true,
                    'role'        => true,
                    'focusable'   => true,
                ),
                'path'    => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'd'         => true,
                    'transform' => true,
                ),
                'polygon' => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'points'    => true,
                    'transform' => true,
                    'focusable' => true,
                ),
            )
        );

        if ( ! $svg ) {
            return false;
        }

        return $svg;

    }

endif;

if( !function_exists( 'grocery_shopping_store_sanitize_sidebar_option_meta' ) ) :

    // Sidebar Option Sanitize.
    function grocery_shopping_store_sanitize_sidebar_option_meta( $input ){

        $grocery_shopping_store_metabox_options = array( 'global-sidebar','left-sidebar','right-sidebar','no-sidebar' );
        if( in_array( $input,$grocery_shopping_store_metabox_options ) ){

            return $input;

        }else{

            return '';

        }
    }

endif;

if( !function_exists( 'grocery_shopping_store_sanitize_pagination_meta' ) ) :

    // Sidebar Option Sanitize.
    function grocery_shopping_store_sanitize_pagination_meta( $input ){

        $grocery_shopping_store_metabox_options = array( 'Center','Right','Left');
        if( in_array( $input,$grocery_shopping_store_metabox_options ) ){

            return $input;

        }else{

            return '';

        }
    }

endif;

if( !function_exists( 'grocery_shopping_store_sanitize_menu_transform' ) ) :

    // Sidebar Option Sanitize.
    function grocery_shopping_store_sanitize_menu_transform( $input ){

        $grocery_shopping_store_metabox_options = array( 'capitalize','uppercase','lowercase');
        if( in_array( $input,$grocery_shopping_store_metabox_options ) ){

            return $input;

        }else{

            return '';

        }
    }

endif;

if( !function_exists( 'grocery_shopping_store_sanitize_page_content_alignment' ) ) :

    // Sidebar Option Sanitize.
    function grocery_shopping_store_sanitize_page_content_alignment( $input ){

        $grocery_shopping_store_metabox_options = array( 'left','center','right');
        if( in_array( $input,$grocery_shopping_store_metabox_options ) ){

            return $input;

        }else{

            return '';

        }
    }

endif;


if( !function_exists( 'grocery_shopping_store_sanitize_footer_widget_title_alignment' ) ) :

    // Footer Option Sanitize.
    function grocery_shopping_store_sanitize_footer_widget_title_alignment( $input ){

        $grocery_shopping_store_metabox_options = array( 'left','center','right');
        if( in_array( $input,$grocery_shopping_store_metabox_options ) ){

            return $input;

        }else{

            return '';

        }
    }

endif;
