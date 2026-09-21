<?php
/**
 * 01–02. Главный экран + фон.
 *
 * @package MPartners
 */

$mp_hero = mp_data( 'hero' );
$mp_note = mp_data( 'advantages.note' );
?>
<section class="hero">
	<div class="hero__backdrop" aria-hidden="true">
		<img class="hero__pattern" src="<?php echo esc_url( mp_img( 'hero-bg' ) ); ?>" alt="" width="3062" height="1119" fetchpriority="high" decoding="async">
		<span class="hero__fade"></span>
		<span class="hero__dots">
			<i class="hero__dot hero__dot--1"></i>
			<i class="hero__dot hero__dot--2"></i>
			<i class="hero__dot hero__dot--3"></i>
			<i class="hero__dot hero__dot--4"></i>
		</span>
	</div>

	<div class="hero__inner">
		<div class="hero__content">
			<h1 class="h-hero hero__title"><?php echo esc_html( $mp_hero['title'] ); ?></h1>
			<p class="hero__subtitle"><?php echo esc_html( $mp_hero['subtitle'] ); ?></p>
		</div>

		<div class="btn-pair hero__actions">
			<a class="btn" href="<?php echo esc_url( $mp_hero['cta']['href'] ); ?>"><?php echo esc_html( $mp_hero['cta']['label'] ); ?></a>
			<a class="btn-icon" href="<?php echo esc_url( $mp_hero['cta']['href'] ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
		</div>

		<div class="note note--shield hero__note">
			<span class="note__icon"><?php mp_icon( 'shield' ); ?></span>
			<p class="note__text"><?php echo esc_html( $mp_note ); ?></p>
		</div>
	</div>
</section>
