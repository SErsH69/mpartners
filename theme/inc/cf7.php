<?php
/**
 * Формы сайта работают на Contact Form 7.
 *
 * Разметка форм живёт в теме, а не в админке: при смене MP_CF7_VERSION
 * формы пересоздаются/обновляются, поэтому вёрстка не расходится с кодом.
 *
 * @package MPartners
 */

const MP_CF7_VERSION = '4';

/**
 * Inline SVG as a string (иконки нужны внутри тела формы).
 *
 * @param string $name Icon name.
 * @return string
 */
function mp_cf7_icon( $name ) {
	ob_start();
	mp_icon( $name );

	return trim( ob_get_clean() );
}

/**
 * Описание форм: тело и письмо.
 *
 * @return array
 */
/**
 * Куда уходят заявки с форм. Адрес правится в «Настройки сайта → Контакты».
 *
 * @return string
 */
function mp_cf7_recipient() {
	$email = (string) mp_data( 'contacts.email', '' );

	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

function mp_cf7_definitions() {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$mail = [
		'subject'            => 'Заявка с сайта M-PARTNERS',
		'sender'             => sprintf( '[_site_title] <wordpress@%s>', $host ),
		'recipient'          => mp_cf7_recipient(),
		'body'               => "Имя: [name]\nТелефон: [phone]\nМессенджер: [channel]\nКонтакт: [contact]\n\nСтраница: [_post_title]\n[_url]\n",
		'additional_headers' => '',
		'attachments'        => '',
		'use_html'           => false,
		'exclude_blank'      => false,
	];

	$form = mp_practices_data()['form'];

	$practices = sprintf(
		'<p class="pg-form__title">%1$s</p>
<div class="pg-form__body">
<div class="pg-form__rows">
<div class="pg-form__row">
<span class="field pg-form__field pg-form__field--name">[text* name placeholder "%2$s"]</span>
<span class="field pg-form__field pg-form__field--phone">[tel* phone placeholder "%3$s"]</span>
</div>
<div class="pg-form__row">
<span class="pg-form__messenger"><span class="pg-form__messenger-icon">%5$s</span>[select channel class:pg-form__select "Telegram" "MAX" "ВКонтакте" "WhatsApp"]<span class="pg-form__messenger-caret">%6$s</span></span>
<span class="field pg-form__field pg-form__field--contact">[text contact placeholder "%4$s"]</span>
</div>
</div>
<div class="pg-form__submit">
[submit class:btn class:btn--light class:btn--block "%7$s"]
<span class="consent pg-form__consent">[acceptance consent "%8$s"]</span>
</div>
</div>',
		esc_html( $form['title'] ),
		esc_attr( $form['fields']['name'] ),
		esc_attr( $form['fields']['phone'] ),
		esc_attr( $form['fields']['contact'] ),
		mp_cf7_icon( 'telegram' ),
		mp_cf7_icon( 'caret' ),
		esc_attr( $form['submit'] ),
		esc_attr( $form['consent'] )
	);

	$case_form = mp_case( 'form' );

	// Форма дела: на десктопе узкая колонка, на планшете и мобилке — широкая,
	// поэтому в разметке два заголовка, лишний скрывается стилями.
	$case = sprintf(
		'<p class="pg-form__title pg-form__title--narrow">%1$s</p>
<p class="pg-form__title pg-form__title--wide">%2$s</p>
<div class="pg-form__body">
<div class="pg-form__rows">
<div class="pg-form__row">
<span class="field pg-form__field pg-form__field--name">[text* name placeholder "%3$s"]</span>
<span class="field pg-form__field pg-form__field--phone">[tel* phone placeholder "%4$s"]</span>
</div>
<div class="pg-form__row">
<span class="pg-form__messenger"><span class="pg-form__messenger-icon">%6$s</span>[select channel class:pg-form__select "Telegram" "MAX" "ВКонтакте" "WhatsApp"]<span class="pg-form__messenger-caret">%7$s</span></span>
<span class="field pg-form__field pg-form__field--contact">[text contact placeholder "%5$s"]</span>
</div>
</div>
<div class="pg-form__submit">
[submit class:btn class:btn--light class:btn--block "%8$s"]
<span class="consent pg-form__consent">[acceptance consent "%9$s"]</span>
</div>
</div>',
		esc_html( $case_form['title'] ),
		esc_html( $case_form['title_wide'] ),
		esc_attr( $form['fields']['name'] ),
		esc_attr( $form['fields']['phone'] ),
		esc_attr( $form['fields']['contact'] ),
		mp_cf7_icon( 'telegram' ),
		mp_cf7_icon( 'caret' ),
		esc_attr( $case_form['submit'] ),
		esc_attr( $form['consent'] )
	);

	$home_form = mp_data( 'form' );

	$home = sprintf(
		'<label class="field"><span class="visually-hidden">%1$s</span>[text* name placeholder "%1$s"]</label>
<label class="field"><span class="visually-hidden">%2$s</span>[tel* phone placeholder "%2$s"]</label>
<fieldset class="contact__channels">
<legend class="contact__channels-label">%3$s</legend>
<div class="contact__channels-row">[radio channel use_label_element default:1 "Telegram" "MAX" "ВКонтакте" "WhatsApp"]</div>
</fieldset>
<label class="field"><span class="visually-hidden">%4$s</span>[text contact placeholder "%4$s"]</label>
[submit class:btn class:btn--light class:btn--block class:contact__submit "%5$s"]
<span class="consent">[acceptance consent "%6$s"]</span>',
		esc_attr( $home_form['fields']['name'] ),
		esc_attr( $home_form['fields']['phone'] ),
		esc_html( $home_form['fields']['channel'] ),
		esc_attr( $home_form['fields']['contact'] ),
		esc_attr( $home_form['submit'] ),
		esc_attr( $home_form['consent'] )
	);


	// --- Квиз «Консультация специалиста» ------------------------------
	$quiz       = mp_data( 'quiz' );
	$quiz_steps = (array) $quiz['steps'];
	$quiz_body  = '';
	$quiz_lines = '';
	$step_index = 0;

	foreach ( $quiz_steps as $step ) {
		$step_index++;
		$options = '';

		foreach ( (array) $step['answers'] as $answer ) {
			// Кавычки внутри вариантов сломали бы шорткод CF7.
			$options .= sprintf( ' "%s"', str_replace( '"', '', $answer ) );
		}

		$quiz_body .= sprintf(
			'<fieldset class="quiz__step%1$s" data-quiz-step="%2$d"%3$s>
<legend class="quiz__question"><span class="quiz__question-icon">%4$s</span><span>%5$s</span></legend>
<div class="quiz__answers">[radio quiz-%2$d use_label_element%6$s]</div>
</fieldset>',
			1 === $step_index ? ' is-active' : '',
			$step_index,
			1 === $step_index ? '' : ' hidden',
			mp_cf7_icon( 'question' ),
			esc_html( $step['question'] ),
			$options
		);

		$quiz_lines .= sprintf( "%s: [quiz-%d]\n", $step['question'], $step_index );
	}

	$quiz_body .= sprintf(
		'<fieldset class="quiz__step quiz__step--contacts" data-quiz-step="%1$d" hidden>
<legend class="quiz__question"><span class="quiz__question-icon">%2$s</span><span>%3$s</span></legend>
<div class="quiz__contacts">
<span class="field quiz__field">[text* name placeholder "%4$s"]</span>
<span class="field quiz__field">[tel* phone placeholder "%5$s"]</span>
</div>
[submit class:btn class:btn--block class:quiz__submit "%6$s"]
<span class="consent quiz__consent">[acceptance consent "%7$s"]</span>
</fieldset>',
		$step_index + 1,
		mp_cf7_icon( 'question' ),
		esc_html( $quiz['contacts']['question'] ),
		esc_attr( $home_form['fields']['name'] ),
		esc_attr( $home_form['fields']['phone'] ),
		esc_attr( $quiz['contacts']['submit'] ),
		esc_attr( $home_form['consent'] )
	);

	$quiz_bars = '';

	for ( $i = 0; $i <= $step_index; $i++ ) {
		$quiz_bars .= sprintf( '<span class="quiz__progress-item%s"></span>', 0 === $i ? ' is-active' : '' );
	}

	$quiz_form = sprintf(
		'<div class="quiz__intro"><p class="h-section quiz__title">%1$s</p><p class="quiz__subtitle">%2$s</p></div>
<div class="quiz__progress" role="progressbar" aria-valuemin="1" aria-valuemax="%3$d" aria-valuenow="1">%4$s</div>
<div class="quiz__steps">%5$s</div>
<div class="quiz__nav">
<button class="quiz__nav-btn" type="button" data-quiz-prev aria-label="Назад" hidden>%6$s</button>
<button class="quiz__nav-btn" type="button" data-quiz-next aria-label="Далее" disabled>%7$s</button>
</div>',
		esc_html( $quiz['title'] ),
		esc_html( $quiz['subtitle'] ),
		$step_index + 1,
		$quiz_bars,
		$quiz_body,
		mp_cf7_icon( 'arrow-left' ),
		mp_cf7_icon( 'arrow-right' )
	);

	$quiz_mail = array_merge(
		$mail,
		[
			'subject' => 'Квиз с сайта M-PARTNERS',
			'body'    => "Имя: [name]\nТелефон: [phone]\n\n" . $quiz_lines . "\nСтраница: [_post_title]\n[_url]\n",
		]
	);


	// --- Форма хаба услуг ---------------------------------------------
	$hub = mp_landings( 'form' );

	$landing = sprintf(
		'<div class="hub-form__rows">
<div class="hub-form__row">
<span class="field hub-form__field">[text* name placeholder "%1$s"]</span>
<span class="field hub-form__field">[tel* phone placeholder "%2$s"]</span>
</div>
<div class="hub-form__row">
<span class="hub-form__messenger"><span class="hub-form__messenger-icon">%4$s</span>[select channel class:hub-form__select "Telegram" "MAX" "ВКонтакте" "WhatsApp"]<span class="hub-form__messenger-caret">%5$s</span></span>
<span class="field hub-form__field">[text contact placeholder "%3$s"]</span>
</div>
</div>
[submit class:btn class:btn--light class:hub-form__submit "%6$s"]
<span class="consent hub-form__consent">[acceptance consent "%7$s"]</span>',
		esc_attr( $hub['fields']['name'] ),
		esc_attr( $hub['fields']['phone'] ),
		esc_attr( $hub['fields']['contact'] ),
		mp_cf7_icon( 'telegram' ),
		mp_cf7_icon( 'caret' ),
		esc_attr( $hub['submit'] ),
		esc_attr( $hub['consent'] )
	);

	return [
		'practices' => [
			'title' => 'M-PARTNERS — практики',
			'form'  => $practices,
			'mail'  => $mail,
		],
		'home'      => [
			'title' => 'M-PARTNERS — главная',
			'form'  => $home,
			'mail'  => $mail,
		],
		'case'      => [
			'title' => 'M-PARTNERS — карточка дела',
			'form'  => $case,
			'mail'  => $mail,
		],
		'quiz'      => [
			'title' => 'M-PARTNERS — квиз',
			'form'  => $quiz_form,
			'mail'  => $quiz_mail,
		],
		'landing'   => [
			'title' => 'M-PARTNERS — хаб услуг',
			'form'  => $landing,
			'mail'  => $mail,
		],
	];
}

/**
 * Создаёт/обновляет формы при смене версии разметки.
 */
/**
 * Сообщения формы по-русски: плагин создаёт их на языке админки, а нам
 * нужен один и тот же русский текст независимо от настроек сайта.
 *
 * @return array
 */
function mp_cf7_messages() {
	return [
		'mail_sent_ok'             => 'Спасибо! Мы получили заявку и свяжемся с вами в ближайшее время.',
		'mail_sent_ng'             => 'Не удалось отправить сообщение. Попробуйте позже или позвоните нам.',
		'validation_error'         => 'Проверьте, пожалуйста, выделенные поля.',
		'spam'                     => 'Не удалось отправить сообщение. Попробуйте позже или позвоните нам.',
		'accept_terms'             => 'Подтвердите согласие с политикой конфиденциальности.',
		'invalid_required'         => 'Заполните это поле.',
		'invalid_too_long'         => 'Слишком длинное значение.',
		'invalid_too_short'        => 'Слишком короткое значение.',
		'invalid_date'             => 'Неверный формат даты.',
		'date_too_early'           => 'Дата слишком ранняя.',
		'date_too_late'            => 'Дата слишком поздняя.',
		'upload_failed'            => 'Не удалось загрузить файл.',
		'upload_file_type_invalid' => 'Такой тип файла загрузить нельзя.',
		'upload_file_too_large'    => 'Файл слишком большой.',
		'upload_failed_php_error'  => 'Произошла ошибка при загрузке файла.',
		'invalid_number'           => 'Введите число.',
		'number_too_small'         => 'Число слишком маленькое.',
		'number_too_large'         => 'Число слишком большое.',
		'quiz_answer_not_correct'  => 'Неверный ответ на проверочный вопрос.',
		'invalid_email'            => 'Введите корректный адрес почты.',
		'invalid_url'              => 'Введите корректную ссылку.',
		'invalid_tel'              => 'Введите корректный номер телефона.',
	];
}

function mp_cf7_sync() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return;
	}

	$definitions = mp_cf7_definitions();

	// Тексты форм берутся из контента, поэтому сверяем не только версию
	// разметки, но и сами тела форм — правки в админке сразу попадают в CF7.
	$signature = md5( MP_CF7_VERSION . wp_json_encode( $definitions ) );

	if ( get_option( 'mp_cf7_signature' ) === $signature ) {
		return;
	}

	$ids = (array) get_option( 'mp_cf7_ids', [] );

	foreach ( $definitions as $slug => $def ) {
		$form = isset( $ids[ $slug ] ) ? WPCF7_ContactForm::get_instance( (int) $ids[ $slug ] ) : null;

		if ( ! $form ) {
			$form = WPCF7_ContactForm::get_template( [ 'title' => $def['title'] ] );
		}

		$form->set_title( $def['title'] );
		$form->set_properties(
			[
				'form'     => $def['form'],
				'mail'     => array_merge( (array) $form->prop( 'mail' ), $def['mail'] ),
				'messages' => array_merge( (array) $form->prop( 'messages' ), mp_cf7_messages() ),
			]
		);

		$ids[ $slug ] = $form->save();
	}

	update_option( 'mp_cf7_ids', $ids );
	update_option( 'mp_cf7_signature', $signature );
}
add_action( 'init', 'mp_cf7_sync', 20 );

