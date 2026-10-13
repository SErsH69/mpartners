<?php
/**
 * Адвокаты — отдельный тип записей.
 *
 * @package MPartners
 */

const MP_LAWYER_VERSION = '5';

/**
 * Кладёт картинку из темы в медиабиблиотеку и возвращает id вложения.
 *
 * @param string $name Имя файла в dist/img/figma без расширения.
 * @param string $title Заголовок вложения.
 * @return int
 */
function mp_sideload_theme_image( $name, $title ) {
	$path = get_template_directory() . '/dist/img/figma/' . $name . '.jpg';

	if ( ! file_exists( $path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $name . '.jpg', null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment = wp_insert_attachment(
		[
			'post_mime_type' => 'image/jpeg',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		],
		$upload['file']
	);

	if ( ! $attachment || is_wp_error( $attachment ) ) {
		return 0;
	}

	wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) );

	return (int) $attachment;
}

/**
 * Адвокаты — отдельные записи, которые прикрепляются к услугам.
 */
function mp_register_lawyer_type() {
	register_post_type(
		'mp_lawyer',
		[
			'labels'        => [
				'name'          => 'Адвокаты',
				'singular_name' => 'Адвокат',
				'add_new'       => 'Добавить адвоката',
				'add_new_item'  => 'Новый адвокат',
				'edit_item'     => 'Редактировать адвоката',
				'menu_name'     => 'Адвокаты',
				'not_found'     => 'Адвокатов нет',
			],
			'public'        => true,
			'has_archive'   => false,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-businessperson',
			'menu_position' => 23,
			'supports'      => [ 'title', 'thumbnail', 'page-attributes', 'revisions' ],
			'rewrite'       => [ 'slug' => 'lawyer' ],
		]
	);
}
add_action( 'init', 'mp_register_lawyer_type' );

/**
 * Адвокаты для блока «Над делом работали».
 *
 * @param int $post_id Запись услуги; 0 — все адвокаты.
 * @param int $limit   Сколько карточек нужно; -1 — все.
 * @return array Список ['name','text','photo','more','href'].
 */
function mp_lawyer_cards( $post_id = 0, $limit = 4 ) {
	$ids = [];

	if ( $post_id && function_exists( 'get_field' ) ) {
		$ids = (array) [];
	}

	$posts = get_posts(
		[
			'post_type'      => 'mp_lawyer',
			'posts_per_page' => $ids ? count( $ids ) : $limit,
			'post__in'       => $ids ? array_map( 'intval', $ids ) : [],
			'orderby'        => $ids ? 'post__in' : 'menu_order',
			'order'          => 'ASC',
		]
	);

	$cards = [];

	foreach ( $posts as $post ) {
		$photo = get_the_post_thumbnail_url( $post->ID, 'full' );

		$cards[] = [
			'name'  => get_the_title( $post ),
			'text'  => function_exists( 'get_field' ) ? (string) get_field( 'lawyer_role', $post->ID ) : '',
			'photo' => $photo ? $photo : 'team-photo-b',
			'more'  => 'Подробнее',
			'href'  => get_permalink( $post ),
		];
	}

	// Пока адвокатов меньше, чем мест в сетке, добираем карточками макета.
	$names = wp_list_pluck( $cards, 'name' );

	foreach ( (array) mp_data( 'team.members', [] ) as $member ) {
		if ( count( $cards ) >= 4 ) {
			break;
		}

		if ( in_array( $member['name'], $names, true ) ) {
			continue;
		}

		$cards[] = $member;
	}

	return $cards;
}

/**
 * Заводит адвоката из макета и прикрепляет его к услугам — один раз.
 */
