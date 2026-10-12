<?php
/**
 * Лендинг, секция 06: «Что получает клиент».
 *
 * @package MPartners
 */

$mp_results = mp_landing( 'results' );
?>
<section class="ld-results">
	<div class="shell">
		<div class="ld-results__panel">
			<img class="ld-results__decor" src="<?php echo esc_url( mp_img( 'decor-quiz-glow1', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<h2 class="ld-results__title"><?php echo esc_html( $mp_results['title'] ); ?></h2>

			<ul class="ld-results__list">
				<?php foreach ( (array) $mp_results['items'] as $mp_index => $mp_item ) : ?>
					<li class="ld-result">
						<span class="ld-result__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $mp_index + 1 ) ); ?></span>
						<p class="ld-result__text"><?php echo esc_html( $mp_item ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
