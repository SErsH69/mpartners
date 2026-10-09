<?php
/**
 * Хаб услуг: первый экран с формой и строкой преимуществ.
 *
 * @package MPartners
 */

$mp_hero = mp_landings( 'hero' );
?>
<section class="hub-hero">
	<div class="shell">
		<div class="hub-hero__panel">
			<img class="hub-hero__decor" src="<?php echo esc_url( mp_img( 'decor-quiz-glow1', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="hub-hero__copy">
				<h1 class="hub-hero__title"><?php echo esc_html( $mp_hero['title'] ); ?></h1>

				<div class="hub-hero__note">
					<span class="hub-hero__note-icon"><?php mp_icon( 'shield' ); ?></span>
					<p class="hub-hero__note-text"><?php mp_nl2br( $mp_hero['guarantee'] ); ?></p>
				</div>
			</div>

			<div class="hub-form">
				<p class="hub-form__lead"><?php echo esc_html( $mp_hero['form_lead'] ); ?></p>
				<?php mp_cf7( 'landing', 'hub-form__body' ); ?>
			</div>
		</div>

		<ul class="hub-adv">
			<?php foreach ( (array) $mp_hero['advantages'] as $mp_item ) : ?>
				<li class="hub-adv__item">
					<span class="hub-adv__icon"><?php mp_icon( 'shield' ); ?></span>
					<span class="hub-adv__text"><?php echo esc_html( $mp_item ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