function mp_seed_lawyers() {
	if ( get_option( 'mp_lawyer_version' ) === MP_LAWYER_VERSION ) {
		return;
	}

	$existing = get_posts(
		[
			'post_type'      => 'mp_lawyer',
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		]
	);

	$data = mp_lawyer_data();

	// В макете адвокат назывался «Имя Фамилия» — заменяем на настоящего.
	if ( $existing ) {
		$placeholder = get_posts(
			[
				'post_type'      => 'mp_lawyer',
				'posts_per_page' => 1,
				'post_status'    => 'any',
				'title'          => 'Имя Фамилия',
			]
		);

		if ( $placeholder ) {
			wp_update_post(
				[
					'ID'         => $placeholder[0]->ID,
					'post_title' => $data['name'],
				]
			);

			if ( function_exists( 'update_field' ) ) {
				update_field( 'lawyer_role', $data['role'], $placeholder[0]->ID );
			}
		}
	}

	if ( ! $existing ) {

		$id = wp_insert_post(
			[
				'post_type'   => 'mp_lawyer',
				'post_title'  => $data['name'],
				'post_status' => 'publish',
				'menu_order'  => 1,
			]
		);

		if ( $id && ! is_wp_error( $id ) ) {
			if ( function_exists( 'update_field' ) ) {
				foreach ( $data as $key => $value ) {
					if ( in_array( $key, [ 'name', 'photo', 'pubs' ], true ) ) {
						continue;
					}

					update_field( 'lawyer_' . $key, mp_content_to_acf( $value ), $id );
				}
			}

			$attachment = mp_sideload_theme_image( $data['photo'], $data['name'] );

			if ( $attachment ) {
				set_post_thumbnail( $id, $attachment );
			}

			$existing = [ $id ];
		}
	}


	update_option( 'mp_lawyer_version', MP_LAWYER_VERSION );
}
add_action( 'init', 'mp_seed_lawyers', 18 );

const MP_LAWYER_PEOPLE_VERSION = '3';

/**
 * Состав коллегии: порядок карточек и файлы фотографий в теме.
 *
 * Роли, кроме управляющего партнёра, заполняются в админке — в списке
 * от клиента их не было.
 *
 * @return array
 */
function mp_lawyer_people() {
	return [
		[
			'name'  => 'Беспалова Екатерина Сергеевна',
			'was'   => 'Екатерина Беспалова',
			'photo' => 'lawyer-bespalova',
			'role'  => 'Управляющий партнер МКА «M-PARTNERS», адвокат по уголовным делам',
			'force' => true, // вместо фотографии из макета ставим настоящую
		],
		[
			'name'  => 'Шелудков Евгений Юрьевич',
			'photo' => 'lawyer-sheludkov',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Задачин Андрей Андреевич',
			'photo' => 'lawyer-zadachin',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Павлов Александр Владимирович',
			'photo' => 'lawyer-pavlov',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Водолазский Роман Сергеевич',
			'photo' => 'lawyer-vodolazskiy',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Левин Владимир Дмитриевич',
			'photo' => 'lawyer-levin',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Мотовилов Андрей Николаевич',
			'photo' => 'lawyer-motovilov',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Поташов Александр Сергеевич',
			'photo' => 'lawyer-potashov',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Кльован Светлана Петровна',
			'photo' => 'lawyer-klovan',
			'role'  => 'Адвокат',
		],
		[
			'name'  => 'Майер Амира Ахматовна',
			'photo' => 'lawyer-majer',
			'role'  => 'Адвокат',
		],
	];
}

/**
 * Находит запись адвоката по имени — текущему или прежнему.
 *
 * @param array $person Запись из mp_lawyer_people().
 * @return int
 */
function mp_find_lawyer( $person ) {
	foreach ( array_filter( [ $person['name'], isset( $person['was'] ) ? $person['was'] : '' ] ) as $title ) {
		$found = get_posts(
			[
				'post_type'      => 'mp_lawyer',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'title'          => $title,
				'fields'         => 'ids',
			]
		);

		if ( $found ) {
			return (int) $found[0];
		}
	}

	return 0;
}

/**
 * Заводит адвокатов коллегии вместо карточек из макета — один раз.
 */
