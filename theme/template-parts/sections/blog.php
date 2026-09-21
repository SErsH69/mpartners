<?php
/**
 * 11. Блог.
 *
 * @package MPartners
 */

$mp_blog = mp_data( 'blog' );
?>
<section class="blog">
	<div class="blog__inner">
		<header class="section-head">
			<h2 class="h-section"><?php echo esc_html( $mp_blog['title'] ); ?></h2>
			<div class="note section-head__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php echo esc_html( $mp_blog['note'] ); ?></p>
			</div>
		</header>

		<ul class="blog__grid">
			<?php foreach ( $mp_blog['items'] as $mp_post ) : ?>
				<?php
				$mp_classes = [ 'post-card', 'post-card--' . $mp_post['theme'] ];

				if ( ! empty( $mp_post['size'] ) ) {
					$mp_classes[] = 'post-card--' . $mp_post['size'];
				}
				?>
				<li class="<?php echo esc_attr( implode( ' ', $mp_classes ) ); ?>">
					<?php if ( 'dark' === $mp_post['theme'] ) : ?>
						<img class="post-card__decor" src="<?php echo esc_url( mp_img( 'decor-blog', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<?php endif; ?>
					<a class="post-card__link" href="#">
						<span class="post-card__category"><?php echo esc_html( $mp_post['category'] ); ?></span>
						<span class="post-card__main">
							<span class="post-card__text">
								<span class="post-card__title"><?php echo esc_html( $mp_post['title'] ); ?></span>
								<span class="post-card__excerpt"><?php mp_nl2br( $mp_post['excerpt'] ); ?></span>
							</span>
							<span class="post-card__date"><?php echo esc_html( $mp_post['date'] ); ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<a class="btn btn--block blog__more" href="<?php echo esc_url( $mp_blog['more']['href'] ); ?>"><?php echo esc_html( $mp_blog['more']['label'] ); ?></a>
	</div>
</section>
