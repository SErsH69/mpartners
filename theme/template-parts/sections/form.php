<?php
/**
 * 12. Форма связи.
 *
 * @package MPartners
 */

$mp_form = mp_data( 'form' );
?>
<section class="contact" id="form">
	<div class="shell">
	<div class="contact__inner">
		<img class="contact__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

		<div class="contact__intro">
			<div class="contact__copy">
				<h2 class="h-section h-section--light contact__title"><?php echo esc_html( $mp_form['title'] ); ?></h2>
				<?php foreach ( $mp_form['text'] as $mp_paragraph ) : ?>
					<p class="contact__text"><?php echo esc_html( $mp_paragraph ); ?></p>
				<?php endforeach; ?>
			</div>

			<div class="contact__socials">
				<p class="contact__socials-label"><?php echo esc_html( $mp_form['socials_label'] ); ?></p>
				<div class="contact__socials-row">
					<div class="socials">
						<?php foreach ( mp_data( 'contacts.socials', [] ) as $mp_social ) : ?>
							<a class="btn-icon btn-icon--surface" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
								<?php mp_icon( $mp_social['icon'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
					<a class="btn" href="<?php echo esc_url( $mp_form['cta']['href'] ); ?>"><?php echo esc_html( $mp_form['cta']['label'] ); ?></a>
				</div>
			</div>
		</div>

		<form class="contact__form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-contact-form>
			<input type="hidden" name="action" value="mp_contact">
			<?php wp_nonce_field( MP_CONTACT_NONCE, '_mp_nonce', false ); ?>

			<label class="field">
				<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['name'] ); ?></span>
				<input type="text" name="name" placeholder="<?php echo esc_attr( $mp_form['fields']['name'] ); ?>" required>
			</label>

			<label class="field">
				<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['phone'] ); ?></span>
				<input type="tel" name="phone" placeholder="<?php echo esc_attr( $mp_form['fields']['phone'] ); ?>" required>
			</label>

			<fieldset class="contact__channels">
				<legend class="contact__channels-label"><?php echo esc_html( $mp_form['fields']['channel'] ); ?></legend>
				<div class="contact__channels-row">
					<?php foreach ( $mp_form['channels'] as $mp_index => $mp_channel ) : ?>
						<label class="chip-toggle<?php echo 1 === $mp_index ? ' is-active' : ''; ?>">
							<input type="radio" name="channel" value="<?php echo esc_attr( $mp_channel ); ?>"<?php echo 1 === $mp_index ? ' checked' : ''; ?>>
							<span><?php echo esc_html( $mp_channel ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</fieldset>

			<label class="field">
				<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['contact'] ); ?></span>
				<input type="text" name="contact" placeholder="<?php echo esc_attr( $mp_form['fields']['contact'] ); ?>">
			</label>

			<button class="btn btn--light btn--block contact__submit" type="submit"><?php echo esc_html( $mp_form['submit'] ); ?></button>

			<p class="contact__message" role="status" aria-live="polite" hidden></p>

			<label class="consent">
				<input type="checkbox" name="consent" required>
				<span class="consent__box" aria-hidden="true"></span>
				<span class="consent__text"><?php echo esc_html( $mp_form['consent'] ); ?></span>
			</label>
		</form>
	</div>
	</div>
</section>
