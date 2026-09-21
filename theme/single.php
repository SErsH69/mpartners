<?php
/**
 * Single template.
 *
 * @package WebulaStarter
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="site-shell site-content">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
				<header class="entry-card__header">
					<h1 class="entry-card__title"><?php the_title(); ?></h1>
					<div class="entry-card__meta">
						<?php webula_posted_on(); ?>
						<?php webula_posted_by(); ?>
					</div>
				</header>

				<div class="entry-card__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php
get_footer();
