<?php
/**
 * Grocery Shopping Store functions and definitions
 * @package Grocery Shopping Store
 */

if ( ! function_exists( 'grocery_shopping_store_after_theme_support' ) ) :

	function grocery_shopping_store_after_theme_support() {
		
		add_theme_support( 'automatic-feed-links' );

		add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('woocommerce', array(
            'gallery_thumbnail_image_width' => 300,
        ));

		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'ffffff',
			)
		);

		$GLOBALS['content_width'] = apply_filters( 'grocery_shopping_store_content_width', 1140 );
		
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 270,
				'width'       => 90,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		
		add_theme_support( 'title-tag' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);

		add_theme_support( 'post-formats', array(
		    'video',
		    'audio',
		    'gallery',
		    'quote',
		    'image'
		) );
		
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );

	}

endif;

add_action( 'after_setup_theme', 'grocery_shopping_store_after_theme_support' );

/**
 * Register and Enqueue Styles.
 */
function grocery_shopping_store_register_styles() {

	wp_enqueue_style( 'dashicons' );

    $theme_version = wp_get_theme()->get( 'Version' );
	$fonts_url = grocery_shopping_store_fonts_url();
    if( $fonts_url ){
    	require_once get_theme_file_path( 'lib/custom/css/wptt-webfont-loader.php' );
        wp_enqueue_style(
			'grocery-shopping-store-google-fonts',
			wptt_get_webfont_url( $fonts_url ),
			array(),
			$theme_version
		);
    }

    wp_enqueue_style( 'swiper', get_template_directory_uri() . '/lib/swiper/css/swiper-bundle.min.css');
    wp_enqueue_style( 'owl.carousel', get_template_directory_uri() . '/lib/custom/css/owl.carousel.min.css');
	wp_enqueue_style( 'grocery-shopping-store-style', get_stylesheet_uri(), array(), $theme_version );

	wp_enqueue_style( 'grocery-shopping-store-style', get_stylesheet_uri() );
	require get_parent_theme_file_path( '/custom_css.php' );
	wp_add_inline_style( 'grocery-shopping-store-style',$grocery_shopping_store_custom_css );

	$grocery_shopping_store_css = '';

	if ( get_header_image() ) :

		$grocery_shopping_store_css .=  '
			#center-header{
				background-image: url('.esc_url(get_header_image()).') !important;
				-webkit-background-size: cover !important;
				-moz-background-size: cover !important;
				-o-background-size: cover !important;
				background-size: cover !important;
			}';

	endif;

	wp_add_inline_style( 'grocery-shopping-store-style', $grocery_shopping_store_css );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}	

	wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'swiper', get_template_directory_uri() . '/lib/swiper/js/swiper-bundle.min.js', array('jquery'), '', 1);
	wp_enqueue_script( 'grocery-shopping-store-custom', get_template_directory_uri() . '/lib/custom/js/theme-custom-script.js', array('jquery'), '', 1);
	wp_enqueue_script( 'owl.carousel', get_template_directory_uri() . '/lib/custom/js/owl.carousel.js', array('jquery'), '', 1);

    // Global Query
    if( is_front_page() ){

    	$posts_per_page = absint( get_option('posts_per_page') );
        $c_paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
        $posts_args = array(
            'posts_per_page'        => $posts_per_page,
            'paged'                 => $c_paged,
        );
        $posts_qry = new WP_Query( $posts_args );
        $max = $posts_qry->max_num_pages;

    }else{
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $c_paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
    }

    $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
    $grocery_shopping_store_pagination_layout = get_theme_mod( 'grocery_shopping_store_pagination_layout',$grocery_shopping_store_default['grocery_shopping_store_pagination_layout'] );
}

add_action( 'wp_enqueue_scripts', 'grocery_shopping_store_register_styles',200 );

function grocery_shopping_store_admin_enqueue_scripts_callback() {
    if ( ! did_action( 'wp_enqueue_media' ) ) {
    wp_enqueue_media();
    }
    wp_enqueue_script('grocery-shopping-store-uploaderjs', get_stylesheet_directory_uri() . '/lib/custom/js/uploader.js', array(), "1.0", true);
}
add_action( 'admin_enqueue_scripts', 'grocery_shopping_store_admin_enqueue_scripts_callback' );

