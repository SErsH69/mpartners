<?php
/**
 * Меню сайта редактируются во «Внешний вид → Меню».
 *
 * Разметка меню в шапке и подвале разная, поэтому шаблоны получают не
 * готовый HTML от `wp_nav_menu()`, а простой список пунктов — так вёрстка
 * остаётся ровно по макету. Пока меню не заполнено, используются пункты
 * из макета.
 *
 * @package MPartners
 */

const MP_MENUS_VERSION = '2';

/**
 * Места для меню.
 *
 * @return array
 */
function mp_menu_locations() {
	return [
		'mp-header'   => 'Шапка — выпадающее меню',
		'mp-footer'   => 'Подвал — меню сайта',
		'mp-services' => 'Подвал — услуги',
	];
}

add_action(
	'after_setup_theme',
	function () {
		register_nav_menus( mp_menu_locations() );
	},
	20
);

/**
 * Пункты меню места или запасные пункты из макета.
 *
 * @param string $location Ключ места.
 * @param array  $fallback Запасные пункты: строки или пары label/href.
 * @return array Список ['label' => …, 'href' => …].
 */
function mp_menu_items( $location, $fallback = [] ) {
	static $cache = [];

	if ( isset( $cache[ $location ] ) ) {
		return $cache[ $location ];
	}

	$items     = [];
	$locations = function_exists( 'get_nav_menu_locations' ) ? get_nav_menu_locations() : [];

	if ( ! empty( $locations[ $location ] ) ) {
		$objects = wp_get_nav_menu_items( $locations[ $location ] );

		if ( $objects ) {
			foreach ( $objects as $object ) {
				if ( (int) $object->menu_item_parent ) {
					continue; // Вложенные пункты в этом макете не используются.
				}

				$items[] = [
					'label' => $object->title,
					'href'  => $object->url,
				];
			}
		}
	}

	if ( ! $items ) {
		foreach ( (array) $fallback as $item ) {
			$items[] = is_array( $item ) ? $item : [
				'label' => $item,
				'href'  => '#',
			];
		}
	}

	$cache[ $location ] = $items;

	return $items;
}

/**
 * Куда ведёт пункт меню с таким названием.
 *
 * @param string $label    Название пункта.
 * @param string $fallback Адрес, если страницы нет.
 * @return string
 */
function mp_menu_url_for( $label, $fallback = '' ) {
	$map = [
		'Услуги'           => 'practices',
		'Практики'         => 'practices',
		'Адвокаты'         => 'lawyer',
		'Дела'             => 'case',
		'Мероприятия'      => 'events',
		'СМИ о нас'        => 'media',
		'Пресс-центр'      => 'press',
		'Блог'             => 'press',
	];

	if ( isset( $map[ $label ] ) ) {
		$page = get_page_by_path( $map[ $label ] );

		if ( $page ) {
			return get_permalink( $page );
		}
	}

	if ( 'Контакты' === $label ) {
		return '#form'; // Открывает попап с формой.
	}

	if ( $fallback ) {
		$page = get_page_by_path( $fallback );

		if ( $page ) {
			return get_permalink( $page );
		}
	}

	return home_url( '/' );
}

/**
 * Создаёт меню с пунктами из макета, чтобы в админке было что править.
 */
function mp_seed_menus() {
	if ( ! is_admin() || get_option( 'mp_menus_version' ) === MP_MENUS_VERSION ) {
		return;
	}

	$sets = [
		'mp-header'   => [
			'name'  => 'Меню сайта',
			'items' => (array) mp_data( 'menu', [] ),
		],
		'mp-footer'   => [
			'name'  => 'Подвал — меню',
			'items' => (array) mp_data( 'menu', [] ),
		],
		'mp-services' => [
			'name'     => 'Подвал — услуги',
			'items'    => (array) mp_data( 'footer.services', [] ),
			'fallback' => 'practices',
		],
	];

	$locations = get_nav_menu_locations();

	foreach ( $sets as $location => $set ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}

		$menu = wp_get_nav_menu_object( $set['name'] );
		$id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $set['name'] );

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		if ( ! wp_get_nav_menu_items( $id ) ) {
			foreach ( $set['items'] as $label ) {
				wp_update_nav_menu_item(
					$id,
					0,
					[
						'menu-item-title'  => is_array( $label ) ? $label['label'] : $label,
						'menu-item-url'    => mp_menu_url_for(
							is_array( $label ) ? $label['label'] : $label,
							isset( $set['fallback'] ) ? $set['fallback'] : ''
						),
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					]
				);
			}
		}

		$locations[ $location ] = $id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	// У меню, созданных раньше, пункты вели на «#» — проставляем адреса.
	foreach ( $locations as $location => $menu_id ) {
		if ( ! isset( $sets[ $location ] ) || ! $menu_id ) {
			continue;
		}

		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
			if ( '#' !== $item->url && '' !== $item->url ) {
				continue;
			}

			wp_update_nav_menu_item(
				$menu_id,
				$item->ID,
				[
					'menu-item-title'  => $item->title,
					'menu-item-url'    => mp_menu_url_for( $item->title, isset( $sets[ $location ]['fallback'] ) ? $sets[ $location ]['fallback'] : '' ),
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				]
			);
		}
	}

	update_option( 'mp_menus_version', MP_MENUS_VERSION );
}
add_action( 'admin_init', 'mp_seed_menus' );
