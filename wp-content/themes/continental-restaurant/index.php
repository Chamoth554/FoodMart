<?php
/**
 * The main template file
 *
 * @package Continental Restaurant
 */

get_header();
?>

<div class="container">
	<div class="main-wrapper">
		<main id="primary" class="site-main ct-post-wrapper lay-width">

			<?php
			if ( have_posts() ) :

				if ( is_home() && ! is_front_page() ) :
					?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
					<?php
				endif;

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();
					
					get_template_part( 'revolution/template-parts/content', get_post_type() );

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