<?php
/**
 * Лендинги услуг — отдельный тип записей со своим оформлением.
 *
 * Хаб (страница со списком) живёт на шаблоне page-landings.php,
 * каждая запись открывается своим лендингом.
 *
 * @package MPartners
 */

const MP_LANDING_VERSION = '1';
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
	$cli = defined( 'WP_CLI' ) && WP_CLI;

	if ( ( ! is_admin() && ! $cli ) || get_option( 'mp_landing_version' ) === MP_LANDING_VERSION ) {
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

	update_option( 'mp_landing_version', MP_LANDING_VERSION );
	flush_rewrite_rules();
}
add_action( 'admin_init', 'mp_seed_landing_page', 7 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	add_action( 'init', 'mp_seed_landing_page', 20 );
}
