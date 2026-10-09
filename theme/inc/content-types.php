<?php
/**
 * Материалы разделов — обычные записи WordPress.
 *
 * У каждого раздела своя корневая рубрика (Пресс-центр, Мероприятия, СМИ о
 * нас), её подрубрики показываются табами над списком. Карточки и
 * внутренние страницы берутся из записей; пока записей нет — из макета.
 *
 * @package MPartners
 */

const MP_SECTIONS_VERSION = '2';

/**
 * Раздел → слаг корневой рубрики.
 *
 * @return array
 */
function mp_section_types() {
	return [
		'press'  => [
			'slug'  => 'press',
			'title' => 'Пресс-центр',
		],
		'events' => [
			'slug'  => 'events',
			'title' => 'Мероприятия',
		],
		'media'  => [
			'slug'  => 'media',
			'title' => 'СМИ о нас',
		],
	];
}

/**
 * Корневая рубрика раздела.
 *
 * @param string $section press|events|media.
 * @return WP_Term|null
 */
function mp_section_term( $section ) {
	$types = mp_section_types();

	if ( ! isset( $types[ $section ] ) ) {
		return null;
	}

	$term = get_term_by( 'slug', $types[ $section ]['slug'], 'category' );

	return $term ? $term : null;
}

/**
 * Раздел, которому принадлежит запись.
 *
 * @param int $post_id Запись.
 * @return string press|events|media.
 */
function mp_post_section( $post_id ) {
	$ids = wp_get_post_categories( $post_id );

	foreach ( mp_section_types() as $section => $meta ) {
		$term = mp_section_term( $section );

		if ( ! $term ) {
			continue;
		}

		foreach ( $ids as $id ) {
			if ( (int) $id === (int) $term->term_id || term_is_ancestor_of( $term->term_id, $id, 'category' ) ) {
				return $section;
			}
		}
	}

	return 'press';
}

/**
 * Поле «Широкая карточка» у записи.
 */
function mp_register_section_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
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
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
					],
				],
			],
			'position' => 'side',
		]
	);
}
add_action( 'acf/init', 'mp_register_section_fields' );

/**
 * Карточки раздела: записи его рубрики, а если их нет — макет.
 *
 * @param string $section press|events|media.
 * @return array
 */
function mp_section_cards( $section ) {
	$term = mp_section_term( $section );

	if ( ! $term ) {
		return (array) mp_press( $section )['items'];
	}

	$posts = get_posts(
		[
			'post_type'      => 'post',
			'posts_per_page' => 10,
			'cat'            => $term->term_id,
		]
	);

	if ( ! $posts ) {
		return (array) mp_press( $section )['items'];
	}

	$cards = [];

	foreach ( $posts as $post ) {
		$terms  = get_the_terms( $post->ID, 'category' );
		$label  = '';
		$slugs  = [];

		foreach ( (array) $terms as $item ) {
			if ( is_wp_error( $item ) || (int) $item->term_id === (int) $term->term_id ) {
				continue;
			}

			$slugs[] = $item->slug;

			if ( ! $label ) {
				$label = $item->name;
			}
		}

		$cards[] = [
			'lead'     => function_exists( 'get_field' ) ? (bool) get_field( 'card_lead', $post->ID ) : false,
			'category' => $label ? $label : $term->name,
			'rubrics'  => $slugs,
			'title'    => get_the_title( $post ),
			'excerpt'  => get_the_excerpt( $post ),
			'date'     => get_the_date( 'j F Y', $post ),
			'href'     => get_permalink( $post ),
		];
	}

	return $cards;
}

/**
 * Подрубрики раздела для табов.
 *
 * @param string $section press|events|media.
 * @return array
 */
function mp_section_filters( $section ) {
	$term = mp_section_term( $section );

	if ( ! $term ) {
		return [];
	}

	$terms = get_terms(
		[
			'taxonomy'   => 'category',
			'parent'     => $term->term_id,
			'hide_empty' => false,
		]
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return [];
	}

	$items = [];

	foreach ( $terms as $child ) {
		$items[] = [
			'label' => $child->name,
			'slug'  => $child->slug,
			'href'  => get_term_link( $child ),
		];
	}

	return $items;
}

/**
 * Заводит рубрики и демо-материалы разделов — один раз.
 */
function mp_seed_sections() {
	if ( ! is_admin() || get_option( 'mp_sections_version' ) === MP_SECTIONS_VERSION ) {
		return;
	}

	foreach ( mp_section_types() as $section => $meta ) {
		$data = mp_press( $section );
		$root = get_term_by( 'slug', $meta['slug'], 'category' );

		if ( ! $root ) {
			$created = wp_insert_term( $meta['title'], 'category', [ 'slug' => $meta['slug'] ] );

			if ( is_wp_error( $created ) ) {
				continue;
			}

			$root = get_term( $created['term_id'], 'category' );
		}

		$rubrics = [];

		foreach ( array_slice( (array) $data['filters'], 1 ) as $name ) {
			$existing = get_term_by( 'name', $name, 'category' );

			if ( $existing && (int) $existing->parent === (int) $root->term_id ) {
				$rubrics[] = $existing->term_id;

				continue;
			}

			$created = wp_insert_term( $name, 'category', [ 'parent' => $root->term_id ] );

			if ( ! is_wp_error( $created ) ) {
				$rubrics[] = $created['term_id'];
			}
		}

		$existing = get_posts(
			[
				'post_type'      => 'post',
				'posts_per_page' => 1,
				'post_status'    => 'any',
				'fields'         => 'ids',
				'cat'            => $root->term_id,
			]
		);

		if ( $existing || ! $rubrics ) {
			continue;
		}

		foreach ( array_values( array_reverse( (array) $data['items'] ) ) as $index => $item ) {
			$id = wp_insert_post(
				[
					'post_type'    => 'post',
					'post_title'   => $item['title'],
					'post_excerpt' => $item['excerpt'],
					'post_status'  => 'publish',
				]
			);

			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}

			wp_set_post_categories( $id, [ (int) $root->term_id, (int) $rubrics[ $index % count( $rubrics ) ] ] );

			if ( function_exists( 'update_field' ) && ! empty( $item['lead'] ) ) {
				update_field( 'card_lead', 1, $id );
			}
		}
	}

	update_option( 'mp_sections_version', MP_SECTIONS_VERSION );
}
add_action( 'admin_init', 'mp_seed_sections', 30 );