function mp_seed_lawyer_people() {
	if ( get_option( 'mp_lawyer_people_version' ) === MP_LAWYER_PEOPLE_VERSION ) {
		return;
	}

	foreach ( mp_lawyer_people() as $index => $person ) {
		$id    = mp_find_lawyer( $person );
		$order = $index + 1;

		if ( $id ) {
			wp_update_post(
				[
					'ID'         => $id,
					'post_title' => $person['name'],
					'post_name'  => mp_translit_slug( $person['name'] ),
					'menu_order' => $order,
				]
			);
		} else {
			$id = wp_insert_post(
				[
					'post_type'   => 'mp_lawyer',
					'post_title'  => $person['name'],
					'post_name'   => mp_translit_slug( $person['name'] ),
					'post_status' => 'publish',
					'menu_order'  => $order,
				]
			);
		}

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		// Биография из макета принадлежит одному адвокату — помечаем его,
		// чтобы у остальных карточка собиралась только из своих полей.
		if ( ! empty( $person['force'] ) ) {
			update_post_meta( $id, '_mp_lawyer_demo', '1' );
		}

		// Должность и контакты не перетираем: в админке их могли уточнить.
		if ( function_exists( 'update_field' ) ) {
			$fields = [
				'role'  => $person['role'],
				'email' => isset( $person['email'] ) ? $person['email'] : 'partners@mpartners.law',
				'phone' => isset( $person['phone'] ) ? $person['phone'] : '8 800 101 42 47',
			];

			foreach ( $fields as $key => $value ) {
				if ( ! get_field( 'lawyer_' . $key, $id ) ) {
					update_field( 'lawyer_' . $key, $value, $id );
				}
			}
		}

		// Повторная выкатка не должна плодить копии в медиабиблиотеке.
		$current = (int) get_post_thumbnail_id( $id );
		$same    = $current && false !== strpos( (string) wp_get_attachment_url( $current ), $person['photo'] );
		$replace = ! $current || ( ! empty( $person['force'] ) && ! $same );

		if ( $replace ) {
			$attachment = mp_sideload_theme_image( $person['photo'], $person['name'] );

			if ( $attachment ) {
				set_post_thumbnail( $id, $attachment );
			}
		}
	}

	update_option( 'mp_lawyer_people_version', MP_LAWYER_PEOPLE_VERSION );
}
add_action( 'init', 'mp_seed_lawyer_people', 19 );

/**
 * Запись адвоката, которой принадлежит биография из макета.
 *
 * @return int
 */
function mp_lawyer_demo_id() {
	static $id = null;

	if ( null !== $id ) {
		return $id;
	}

	$found = get_posts(
		[
			'post_type'      => 'mp_lawyer',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'meta_key'       => '_mp_lawyer_demo', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery
			'fields'         => 'ids',
		]
	);

	$id = $found ? (int) $found[0] : 0;

	return $id;
}

/**
 * Поля карточки адвоката — только свои, без биографии из макета.
 *
 * @param int $post_id Запись адвоката.
 * @return array
 */
function mp_lawyer_fields( $post_id ) {
	$data = mp_lawyer_data();

	$card = [
		'photo'    => '',
		'name'     => get_the_title( $post_id ),
		'role'     => '',
		'email'    => '',
		'phone'    => '',
		'card'     => [
			'label' => '',
			'href'  => '',
		],
		'registry' => [
			'label'  => isset( $data['registry']['label'] ) ? $data['registry']['label'] : 'Реестровый номер',
			'number' => '',
			'link'   => [
				'label' => '',
				'href'  => '',
			],
		],
		'spec'     => [
			'title' => '',
			'items' => [],
			'note'  => '',
		],
		'timeline' => [],
		'pubs'     => isset( $data['pubs'] ) ? $data['pubs'] : [],
	];

	if ( ! function_exists( 'get_field' ) ) {
		return $card;
	}

	foreach ( [ 'role', 'email', 'phone', 'card', 'registry', 'spec', 'timeline' ] as $key ) {
		$saved = get_field( 'lawyer_' . $key, $post_id );

		if ( null === $saved || '' === $saved || [] === $saved ) {
			continue;
		}

		$value = function_exists( 'mp_content_from_acf' ) ? mp_content_from_acf( $saved ) : $saved;

		$card[ $key ] = is_array( $card[ $key ] ) && is_array( $value )
			? array_replace_recursive( $card[ $key ], $value )
			: $value;
	}

	return $card;
}
