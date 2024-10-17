<?php
/**
 * The template for displaying single posts and pages.
 * @package Grocery Shopping Store
 * @since 1.0.0
 */
get_header();

    $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
    $grocery_shopping_store_global_sidebar_layout = esc_html( get_theme_mod( 'grocery_shopping_store_global_sidebar_layout',$grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'] ) );
    $grocery_shopping_store_post_sidebar = esc_html( get_post_meta( $post->ID, 'grocery_shopping_store_post_sidebar_option', true ) );
    $grocery_shopping_store_sidebar_column_class = 'column-order-1';

    if (!empty($grocery_shopping_store_post_sidebar)) {
        $grocery_shopping_store_global_sidebar_layout = $grocery_shopping_store_post_sidebar;
    }

    if ($grocery_shopping_store_global_sidebar_layout == 'left-sidebar') {
        $grocery_shopping_store_sidebar_column_class = 'column-order-2';
    } ?>

    <div id="single-page" class="singular-main-block">
        <div class="wrapper">
            <div class="column-row">

                <div id="primary" class="content-area <?php echo esc_attr($grocery_shopping_store_sidebar_column_class); ?>">
                    <main id="site-content" class="" role="main">

                        <?php
                            grocery_shopping_store_breadcrumb();

                        if( have_posts() ): ?>

                            <div class="article-wraper">

                                <?php while (have_posts()) :
                                    the_post();

                                    get_template_part('template-parts/content', 'single');

                                    if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && !post_password_required() ) { ?>

                                        <div class="comments-wrapper">
                                            <?php comments_template(); ?>
                                        </div>

                                    <?php
                                    }

                                endwhile; ?>

                            </div>

                        <?php
                        else :

                            get_template_part('template-parts/content', 'none');

                        endif;

                        /**
                         * Navigation
                         * 
                         * @hooked grocery_shopping_store_related_posts - 20  
                         * @hooked grocery_shopping_store_single_post_navigation - 30  
                        */

                        do_action('grocery_shopping_store_navigation_action'); ?>

                    </main>
                </div>
                <?php get_sidebar();?>
            </div>
        </div>
    </div>

<?php
get_footer();
