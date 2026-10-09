<?php
/**
 * Практики: сетка карточек.
 *
 * На десктопе — обложка сверху и подпись под ней, три в ряд.
 * На планшете и мобилке карточка превращается в бежевую плашку: текст
 * сверху, картинка снизу, под плашкой — пара кнопок.
 *
 * @package MPartners
 */

// Карточки — записи типа «Практики»; пока их нет, берутся из макета.
$mp_cards = function_exists( 'mp_practice_cards' ) ? mp_practice_cards() : mp_practice( 'cards', [] );
?>
<section class="pg-cards">
	<ul class="pg-cards__grid">
		<?php foreach ( $mp_cards as $mp_card ) : ?>
			<li class="pg-card">
				<article class="pg-card__panel">
					<div class="pg-card__body">
						<h2 class="pg-card__title"><?php echo esc_html( $mp_card['title'] ); ?></h2>
						<p class="pg-card__text"><?php echo esc_html( $mp_card['text'] ); ?></p>
					</div>

					<a class="pg-card__link" href="<?php echo esc_url( isset( $mp_card['href'] ) ? $mp_card['href'] : '#' ); ?>" aria-label="<?php echo esc_attr( $mp_card['title'] ); ?>"></a>

					<div class="pg-card__cover">
						<img class="pg-card__image" src="<?php echo esc_url( mp_img( $mp_card['image'], 'jpg' ) ); ?>" alt="" width="786" height="488" loading="lazy" decoding="async">
					</div>
				</article>

				<div class="btn-pair pg-card__actions">
					<a class="btn" href="<?php echo esc_url( isset( $mp_card['href'] ) ? $mp_card['href'] : '#form' ); ?>"><?php echo esc_html( mp_practice( 'card_cta' ) ); ?></a>
					<a class="btn-icon" href="<?php echo esc_url( isset( $mp_card['href'] ) ? $mp_card['href'] : '#form' ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>

	<button class="btn btn--block pg-cards__more" type="button" data-more hidden><?php echo esc_html( mp_practice( 'more' ) ); ?></button>
</section>
