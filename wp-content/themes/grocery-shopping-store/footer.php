<?php
/**
 * The template for displaying the footer
 * @package Grocery Shopping Store
 * @since 1.0.0
 */

/**
 * Toogle Contents
 * @hooked grocery_shopping_store_content_offcanvas - 30
*/

do_action('grocery_shopping_store_before_footer_content_action'); ?>

</div>

<footer id="site-footer" role="contentinfo">

    <?php
    /**
     * Footer Content
     * @hooked grocery_shopping_store_footer_content_widget - 10
     * @hooked grocery_shopping_store_footer_content_info - 20
    */

    do_action('grocery_shopping_store_footer_content_action'); ?>

</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
