<?php
/**
* Color Settings.
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$wp_customize->add_setting( 'grocery_shopping_store_default_text_color',
    array(
    'default'           => '',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control( 
    new WP_Customize_Color_Control( 
    $wp_customize, 
    'grocery_shopping_store_default_text_color',
    array(
        'label'      => esc_html__( 'Text Color', 'grocery-shopping-store' ),
        'section'    => 'colors',
        'settings'   => 'grocery_shopping_store_default_text_color',
    ) ) 
);

$wp_customize->add_setting( 'grocery_shopping_store_border_color',
    array(
    'default'           => '',
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
    )
);
$wp_customize->add_control( 
    new WP_Customize_Color_Control( 
    $wp_customize, 
    'grocery_shopping_store_border_color',
    array(
        'label'      => esc_html__( 'Border Color', 'grocery-shopping-store' ),
        'section'    => 'colors',
        'settings'   => 'grocery_shopping_store_border_color',
    ) ) 
);