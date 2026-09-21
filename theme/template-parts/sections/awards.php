<?php
/**
 * 08. Награды.
 *
 * @package MPartners
 */

$mp_awards = mp_data( 'awards' );
?>
<section class="awards">
	<div class="awards__inner">
		<h2 class="h-section"><?php echo esc_html( $mp_awards['title'] ); ?></h2>

		<ul class="awards__list">
			<?php foreach ( $mp_awards['items'] as $mp_award ) : ?>
				<li class="award">
					<span class="award__source"><?php echo esc_html( $mp_award['source'] ); ?></span>
					<span class="award__title"><?php echo esc_html( $mp_award['title'] ); ?></span>
					<span class="award__year"><?php echo esc_html( $mp_award['year'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
