<?php
/**
 * Практики — отдельные записи.
 *
 * Карточки на странице «Практики» берутся из записей этого типа: заголовок,
 * текст карточки и изображение записи. Порядок задаётся полем «Порядок»
 * (атрибуты страницы). Пока записей нет, показываются карточки из макета.
 *
 * @package MPartners
 */

const MP_PRACTICE_VERSION = '1';
const MP_PRACTICE_TYPE    = 'mp_practice';

/**
 * Регистрация типа записи.
 */
function mp_register_practice_type() {
	register_post_type(
		MP_PRACTICE_TYPE,
		[
			'labels'       => [
				'name'               => 'Практики',
				'singular_name'      => 'Практика',
				'add_new'            => 'Добавить практику',
				'add_new_item'       => 'Новая практика',
				'edit_item'          => 'Редактировать практику',
				'new_item'           => 'Новая практика',
				'view_item'          => 'Смотреть практику',
				'search_items'       => 'Искать практики',
				'not_found'          => 'Практики не найдены',
				'not_found_in_trash' => 'В корзине практик нет',
				'menu_name'          => 'Практики',
			],
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'menu_position' => 22,
			'supports'     => [ 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt' ],
			'rewrite'      => [ 'slug' => 'practice' ],
		]
	);
}
add_action( 'init', 'mp_register_practice_type' );

/**
 * Поле «Текст карточки» у практики.
 */
function mp_register_practice_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'      => 'group_mp_practice_card',
			'title'    => 'Карточка практики',
			'fields'   => [
				[
					'key'          => 'field_mp_practice_text',
					'label'        => 'Текст карточки',
					'name'         => 'practice_text',
					'type'         => 'textarea',
					'rows'         => 5,
					'instructions' => 'Короткое описание под заголовком в сетке практик.',
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => MP_PRACTICE_TYPE,
					],
				],
			],
			'position' => 'acf_after_title',
		]
	);
}
add_action( 'acf/init', 'mp_register_practice_fields' );

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
 * Создаёт записи практик из карточек макета — один раз.
 */
function mp_seed_practices() {
	if ( ! is_admin() || get_option( 'mp_practice_version' ) === MP_PRACTICE_VERSION ) {
		return;
	}

	$existing = get_posts(
		[
			'post_type'      => MP_PRACTICE_TYPE,
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		]
	);

	if ( $existing ) {
		update_option( 'mp_practice_version', MP_PRACTICE_VERSION );

		return;
	}

	$cards = mp_practices_data()['cards'];

	foreach ( array_values( $cards ) as $index => $card ) {
		$id = wp_insert_post(
			[
				'post_type'    => MP_PRACTICE_TYPE,
				'post_title'   => $card['title'],
				'post_status'  => 'publish',
				'menu_order'   => $index + 1,
			]
		);

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		if ( function_exists( 'update_field' ) ) {
			update_field( 'practice_text', $card['text'], $id );
		}

		$attachment = mp_sideload_theme_image( $card['image'], $card['title'] );

		if ( $attachment ) {
			set_post_thumbnail( $id, $attachment );
		}
	}

	update_option( 'mp_practice_version', MP_PRACTICE_VERSION );
}
add_action( 'admin_init', 'mp_seed_practices', 20 );

/**
 * Карточки для страницы «Практики»: записи, а если их нет — макет.
 *
 * @return array
 */
function mp_practice_cards() {
	$posts = get_posts(
		[
			'post_type'      => MP_PRACTICE_TYPE,
			'posts_per_page' => -1,
			'orderby'        => [
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			],
		]
	);

	if ( ! $posts ) {
		return (array) mp_practice( 'cards', [] );
	}

	$cards = [];

	foreach ( $posts as $post ) {
		$image = get_the_post_thumbnail_url( $post->ID, 'full' );

		$cards[] = [
			'title' => get_the_title( $post ),
			'text'  => function_exists( 'get_field' ) ? (string) get_field( 'practice_text', $post->ID ) : '',
			'image' => $image ? $image : '',
			'href'  => get_permalink( $post ),
		];
	}

	return $cards;
}
