<?php
/**
* Widget Functions.
*
* @package Grocery Shopping Store
*/


function grocery_shopping_store_widgets_init(){

	register_sidebar(array(
	    'name' => esc_html__('Main Sidebar', 'grocery-shopping-store'),
	    'id' => 'sidebar-1',
	    'description' => esc_html__('Add widgets here.', 'grocery-shopping-store'),
	    'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    'after_widget' => '</div>',
	    'before_title' => '<h3 class="widget-title"><span>',
	    'after_title' => '</span></h3>',
	));


    $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
    $grocery_shopping_store_grocery_shopping_store_footer_column_layout = absint( get_theme_mod( 'grocery_shopping_store_footer_column_layout',$grocery_shopping_store_default['grocery_shopping_store_footer_column_layout'] ) );

    for( $i = 0; $i < $grocery_shopping_store_grocery_shopping_store_footer_column_layout; $i++ ){
    	
    	if( $i == 0 ){ $count = esc_html__('One','grocery-shopping-store'); }
    	if( $i == 1 ){ $count = esc_html__('Two','grocery-shopping-store'); }
    	if( $i == 2 ){ $count = esc_html__('Three','grocery-shopping-store'); }

	    register_sidebar( array(
	        'name' => esc_html__('Footer Widget ', 'grocery-shopping-store').$count,
	        'id' => 'grocery-shopping-store-footer-widget-'.$i,
	        'description' => esc_html__('Add widgets here.', 'grocery-shopping-store'),
	        'before_widget' => '<div id="%1$s" class="widget %2$s">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="widget-title">',
	        'after_title' => '</h2>',
	    ));
	}

}

add_action('widgets_init', 'grocery_shopping_store_widgets_init');