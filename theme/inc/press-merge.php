<?php
/**
 * Объединение разделов: «СМИ о нас» уезжает в пресс-центр, выигранные дела
 * становятся самостоятельным разделом.
 *
 * В меню «Пресс-центр» и «СМИ о нас» читались как один и тот же раздел, а
 * дел в меню не было вовсе. Материалы при этом остаются на своих адресах —
 * меняются только рубрики.
 *
 * @package MPartners
 */

const MP_PRESS_MERGE_VERSION = '1';

/**
 * Все записи рубрики, включая дочерние.
 *
 * @param int $term_id Рубрика.
 * @return array ID записей.
 */
function mp_merge_term_posts( $term_id ) {
	return get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'cat'            => (int) $term_id,
		]
	);
}

/**
 * Разовый перенос рубрик.
 */
function mp_merge_press_sections() {
	if ( get_option( 'mp_press_merge_version' ) === MP_PRESS_MERGE_VERSION ) {
		return;
	}

	$press = get_term_by( 'slug', 'press', 'category' );

	if ( ! $press ) {
		return;
	}

	// Дела: рубрика внутри пресс-центра становится корневой рубрикой
	// раздела — иначе материалы показывались бы в двух списках сразу.
	$cases = get_term_by( 'slug', 'dela', 'category' );

	if ( ! $cases ) {
		$cases = get_term_by( 'name', 'Выигранные дела', 'category' );
	}

	if ( ! $cases ) {
		$created = wp_insert_term(
			'Выигранные дела',
			'category',
			[ 'slug' => 'dela' ]
		);

		$cases = is_wp_error( $created ) ? null : get_term( $created['term_id'], 'category' );
	}

	if ( $cases ) {
		wp_update_term(
			$cases->term_id,
			'category',
			[
				'parent' => 0,
				'slug'   => 'dela',
			]
		);

		foreach ( mp_merge_term_posts( $cases->term_id ) as $id ) {
			wp_remove_object_terms( $id, [ (int) $press->term_id ], 'category' );
		}
	}

	// СМИ о нас: материалы переезжают в пресс-центр, рубрика с публикациями
	// уже там, пустые макетные рубрики убираем.
	$media = get_term_by( 'slug', 'media', 'category' );

	if ( $media ) {
		foreach ( mp_merge_term_posts( $media->term_id ) as $id ) {
			wp_set_post_terms( $id, [ (int) $press->term_id ], 'category', true );
			wp_remove_object_terms( $id, [ (int) $media->term_id ], 'category' );
		}

		$children = get_terms(
			[
				'taxonomy'   => 'category',
				'parent'     => (int) $media->term_id,
				'hide_empty' => false,
			]
		);

		foreach ( (array) $children as $child ) {
			if ( is_wp_error( $child ) ) {
				continue;
			}

			if ( (int) $child->count ) {
				wp_update_term( $child->term_id, 'category', [ 'parent' => (int) $press->term_id ] );
			} else {
				wp_delete_term( $child->term_id, 'category' );
			}
		}

		wp_delete_term( $media->term_id, 'category' );
	}

	// Страница раздела больше не нужна: её список и перечень публикаций
	// теперь на странице пресс-центра, а старый адрес ведёт туда же.
	$page = get_page_by_path( 'media' );

	if ( $page && 'trash' !== $page->post_status ) {
		wp_trash_post( $page->ID );
	}

	clean_term_cache( [], 'category' );

	update_option( 'mp_press_merge_version', MP_PRESS_MERGE_VERSION );
}
add_action( 'init', 'mp_merge_press_sections', 18 );

const MP_EVENTS_RUBRICS_VERSION = '1';

/**
 * Семинары и вебинары лежали в «Конференциях»: раскладываем их по своим
 * рубрикам, иначе два таба раздела всегда пустые.
 */
function mp_fix_events_rubrics() {
	if ( get_option( 'mp_events_rubrics_version' ) === MP_EVENTS_RUBRICS_VERSION ) {
		return;
	}

	$root = get_term_by( 'slug', 'events', 'category' );

	if ( ! $root ) {
		return;
	}

	$rubrics = [
		'вебинар' => get_term_by( 'slug', 'vebinary', 'category' ),
		'семинар' => get_term_by( 'slug', 'seminary', 'category' ),
	];

	$conf = get_term_by( 'slug', 'konferenczii', 'category' );

	foreach ( mp_merge_term_posts( $root->term_id ) as $id ) {
		$text = mb_strtolower( get_post_field( 'post_title', $id ) . ' ' . get_post_field( 'post_content', $id ) );

		foreach ( $rubrics as $word => $term ) {
			if ( ! $term || false === mb_strpos( $text, $word ) ) {
				continue;
			}

			wp_set_post_terms( $id, [ (int) $term->term_id ], 'category', true );

			if ( $conf ) {
				wp_remove_object_terms( $id, [ (int) $conf->term_id ], 'category' );
			}

			break;
		}
	}

	update_option( 'mp_events_rubrics_version', MP_EVENTS_RUBRICS_VERSION );
}
add_action( 'init', 'mp_fix_events_rubrics', 19 );

/**
 * Старый адрес «СМИ о нас» ведёт в пресс-центр.
 */
function mp_media_redirect() {
	if ( ! is_404() ) {
		return;
	}

	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';

	if ( 'media' !== trim( (string) $path, '/' ) ) {
		return;
	}

	$press = get_page_by_path( 'press' );

	if ( ! $press ) {
		return;
	}

	wp_safe_redirect( get_permalink( $press ), 301 );
	exit;
}
add_action( 'template_redirect', 'mp_media_redirect' );
