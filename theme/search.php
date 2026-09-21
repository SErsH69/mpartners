<?php
/**
 * Search results template.
 *
 * @package WebulaStarter
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="site-shell site-content">
		<header class="entry-card">
			<h1 class="entry-card__title">
				<?php
				printf(
					esc_html__( 'Search results for: %s', 'webula-starter' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
					<h2 class="entry-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="entry-card__content">
						<?php the_excerpt(); ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>
		<?php else : ?>
			<section class="entry-card">
				<p><?php esc_html_e( 'Nothing matched your search.', 'webula-starter' ); ?></p>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
