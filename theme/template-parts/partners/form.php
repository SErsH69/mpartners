<?php
/**
 * Партнерство: заявка на сотрудничество.
 *
 * @package MPartners
 */

$mp_invite = mp_partners( 'invite' );
?>
<section class="ld-consult pn-form" id="partner-form">
	<div class="shell">
		<div class="ld-consult__panel">
			<img class="ld-consult__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="ld-consult__copy">
				<div class="ld-consult__intro">
					<h2 class="ld-consult__title">Заявка на партнерство</h2>
					<p class="ld-consult__text">Расскажите о компании и направлении работы — обсудим формат сотрудничества и условия партнерского вознаграждения</p>
				</div>

				<div class="ld-consult__footer">
					<p class="ld-consult__note">Конфиденциально. Ответим в течение одного рабочего дня</p>
				</div>
			</div>

			<div class="ld-form">
				<?php mp_cf7( 'partner', 'ld-form__body' ); ?>
			</div>
		</div>
	</div>
</section>
