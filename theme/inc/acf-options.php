<?php
/**
 * Страницы настроек в админке.
 *
 * Весь редактируемый контент сайта живёт на страницах опций ACF —
 * поля для них собираются автоматически из массивов `inc/*-data.php`
 * (см. inc/admin-fields.php).
 *
 * @package MPartners
 */

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		acf_add_options_page(
			[
				'page_title' => 'Контент сайта',
				'menu_title' => 'Контент сайта',
				'menu_slug'  => 'mp-content',
				'icon_url'   => 'dashicons-edit-page',
				'position'   => 21,
				'redirect'   => true,
			]
		);
	}
);
