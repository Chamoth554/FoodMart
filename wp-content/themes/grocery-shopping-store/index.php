<?php
/**
 * The main template file
 * @package Grocery Shopping Store
 * @since 1.0.0
 */

get_header();
$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
$grocery_shopping_store_global_sidebar_layout = esc_html( get_theme_mod( 'grocery_shopping_store_global_sidebar_layout',$grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout'] ) );
$grocery_shopping_store_sidebar_column_class = 'column-order-2';
if ($grocery_shopping_store_global_sidebar_layout == 'right-sidebar') {
    $grocery_shopping_store_sidebar_column_class = 'column-order-1';
}

global $grocery_shopping_store_archive_first_class; ?>
    <div class="archive-main-block">
        <div class="wrapper">
            <div class="column-row">

                <div id="primary" class="content-area <?php echo esc_attr($grocery_shopping_store_sidebar_column_class) ; ?>">
                    <main id="site-content" role="main">

                        <?php

                        if( !is_front_page() ) {
                            grocery_shopping_store_breadcrumb();
                        }

                        if( have_posts() ): ?>

                            <div class="article-wraper article-wraper-archive">
                                <?php
                                while( have_posts() ):
                                    the_post();

                                    get_template_part( 'template-parts/content', get_post_format() );

                                endwhile; ?>
                            </div>

                            <?php
                            if( is_search() ){
                                the_posts_pagination();
                            }else{
                                do_action('grocery_shopping_store_archive_pagination');
                            }

                        else :

                            get_template_part('template-parts/content', 'none');

                        endif; ?>
                    </main>
                </div>
                <?php get_sidebar();?>
            </div>
        </div>
    </div>
<?php
get_footer();
