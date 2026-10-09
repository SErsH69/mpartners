<?php
/**
 * Лендинги услуг — отдельный тип записей со своим оформлением.
 *
 * Хаб (страница со списком) живёт на шаблоне page-landings.php,
 * каждая запись открывается своим лендингом.
 *
 * @package MPartners
 */

const MP_LANDING_VERSION = '3';
const MP_LANDING_TYPE    = 'mp_landing';

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
function mp_landing_groups() {
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
		$index = function_exists( 'get_field' ) ? (int) get_field( 'landing_group', $post->ID ) : 0;

		$byGroup[ $index ][] = [
			'label' => get_the_title( $post ),
			'href'  => get_permalink( $post ),
		];
	}

	$result = [];

	foreach ( $groups as $index => $group ) {
		$items = isset( $byGroup[ $index ] ) ? $byGroup[ $index ] : [];

		// Пока лендингов нет, показываем состав из макета.
		if ( ! $items ) {
			foreach ( (array) $group['items'] as $label ) {
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
 * Заводит страницу хаба.
 */
function mp_seed_landing_page() {
	// Разовая миграция: выполняется на первом же запросе после выкатки,
	// чтобы страница появилась без захода в админку.
	if ( get_option( 'mp_landing_version' ) === MP_LANDING_VERSION ) {
		return;
	}

	$page = get_page_by_path( 'uslugi' );

	if ( ! $page ) {
		$id = wp_insert_post(
			[
				'post_type'   => 'page',
				'post_name'   => 'uslugi',
				'post_title'  => 'Услуги',
				'post_status' => 'publish',
			]
		);

		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_wp_page_template', 'page-landings.php' );
		}
	} elseif ( get_page_template_slug( $page ) !== 'page-landings.php' ) {
		update_post_meta( $page->ID, '_wp_page_template', 'page-landings.php' );
	}

	// Первый лендинг из макета, чтобы страница услуги была на что открывать.
	$existing = get_posts(
		[
			'post_type'      => MP_LANDING_TYPE,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
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

		if ( $landing && ! is_wp_error( $landing ) && function_exists( 'update_field' ) ) {
			update_field( 'landing_group', '1', $landing );
		}
	}

	// Кириллический слаг отдавал бы длинный %-адрес — переводим в латиницу.
	foreach ( get_posts( [ 'post_type' => MP_LANDING_TYPE, 'posts_per_page' => -1, 'post_status' => 'any' ] ) as $post ) {
		if ( preg_match( '~^[a-z0-9-]+$~', $post->post_name ) ) {
			continue;
		}

		$slug = function_exists( 'ctl_sanitize_title' ) ? ctl_sanitize_title( $post->post_title ) : sanitize_title( $post->post_title );

		if ( $slug && preg_match( '~^[a-z0-9-]+$~', $slug ) ) {
			wp_update_post( [ 'ID' => $post->ID, 'post_name' => $slug ] );
		}
	}

	update_option( 'mp_landing_version', MP_LANDING_VERSION );
	flush_rewrite_rules();
}
add_action( 'init', 'mp_seed_landing_page', 20 );
