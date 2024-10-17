<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Continental Restaurant
 */

get_header();
?>

<div class="container">
	<div class="site-wrapper">
		<main id="primary" class="site-main">
			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'continental-restaurant' ); ?></h1>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'continental-restaurant' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</section>
		</main>
	</div>
</div>

<?php
get_footer();