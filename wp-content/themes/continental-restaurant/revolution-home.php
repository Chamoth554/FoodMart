<?php
/**
 * Template Name: Home Page
 */

get_header();
?>

<main id="primary">

    <?php 
    $continental_restaurant_main_slider_wrap = absint(get_theme_mod('continental_restaurant_enable_slider', 1));
    if($continental_restaurant_main_slider_wrap == 1): 
    ?>
    <section id="main-slider-wrap">
        <div class="owl-carousel">
            <?php for ($continental_restaurant_main_i=1; $continental_restaurant_main_i <= 3; $continental_restaurant_main_i++): ?>
                <?php if ($continental_restaurant_slider_image = get_theme_mod('continental_restaurant_slider_image'.$continental_restaurant_main_i)): ?>
                    <div class="main-slider-inner-box">
                        <img src="<?php echo esc_url($continental_restaurant_slider_image); ?>" alt="<?php echo esc_attr( get_theme_mod('continental_restaurant_slider_heading'.$continental_restaurant_main_i) ); ?>">
                        <div class="main-slider-content-box">
                            <?php if ($continental_restaurant_top_text = get_theme_mod('continental_restaurant_slider_top_text'.$continental_restaurant_main_i)): ?>
                                <p class="slider-top"><?php echo esc_html($continental_restaurant_top_text); ?></p>
                            <?php endif; ?>
                            <?php if ($continental_restaurant_heading = get_theme_mod('continental_restaurant_slider_heading' . $continental_restaurant_main_i)): 
                                // Split the heading into an array of words
                                $continental_restaurant_heading_words = explode(' ', $continental_restaurant_heading);

                                // Remove the last word from the array and store it separately
                                $continental_restaurant_last_word = array_pop($continental_restaurant_heading_words);

                                // Rebuild the heading without the last word
                                $heading_without_last_word = implode(' ', $continental_restaurant_heading_words);
                            ?>
                                <h1>
                                    <?php echo esc_html($heading_without_last_word); ?>
                                        <span class="highlight-last-word"><?php echo esc_html($continental_restaurant_last_word); ?></span>
                                </h1>
                            <?php endif; ?>

                            <?php if ($continental_restaurant_text = get_theme_mod('continental_restaurant_slider_text'.$continental_restaurant_main_i)): ?>
                                <p><?php echo esc_html($continental_restaurant_text); ?></p>
                            <?php endif; ?>
                            <div class="main-slider-button">
                                <?php if ($continental_restaurant_button_link = get_theme_mod('continental_restaurant_slider_button1_link'.$continental_restaurant_main_i) && $continental_restaurant_button_text = get_theme_mod('continental_restaurant_slider_button1_text'.$continental_restaurant_main_i)): ?>
                                    <a href="<?php echo esc_url($continental_restaurant_button_link); ?>"><?php echo esc_html($continental_restaurant_button_text); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php 
    $continental_restaurant_main_expert_wrap = absint(get_theme_mod('continental_restaurant_enable_new_arrivals', 0));
    if ($continental_restaurant_main_expert_wrap == 1): 
    ?>
    <section id="main-expert-wrap">
        <div class="container">
            <div class="heading-expert-wrap">
                <?php if ($continental_restaurant_new_arrivals_top_heading = get_theme_mod('continental_restaurant_new_arrivals_top_heading')): ?>
                    <p class="small-title"><?php echo esc_html($continental_restaurant_new_arrivals_top_heading); ?></p>
                <?php endif; ?>
                <?php if ($continental_restaurant_new_arrivals_heading = get_theme_mod('continental_restaurant_new_arrivals_heading')): ?>
                    <h2><?php echo esc_html($continental_restaurant_new_arrivals_heading); ?></h2>
                <?php endif; ?>
            </div>
            <div class="flex-row">
                <?php if (class_exists('WooCommerce')): ?>
                    <?php
                    $args = array( 
                        'post_type' => 'product',
                        'product_cat' => get_theme_mod('continental_restaurant_product_category'),
                        'order' => 'ASC',
                        'posts_per_page' => 50
                    );
                    $loop = new WP_Query($args);
                    if ($loop->have_posts()):
                        while ($loop->have_posts()): $loop->the_post();
                            global $product;
                    ?>
                            <div class="product-box">  
                                <div class="product-box-content">
                                    <div class="product-image">
                                        <?php
                                        if (has_post_thumbnail()) {
                                            echo get_the_post_thumbnail(get_the_ID(), 'shop_catalog');
                                        } else {
                                            echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Placeholder', 'continental-restaurant') . '" />';
                                        }
                                        ?>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-heading-text"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                    <div class="product-buttons">
                                        <a href="<?php echo esc_url( add_query_arg( 'add-to-cart', get_the_ID(), wc_get_cart_url() ) ); ?>" class="buy-now-button">
                                            <?php echo esc_html__('Buy Now', 'continental-restaurant'); ?>
                                        </a>

                                        <a href="<?php the_permalink(); ?>" class="read-more-button">
                                            <?php echo esc_html__('Read More', 'continental-restaurant'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div> 
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php
get_footer();
?>