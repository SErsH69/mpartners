<?php
/**
 * 10. Отзывы — рекомендательные письма.
 *
 * @package MPartners
 */

$mp_reviews = mp_data( 'reviews' );
?>
<section class="reviews">
	<div class="reviews__head">
		<h2 class="h-section h-section--light reviews__title"><?php echo esc_html( $mp_reviews['title'] ); ?></h2>
		<div class="note note--dark reviews__note">
			<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
			<p class="note__text"><?php echo esc_html( $mp_reviews['note'] ); ?></p>
		</div>
	</div>

	<ul class="reviews__track" data-drag-scroll data-dots="reviews-dots">
		<?php foreach ( $mp_reviews['items'] as $mp_review ) : ?>
			<li class="letter">
				<img class="letter__scan" src="<?php echo esc_url( mp_img( $mp_review['scan'] ) ); ?>" alt="Рекомендательное письмо" loading="lazy" decoding="async">
				<div class="letter__body">
					<img class="letter__logo" src="<?php echo esc_url( mp_img( $mp_review['logo'] ) ); ?>" alt="" loading="lazy" decoding="async">
					<div class="letter__content">
						<p class="letter__company"><?php echo esc_html( $mp_review['company'] ); ?></p>
						<p class="letter__quote"><?php echo esc_html( $mp_review['text'] ); ?></p>
					</div>
					<p class="letter__date"><?php echo esc_html( $mp_review['date'] ); ?></p>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="dots reviews__dots" id="reviews-dots" aria-hidden="true">
		<?php foreach ( $mp_reviews['items'] as $mp_index => $mp_review ) : ?>
			<span class="dots__dot<?php echo 0 === $mp_index ? ' is-active' : ''; ?>"></span>
		<?php endforeach; ?>
	</div>
</section>
