<?php
/**
* Footer Settings.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$wp_customize->add_section( 'grocery_shopping_store_footer_widget_area',
	array(
	'title'      => esc_html__( 'Footer Settings', 'grocery-shopping-store' ),
	'priority'   => 200,
	'capability' => 'edit_theme_options',
	'panel'      => 'grocery_shopping_store_theme_option_panel',
	)
);

$wp_customize->add_setting('grocery_shopping_store_display_footer',
    array(
	    'default' => $grocery_shopping_store_default['grocery_shopping_store_display_footer'],
	    'capability' => 'edit_theme_options',
	    'sanitize_callback' => 'grocery_shopping_store_sanitize_checkbox',
	)
);
$wp_customize->add_control('grocery_shopping_store_display_footer',
    array(
        'label' => esc_html__('Enable Footer', 'grocery-shopping-store'),
        'section' => 'grocery_shopping_store_footer_widget_area',
        'type' => 'checkbox',
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_footer_column_layout',
	array(
	'default'           => $grocery_shopping_store_default['grocery_shopping_store_footer_column_layout'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'grocery_shopping_store_sanitize_select',
	)
);
$wp_customize->add_control( 'grocery_shopping_store_footer_column_layout',
	array(
	'label'       => esc_html__( 'Footer Column Layout', 'grocery-shopping-store' ),
	'section'     => 'grocery_shopping_store_footer_widget_area',
	'type'        => 'select',
	'choices'               => array(
		'1' => esc_html__( 'One Column', 'grocery-shopping-store' ),
		'2' => esc_html__( 'Two Column', 'grocery-shopping-store' ),
		'3' => esc_html__( 'Three Column', 'grocery-shopping-store' ),
	    ),
	)
);

$wp_customize->add_setting( 'grocery_shopping_store_footer_widget_title_alignment', 
	array(
	    'default'           => $grocery_shopping_store_default['grocery_shopping_store_footer_widget_title_alignment'],
	    'capability'        => 'edit_theme_options',
	    'sanitize_callback' => 'grocery_shopping_store_sanitize_footer_widget_title_alignment',
    )
);
$wp_customize->add_control( 'grocery_shopping_store_footer_widget_title_alignment',
    array(
	    'label'       => esc_html__( 'Footer Widget Title Alignment', 'grocery-shopping-store' ),
	    'section'     => 'grocery_shopping_store_footer_widget_area',
	    'type'        => 'select',
	    'choices'     => array(
	        'left' => esc_html__( 'Left', 'grocery-shopping-store' ),
	        'center'  => esc_html__( 'Center', 'grocery-shopping-store' ),
	        'right'    => esc_html__( 'Right', 'grocery-shopping-store' ),
	        ),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_footer_copyright_text',
	array(
	'default'           => $grocery_shopping_store_default['grocery_shopping_store_footer_copyright_text'],
	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control( 'grocery_shopping_store_footer_copyright_text',
	array(
	'label'    => esc_html__( 'Footer Copyright Text', 'grocery-shopping-store' ),
	'section'  => 'grocery_shopping_store_footer_widget_area',
	'type'     => 'text',
	)
);

$wp_customize->add_setting('grocery_shopping_store_copyright_font_size',
    array(
        'default'           => $grocery_shopping_store_default['grocery_shopping_store_copyright_font_size'],
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'grocery_shopping_store_sanitize_number_range',
    )
);
$wp_customize->add_control('grocery_shopping_store_copyright_font_size',
    array(
        'label'       => esc_html__('Copyright Font Size', 'grocery-shopping-store'),
        'section'     => 'grocery_shopping_store_footer_widget_area',
        'type'        => 'number',
        'input_attrs' => array(
           'min'   => 5,
           'max'   => 30,
           'step'   => 1,
    	),
    )
);

$wp_customize->add_setting( 'grocery_shopping_store_footer_widget_background_color', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_hex_color'
));
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'grocery_shopping_store_footer_widget_background_color', array(
    'label'     => __('Footer Widget Background Color', 'grocery-shopping-store'),
    'description' => __('It will change the complete footer widget background color.', 'grocery-shopping-store'),
    'section' => 'grocery_shopping_store_footer_widget_area',
    'settings' => 'grocery_shopping_store_footer_widget_background_color',
)));

$wp_customize->add_setting('footer_widget_background_image',array(
    'default'   => '',
    'sanitize_callback' => 'esc_url_raw',
));
$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'footer_widget_background_image',array(
    'label' => __('Footer Widget Background Image','grocery-shopping-store'),
    'section' => 'grocery_shopping_store_footer_widget_area'
)));
