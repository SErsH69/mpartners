<?php
/**
 * Лендинги услуг — отдельный тип записей со своим оформлением.
 *
 * Хаб (страница со списком) живёт на шаблоне page-landings.php,
 * каждая запись открывается своим лендингом.
 *
 * @package MPartners
 */

const MP_LANDING_VERSION = '5';
const MP_LANDING_TYPE    = 'mp_landing';
const MP_HUB_TYPE        = 'mp_hub';


/**
 * Хаб услуг — отдельный тип записей: у каждого хаба своя страница
 * с разделами, внутрь которых открываются лендинги услуг.
 */
function mp_register_hub_type() {
	register_post_type(
		MP_HUB_TYPE,
		[
			'labels'        => [
				'name'          => 'Услуги хаб',
				'singular_name' => 'Хаб услуг',
				'add_new'       => 'Добавить хаб',
				'add_new_item'  => 'Новый хаб',
				'edit_item'     => 'Редактировать хаб',
				'all_items'     => 'Все хабы',
				'menu_name'     => 'Услуги хаб',
				'not_found'     => 'Хабов пока нет',
			],
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 22,
			'has_archive'   => false,
			'rewrite'       => [ 'slug' => 'uslugi', 'with_front' => false ],
			'supports'      => [ 'title', 'page-attributes' ],
		]
	);
}
add_action( 'init', 'mp_register_hub_type', 5 );

/**
 * Первый опубликованный хаб — на него ведёт пункт меню «Услуги».
 *
 * @return WP_Post|null
 */
function mp_primary_hub() {
	$hubs = get_posts(
		[
			'post_type'      => MP_HUB_TYPE,
			'posts_per_page' => 1,
			'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'ASC' ],
		]
	);

	return $hubs ? $hubs[0] : null;
}

/**
 * Регистрация типа записей.
 */
function mp_register_landing_type() {
	register_post_type(
		MP_LANDING_TYPE,
		[
			'labels'        => [
				'name'               => 'Лендинги услуг',
				'singular_name'      => 'Лендинг',
				'add_new'            => 'Добавить лендинг',
				'add_new_item'       => 'Новый лендинг',
				'edit_item'          => 'Редактировать лендинг',
				'all_items'          => 'Все лендинги',
				'menu_name'          => 'Лендинги услуг',
				'search_items'       => 'Искать лендинг',
				'not_found'          => 'Лендингов пока нет',
			],
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-megaphone',
			'menu_position' => 23,
			'has_archive'   => false,
			'rewrite'       => [ 'slug' => 'usluga', 'with_front' => false ],
			'supports'      => [ 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ],
		]
	);
}
add_action( 'init', 'mp_register_landing_type', 5 );

/**
 * Группа лендинга в хабе: подпись раздела, к которому относится услуга.
 */
function mp_register_landing_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$choices = [];

	foreach ( (array) mp_landings( 'groups', [] ) as $index => $group ) {
		$choices[ (string) $index ] = isset( $group['title'] ) ? $group['title'] : (string) $index;
	}

	acf_add_local_field_group(
		[
			'key'      => 'group_mp_landing',
			'title'    => 'Размещение в хабе',
			'fields'   => [
				[
					'key'          => 'field_mp_landing_hub',
					'label'        => 'Хаб услуг',
					'name'         => 'landing_hub',
					'type'         => 'post_object',
					'post_type'    => [ MP_HUB_TYPE ],
					'return_format' => 'id',
					'allow_null'   => 1,
					'instructions' => 'К какому хабу относится услуга.',
				],
				[
					'key'           => 'field_mp_landing_group',
					'label'         => 'Раздел хаба',
					'name'          => 'landing_group',
					'type'          => 'select',
					'choices'       => $choices,
					'default_value' => '0',
					'instructions'  => 'В каком блоке страницы «Услуги» показывать карточку.',
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => MP_LANDING_TYPE,
					],
				],
			],
		]
	);
}
add_action( 'acf/init', 'mp_register_landing_fields' );

/**
 * Карточки хаба: записи, разложенные по разделам макета.
 *
 * @return array Список разделов с ключами `title` и `items` (label + href).
 */