/**
 * Выводит форму CF7 по внутреннему ключу.
 *
 * @param string $slug  Ключ из mp_cf7_definitions().
 * @param string $class Дополнительные классы для <form>.
 * @return bool Удалось ли вывести форму.
 */
function mp_cf7( $slug, $class = '' ) {
	$ids = (array) get_option( 'mp_cf7_ids', [] );

	if ( empty( $ids[ $slug ] ) || ! shortcode_exists( 'contact-form-7' ) ) {
		return false;
	}

	echo do_shortcode(
		sprintf(
			'[contact-form-7 id="%d" html_class="%s"]',
			(int) $ids[ $slug ],
			esc_attr( $class )
		)
	);

	return true;
}

// Вёрстка форм своя — автоматические <p>/<br> от CF7 её ломают.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * CF7 не умеет обязательные радио-группы, а в квизе ответ нужен на каждом
 * шаге — проверяем сами.
 *
 * @param WPCF7_Validation $result Validation result.
 * @param WPCF7_FormTag    $tag    Form tag.
 * @return WPCF7_Validation
 */
function mp_cf7_validate_quiz( $result, $tag ) {
	if ( 0 !== strpos( $tag->name, 'quiz-' ) ) {
		return $result;
	}

	$value = isset( $_POST[ $tag->name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $tag->name ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( '' === $value ) {
		$result->invalidate( $tag, 'Выберите один из вариантов.' );
	}

	return $result;
}
add_filter( 'wpcf7_validate_radio', 'mp_cf7_validate_quiz', 10, 2 );
