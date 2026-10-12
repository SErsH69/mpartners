<?php
/**
 * Лендинг, секция 07: форма консультации.
 *
 * @package MPartners
 */

$mp_consult = mp_landing( 'consult' );
?>
<section class="ld-consult" id="consult">
	<div class="shell">
		<div class="ld-consult__panel">
			<img class="ld-consult__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="ld-consult__copy">
				<div class="ld-consult__intro">
					<h2 class="ld-consult__title"><?php echo esc_html( $mp_consult['title'] ); ?></h2>
					<p class="ld-consult__text"><?php echo esc_html( $mp_consult['text'] ); ?></p>
				</div>

				<div class="ld-consult__footer">
					<p class="ld-consult__note"><?php echo esc_html( $mp_consult['note'] ); ?></p>

					<div class="ld-consult__actions">
						<div class="socials">
							<?php foreach ( mp_data( 'contacts.socials', [] ) as $mp_social ) : ?>
								<a class="btn-icon btn-icon--surface ld-consult__social" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
									<?php mp_icon( $mp_social['icon'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>

						<a class="btn ld-consult__button" href="#form"><?php echo esc_html( $mp_consult['cta'] ); ?></a>
					</div>
				</div>
			</div>

			<div class="ld-form">
				<?php mp_cf7( 'consult', 'ld-form__body' ); ?>
			</div>
		</div>
	</div>
</section>
