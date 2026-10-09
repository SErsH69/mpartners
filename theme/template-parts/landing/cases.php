<?php
/**
 * Лендинг, секция 02: «В каких случаях нужен адвокат».
 *
 * @package MPartners
 */

$mp_cases = mp_landing( 'cases' );
?>
<section class="ld-cases">
	<div class="shell">
		<div class="ld-cases__head">
			<h2 class="ld-cases__title"><?php echo esc_html( $mp_cases['title'] ); ?></h2>

			<div class="note note--dark ld-cases__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php echo esc_html( $mp_cases['note'] ); ?></p>
			</div>
		</div>
	</div>

	<ul class="ld-cases__track" data-drag-scroll>
		<?php foreach ( (array) $mp_cases['items'] as $mp_index => $mp_item ) : ?>
			<li class="ld-case">
				<img class="ld-case__art" src="<?php echo esc_url( mp_img( 'case-art-' . ( $mp_index + 1 ), 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
				<span class="ld-case__number"><?php echo esc_html( sprintf( '%02d', $mp_index + 1 ) ); ?></span>
				<h3 class="ld-case__title"><?php echo esc_html( $mp_item['title'] ); ?></h3>
				<p class="ld-case__text"><?php echo esc_html( $mp_item['text'] ); ?></p>
				<span class="ld-case__mark" aria-hidden="true"><?php mp_icon( 'arrow-right' ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
