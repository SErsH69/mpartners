<?php
/**
 * Внутренняя страница: блок «Читайте также».
 *
 * @package MPartners
 */

$mp_related = function_exists( 'mp_recent_cards' )
	? mp_recent_cards( 3, get_the_ID(), (array) mp_lawyer( 'pubs.items', [] ) )
	: (array) mp_lawyer( 'pubs.items', [] );
?>
<section class="lw-pubs art-related">
	<div class="shell">
		<h2 class="h-lawyer lw-pubs__title"><?php echo esc_html( mp_article( 'related' ) ); ?></h2>

		<ul class="lw-pubs__grid">
			<?php foreach ( $mp_related as $mp_post ) : ?>
				<li class="post-card post-card--light lw-pub">
					<a class="post-card__link" href="<?php echo esc_url( isset( $mp_post['href'] ) ? $mp_post['href'] : home_url( '/press/' ) ); ?>">
						<span class="post-card__category"><?php echo esc_html( $mp_post['category'] ); ?></span>
						<span class="post-card__main">
							<span class="post-card__text">
								<span class="post-card__title"><?php echo esc_html( $mp_post['title'] ); ?></span>
								<span class="post-card__excerpt"><?php echo esc_html( $mp_post['excerpt'] ); ?></span>
							</span>
							<span class="post-card__date"><?php echo esc_html( $mp_post['date'] ); ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
