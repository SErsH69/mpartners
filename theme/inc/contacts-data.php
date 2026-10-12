<?php
/**
 * Контент страницы «Контакты».
 *
 * @package MPartners
 */

/**
 * Данные страницы.
 *
 * @return array
 */
function mp_contacts_data() {
	return [
		'title'   => 'Контакты',
		'offices' => [
			[
				'title'   => 'Офис в Москве',
				'phone'   => '8 800 101 42 47',
				'email'   => 'partners@mpartners.law',
				'address' => '127006, Москва, Каланчевская, 47, пом. 6/1',
				'map'     => 'https://yandex.ru/map-widget/v1/?ll=37.646022%2C55.778613&z=17&pt=37.646022%2C55.778613%2Cpm2rdm',
				'route'   => 'https://yandex.ru/maps/?ll=37.646022%2C55.778613&z=17&pt=37.646022%2C55.778613%2Cpm2rdm',
			],
			[
				'title'   => 'Офис в Омске',
				'phone'   => '8 800 101 42 47',
				'email'   => 'partners@mpartners.law',
				'address' => '644099, Омск, Интернациональная, 14',
				'map'     => 'https://yandex.ru/map-widget/v1/?ll=73.369452%2C54.990679&z=17&pt=73.369452%2C54.990679%2Cpm2rdm',
				'route'   => 'https://yandex.ru/maps/?ll=73.369452%2C54.990679&z=17&pt=73.369452%2C54.990679%2Cpm2rdm',
			],
		],
	];
}

/**
 * Значение из контента страницы.
 *
 * @param string $path    Путь вида `offices`.
 * @param mixed  $default Запасное значение.
 * @return mixed
 */
function mp_contacts( $path = '', $default = '' ) {
	if ( function_exists( 'mp_content_get' ) ) {
		$saved = mp_content_get( 'contacts', $path, null );

		if ( null !== $saved && '' !== $saved && [] !== $saved ) {
			return $saved;
		}
	}

	$value = mp_contacts_data();

	foreach ( array_filter( explode( '.', $path ) ) as $key ) {
		if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) ) {
			return $default;
		}

		$value = $value[ $key ];
	}

	return $value;
}
