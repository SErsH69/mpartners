<?php
/**
 * Лендинг, секция 14: предварительный анализ ситуации.
 *
 * @package MPartners
 */

$mp_analysis = mp_landing( 'analysis' );
?>
<section class="ld-analysis">
	<div class="shell">
		<div class="ld-analysis__panel">
			<img class="ld-analysis__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="ld-analysis__copy">
				<h2 class="ld-analysis__title"><?php echo esc_html( $mp_analysis['title'] ); ?></h2>
				<p class="ld-analysis__text"><?php echo esc_html( $mp_analysis['text'] ); ?></p>
			</div>

			<p class="ld-analysis__note"><?php echo esc_html( $mp_analysis['note'] ); ?></p>
			<a class="btn btn--light ld-analysis__button" href="#form"><?php echo esc_html( $mp_analysis['label'] ); ?></a>
		</div>
	</div>
</section>
