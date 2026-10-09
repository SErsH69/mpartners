<?php
/**
 * Дело: блоки «Ситуация», «Проведенная работа», «Результат» и форма заявки.
 *
 * На десктопе форма стоит узкой колонкой справа, на планшете и мобилке
 * уезжает под описание и становится широкой.
 *
 * @package MPartners
 */

$mp_result = mp_case( 'result' );
?>
<section class="case-details">
	<div class="shell">
		<div class="case-details__inner">
			<div class="case-details__blocks">
				<?php foreach ( mp_case( 'blocks', [] ) as $mp_block ) : ?>
					<article class="case-block">
						<p class="case-block__label">
							<span class="case-block__icon"><?php mp_icon( 'info' ); ?></span>
							<span><?php echo esc_html( $mp_block['label'] ); ?></span>
						</p>
						<p class="case-block__text"><?php echo esc_html( $mp_block['text'] ); ?></p>
					</article>
				<?php endforeach; ?>

				<article class="case-block case-block--result">
					<h2 class="case-block__title"><?php echo esc_html( $mp_result['title'] ); ?></h2>
					<p class="case-block__text"><?php echo esc_html( $mp_result['text'] ); ?></p>
				</article>
			</div>

			<?php mp_cf7( 'case', 'pg-form pg-form--case' ); ?>
		</div>
	</div>
</section>
