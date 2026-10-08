<?php
/**
 * Практики: нижний блок с приглашением на консультацию.
 *
 * @package MPartners
 */

$mp_cta = mp_practice( 'cta' );
?>
<section class="pg-cta">
	<div class="shell">
		<div class="pg-cta__panel">
			<img class="pg-cta__decor" src="<?php echo esc_url( mp_img( 'pg-decor-flower', 'svg' ) ); ?>" alt="" aria-hidden="true" width="916" height="916" loading="lazy" decoding="async">

			<div class="pg-cta__inner">
				<div class="pg-cta__copy">
					<p class="pg-cta__title"><?php echo esc_html( $mp_cta['title'] ); ?></p>
					<p class="pg-cta__text"><?php mp_nl2br( $mp_cta['text'] ); ?></p>
				</div>

				<a class="btn btn--light pg-cta__button" href="#form"><?php echo esc_html( $mp_cta['button'] ); ?></a>
			</div>
		</div>
	</div>
</section>
