<?php

$grocery_shopping_store_custom_css = "";

$grocery_shopping_store_theme_pagination_options_alignment = get_theme_mod('grocery_shopping_store_theme_pagination_options_alignment', 'Center');
	if ($grocery_shopping_store_theme_pagination_options_alignment == 'Center') {
	    $grocery_shopping_store_custom_css .= '.pagination{';
	    $grocery_shopping_store_custom_css .= 'text-align: center;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_pagination_options_alignment == 'Right') {
	    $grocery_shopping_store_custom_css .= '.pagination{';
	    $grocery_shopping_store_custom_css .= 'text-align: Right;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_pagination_options_alignment == 'Left') {
	    $grocery_shopping_store_custom_css .= '.pagination{';
	    $grocery_shopping_store_custom_css .= 'text-align: Left;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	 $grocery_shopping_store_theme_breadcrumb_options_alignment = get_theme_mod('grocery_shopping_store_theme_breadcrumb_options_alignment', 'Left');
	if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Center') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Right') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul{';
	    $grocery_shopping_store_custom_css .= 'text-align: Right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Left') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul{';
	    $grocery_shopping_store_custom_css .= 'text-align: Left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_menu_text_transform = get_theme_mod('grocery_shopping_store_menu_text_transform', 'Capitalize');
	if ($grocery_shopping_store_menu_text_transform == 'Capitalize') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: Capitalize !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_menu_text_transform == 'uppercase') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: uppercase !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_menu_text_transform == 'lowercase') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: lowercase !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_single_page_content_alignment = get_theme_mod('grocery_shopping_store_single_page_content_alignment', 'left');
	if ($grocery_shopping_store_single_page_content_alignment == 'left') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page{';
	    $grocery_shopping_store_custom_css .= 'text-align: left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_page_content_alignment == 'center') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_page_content_alignment == 'right') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page{';
	    $grocery_shopping_store_custom_css .= 'text-align: right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_footer_widget_title_alignment = get_theme_mod('grocery_shopping_store_footer_widget_title_alignment', 'left');
	if ($grocery_shopping_store_footer_widget_title_alignment == 'left') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_footer_widget_title_alignment == 'center') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_footer_widget_title_alignment == 'right') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

    $grocery_shopping_store_show_hide_related_product = get_theme_mod('grocery_shopping_store_show_hide_related_product',true);
    if($grocery_shopping_store_show_hide_related_product != true){
        $grocery_shopping_store_custom_css .='.related.products{';
            $grocery_shopping_store_custom_css .='display: none;';
        $grocery_shopping_store_custom_css .='}';
    }