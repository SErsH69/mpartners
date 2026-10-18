<?php
/**
 * Партнерство: с кем сотрудничает коллегия.
 *
 * @package MPartners
 */

$mp_partners = mp_partners( 'partners' );
$mp_items    = isset( $mp_partners['items'] ) ? (array) $mp_partners['items'] : [];

if ( ! $mp_items ) {
	return;
}
?>
<section class="pn-list">
	<div class="shell">
		<div class="pn-list__head">
			<h2 class="h-section pn-list__title"><?php echo esc_html( $mp_partners['title'] ); ?></h2>

			<?php if ( ! empty( $mp_partners['note'] ) ) : ?>
				<div class="note pn-list__note">
					<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
					<p class="note__text"><?php echo esc_html( $mp_partners['note'] ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<ul class="pn-list__grid">
			<?php
			foreach ( $mp_items as $mp_item ) :
				$mp_href = isset( $mp_item['href'] ) ? $mp_item['href'] : '';
				$mp_tag  = '' !== $mp_href ? 'a' : 'div';
				?>
				<li class="pn-partner">
					<<?php echo esc_attr( $mp_tag ); ?> class="pn-partner__body"<?php echo '' !== $mp_href ? ' href="' . esc_url( $mp_href ) . '" target="_blank" rel="noopener noreferrer"' : ''; ?>>
						<p class="pn-partner__name"><?php echo esc_html( $mp_item['name'] ); ?></p>
						<?php if ( ! empty( $mp_item['text'] ) ) : ?>
							<p class="pn-partner__text"><?php echo esc_html( $mp_item['text'] ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== $mp_href ) : ?>
							<span class="pn-partner__link">Перейти на сайт <?php mp_icon( 'arrow-ne' ); ?></span>
						<?php endif; ?>
					</<?php echo esc_attr( $mp_tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
