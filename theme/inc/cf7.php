<?php
/**
 * Формы сайта работают на Contact Form 7.
 *
 * Разметка форм живёт в теме, а не в админке: при смене MP_CF7_VERSION
 * формы пересоздаются/обновляются, поэтому вёрстка не расходится с кодом.
 *
 * @package MPartners
 */

const MP_CF7_VERSION = '3';

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
function mp_cf7_definitions() {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$mail = [
		'subject'            => 'Заявка с сайта M-PARTNERS',
		'sender'             => sprintf( '[_site_title] <wordpress@%s>', $host ),
		'recipient'          => get_option( 'admin_email' ),
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
	];
}

/**
 * Создаёт/обновляет формы при смене версии разметки.
 */
function mp_cf7_sync() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return;
	}

	if ( get_option( 'mp_cf7_version' ) === MP_CF7_VERSION ) {
		return;
	}

	$ids = (array) get_option( 'mp_cf7_ids', [] );

	foreach ( mp_cf7_definitions() as $slug => $def ) {
		$form = isset( $ids[ $slug ] ) ? WPCF7_ContactForm::get_instance( (int) $ids[ $slug ] ) : null;

		if ( ! $form ) {
			$form = WPCF7_ContactForm::get_template( [ 'title' => $def['title'] ] );
		}

		$form->set_title( $def['title'] );
		$form->set_properties(
			[
				'form' => $def['form'],
				'mail' => array_merge( (array) $form->prop( 'mail' ), $def['mail'] ),
			]
		);

		$ids[ $slug ] = $form->save();
	}

	update_option( 'mp_cf7_ids', $ids );
	update_option( 'mp_cf7_version', MP_CF7_VERSION );
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