function mp_landing_groups( $hub_id = 0 ) {
	$hub_id = $hub_id ? (int) $hub_id : ( is_singular( MP_HUB_TYPE ) ? get_the_ID() : 0 );

	if ( ! $hub_id ) {
		$hub    = mp_primary_hub();
		$hub_id = $hub ? $hub->ID : 0;
	}

	$groups = (array) mp_landings( 'groups', [] );
	$posts  = get_posts(
		[
			'post_type'      => MP_LANDING_TYPE,
			'posts_per_page' => -1,
			'orderby'        => [
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			],
		]
	);

	$byGroup = [];

	foreach ( $posts as $post ) {
		if ( ! function_exists( 'get_field' ) ) {
			continue;
		}

		// Лендинг показываем только в своём хабе.
		$own = (int) get_field( 'landing_hub', $post->ID );

		if ( $hub_id && $own && $own !== $hub_id ) {
			continue;
		}

		$index = (int) get_field( 'landing_group', $post->ID );

		$byGroup[ $index ][] = [
			'label' => get_the_title( $post ),
			'href'  => get_permalink( $post ),
		];
	}

	$result = [];

	foreach ( $groups as $index => $group ) {
		$items = isset( $byGroup[ $index ] ) ? $byGroup[ $index ] : [];

		// Пока в разделе нет ни одной записи, показываем состав из макета.
		if ( ! $items ) {
			foreach ( mp_landings_fallback_items( $index ) as $label ) {
				$items[] = [
					'label' => $label,
					'href'  => '',
				];
			}
		}

		$result[] = [
			'title' => $group['title'],
			'items' => $items,
		];
	}

	return $result;
}

/**
 * Заводит хаб услуг и первый лендинг.
 */
function mp_seed_landing_page() {
	if ( get_option( 'mp_landing_version' ) === MP_LANDING_VERSION ) {
		return;
	}

	$hub = mp_primary_hub();

	if ( ! $hub ) {
		$hub_id = wp_insert_post(
			[
				'post_type'   => MP_HUB_TYPE,
				'post_title'  => 'Уголовная защита бизнеса',
				'post_name'   => 'ugolovnaya-zashhita-biznesa',
				'post_status' => 'publish',
				'menu_order'  => 1,
			]
		);
	} else {
		$hub_id = $hub->ID;
	}

	// Старая страница-хаб больше не нужна: её адрес занимает тип записей.
	$page = get_page_by_path( 'uslugi' );

	if ( $page && 'page' === $page->post_type && 'publish' === $page->post_status ) {
		wp_update_post( [ 'ID' => $page->ID, 'post_status' => 'draft' ] );
	}

	$existing = get_posts(
		[
			'post_type'      => MP_LANDING_TYPE,
			'posts_per_page' => -1,
			'post_status'    => 'any',
		]
	);

	if ( ! $existing ) {
		$landing = wp_insert_post(
			[
				'post_type'   => MP_LANDING_TYPE,
				'post_title'  => mp_landing_page_data()['hero']['title'],
				// Слаг задаём явно: Cyr-To-Lat на этом хуке ещё не работает.
				'post_name'   => 'advokat-po-ekonomicheskim-prestupleniyam',
				'post_status' => 'publish',
				'menu_order'  => 1,
			]
		);

		if ( $landing && ! is_wp_error( $landing ) ) {
			$existing = [ get_post( $landing ) ];
		}
	}

	// Привязываем лендинги к хабу и приводим слаги к латинице.
	foreach ( (array) $existing as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		if ( function_exists( 'update_field' ) ) {
			if ( ! get_field( 'landing_hub', $post->ID ) && $hub_id ) {
				update_field( 'landing_hub', $hub_id, $post->ID );
			}

			if ( '' === (string) get_field( 'landing_group', $post->ID ) ) {
				update_field( 'landing_group', '1', $post->ID );
			}
		}

		if ( preg_match( '~^[a-z0-9-]+$~', $post->post_name ) ) {
			continue;
		}

		$slug = mp_translit_slug( $post->post_title );

		if ( $slug ) {
			wp_update_post( [ 'ID' => $post->ID, 'post_name' => $slug ] );
		}
	}

	update_option( 'mp_landing_version', MP_LANDING_VERSION );
	flush_rewrite_rules();
}
// Раньше сидирования меню: пункт «Услуги» должен найти готовый хаб.
add_action( 'init', 'mp_seed_landing_page', 10 );

/**
 * Латинский слаг из русского заголовка.
 *
 * Cyr-To-Lat работает на хуках сохранения, а записи мы заводим кодом,
 * поэтому транслитерацию делаем сами.
 *
 * @param string $title Заголовок.
 * @return string
 */
function mp_translit_slug( $title ) {
	$map = [
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
		'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm',
		'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
		'ф' => 'f', 'х' => 'h', 'ц' => 'cz', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh',
		'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
	];

	$slug = strtr( mb_strtolower( (string) $title ), $map );
	$slug = preg_replace( '~[^a-z0-9]+~', '-', $slug );

	return trim( (string) $slug, '-' );
}

