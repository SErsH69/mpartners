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

const MP_MENUS_VERSION = '3';

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
		'Услуги'      => 'practices',
		'Практики'    => 'practices',
		'Адвокаты'    => 'lawyers',
		'Мероприятия' => 'events',
		'СМИ о нас'   => 'media',
		'Пресс-центр' => 'press',
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
	$cli = defined( 'WP_CLI' ) && WP_CLI;

	if ( ( ! is_admin() && ! $cli ) || get_option( 'mp_menus_version' ) === MP_MENUS_VERSION ) {
		return;
	}

	$items = [ 'Услуги', 'Адвокаты', 'Пресс-центр', 'Мероприятия', 'СМИ о нас', 'Контакты' ];

	$sets = [
		'mp-header'   => [
			'name'  => 'Меню сайта',
			'items' => $items,
		],
		'mp-footer'   => [
			'name'  => 'Подвал — меню',
			'items' => $items,
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

	// Приводим уже созданные меню к актуальному составу: лишние пункты
	// убираем, у остальных проставляем адреса.
	$stale = [ 'О коллегии', 'Дела', 'Партнеры', 'Партнёры', 'Блог' ];

	foreach ( $locations as $location => $menu_id ) {
		if ( ! isset( $sets[ $location ] ) || ! $menu_id ) {
			continue;
		}

		$services = 'mp-services' === $location;

		if ( ! $services ) {
			foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
				if ( in_array( $item->title, $stale, true ) ) {
					wp_delete_post( $item->ID, true );
				}
			}
		}

		$present = [];

		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
			$present[] = $item->title;
			$target    = mp_menu_url_for( $item->title, isset( $sets[ $location ]['fallback'] ) ? $sets[ $location ]['fallback'] : '' );

			// В меню услуг названия произвольные — там не трогаем рабочие
			// ссылки; в шапке и подвале адреса приводим к актуальным страницам.
			if ( $services && '#' !== $item->url && '' !== $item->url && $item->url !== home_url( '/' ) ) {
				continue;
			}

			if ( $item->url === $target ) {
				continue;
			}

			wp_update_nav_menu_item(
				$menu_id,
				$item->ID,
				[
					'menu-item-title'  => $item->title,
					'menu-item-url'    => $target,
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				]
			);
		}

		// Недостающие разделы добавляем — иначе после чистки меню окажется
		// короче, чем нужно сайту.
		if ( ! $services ) {
			foreach ( $sets[ $location ]['items'] as $label ) {
				if ( in_array( $label, $present, true ) ) {
					continue;
				}

				wp_update_nav_menu_item(
					$menu_id,
					0,
					[
						'menu-item-title'  => $label,
						'menu-item-url'    => mp_menu_url_for( $label ),
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					]
				);
			}

			// Порядок после чистки и добавления сбивается — выстраиваем
			// пункты так же, как в списке разделов.
			$order = array_flip( $sets[ $location ]['items'] );

			foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
				if ( ! isset( $order[ $item->title ] ) ) {
					continue;
				}

				wp_update_post(
					[
						'ID'         => $item->ID,
						'menu_order' => $order[ $item->title ] + 1,
					]
				);
			}
		}
	}

	update_option( 'mp_menus_version', MP_MENUS_VERSION );
}
add_action( 'admin_init', 'mp_seed_menus' );

/**
 * Адрес кнопки: сохранённая ссылка, иначе страница по слагу, иначе попап
 * с формой — чтобы в вёрстке не оставалось «мёртвых» `#`.
 *
 * @param mixed  $href     Saved href (string or ['href' => …]).
 * @param string $fallback Page slug.
 * @return string
 */
function mp_link( $href, $fallback = '' ) {
	if ( is_array( $href ) ) {
		$href = isset( $href['href'] ) ? $href['href'] : '';
	}

	$href = (string) $href;

	if ( '' !== $href && '#' !== $href ) {
		return $href;
	}

	if ( $fallback ) {
		$page = get_page_by_path( $fallback );

		// Черновик (например заготовка политики от WordPress) отдал бы 404.
		if ( $page && 'publish' === $page->post_status ) {
			return get_permalink( $page );
		}
	}

	return '#form';
}

/**
 * Страницы разделов: создаются сами, чтобы сайт одинаково разворачивался
 * на любом сервере.
 */
function mp_seed_pages() {
	$cli = defined( 'WP_CLI' ) && WP_CLI;

	if ( ! is_admin() && ! $cli ) {
		return;
	}

	$pages = [
		'practices' => [ 'Практики', 'page-practices.php' ],
		'lawyers'   => [ 'Адвокаты', 'page-lawyers.php' ],
		'press'     => [ 'Пресс-центр', 'page-press.php' ],
		'events'    => [ 'Мероприятия', 'page-events.php' ],
		'media'     => [ 'СМИ о нас', 'page-media.php' ],
	];

	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $slug );

		if ( $existing ) {
			if ( get_page_template_slug( $existing ) !== $page[1] ) {
				update_post_meta( $existing->ID, '_wp_page_template', $page[1] );
			}

			continue;
		}

		$id = wp_insert_post(
			[
				'post_type'   => 'page',
				'post_name'   => $slug,
				'post_title'  => $page[0],
				'post_status' => 'publish',
			]
		);

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', $page[1] );
		}
	}
}
add_action( 'admin_init', 'mp_seed_pages', 5 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	add_action( 'init', 'mp_seed_pages', 5 );
}
