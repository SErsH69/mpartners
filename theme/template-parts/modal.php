<?php
/**
 * Попап с формой: открывается по кнопкам «Заказать звонок», «Обсудить
 * ситуацию» и другим ссылкам на форму.
 *
 * @package MPartners
 */

$mp_bot = mp_data( 'contacts.max_bot', [] );
?>
<div class="modal" id="mp-modal" hidden>
	<div class="modal__overlay" data-modal-close></div>

	<div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="mp-modal-title">
		<button class="modal__close" type="button" data-modal-close aria-label="Закрыть"><?php mp_icon( 'close' ); ?></button>

		<p class="modal__title" id="mp-modal-title"><?php echo esc_html( mp_data( 'form.title', 'Оставьте заявку' ) ); ?></p>

		<?php mp_cf7( 'practices', 'pg-form pg-form--modal' ); ?>

		<?php if ( ! empty( $mp_bot['href'] ) ) : ?>
			<p class="modal__note">
				Или напишите нам в MAX —
				<a href="<?php echo esc_url( $mp_bot['href'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $mp_bot['label'] ); ?></a>
			</p>
		<?php endif; ?>
	</div>
</div>
