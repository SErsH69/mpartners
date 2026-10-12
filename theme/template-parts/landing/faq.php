<?php
/**
 * Лендинг, секция 13: часто задаваемые вопросы.
 *
 * Два столбца вопросов; ответ раскрывается по клику. Без скрипта
 * работает как обычный <details>.
 *
 * @package MPartners
 */

$mp_faq   = mp_landing( 'faq' );
$mp_items = array_values( (array) $mp_faq['items'] );
$mp_half  = (int) ceil( count( $mp_items ) / 2 );
$mp_cols  = [ array_slice( $mp_items, 0, $mp_half ), array_slice( $mp_items, $mp_half ) ];
?>
<section class="ld-faq">
	<div class="shell">
		<h2 class="ld-faq__title"><?php echo esc_html( $mp_faq['title'] ); ?></h2>

		<div class="ld-faq__columns">
			<?php foreach ( $mp_cols as $mp_col ) : ?>
				<div class="ld-faq__column">
					<?php foreach ( $mp_col as $mp_item ) : ?>
						<details class="ld-faq__item">
							<summary class="ld-faq__question">
								<span><?php echo esc_html( $mp_item['question'] ); ?></span>
								<span class="ld-faq__toggle" aria-hidden="true"><?php mp_icon( 'plus' ); ?></span>
							</summary>
							<p class="ld-faq__answer"><?php echo esc_html( $mp_item['answer'] ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
