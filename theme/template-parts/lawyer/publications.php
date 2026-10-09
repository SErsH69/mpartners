<?php
/**
 * Адвокат: публикации — те же карточки, что в блоге на главной.
 *
 * @package MPartners
 */

$mp_pubs = mp_lawyer( 'pubs' );
?>
<section class="lw-pubs">
	<div class="shell">
		<h2 class="h-lawyer lw-pubs__title"><?php echo esc_html( $mp_pubs['title'] ); ?></h2>

		<ul class="lw-pubs__grid">
			<?php foreach ( $mp_pubs['items'] as $mp_post ) : ?>
				<li class="post-card post-card--light lw-pub">
					<a class="post-card__link" href="#">
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
