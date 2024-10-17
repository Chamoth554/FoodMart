<?php
/**
* Posts Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

// Single Post Section.
$wp_customize->add_section( 'grocery_shopping_store_posts_settings',
	array(
	'title'      => esc_html__( 'Meta Information Settings', 'grocery-shopping-store' ),
	'priority'   => 35,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

$wp_customize->add_setting('grocery_shopping_store_display_archive_post_image',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_display_archive_post_image'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_display_archive_post_image',
    array(
        'label' => esc_html__('Enable Posts Image', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_author',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_author'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_author',
    array(
        'label' => esc_html__('Enable Posts Author', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_date',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_date'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_date',
    array(
        'label' => esc_html__('Enable Posts Date', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_category',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_category'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_category',
    array(
        'label' => esc_html__('Enable Posts Category', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_post_tags',
    array(
        'default' => $grocery_shopping_store_default['grocery_shopping_store_post_tags'],
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
    )
);
$wp_customize->add_control('grocery_shopping_store_post_tags',
    array(
        'label' => esc_html__('Enable Posts Tags', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_posts_settings',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting('grocery_shopping_store_excerpt_limit',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_excerpt_limit'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_excerpt_limit',
    array(
        'label'       => esc_html__('Blog Post Excerpt limit', 'grocery-shopping-store'),
        'section'     => 'grocery_shopping_store_posts_settings',
        'type'        => 'number',
        'input_attrs' => array(
           'min'   => 1,
           'max'   => 45,
           'step'   => 1,
        ),
    )
);