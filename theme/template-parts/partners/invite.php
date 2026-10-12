<?php
/**
 * Партнерство: приглашение и условия.
 *
 * @package MPartners
 */

$mp_invite = mp_partners( 'invite' );
?>
<section class="pn-invite">
	<div class="shell">
		<div class="pn-invite__panel">
			<img class="pn-invite__decor" src="<?php echo esc_url( mp_img( 'decor-quiz-glow1', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<h2 class="pn-invite__title"><?php echo esc_html( $mp_invite['title'] ); ?></h2>

			<div class="pn-invite__body">
				<p class="pn-invite__text"><?php echo esc_html( $mp_invite['text'] ); ?></p>

				<?php foreach ( [ $mp_invite['who'], $mp_invite['gain'] ] as $mp_block ) : ?>
					<p class="pn-invite__label"><?php echo esc_html( $mp_block['label'] ); ?></p>
					<ul class="pn-invite__list">
						<?php foreach ( (array) $mp_block['items'] as $mp_item ) : ?>
							<li><?php echo esc_html( $mp_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			</div>

			<a class="btn btn--light pn-invite__button" href="#form"><?php echo esc_html( $mp_invite['label'] ); ?></a>
		</div>
	</div>
</section>
