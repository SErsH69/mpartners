<?php
/**
 * О компании: история в фактах и событиях.
 *
 * @package MPartners
 */

$mp_history = mp_about( 'history' );
?>
<section class="ab-history">
	<div class="shell">
		<div class="ab-history__head">
			<h2 class="ab-history__title"><?php echo esc_html( $mp_history['title'] ); ?></h2>

			<div class="ab-history__note">
				<span class="ab-history__note-icon"><?php mp_icon( 'info' ); ?></span>
				<p class="ab-history__note-text"><?php echo esc_html( $mp_history['note'] ); ?></p>
			</div>
		</div>
	</div>

	<ul class="ab-history__track" data-drag-scroll>
		<?php foreach ( (array) $mp_history['items'] as $mp_item ) : ?>
			<li class="ab-step">
				<span class="ab-step__icon" aria-hidden="true">
					<img src="<?php echo esc_url( mp_img( ! empty( $mp_item['icon'] ) ? $mp_item['icon'] : 'history-1', 'svg' ) ); ?>" alt="" loading="lazy" decoding="async">
				</span>
				<div class="ab-step__body">
					<p class="ab-step__year"><?php echo esc_html( $mp_item['year'] ); ?></p>
					<p class="ab-step__text"><?php echo esc_html( $mp_item['text'] ); ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
