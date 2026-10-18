<?php
/**
 * О компании: презентация коллегии.
 *
 * Оформление общее с блоком презентации на лендингах.
 *
 * @package MPartners
 */

$mp_deck = mp_about( 'deck' );
$mp_file = ! empty( $mp_deck['file'] ) ? $mp_deck['file'] : get_template_directory_uri() . '/files/prez-kollegiya.pdf';
?>
<section class="ld-deck ab-deck">
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
