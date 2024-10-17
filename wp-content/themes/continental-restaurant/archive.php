<?php
/**
 * The template for displaying archive pages
 *
 * @package Continental Restaurant
 */

get_header();
?>
 
<div class="container">
	<div class="main-wrapper">
		<main id="primary" class="site-main ct-post-wrapper lay-width">

			<?php if ( have_posts() ) : ?>

				<?php do_action('continental_restaurant_breadcrumbs'); ?>

				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header>

				<?php
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