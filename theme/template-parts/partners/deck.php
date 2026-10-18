<?php
/**
 * Партнерство: презентация программы.
 *
 * @package MPartners
 */

$mp_deck = mp_partners( 'deck' );
$mp_file = ! empty( $mp_deck['file'] ) ? $mp_deck['file'] : '';

// Файл кладём в тему: показываем блок, только когда он там есть.
if ( '' === $mp_file && file_exists( get_template_directory() . '/files/prez-partnerstvo.pdf' ) ) {
	$mp_file = get_template_directory_uri() . '/files/prez-partnerstvo.pdf';
}

if ( '' === $mp_file ) {
	return;
}
?>
<section class="ld-deck pn-deck">
	<div class="shell">
		<div class="ld-deck__panel">
			<div class="ld-deck__copy">
				<h2 class="ld-deck__title"><?php echo esc_html( $mp_deck['title'] ); ?></h2>
				<p class="ld-deck__text"><?php echo esc_html( $mp_deck['text'] ); ?></p>
			</div>

			<a class="btn btn--light ld-deck__button" href="<?php echo esc_url( $mp_file ); ?>" download><?php echo esc_html( $mp_deck['label'] ); ?></a>
		</div>
	</div>
</section>
