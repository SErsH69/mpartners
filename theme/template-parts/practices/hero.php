<?php
/**
 * Практики: первый экран — тёмная панель с заголовком, формой и плашками.
 *
 * @package MPartners
 */

$mp_hero = mp_practice( 'hero' );
$mp_form = mp_practice( 'form' );
?>
<section class="pg-hero">
	<div class="shell">
		<div class="pg-hero__panel">
			<img class="pg-hero__decor" src="<?php echo esc_url( mp_img( 'pg-decor-blob', 'svg' ) ); ?>" alt="" aria-hidden="true" width="825" height="825" decoding="async">

			<div class="pg-hero__copy">
				<h1 class="h-page pg-hero__title"><?php echo esc_html( $mp_hero['title'] ); ?></h1>

				<div class="pg-hero__note">
					<span class="pg-hero__note-icon"><?php mp_icon( 'shield' ); ?></span>
					<p class="pg-hero__note-text"><?php echo esc_html( $mp_hero['note'] ); ?></p>
				</div>
			</div>

			<?php if ( ! mp_cf7( 'practices', 'pg-form' ) ) : ?>
				<form class="pg-form" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-contact-form>
					<input type="hidden" name="action" value="mp_contact">
					<?php wp_nonce_field( MP_CONTACT_NONCE, '_mp_nonce', false ); ?>

					<p class="pg-form__title"><?php echo esc_html( $mp_form['title'] ); ?></p>

					<div class="pg-form__body">
						<div class="pg-form__rows">
							<div class="pg-form__row">
								<label class="field pg-form__field pg-form__field--name">
									<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['name'] ); ?></span>
									<input type="text" name="name" placeholder="<?php echo esc_attr( $mp_form['fields']['name'] ); ?>" required>
								</label>

								<label class="field pg-form__field pg-form__field--phone">
									<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['phone'] ); ?></span>
									<input type="tel" name="phone" placeholder="<?php echo esc_attr( $mp_form['fields']['phone'] ); ?>" required>
								</label>
							</div>

							<div class="pg-form__row">
								<div class="pg-form__messenger">
									<span class="pg-form__messenger-icon"><?php mp_icon( 'telegram' ); ?></span>
									<select class="pg-form__select" name="channel" aria-label="<?php esc_attr_e( 'Мессенджер', 'm-partners' ); ?>">
										<?php foreach ( mp_data( 'form.channels', [ 'Telegram' ] ) as $mp_channel ) : ?>
											<option value="<?php echo esc_attr( $mp_channel ); ?>"<?php selected( $mp_channel, $mp_form['messenger'] ); ?>><?php echo esc_html( $mp_channel ); ?></option>
										<?php endforeach; ?>
									</select>
									<span class="pg-form__messenger-caret" aria-hidden="true"><?php mp_icon( 'caret' ); ?></span>
								</div>

								<label class="field pg-form__field pg-form__field--contact">
									<span class="visually-hidden"><?php echo esc_html( $mp_form['fields']['contact'] ); ?></span>
									<input type="text" name="contact" placeholder="<?php echo esc_attr( $mp_form['fields']['contact'] ); ?>">
								</label>
							</div>
						</div>

						<div class="pg-form__submit">
							<button class="btn btn--light btn--block" type="submit"><?php echo esc_html( $mp_form['submit'] ); ?></button>

							<label class="consent">
								<input type="checkbox" name="consent" required>
								<span class="consent__box" aria-hidden="true"></span>
								<span class="consent__text"><?php echo esc_html( $mp_form['consent'] ); ?></span>
							</label>
						</div>
					</div>
				</form>
			<?php endif; ?>
		</div>

		<ul class="pg-benefits">
			<?php foreach ( mp_practice( 'benefits', [] ) as $mp_benefit ) : ?>
				<li class="pg-benefit">
					<span class="pg-benefit__icon"><?php mp_icon( 'shield' ); ?></span>
					<span class="pg-benefit__text"><?php echo esc_html( $mp_benefit ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
