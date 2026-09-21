<?php
/**
 * Обработка формы связи из секции «12. Форма связи».
 *
 * @package MPartners
 */

/**
 * Nonce action name.
 */
const MP_CONTACT_NONCE = 'mp_contact';

/**
 * Handle the contact form submission.
 */
function mp_handle_contact() {
	$nonce = isset( $_POST['_mp_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_mp_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, MP_CONTACT_NONCE ) ) {
		wp_send_json_error( [ 'message' => 'Обновите страницу и попробуйте ещё раз.' ], 403 );
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$channel = isset( $_POST['channel'] ) ? sanitize_text_field( wp_unslash( $_POST['channel'] ) ) : '';
	$contact = isset( $_POST['contact'] ) ? sanitize_text_field( wp_unslash( $_POST['contact'] ) ) : '';
	$consent = ! empty( $_POST['consent'] );

	if ( '' === $name || '' === $phone ) {
		wp_send_json_error( [ 'message' => 'Заполните имя и номер телефона.' ], 400 );
	}

	if ( ! $consent ) {
		wp_send_json_error( [ 'message' => 'Нужно согласие на обработку персональных данных.' ], 400 );
	}

	$to      = apply_filters( 'mp_contact_recipient', get_option( 'admin_email' ) );
	$subject = 'Заявка с сайта ' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

	$body = implode(
		"\n",
		[
			'Имя: ' . $name,
			'Телефон: ' . $phone,
			'Канал связи: ' . ( $channel ?: '—' ),
			'Контакт: ' . ( $contact ?: '—' ),
		]
	);

	$sent = wp_mail( $to, $subject, $body );

	do_action( 'mp_contact_submitted', compact( 'name', 'phone', 'channel', 'contact' ), $sent );

	if ( ! $sent ) {
		wp_send_json_error( [ 'message' => 'Не удалось отправить заявку. Позвоните нам, пожалуйста.' ], 500 );
	}

	wp_send_json_success( [ 'message' => 'Заявка отправлена. Свяжемся с вами в ближайшее время.' ] );
}
add_action( 'wp_ajax_mp_contact', 'mp_handle_contact' );
add_action( 'wp_ajax_nopriv_mp_contact', 'mp_handle_contact' );
