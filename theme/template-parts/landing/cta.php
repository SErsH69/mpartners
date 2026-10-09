<?php
/**
 * Хаб услуг: блок записи на консультацию.
 *
 * @package MPartners
 */

$mp_cta = mp_landings( 'cta' );
?>
<section class="hub-cta">
	<div class="shell">
		<div class="hub-cta__panel">
			<img class="hub-cta__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="hub-cta__copy">
				<h2 class="hub-cta__title"><?php echo esc_html( $mp_cta['title'] ); ?></h2>
				<p class="hub-cta__text"><?php echo esc_html( $mp_cta['text'] ); ?></p>
			</div>

			<a class="btn btn--light hub-cta__button" href="#form"><?php echo esc_html( $mp_cta['label'] ); ?></a>
		</div>
	</div>
</section>
