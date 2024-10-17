<?php
/**
* Header Banner Options.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
$grocery_shopping_store_post_category_list = grocery_shopping_store_post_category_list();

$wp_customize->add_section( 'grocery_shopping_store_header_slider_setting',
    array(
    'title'      => esc_html__( 'Slider Settings', 'grocery-shopping-store' ),
    'priority'   => 10,
    'capability' => 'edit_theme_options',
    'panel'      => 'grocery_shopping_store_theme_home_pannel',
    )
);

$wp_customize->add_setting('grocery_shopping_store_display_header_text',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_header_slider'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_header_text',
    array(
        'label' => esc_html__('Enable / Disable Tagline', 'grocery-shopping-store'),
        'section' => 'title_tagline',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_header_slider',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_header_slider'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_header_slider',
    array(
        'label' => esc_html__('Enable Slider', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_header_slider_setting',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_slider_section_small_title',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_slider_section_small_title'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_slider_section_small_title',
    array(
    'label'    => esc_html__( 'Slider Sub Title', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_header_slider_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_slider_section_sub_title',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_slider_section_sub_title'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_slider_section_sub_title',
    array(
    'label'    => esc_html__( 'Slider Title', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_header_slider_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_slider_section_content',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_slider_section_content'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_slider_section_content',
    array(
    'label'    => esc_html__( 'Slider Content', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_header_slider_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_slider_section_button',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_slider_section_button'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_slider_section_button',
    array(
    'label'    => esc_html__( 'Slider Button Url', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_header_slider_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_slider_section_button_url',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_slider_section_button_url'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_slider_section_button_url',
    array(
    'label'    => esc_html__( 'Slider Button Url', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_header_slider_setting',
    'type'     => 'url',
    )
);

$wp_customize->add_setting('grocery_shopping_store_banner_right_image_1',
    array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control( $wp_customize,'grocery_shopping_store_banner_right_image_1',
        array(
            'label' => __('Slider Right Image 1','grocery-shopping-store'),
            'section' => 'grocery_shopping_store_header_slider_setting',
            'settings' => 'grocery_shopping_store_banner_right_image_1',
        )
    )
);

// Products Settings

$wp_customize->add_section( 'grocery_shopping_store_product_setion_setting',
    array(
    'title'      => esc_html__( 'Products Settings', 'grocery-shopping-store' ),
    'priority'   => 10,
    'capability' => 'edit_theme_options',
    'panel'      => 'grocery_shopping_store_theme_home_pannel',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_product_section_title',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_product_section_title'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_product_section_title',
    array(
    'label'    => esc_html__( 'Product Title', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_product_setion_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_product_section_button',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_product_section_button'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_product_section_button',
    array(
    'label'    => esc_html__( 'Product Button Text', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_product_setion_setting',
    'type'     => 'text',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_product_section_button_url',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_product_section_button_url'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_product_section_button_url',
    array(
    'label'    => esc_html__( 'Product Button Url', 'grocery-shopping-store' ),
    'section'  => 'grocery_shopping_store_product_setion_setting',
    'type'     => 'text',
    )
);

$grocery_shopping_store_args = array(
    'type'                     => 'product',
    'child_of'                 => 0,
    'parent'                   => '',
    'orderby'                  => 'term_group',
    'order'                    => 'ASC',
    'hide_empty'               => false,
    'hierarchical'             => 1,
    'number'                   => '',
    'taxonomy'                 => 'product_cat',
    'pad_counts'               => false
);

$categories = get_categories($grocery_shopping_store_args);
$cat_posts = array();
$m = 0;
$cat_posts[]='Select';
foreach($categories as $category){
    if($m==0){
        $default = $category->slug;
        $m++;
    }
    $cat_posts[$category->slug] = $category->name;
}

$wp_customize->add_setting('grocery_shopping_store_featured_product_category',array(
    'default'   => 'select',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_select',
));
$wp_customize->add_control('grocery_shopping_store_featured_product_category',array(
    'type'    => 'select',
    'choices' => $cat_posts,
    'label' => __('Select category to display products ','grocery-shopping-store'),
    'section' => 'grocery_shopping_store_product_setion_setting',
));