/**
 * Старый адрес страницы услуг ведёт на хаб, чтобы ссылка не умерла.
 */
function mp_redirect_old_hub() {
	if ( ! is_404() ) {
		return;
	}

	$path = trim( (string) wp_parse_url( add_query_arg( [] ), PHP_URL_PATH ), '/' );

	if ( 'uslugi' !== $path ) {
		return;
	}

	$hub = mp_primary_hub();

	if ( $hub ) {
		wp_safe_redirect( get_permalink( $hub ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mp_redirect_old_hub' );

/**
 * Карточки услуг для блока «Как мы решаем сложные ситуации».
 *
 * @return array Пустой массив, если лендингов ещё нет.
 */
function mp_service_cards() {
	$posts = get_posts(
		[
			'post_type'      => MP_LANDING_TYPE,
			'posts_per_page' => -1,
			'orderby'        => [
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			],
		]
	);

	$cards = [];

	foreach ( $posts as $post ) {
		$image = get_the_post_thumbnail_url( $post->ID, 'full' );

		$cards[] = [
			'title' => get_the_title( $post ),
			'text'  => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'image' => $image ? $image : '',
			'href'  => get_permalink( $post ),
		];
	}

	return $cards;
}

/**
 * Ссылка на услугу с таким названием. Если лендинга нет — хаб.
 *
 * @param string $title Название услуги.
 * @return string
 */
function mp_title_words( $title ) {
	$title = mb_strtolower( (string) $title );
	$parts = preg_split( '~[^\p{L}\p{N}]+~u', $title, -1, PREG_SPLIT_NO_EMPTY );

	return array_values(
		array_filter(
			(array) $parts,
			static function ( $word ) {
				return mb_strlen( $word ) > 3;
			}
		)
	);
}

/**
 * Ссылка на услугу с таким названием. Если лендинга нет — хаб.
 *
 * @param string $title Название услуги.
 * @return string
 */
function mp_service_link( $title, $fallback = true ) {
	$posts = get_posts(
		[
			'post_type'        => MP_LANDING_TYPE,
			'title'            => (string) $title,
			'posts_per_page'   => 1,
			'suppress_filters' => false,
		]
	);

	if ( $posts ) {
		return get_permalink( $posts[0] );
	}

	// Названия в макете и у лендинга могут отличаться на пару слов
	// («Защита при доследственных проверках» и «Защита бизнеса при…»),
	// поэтому ищем по совпадению слов.
	$words = mp_title_words( $title );

	if ( $words ) {
		$best = 0;
		$href = '';

		foreach ( mp_service_cards() as $card ) {
			$other = mp_title_words( $card['title'] );
			$same  = count( array_intersect( $words, $other ) );
			$score = $same / max( count( $words ), 1 );

			if ( $score > $best ) {
				$best = $score;
				$href = $card['href'];
			}
		}

		if ( $best >= 0.6 && $href ) {
			return $href;
		}
	}

	// Для услуг без своей страницы ссылки нет: клик по ним уводил на хаб,
	// то есть на ту же страницу, откуда человек кликнул.
	if ( ! $fallback ) {
		return '';
	}

	$hub = mp_primary_hub();

	return $hub ? get_permalink( $hub ) : home_url( '/' );
}

/**
 * Удаление старого раздела «Практики».
 *
 * Записи и страница переносятся в корзину — при необходимости их можно
 * восстановить в админке.
 */
const MP_CLEANUP_VERSION = '1';

/**
 * Разовая уборка старого раздела.
 */
function mp_cleanup_practices() {
	if ( get_option( 'mp_cleanup_version' ) === MP_CLEANUP_VERSION ) {
		return;
	}

	foreach ( get_posts(
		[
			'post_type'      => 'mp_practice',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		]
	) as $id ) {
		wp_trash_post( $id );
	}

	$page = get_page_by_path( 'practices' );

	if ( $page && 'page' === $page->post_type ) {
		wp_trash_post( $page->ID );
	}

	update_option( 'mp_cleanup_version', MP_CLEANUP_VERSION );
	flush_rewrite_rules();
}
add_action( 'init', 'mp_cleanup_practices', 19 );

const MP_LANDINGS_SEED_VERSION = '2';

/**
 * Значение поля пустое: пустая строка, пустой массив или структура из них.
 *
 * @param mixed $value Значение поля ACF.
 * @return bool
 */
function mp_acf_is_empty( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $item ) {
			if ( ! mp_acf_is_empty( $item ) ) {
				return false;
			}
		}

		return true;
	}

	return null === $value || false === $value || '' === $value || 0 === $value;
}

/**
 * Заводит лендинги услуг из макета — по одному разу на запись.
 *
 * Поля, которые уже заполнены в админке, не трогаем: миграция только
 * добавляет недостающее.
 */
function mp_seed_landings_content() {
	if ( get_option( 'mp_landings_seed_version' ) === MP_LANDINGS_SEED_VERSION ) {
		return;
	}

	if ( ! function_exists( 'mp_landings_content' ) ) {
		return;
	}

	$hub = mp_primary_hub();

	if ( ! $hub ) {
		return;
	}

	foreach ( mp_landings_content() as $index => $item ) {
		$post = get_page_by_path( $item['slug'], OBJECT, MP_LANDING_TYPE );

		if ( ! $post ) {
			$found = get_posts(
				[
					'post_type'      => MP_LANDING_TYPE,
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'title'          => $item['title'],
				]
			);

			$post = $found ? $found[0] : null;
		}

		$id = $post ? (int) $post->ID : wp_insert_post(
			[
				'post_type'   => MP_LANDING_TYPE,
				'post_title'  => $item['title'],
				'post_name'   => $item['slug'],
				'post_status' => 'publish',
				'menu_order'  => $index + 2,
			]
		);

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		if ( ! function_exists( 'update_field' ) ) {
			continue;
		}

		if ( ! get_field( 'landing_hub', $id ) ) {
			update_field( 'landing_hub', $hub->ID, $id );
		}

		$group = get_field( 'landing_group', $id );

		if ( null === $group || '' === $group ) {
			update_field( 'landing_group', (string) $item['group'], $id );
		}

		foreach ( $item['content'] as $key => $value ) {
			$name = 'landing_' . $key;

			// ACF возвращает пустую структуру группы, а не null, поэтому
			// проверяем, есть ли в ней хоть что-то заполненное.
			if ( ! mp_acf_is_empty( get_field( $name, $id ) ) ) {
				continue;
			}

			update_field( $name, mp_content_to_acf( $value ), $id );
		}
	}

	update_option( 'mp_landings_seed_version', MP_LANDINGS_SEED_VERSION );
}
add_action( 'init', 'mp_seed_landings_content', 13 );

const MP_LANDINGS_REFRESH_VERSION = '1';

/**
 * Разовое обновление блоков, которые приехали из макета неполными: в «этапах»
 * был только последний шаг, в «итогах» — три пункта из шести-семи. Эти два
 * блока перезаписываем значениями из темы, остальные поля не трогаем.
 */
function mp_refresh_landings_blocks() {
	if ( get_option( 'mp_landings_refresh_version' ) === MP_LANDINGS_REFRESH_VERSION ) {
		return;
	}

	if ( ! function_exists( 'update_field' ) || ! function_exists( 'mp_landings_content' ) ) {
		return;
	}

	foreach ( mp_landings_content() as $item ) {
		$post = get_page_by_path( $item['slug'], OBJECT, MP_LANDING_TYPE );

		if ( ! $post ) {
			continue;
		}

		foreach ( [ 'stages', 'results' ] as $key ) {
			if ( isset( $item['content'][ $key ] ) ) {
				update_field( 'landing_' . $key, mp_content_to_acf( $item['content'][ $key ] ), $post->ID );
			}
		}
	}

	update_option( 'mp_landings_refresh_version', MP_LANDINGS_REFRESH_VERSION );
}
add_action( 'init', 'mp_refresh_landings_blocks', 14 );

/**
 * Презентация для страницы услуги: у аудита, советника и представления
 * интересов она своя, у остальных — общая по уголовной защите.
 *
 * @param string $slug Адрес лендинга.
 * @return string
 */
function mp_landing_deck_file( $slug ) {
	$map = [
		'ugolovno-pravovoj-audit-biznesa'                   => 'prez-audit.pdf',
		'ekspress-audit-riskov-moshennichestva'             => 'prez-audit.pdf',
		'sovetnik-pervogo-licza'                            => 'prez-sovetnik.pdf',
		'predstavlenie-interesov-svidetelej-i-poterpevshih' => 'prez-predstavlenie.pdf',
	];

	$file = isset( $map[ $slug ] ) ? $map[ $slug ] : 'prez-zashchita.pdf';

	return get_template_directory_uri() . '/files/' . $file;
}
