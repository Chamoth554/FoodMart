<?php
/**
 * The sidebar containing the main widget area
 * @package Grocery Shopping Store
 */

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

$post_id = get_the_ID(); // Get the post ID

$grocery_shopping_store_post_sidebar = esc_html(get_post_meta($post_id, 'grocery_shopping_store_post_sidebar_option', true));
$grocery_shopping_store_sidebar_column_class = 'column-order-2';

if (empty($grocery_shopping_store_post_sidebar)) {
    $grocery_shopping_store_global_sidebar_layout = esc_html(get_theme_mod('grocery_shopping_store_global_sidebar_layout', $grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout']));
} else {
    $grocery_shopping_store_global_sidebar_layout = $grocery_shopping_store_post_sidebar;
}
if (!is_active_sidebar('sidebar-1') || $grocery_shopping_store_global_sidebar_layout == 'no-sidebar') {
    return;
}

if ($grocery_shopping_store_global_sidebar_layout == 'left-sidebar') {
    $grocery_shopping_store_sidebar_column_class = 'column-order-1';
}
?>

<aside id="secondary" class="widget-area <?php echo esc_attr($grocery_shopping_store_sidebar_column_class); ?>">
    <div class="widget-area-wrapper">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside>
