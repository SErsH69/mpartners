<?php
/**
 * Лендинг, секция 12: рейтинги и партнёры.
 *
 * @package MPartners
 */

$mp_ratings = mp_landing( 'ratings' );
$mp_logos   = (array) $mp_ratings['logos'];
?>
<section class="ld-ratings">
	<div class="shell">
		<div class="ld-ratings__panel">
			<img class="ld-ratings__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="ld-ratings__copy">
				<div class="ld-ratings__plate">
					<p class="ld-ratings__title"><?php echo esc_html( $mp_ratings['title'] ); ?></p>
					<a class="ld-ratings__link" href="<?php echo esc_url( mp_link( isset( $mp_ratings['href'] ) ? $mp_ratings['href'] : '' ) ); ?>">
						<?php mp_icon( 'video' ); ?>
						<span><?php echo esc_html( $mp_ratings['link'] ); ?></span>
					</a>
				</div>

				<p class="ld-ratings__label"><?php echo esc_html( $mp_ratings['label'] ); ?></p>
			</div>

			<div class="logo-marquee">
		<ul class="ld-ratings__logos is-marquee">
				<?php foreach ( array_merge( $mp_logos, $mp_logos ) as $mp_logo ) : ?>
					<li class="ld-logo">
						<img src="<?php echo esc_url( mp_img( $mp_logo ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
					</li>
				<?php endforeach; ?>
			</ul>
			</div>
		</div>
	</div>
</section>
