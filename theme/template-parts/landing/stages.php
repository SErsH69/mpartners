<?php
/**
 * Лендинг, секция 04: «Как проходит защита» — колода этапов.
 *
 * На десктопе этапы лежат стопкой: верхние края пройденных видны над
 * активной карточкой, клик поднимает нужный этап. Ниже 1024px — обычный список.
 *
 * @package MPartners
 */

$mp_stages = mp_landing( 'stages' );
$mp_items  = (array) $mp_stages['items'];
?>
<section class="ld-stages">
	<div class="shell">
		<div class="ld-stages__head">
			<h2 class="ld-stages__title"><?php echo esc_html( $mp_stages['title'] ); ?></h2>

			<div class="ld-stages__note">
				<span class="ld-stages__note-icon"><?php mp_icon( 'info' ); ?></span>
				<p class="ld-stages__note-text"><?php echo esc_html( $mp_stages['note'] ); ?></p>
			</div>
		</div>

		<div class="ld-stages__deck" data-stages style="--count: <?php echo esc_attr( count( $mp_items ) ); ?>">
			<?php foreach ( $mp_items as $mp_index => $mp_stage ) : ?>
				<article class="ld-stage" data-stage style="--i: <?php echo esc_attr( $mp_index ); ?>">
					<button class="ld-stage__head" type="button" data-stage-open>
						<span class="ld-stage__title"><?php echo esc_html( $mp_stage['title'] ); ?></span>
					</button>

					<div class="ld-stage__body">
						<span class="ld-stage__body-icon"><?php mp_icon( 'info' ); ?></span>
						<p class="ld-stage__text"><?php echo esc_html( $mp_stage['text'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
