<?php
/**
 * Материалы разделов.
 *
 * Пресс-центр — обычные записи WordPress с рубриками. Мероприятия и «СМИ о
 * нас» — свои типы записей со своими рубриками. Карточки в списках и
 * внутренние страницы берутся из них; пока записей нет, показываются
 * материалы из макета.
 *
 * @package MPartners
 */

const MP_SECTIONS_VERSION = '1';

/**
 * Раздел → тип записи и таксономия.
 *
 * @return array
 */
function mp_section_types() {
	return [
		'press'  => [
			'post_type' => 'post',
			'taxonomy'  => 'category',
		],
		'events' => [
			'post_type' => 'mp_event',
			'taxonomy'  => 'mp_event_cat',
		],
		'media'  => [
			'post_type' => 'mp_media',
			'taxonomy'  => 'mp_media_cat',
		],
	];
}

/**
 * Регистрация типов записей и рубрик.
 */
function mp_register_section_types() {
	register_post_type(
		'mp_event',
		[
			'labels'        => [
				'name'          => 'Мероприятия',
				'singular_name' => 'Мероприятие',
				'add_new'       => 'Добавить мероприятие',
				'add_new_item'  => 'Новое мероприятие',
				'edit_item'     => 'Редактировать мероприятие',
				'menu_name'     => 'Мероприятия',
				'not_found'     => 'Мероприятий нет',
			],
			'public'        => true,
			'has_archive'   => false,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 23,
			'supports'      => [ 'title', 'excerpt', 'thumbnail', 'revisions' ],
			'rewrite'       => [ 'slug' => 'event' ],
		]
	);

	register_post_type(
		'mp_media',
		[
			'labels'        => [
				'name'          => 'СМИ о нас',
				'singular_name' => 'Публикация в СМИ',
				'add_new'       => 'Добавить публикацию',
				'add_new_item'  => 'Новая публикация',
				'edit_item'     => 'Редактировать публикацию',
				'menu_name'     => 'СМИ о нас',
				'not_found'     => 'Публикаций нет',
			],
			'public'        => true,
			'has_archive'   => false,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-megaphone',
			'menu_position' => 24,
			'supports'      => [ 'title', 'excerpt', 'thumbnail', 'revisions' ],
			'rewrite'       => [ 'slug' => 'smi' ],
		]
	);

	foreach ( [ 'mp_event_cat' => [ 'mp_event', 'Рубрики мероприятий' ], 'mp_media_cat' => [ 'mp_media', 'Рубрики СМИ' ] ] as $taxonomy => $meta ) {
		register_taxonomy(
			$taxonomy,
			$meta[0],
			[
				'labels'            => [
					'name'          => $meta[1],
					'singular_name' => 'Рубрика',
					'add_new_item'  => 'Добавить рубрику',
					'menu_name'     => 'Рубрики',
				],
				'hierarchical'      => true,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => [ 'slug' => str_replace( '_cat', '', $taxonomy ) ],
			]
		);
	}
}
add_action( 'init', 'mp_register_section_types' );

/**
 * Поля карточки материала.
 */
function mp_register_section_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$location = [];

	foreach ( mp_section_types() as $meta ) {
		$location[] = [
			[
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $meta['post_type'],
			],
		];
	}

	acf_add_local_field_group(
		[
			'key'      => 'group_mp_section_card',
			'title'    => 'Карточка в списке',
			'fields'   => [
				[
					'key'          => 'field_mp_card_lead',
					'label'        => 'Широкая карточка',
					'name'         => 'card_lead',
					'type'         => 'true_false',
					'ui'           => 1,
					'instructions' => 'Тёмная карточка на всю ширину двух колонок — как первая и последняя в макете.',
				],
			],
			'location' => $location,
			'position' => 'side',
		]
	);
}
add_action( 'acf/init', 'mp_register_section_fields' );

/**
 * Карточки раздела: записи, а если их нет — материалы из макета.
 *
 * @param string $section press|events|media.
 * @return array
 */
function mp_section_cards( $section ) {
	$types = mp_section_types();

	if ( ! isset( $types[ $section ] ) ) {
		return [];
	}

	$posts = get_posts(
		[
			'post_type'      => $types[ $section ]['post_type'],
			'posts_per_page' => 10,
		]
	);

	if ( ! $posts ) {
		return (array) mp_press( $section )['items'];
	}

	$cards = [];

	foreach ( $posts as $post ) {
		$terms = get_the_terms( $post->ID, $types[ $section ]['taxonomy'] );

		$cards[] = [
			'lead'     => function_exists( 'get_field' ) ? (bool) get_field( 'card_lead', $post->ID ) : false,
			'category' => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
			'title'    => get_the_title( $post ),
			'excerpt'  => get_the_excerpt( $post ),
			'date'     => get_the_date( 'j F Y', $post ),
			'href'     => get_permalink( $post ),
		];
	}

	return $cards;
}

/**
 * Рубрики раздела для строки фильтров.
 *
 * @param string $section press|events|media.
 * @return array Названия рубрик.
 */
function mp_section_filters( $section ) {
	$types = mp_section_types();

	if ( ! isset( $types[ $section ] ) ) {
		return [];
	}

	$terms = get_terms(
		[
			'taxonomy'   => $types[ $section ]['taxonomy'],
			'hide_empty' => false,
		]
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return [];
	}

	$names = [];

	foreach ( $terms as $term ) {
		if ( 'category' === $types[ $section ]['taxonomy'] && 'uncategorized' === $term->slug ) {
			continue;
		}

		$names[] = [
			'label' => $term->name,
			'href'  => get_term_link( $term ),
		];
	}

	return $names;
}

/**
 * Заводит рубрики и материалы разделов из макета — один раз.
 */
function mp_seed_sections() {
	if ( ! is_admin() || get_option( 'mp_sections_version' ) === MP_SECTIONS_VERSION ) {
		return;
	}

	foreach ( mp_section_types() as $section => $meta ) {
		$data = mp_press( $section );

		// Рубрики из строки фильтров, кроме первой («Все рубрики»).
		foreach ( array_slice( (array) $data['filters'], 1 ) as $name ) {
			if ( ! term_exists( $name, $meta['taxonomy'] ) ) {
				wp_insert_term( $name, $meta['taxonomy'] );
			}
		}

		$existing = get_posts(
			[
				'post_type'      => $meta['post_type'],
				'posts_per_page' => 1,
				'post_status'    => 'any',
				'fields'         => 'ids',
			]
		);

		if ( $existing ) {
			continue;
		}

		$rubrics = array_values( array_slice( (array) $data['filters'], 1 ) );

		foreach ( array_values( array_reverse( (array) $data['items'] ) ) as $index => $item ) {
			$id = wp_insert_post(
				[
					'post_type'    => $meta['post_type'],
					'post_title'   => $item['title'],
					'post_excerpt' => $item['excerpt'],
					'post_status'  => 'publish',
				]
			);

			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}

			// Рубрику берём из строки фильтров — чтобы их было столько же,
			// сколько в макете, а не по рубрике на каждый материал.
			if ( $rubrics ) {
				wp_set_object_terms( $id, $rubrics[ $index % count( $rubrics ) ], $meta['taxonomy'] );
			}

			if ( function_exists( 'update_field' ) && ! empty( $item['lead'] ) ) {
				update_field( 'card_lead', 1, $id );
			}
		}
	}

	update_option( 'mp_sections_version', MP_SECTIONS_VERSION );
}
add_action( 'admin_init', 'mp_seed_sections', 30 );
