<?php
/**
 * Fallback template.
 *
 * @package WebulaStarter
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="site-shell site-content">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
					<header class="entry-card__header">
						<?php if ( is_singular() ) : ?>
							<h1 class="entry-card__title"><?php the_title(); ?></h1>
						<?php else : ?>
							<h2 class="entry-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php endif; ?>
					</header>

					<div class="entry-card__content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<section class="entry-card">
				<h1 class="entry-card__title"><?php esc_html_e( 'Nothing found', 'webula-starter' ); ?></h1>
				<p><?php esc_html_e( 'The starter theme is ready for your next project.', 'webula-starter' ); ?></p>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
