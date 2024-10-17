<?php
/**
 *
 * Pagination Functions
 *
 * @package Grocery Shopping Store
 */

if( !function_exists('grocery_shopping_store_archive_pagination_x') ):

	// Archive Page Navigation
	function grocery_shopping_store_archive_pagination_x(){

		the_posts_pagination();
	}

endif;
add_action('grocery_shopping_store_archive_pagination','grocery_shopping_store_archive_pagination_x',20);