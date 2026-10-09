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

const MP_SECTIONS_VERSION = '4';

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
	$cli = defined( 'WP_CLI' ) && WP_CLI;

	if ( ( ! is_admin() && ! $cli ) || get_option( 'mp_sections_version' ) === MP_SECTIONS_VERSION ) {
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

		// Широкая карточка — только у тех материалов, что помечены в макете.
		$leads = [];

		foreach ( (array) $data['items'] as $item ) {
			if ( ! empty( $item['lead'] ) ) {
				$leads[] = $item['title'];
			}
		}

		if ( $existing ) {
			if ( function_exists( 'update_field' ) ) {
				$posts = get_posts(
					[
						'post_type'      => 'post',
						'posts_per_page' => -1,
						'post_status'    => 'any',
						'cat'            => $root->term_id,
					]
				);

				$dates = [];

				foreach ( (array) $data['items'] as $item ) {
					$dates[ $item['title'] ] = isset( $item['date'] ) ? $item['date'] : '';
				}

				foreach ( $posts as $post ) {
					update_field( 'card_lead', in_array( $post->post_title, $leads, true ) ? 1 : 0, $post->ID );

					$date = isset( $dates[ $post->post_title ] ) ? mp_parse_ru_date( $dates[ $post->post_title ] ) : '';

					if ( $date && substr( $post->post_date, 0, 10 ) !== substr( $date, 0, 10 ) ) {
						wp_update_post(
							[
								'ID'            => $post->ID,
								'post_date'     => $date,
								'post_date_gmt' => get_gmt_from_date( $date ),
							]
						);
					}
				}
			}

			continue;
		}

		if ( ! $rubrics ) {
			continue;
		}

		foreach ( array_values( array_reverse( (array) $data['items'] ) ) as $index => $item ) {
			$args = [
				'post_type'    => 'post',
				'post_title'   => $item['title'],
				'post_excerpt' => $item['excerpt'],
				'post_status'  => 'publish',
			];

			$date = mp_parse_ru_date( isset( $item['date'] ) ? $item['date'] : '' );

			if ( $date ) {
				$args['post_date']     = $date;
				$args['post_date_gmt'] = get_gmt_from_date( $date );
			}

			$id = wp_insert_post( $args );

			if ( ! $id || is_wp_error( $id ) ) {
				continue;
			}

			wp_set_post_categories( $id, [ (int) $root->term_id, (int) $rubrics[ $index % count( $rubrics ) ] ] );

			if ( function_exists( 'update_field' ) ) {
				update_field( 'card_lead', in_array( $item['title'], $leads, true ) ? 1 : 0, $id );
			}
		}
	}

	update_option( 'mp_sections_version', MP_SECTIONS_VERSION );
}
add_action( 'admin_init', 'mp_seed_sections', 30 );

/**
 * Склонение числительных: 1 материал, 2 материала, 5 материалов.
 *
 * @param int    $number Count.
 * @param string $one    Form for 1.
 * @param string $few    Form for 2–4.
 * @param string $many   Form for 5+.
 * @return string
 */
function mp_plural( $number, $one, $few, $many ) {
	$number = abs( (int) $number ) % 100;
	$tail   = $number % 10;

	if ( $number > 10 && $number < 20 ) {
		return $many;
	}

	if ( $tail > 1 && $tail < 5 ) {
		return $few;
	}

	return 1 === $tail ? $one : $many;
}

/**
 * Подпись карточки в результатах поиска: тип записи или рубрика.
 *
 * @param WP_Post $post Post object.
 * @return string
 */
function mp_search_label( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( 'mp_practice' === $post->post_type ) {
		return 'Услуга';
	}

	if ( 'mp_lawyer' === $post->post_type ) {
		return 'Адвокат';
	}

	if ( 'page' === $post->post_type ) {
		return 'Страница';
	}

	$section = mp_post_section( $post->ID );

	if ( $section ) {
		$types = mp_section_types();

		if ( isset( $types[ $section ]['title'] ) ) {
			return $types[ $section ]['title'];
		}
	}

	$terms = get_the_terms( $post, 'category' );

	return $terms && ! is_wp_error( $terms ) ? $terms[0]->name : 'Материал';
}

/**
 * Свежие записи в виде карточек блога. Пока записей нет, возвращает
 * `$fallback` — карточки из макета.
 *
 * @param int   $count    How many posts.
 * @param int   $exclude  Post ID to skip.
 * @param array $fallback Cards from the mockup.
 * @return array
 */
function mp_recent_cards( $count = 3, $exclude = 0, $fallback = [] ) {
	$posts = get_posts(
		[
			'post_type'        => 'post',
			'posts_per_page'   => (int) $count,
			'post__not_in'     => $exclude ? [ (int) $exclude ] : [],
			'suppress_filters' => false,
		]
	);

	if ( ! $posts ) {
		return (array) $fallback;
	}

	$cards = [];

	foreach ( $posts as $post ) {
		$cards[] = [
			'category' => mp_search_label( $post ),
			'title'    => get_the_title( $post ),
			'excerpt'  => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 18 ),
			'date'     => get_the_date( 'd.m.Y', $post ),
			'href'     => get_permalink( $post ),
		];
	}

	return $cards;
}

/**
 * Дата вида «15 июня 2025» → формат WordPress.
 *
 * @param string $value Human readable date.
 * @return string Empty string when the date cannot be read.
 */
function mp_parse_ru_date( $value ) {
	$months = [
		'января'   => '01',
		'февраля'  => '02',
		'марта'    => '03',
		'апреля'   => '04',
		'мая'      => '05',
		'июня'     => '06',
		'июля'     => '07',
		'августа'  => '08',
		'сентября' => '09',
		'октября'  => '10',
		'ноября'   => '11',
		'декабря'  => '12',
	];

	if ( ! preg_match( '~^(\d{1,2})\s+([а-яё]+)\s+(\d{4})~ui', (string) $value, $m ) ) {
		return '';
	}

	$month = mb_strtolower( $m[2] );

	if ( ! isset( $months[ $month ] ) ) {
		return '';
	}

	return sprintf( '%s-%s-%02d 09:00:00', $m[3], $months[ $month ], (int) $m[1] );
}
