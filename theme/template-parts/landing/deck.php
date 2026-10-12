<?php
/**
 * Лендинг, секция 10: презентация об аудите.
 *
 * @package MPartners
 */

$mp_deck = mp_landing( 'deck' );
$mp_file = isset( $mp_deck['file'] ) ? $mp_deck['file'] : '';
?>
<section class="ld-deck">
	<div class="shell">
		<div class="ld-deck__panel">
			<div class="ld-deck__copy">
				<h2 class="ld-deck__title"><?php echo esc_html( $mp_deck['title'] ); ?></h2>
				<p class="ld-deck__text"><?php echo esc_html( $mp_deck['text'] ); ?></p>
			</div>

			<?php if ( $mp_file ) : ?>
				<a class="btn btn--light ld-deck__button" href="<?php echo esc_url( $mp_file ); ?>" download><?php echo esc_html( $mp_deck['label'] ); ?></a>
			<?php else : ?>
				<?php // Пока файл не загружен, кнопка открывает форму заявки. ?>
				<a class="btn btn--light ld-deck__button" href="#form"><?php echo esc_html( $mp_deck['label'] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
