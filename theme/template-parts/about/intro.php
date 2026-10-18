<?php
/**
 * О компании: тёмная полоса — о коллегии, фотолента, рейтинги и партнёры.
 *
 * @package MPartners
 */

$mp_intro = mp_about( 'intro' );
?>
<section class="ab-intro">
	<div class="shell">
		<div class="ab-intro__head">
			<h2 class="ab-intro__title"><?php echo esc_html( $mp_intro['title'] ); ?></h2>
			<p class="ab-intro__text"><?php echo esc_html( $mp_intro['text'] ); ?></p>
		</div>
	</div>

	<ul class="ab-intro__photos" data-drag-scroll>
		<?php foreach ( (array) $mp_intro['photos'] as $mp_photo ) : ?>
			<li class="ab-intro__photo">
				<img src="<?php echo esc_url( mp_img( $mp_photo, 'jpg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="shell">
		<div class="ab-intro__plates">
			<div class="ab-intro__plate">
				<p class="ab-intro__rating"><?php echo esc_html( $mp_intro['rating'] ); ?></p>
				<a class="ab-intro__link" href="<?php echo esc_url( mp_link( isset( $mp_intro['href'] ) ? $mp_intro['href'] : '' ) ); ?>">
					<span><?php echo esc_html( $mp_intro['link'] ); ?></span>
					<?php mp_icon( 'arrow-ne' ); ?>
				</a>
			</div>

			<p class="ab-intro__label"><?php echo esc_html( $mp_intro['label'] ); ?></p>
		</div>
	</div>

	<div class="logo-marquee">
		<ul class="ab-intro__logos is-marquee">
		<?php foreach ( array_merge( (array) $mp_intro['logos'], (array) $mp_intro['logos'] ) as $mp_logo ) : ?>
			<li class="ld-logo">
				<img src="<?php echo esc_url( mp_img( $mp_logo ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</section>
