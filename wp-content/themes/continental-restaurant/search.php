<?php
/**
 * The template for displaying search results pages
 *
 * @package Continental Restaurant
 */

get_header();
?>

<div class="container">
	<div class="main-wrapper">
		<main id="primary" class="site-main ct-post-wrapper lay-width">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'continental-restaurant' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header><!-- .page-header -->

				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					get_template_part( 'revolution/template-parts/content', 'search' );

				endwhile;

				the_posts_navigation();

			else :

				get_template_part( 'revolution/template-parts/content', 'none' );

			endif;
			?>
		</main>
		<?php
		get_sidebar();	?>
	</div>
</div>
<?php

get_footer();