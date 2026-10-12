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
 * @return array Список ['name','text','photo','more','href'].
 */
function mp_lawyer_cards( $post_id = 0 ) {
	$ids = [];

	if ( $post_id && function_exists( 'get_field' ) ) {
		$ids = (array) [];
	}

	$posts = get_posts(
		[
			'post_type'      => 'mp_lawyer',
			'posts_per_page' => $ids ? count( $ids ) : 4,
			'post__in'       => $ids ? array_map( 'intval', $ids ) : [],
			'orderby'        => $ids ? 'post__in' : 'menu_order',
			'order'          => 'ASC',
		]
	);

	// Карточки из макета показываем, только пока в админке нет ни одного
	// адвоката — дальше везде выводим настоящие записи.
	if ( ! $posts ) {
		return (array) mp_data( 'team.members', [] );
	}

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
