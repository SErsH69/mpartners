<?php
/**
 * Лендинг, секция 01: первый экран.
 *
 * @package MPartners
 */

$mp_hero = mp_landing( 'hero' );
$mp_cover = get_the_post_thumbnail_url( get_the_ID(), 'full' );
?>
<section class="ld-hero">
	<div class="ld-hero__backdrop" aria-hidden="true">
		<img class="ld-hero__image" src="<?php echo esc_url( $mp_cover ? $mp_cover : mp_img( 'landing-hero' ) ); ?>" alt="" decoding="async">
		<span class="ld-hero__veil"></span>
	</div>

	<div class="shell">
		<div class="ld-hero__main">
			<div class="ld-hero__copy">
				<h1 class="ld-hero__title"><?php the_title(); ?></h1>
				<p class="ld-hero__text"><?php echo esc_html( $mp_hero['text'] ); ?></p>
			</div>

			<div class="btn-pair ld-hero__actions">
				<a class="btn" href="#form"><?php echo esc_html( $mp_hero['cta'] ); ?></a>
				<a class="btn-icon" href="#form" aria-hidden="true" tabindex="-1"><?php mp_icon( 'arrow-ne' ); ?></a>
			</div>
		</div>

		<ul class="ld-bullets">
			<?php foreach ( (array) $mp_hero['bullets'] as $mp_bullet ) : ?>
				<li class="ld-bullet">
					<span class="ld-bullet__icon"><?php mp_icon( 'shield' ); ?></span>
					<span class="ld-bullet__text"><?php echo esc_html( $mp_bullet ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
