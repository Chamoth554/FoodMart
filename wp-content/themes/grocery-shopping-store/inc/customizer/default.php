<?php
/**
 * Default Values.
 *
 * @package Grocery Shopping Store
 */

if ( ! function_exists( 'grocery_shopping_store_get_default_theme_options' ) ) :
	function grocery_shopping_store_get_default_theme_options() {

		$grocery_shopping_store_defaults = array();

        // Header
        $grocery_shopping_store_defaults['grocery_shopping_store_header_layout_button']          =  esc_html__( 'Buy Now', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_header_layout_button_url']      =  esc_url( '#', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_header_search']                 = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_loader']                  = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_footer_column_layout']          = 3;
        $grocery_shopping_store_defaults['grocery_shopping_store_menu_font_size']                 = 14;
        $grocery_shopping_store_defaults['grocery_shopping_store_copyright_font_size']                 = 16;
        $grocery_shopping_store_defaults['grocery_shopping_store_breadcrumb_font_size']                 = 16;
        $grocery_shopping_store_defaults['grocery_shopping_store_excerpt_limit']                 = 10;
        $grocery_shopping_store_defaults['grocery_shopping_store_menu_text_transform']                 = 'capitalize';  
        $grocery_shopping_store_defaults['grocery_shopping_store_single_page_content_alignment']                 = 'left';
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_pagination_options_alignment']                 = 'Center'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_breadcrumb_options_alignment']                 = 'Left'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_per_columns']                 = 3;  
        $grocery_shopping_store_defaults['grocery_shopping_store_product_per_page']                 = 9;

        //Slider 

        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_small_title']    =  esc_html__( 'Taste Redefined', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_sub_title']      =  esc_html__( 'Where Every Flavor Tells A Story', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_content']        =  esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, industry\'s standard dummy text ever since the 1500s,', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button_url']     =  esc_url( '#', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button']         =  esc_html__( 'Learn More', 'grocery-shopping-store' );

	// Options.
        $grocery_shopping_store_defaults['grocery_shopping_store_logo_width_range']                 = 300;
        
        $grocery_shopping_store_defaults['grocery_shopping_store_global_sidebar_layout']	        = 'right-sidebar';
        
        $grocery_shopping_store_defaults['grocery_shopping_store_pagination_layout']                = 'numeric';
	$grocery_shopping_store_defaults['grocery_shopping_store_footer_column_layout'] 	        = 2;
	$grocery_shopping_store_defaults['grocery_shopping_store_footer_copyright_text'] 	        = esc_html__( 'All rights reserved.', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_twp_navigation_type']              = 'theme-normal-navigation';
        $grocery_shopping_store_defaults['grocery_shopping_store_post_author']                      = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_date']                        = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_category']                	= 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_tags']                        = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_floating_next_previous_nav']       = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_header_slider']                    = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_category_section']                 = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_courses_category_section']         = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_footer']            = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_footer_widget_title_alignment']                 = 'left'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_show_hide_related_product']          = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_image']            = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_background_color']                 = '#fff';

        //Product
        
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section']                   = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_title']            = esc_html__( 'Featured Products', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button']            = esc_html__( 'View All', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button_url']            = esc_url( '#', 'grocery-shopping-store' );



	// Pass through filter.
	$grocery_shopping_store_defaults = apply_filters( 'grocery_shopping_store_filter_default_theme_options', $grocery_shopping_store_defaults );

		return $grocery_shopping_store_defaults;
	}
endif;
