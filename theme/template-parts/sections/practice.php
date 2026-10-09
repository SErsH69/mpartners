<?php
/**
 * 09. Практика — «Как мы решаем сложные ситуации».
 *
 * @package MPartners
 */

$mp_practice = mp_data( 'practice' );

// Карточки — настоящие услуги; оформление и количество как в макете.
$mp_cases = (array) $mp_practice['items'];

if ( function_exists( 'mp_practice_cards' ) ) {
	$mp_posts = mp_practice_cards();
	$mp_cases = array_slice( $mp_posts ? $mp_posts : $mp_cases, 0, count( $mp_cases ) );
}
?>
<section class="practice">
	<div class="practice__inner">
		<header class="section-head">
			<h2 class="h-section"><?php echo esc_html( $mp_practice['title'] ); ?></h2>
			<div class="note section-head__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php echo esc_html( $mp_practice['note'] ); ?></p>
			</div>
		</header>

		<ul class="practice__grid">
			<?php foreach ( $mp_cases as $mp_index => $mp_case ) : ?>
				<?php $mp_case_href = isset( $mp_case['href'] ) ? $mp_case['href'] : mp_practice_link( $mp_case['title'] ); ?>
				<li class="case-card">
					<?php if ( 0 === $mp_index ) : ?>
						<a class="case-card__more" href="<?php echo esc_url( $mp_case_href ); ?>"><?php echo esc_html( $mp_practice['more'] ); ?></a>
					<?php endif; ?>
					<div class="case-card__body">
						<div class="case-card__text">
							<h3 class="case-card__title"><?php echo esc_html( $mp_case['title'] ); ?></h3>
							<p class="case-card__excerpt"><?php echo esc_html( $mp_case['text'] ); ?></p>
						</div>
						<img class="case-card__image" src="<?php echo esc_url( mp_img( $mp_case['image'] ) ); ?>" alt="" loading="lazy" decoding="async">
					</div>
					<div class="btn-pair case-card__actions">
						<a class="btn" href="<?php echo esc_url( $mp_case_href ); ?>"><?php echo esc_html( $mp_practice['more'] ); ?></a>
						<a class="btn-icon" href="<?php echo esc_url( $mp_case_href ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<a class="btn btn--block practice__all" href="<?php echo esc_url( mp_link( $mp_practice['all'], 'practices' ) ); ?>"><?php echo esc_html( $mp_practice['all']['label'] ); ?></a>
	</div>
</section>
