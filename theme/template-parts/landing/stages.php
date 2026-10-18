<?php
/**
 * Лендинг, секция 04: «Как проходит защита» — колода этапов.
 *
 * Этапы лежат стопкой, как в макете: у пройденных виден только верхний край.
 * Колода листается скроллом страницы — пока секция закреплена, карточки
 * по очереди выезжают снизу; по строке можно и кликнуть. Ниже 1024px —
 * обычный список, без закрепления.
 *
 * @package MPartners
 */

$mp_stages = mp_landing( 'stages' );
$mp_items  = (array) $mp_stages['items'];
$mp_count  = count( $mp_items );
?>
<section class="ld-stages">
	<div class="shell">
		<div class="ld-stages__scroll" data-stages style="--count: <?php echo esc_attr( $mp_count ); ?>">
			<div class="ld-stages__pin">
				<div class="ld-stages__head">
					<h2 class="ld-stages__title"><?php echo esc_html( $mp_stages['title'] ); ?></h2>

					<?php if ( ! empty( $mp_stages['note'] ) ) : ?>
						<div class="ld-stages__note">
							<span class="ld-stages__note-icon"><?php mp_icon( 'info' ); ?></span>
							<p class="ld-stages__note-text"><?php echo esc_html( $mp_stages['note'] ); ?></p>
						</div>
					<?php endif; ?>
				</div>
				<div class="ld-stages__deck" data-stages-deck>
					<?php foreach ( $mp_items as $mp_index => $mp_stage ) : ?>
						<article class="ld-stage" data-stage style="--i: <?php echo esc_attr( $mp_index ); ?>">
							<img class="ld-stage__decor" src="<?php echo esc_url( mp_img( 'decor-stage', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

							<button class="ld-stage__head" type="button" data-stage-open>
								<span class="ld-stage__title"><?php echo esc_html( $mp_stage['title'] ); ?></span>
								<span class="ld-stage__next" aria-hidden="true"><?php mp_icon( 'caret' ); ?></span>
								<span class="ld-stage__step">
									<?php echo esc_html( sprintf( '%02d', $mp_index + 1 ) ); ?>
									<span class="ld-stage__step-all">/ <?php echo esc_html( sprintf( '%02d', $mp_count ) ); ?></span>
								</span>
							</button>

							<div class="ld-stage__body">
								<span class="ld-stage__body-icon"><?php mp_icon( 'info' ); ?></span>
								<p class="ld-stage__text"><?php echo esc_html( $mp_stage['text'] ); ?></p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
</section>
