<?php
/**
 * 10. Рейтинги и признание — дипломы и ссылки на рейтинги.
 *
 * @package MPartners
 */

$mp_reviews = mp_data( 'reviews' );
$mp_items   = (array) $mp_reviews['items'];

if ( ! $mp_items ) {
	return;
}
?>
<section class="reviews">
	<div class="reviews__head">
		<h2 class="h-section h-section--light reviews__title"><?php echo esc_html( $mp_reviews['title'] ); ?></h2>
		<?php if ( ! empty( $mp_reviews['note'] ) ) : ?>
			<div class="note note--dark reviews__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php echo esc_html( $mp_reviews['note'] ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<ul class="reviews__track" data-drag-scroll data-dots="reviews-dots" data-gallery>
		<?php
		foreach ( $mp_items as $mp_item ) :
			// Диплом есть не у каждого рейтинга: без скана карточка текстовая.
			$mp_scan = mp_img( isset( $mp_item['image'] ) ? $mp_item['image'] : '' );
			$mp_href = isset( $mp_item['href'] ) ? $mp_item['href'] : '';
			$mp_label = ! empty( $mp_item['label'] ) ? $mp_item['label'] : 'Смотреть рейтинг';
			?>
			<li class="rating<?php echo $mp_scan ? '' : ' rating--plain'; ?>">
				<?php if ( $mp_scan ) : ?>
					<a class="rating__link" data-lg-item href="<?php echo esc_url( $mp_scan ); ?>" data-sub-html="<?php echo esc_attr( $mp_item['source'] . ' — ' . $mp_item['title'] ); ?>" aria-label="Открыть диплом">
						<img class="rating__scan" src="<?php echo esc_url( $mp_scan ); ?>" alt="<?php echo esc_attr( 'Диплом ' . $mp_item['source'] ); ?>" loading="lazy" decoding="async">
					</a>
				<?php endif; ?>

				<div class="rating__body">
					<p class="rating__source"><?php echo esc_html( $mp_item['source'] ); ?></p>

					<div class="rating__content">
						<p class="rating__award"><?php echo esc_html( $mp_item['title'] ); ?></p>
						<?php if ( ! empty( $mp_item['text'] ) ) : ?>
							<p class="rating__text"><?php echo esc_html( $mp_item['text'] ); ?></p>
						<?php endif; ?>
					</div>

					<div class="rating__foot">
						<span class="rating__year"><?php echo esc_html( $mp_item['year'] ); ?></span>
						<?php if ( '' !== $mp_href ) : ?>
							<a class="rating__more" href="<?php echo esc_url( $mp_href ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( $mp_label ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="dots reviews__dots" id="reviews-dots" aria-hidden="true">
		<?php foreach ( $mp_items as $mp_index => $mp_item ) : ?>
			<span class="dots__dot<?php echo 0 === $mp_index ? ' is-active' : ''; ?>"></span>
		<?php endforeach; ?>
	</div>
</section>
