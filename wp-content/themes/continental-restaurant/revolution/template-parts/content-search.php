<?php
/**
 * Template part for displaying results in search pages
 *
 * @package Continental Restaurant
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="card-item card-blog-post">
		<header class="entry-header">
			<?php
			if ( 'post' === get_post_type() ) :
				
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
				endif;

				?>
					<div class="entry-meta">
						<?php
						continental_restaurant_posted_on();
						continental_restaurant_posted_by();
						?>
					</div>

			<?php endif; ?>
		</header>
		<?php
			if (has_post_thumbnail()) {
			?>
				<div class="card-media">
					<?php continental_restaurant_post_thumbnail(); ?>
				</div>
			<?php 
			} 
		?>

		<div class="entry-content">
			<?php

				if ( is_singular() ) :
					the_content();
				else :
					echo "<p>".wp_trim_words(get_the_excerpt(), 40)."</p>";
					?>
					<a href="<?php the_permalink(); ?>" class="btn read-btn text-uppercase"> <?php echo esc_html(get_theme_mod('continental_restaurant_read_more_text', __('Continue Reading....', 'continental-restaurant'))); ?> </a>
					<?php
				endif;
				?>


			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'continental-restaurant' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->