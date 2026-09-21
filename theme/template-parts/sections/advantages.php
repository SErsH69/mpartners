<?php
/**
 * 03. Преимущества.
 *
 * @package MPartners
 */

$mp_adv = mp_data( 'advantages' );
?>
<section class="advantages">
	<div class="advantages__inner">
		<div class="note note--shield advantages__note">
			<span class="note__icon"><?php mp_icon( 'shield' ); ?></span>
			<p class="note__text"><?php echo esc_html( $mp_adv['note'] ); ?></p>
		</div>

		<ul class="advantages__grid">
			<?php foreach ( $mp_adv['cards'] as $mp_card ) : ?>
				<li class="adv-card">
					<img class="adv-card__decor" src="<?php echo esc_url( mp_img( $mp_card['decor'], 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<p class="adv-card__text"><?php echo esc_html( $mp_card['text'] ); ?></p>
					<?php if ( ! empty( $mp_card['metric'] ) ) : ?>
						<p class="adv-card__metric">
							<span class="adv-card__number"><?php echo esc_html( $mp_card['metric'] ); ?></span>
							<span class="adv-card__unit"><?php mp_nl2br( $mp_card['unit'] ); ?></span>
						</p>
					<?php else : ?>
						<p class="adv-card__title"><?php echo esc_html( $mp_card['title'] ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