/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function grocery_shopping_store_menus() {

	$locations = array(
		'grocery-shopping-store-primary-menu'  => esc_html__( 'Primary Menu', 'grocery-shopping-store' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'grocery_shopping_store_menus' );

add_filter('loop_shop_columns', 'grocery_shopping_store_loop_columns');
if (!function_exists('grocery_shopping_store_loop_columns')) {
	function grocery_shopping_store_loop_columns() {
		$grocery_shopping_store_columns = get_theme_mod( 'grocery_shopping_store_per_columns', 3 );
		return $grocery_shopping_store_columns;
	}
}

add_filter( 'loop_shop_per_page', 'grocery_shopping_store_per_page', 20 );
function grocery_shopping_store_per_page( $grocery_shopping_store_cols ) {
  	$grocery_shopping_store_cols = get_theme_mod( 'grocery_shopping_store_product_per_page', 9 );
	return $grocery_shopping_store_cols;
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/classes/class-svg-icons.php';
require get_template_directory() . '/classes/class-walker-menu.php';
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/custom-functions.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/classes/body-classes.php';
require get_template_directory() . '/inc/widgets/widgets.php';
require get_template_directory() . '/inc/metabox.php';
require get_template_directory() . '/inc/pagination.php';
require get_template_directory() . '/lib/breadcrumbs/breadcrumbs.php';
require get_template_directory() . '/lib/custom/css/dynamic-style.php';
require get_template_directory() . '/inc/TGM/tgm.php';

/**
 * For Admin Page
 */
if (is_admin()) {
	require get_template_directory() . '/inc/get-started/get-started.php';
}

if (! defined( 'GROCERY_SHOPPING_STORE_DOCS_PRO' ) ){
define('GROCERY_SHOPPING_STORE_DOCS_PRO',__('https://layout.omegathemes.com/steps/pro-grocery-shopping-store/','grocery-shopping-store'));
}
if (! defined( 'GROCERY_SHOPPING_STORE_BUY_NOW' ) ){
define('GROCERY_SHOPPING_STORE_BUY_NOW',__('https://www.omegathemes.com/products/grocery-store-wordpress-theme','grocery-shopping-store'));
}
if (! defined( 'GROCERY_SHOPPING_STORE_SUPPORT_FREE' ) ){
define('GROCERY_SHOPPING_STORE_SUPPORT_FREE',__('https://wordpress.org/support/theme/grocery-shopping-store/','grocery-shopping-store'));
}
if (! defined( 'GROCERY_SHOPPING_STORE_REVIEW_FREE' ) ){
define('GROCERY_SHOPPING_STORE_REVIEW_FREE',__('https://wordpress.org/support/theme/grocery-shopping-store/reviews/#new-post','grocery-shopping-store'));
}
if (! defined( 'GROCERY_SHOPPING_STORE_DEMO_PRO' ) ){
define('GROCERY_SHOPPING_STORE_DEMO_PRO',__('https://layout.omegathemes.com/grocery-shopping-store/','grocery-shopping-store'));
}
if (! defined( 'GROCERY_SHOPPING_STORE_LITE_DOCS_PRO' ) ){
define('GROCERY_SHOPPING_STORE_LITE_DOCS_PRO',__('https://layout.omegathemes.com/steps/free-grocery-shopping-store/','grocery-shopping-store'));
}

function grocery_shopping_store_remove_customize_register() {
    global $wp_customize;

    $wp_customize->remove_setting( 'display_header_text' );
    $wp_customize->remove_control( 'display_header_text' );

}

add_action( 'customize_register', 'grocery_shopping_store_remove_customize_register', 11 );

// Apply styles based on customizer settings

function grocery_shopping_store_customizer_css() {
    ?>
    <style type="text/css">
        <?php
        $grocery_shopping_store_footer_widget_background_color = get_theme_mod('grocery_shopping_store_footer_widget_background_color');
        if ($grocery_shopping_store_footer_widget_background_color) {
            echo '.footer-widgetarea { background-color: ' . esc_attr($grocery_shopping_store_footer_widget_background_color) . '; }';
        }

        $footer_widget_background_image = get_theme_mod('footer_widget_background_image');
        if ($footer_widget_background_image) {
            echo '.footer-widgetarea { background-image: url(' . esc_url($footer_widget_background_image) . '); }';
        }
        $grocery_shopping_store_copyright_font_size = get_theme_mod('grocery_shopping_store_copyright_font_size');
        if ($grocery_shopping_store_copyright_font_size) {
            echo '.footer-copyright { font-size: ' . esc_attr($grocery_shopping_store_copyright_font_size) . 'px;}';
        }
        ?>
    </style>
    <?php
}
add_action('wp_head', 'grocery_shopping_store_customizer_css');