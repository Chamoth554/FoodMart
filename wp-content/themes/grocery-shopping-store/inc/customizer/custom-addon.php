<?php
/**
* Custom Addons.
*
* @package Grocery Shopping Store
*/

$wp_customize->add_section( 'grocery_shopping_store_theme_pagination_options',
    array(
    'title'      => esc_html__( 'Customizer Custom Settings', 'grocery-shopping-store' ),
    'priority'   => 10,
    'capability' => 'edit_theme_options',
    'panel'      => 'grocery_shopping_store_theme_addons_panel',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_theme_pagination_options_alignment',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_theme_pagination_options_alignment'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_pagination_meta',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_theme_pagination_options_alignment',
    array(
    'label'       => esc_html__( 'Pagination Alignment', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_theme_pagination_options',
    'type'        => 'select',
    'choices'     => array(
        'Center'    => esc_html__( 'Center', 'grocery-shopping-store' ),
        'Right' => esc_html__( 'Right', 'grocery-shopping-store' ),
        'Left'  => esc_html__( 'Left', 'grocery-shopping-store' ),
        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_theme_breadcrumb_options_alignment',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_theme_breadcrumb_options_alignment'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_pagination_meta',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_theme_breadcrumb_options_alignment',
    array(
    'label'       => esc_html__( 'Breadcrumb Alignment', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_theme_pagination_options',
    'type'        => 'select',
    'choices'     => array(
        'Center'    => esc_html__( 'Center', 'grocery-shopping-store' ),
        'Right' => esc_html__( 'Right', 'grocery-shopping-store' ),
        'Left'  => esc_html__( 'Left', 'grocery-shopping-store' ),
        ),
    )
);

$wp_customize->add_setting('grocery_shopping_store_breadcrumb_font_size',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_breadcrumb_font_size'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_breadcrumb_font_size',
    array(
        'label'       => esc_html__('Breadcrumb Font Size', 'grocery-shopping-store'),
        'section'     => 'grocery_shopping_store_theme_pagination_options',
        'type'        => 'number',
        'input_attrs' => array(
           'min'   => 1,
           'max'   => 45,
           'step'   => 1,
        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_single_page_content_alignment',
    array(
    'default'           => $grocery_shopping_store_default['grocery_shopping_store_single_page_content_alignment'],
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'grocery_shopping_store_sanitize_page_content_alignment',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_single_page_content_alignment',
    array(
    'label'       => esc_html__( 'Single Page Content Alignment', 'grocery-shopping-store' ),
    'section'     => 'grocery_shopping_store_theme_pagination_options',
    'type'        => 'select',
    'choices'     => array(
        'left' => esc_html__( 'Left', 'grocery-shopping-store' ),
        'center'  => esc_html__( 'Center', 'grocery-shopping-store' ),
        'right'    => esc_html__( 'Right', 'grocery-shopping-store' ),
        ),
    )
);