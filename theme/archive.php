<?php
/**
 * Archive template.
 *
 * @package WebulaStarter
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="site-shell site-content">
		<header class="entry-card">
			<h1 class="entry-card__title"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="entry-card__content">', '</div>' ); ?>
		</header>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
				<h2 class="entry-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-card__meta">
					<?php webula_posted_on(); ?>
					<?php webula_posted_by(); ?>
				</div>
				<div class="entry-card__content">
					<?php the_excerpt(); ?>
				</div>
			</article>
		<?php endwhile; ?>

		<?php the_posts_navigation(); ?>
	</div>
</main>
<?php
get_footer();
