<?php
/**
 * 08. Награды и рейтинги.
 *
 * @package MPartners
 */

$mp_awards = mp_data( 'awards' );
?>
<section class="awards">
	<div class="awards__inner">
		<h2 class="h-section"><?php echo esc_html( $mp_awards['title'] ); ?></h2>

		<ul class="awards__list">
			<?php
			foreach ( $mp_awards['items'] as $mp_award ) :
				$mp_href = isset( $mp_award['href'] ) ? $mp_award['href'] : '';
				$mp_tag  = '' !== $mp_href ? 'a' : 'div';
				?>
				<li class="awards__item">
					<<?php echo esc_attr( $mp_tag ); ?> class="award<?php echo '' !== $mp_href ? ' award--link' : ''; ?>"<?php echo '' !== $mp_href ? ' href="' . esc_url( $mp_href ) . '" target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<span class="award__source"><?php echo esc_html( $mp_award['source'] ); ?></span>
						<span class="award__title"><?php echo esc_html( $mp_award['title'] ); ?></span>
						<span class="award__year"><?php echo esc_html( $mp_award['year'] ); ?></span>
					</<?php echo esc_attr( $mp_tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
