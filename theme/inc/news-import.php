<?php
/**
 * Перенос новостей со старого сайта коллегии.
 *
 * Материалы лежат в `inc/news-content.php`, сюда они приезжают записями
 * WordPress: по пять штук за запрос, чтобы не ронять хостинг на картинках.
 *
 * @package MPartners
 */

const MP_NEWS_VERSION = '1';
const MP_NEWS_BATCH   = 5;

/**
 * Рубрика раздела по названию: Новости, Конференции и т.д.
 *
 * @param string $name Название рубрики.
 * @param int    $root Родительская рубрика раздела.
 * @return int
 */
function mp_news_rubric( $name, $root ) {
	$term = get_term_by( 'name', $name, 'category' );

	if ( $term ) {
		return (int) $term->term_id;
	}

	$created = wp_insert_term( $name, 'category', [ 'parent' => $root ] );

	return is_wp_error( $created ) ? 0 : (int) $created['term_id'];
}

/**
 * Запись с таким заголовком уже есть.
 *
 * @param string $title Заголовок.
 * @return int
 */
function mp_news_existing( $title ) {
	$found = get_posts(
		[
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'title'          => $title,
			'fields'         => 'ids',
		]
	);

	return $found ? (int) $found[0] : 0;
}

/**
 * Убирает материалы из макета: они были заглушкой, пока не было настоящих.
 */
function mp_news_drop_demo() {
	if ( ! function_exists( 'mp_press' ) ) {
		return;
	}

	foreach ( [ 'press', 'events', 'media' ] as $section ) {
		$data = mp_press( $section );

		foreach ( (array) $data['items'] as $item ) {
			if ( empty( $item['title'] ) ) {
				continue;
			}

			$id = mp_news_existing( $item['title'] );

			if ( $id ) {
				wp_trash_post( $id );
			}
		}
	}
}

/**
 * Переносит материалы порциями.
 */
function mp_import_news() {
	if ( get_option( 'mp_news_version' ) === MP_NEWS_VERSION ) {
		return;
	}

	if ( ! function_exists( 'mp_news_content' ) || ! function_exists( 'mp_section_term' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$done  = (array) get_option( 'mp_news_done', [] );
	$items = mp_news_content();
	$count = 0;

	foreach ( $items as $index => $item ) {
		if ( in_array( (string) $index, $done, true ) ) {
			continue;
		}

		if ( $count >= MP_NEWS_BATCH ) {
			break; // остальное — следующим запросом, прогресс сохраняем ниже
		}

		++$count;
		$done[] = (string) $index;

		if ( mp_news_existing( $item['title'] ) ) {
			continue;
		}

		$args = [
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_content' => $item['content'],
			'post_excerpt' => $item['excerpt'],
		];

		if ( ! empty( $item['date'] ) ) {
			$args['post_date']     = $item['date'];
			$args['post_date_gmt'] = get_gmt_from_date( $item['date'] );
		}

		$id = wp_insert_post( $args );

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		$root     = mp_section_term( $item['section'] );
		$category = [];

		if ( $root ) {
			$category[] = (int) $root->term_id;

			$rubric = mp_news_rubric( $item['rubric'], (int) $root->term_id );

			if ( $rubric ) {
				$category[] = $rubric;
			}
		}

		if ( $category ) {
			wp_set_post_categories( $id, $category );
		}

		if ( ! empty( $item['source'] ) ) {
			update_post_meta( $id, '_mp_source_url', esc_url_raw( $item['source'] ) );
		}

		if ( ! empty( $item['image'] ) ) {
			$attachment = media_sideload_image( $item['image'], $id, $item['title'], 'id' );

			if ( ! is_wp_error( $attachment ) ) {
				set_post_thumbnail( $id, (int) $attachment );
			}
		}
	}

	update_option( 'mp_news_done', $done );

	if ( count( $done ) >= count( $items ) ) {
		mp_news_drop_demo();
		update_option( 'mp_news_version', MP_NEWS_VERSION );
	}
}
add_action( 'init', 'mp_import_news', 25 );

const MP_CASES_VERSION = '1';

/**
 * Переносит выигранные дела со страницы «Проекты» старого сайта.
 *
 * Они живут в пресс-центре отдельной рубрикой, чтобы их можно было
 * отфильтровать и при желании вынести на свою страницу.
 */
function mp_import_cases() {
	if ( get_option( 'mp_cases_version' ) === MP_CASES_VERSION ) {
		return;
	}

	if ( ! function_exists( 'mp_cases_content' ) || ! function_exists( 'mp_section_term' ) ) {
		return;
	}

	$root = mp_section_term( 'press' );

	if ( ! $root ) {
		return;
	}

	$rubric = mp_news_rubric( 'Выигранные дела', (int) $root->term_id );
	$done   = (array) get_option( 'mp_cases_done', [] );
	$items  = mp_cases_content();
	$count  = 0;

	foreach ( $items as $index => $item ) {
		if ( in_array( (string) $index, $done, true ) ) {
			continue;
		}

		if ( $count >= 8 ) {
			break;
		}

		++$count;
		$done[] = (string) $index;

		if ( mp_news_existing( $item['title'] ) ) {
			continue;
		}

		$args = [
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_content' => $item['content'],
			'post_excerpt' => $item['excerpt'],
		];

		if ( ! empty( $item['date'] ) ) {
			$args['post_date']     = $item['date'];
			$args['post_date_gmt'] = get_gmt_from_date( $item['date'] );
		}

		$id = wp_insert_post( $args );

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		$categories = [ (int) $root->term_id ];

		if ( $rubric ) {
			$categories[] = $rubric;
		}

		wp_set_post_categories( $id, $categories );
	}

	update_option( 'mp_cases_done', $done );

	if ( count( $done ) >= count( $items ) ) {
		update_option( 'mp_cases_version', MP_CASES_VERSION );
	}
}
add_action( 'init', 'mp_import_cases', 26 );

const MP_PUBS_VERSION = '1';

/**
 * Публикации адвокатов в СМИ: карточка со ссылкой на материал издания.
 */
function mp_import_pubs() {
	if ( get_option( 'mp_pubs_version' ) === MP_PUBS_VERSION ) {
		return;
	}

	if ( ! function_exists( 'mp_pubs_content' ) || ! function_exists( 'mp_section_term' ) ) {
		return;
	}

	$root = mp_section_term( 'media' );

	if ( ! $root ) {
		return;
	}

	$rubric = mp_news_rubric( 'Публикации в СМИ', (int) $root->term_id );
	$done   = (array) get_option( 'mp_pubs_done', [] );
	$items  = mp_pubs_content();
	$count  = 0;

	foreach ( $items as $index => $item ) {
		if ( in_array( (string) $index, $done, true ) ) {
			continue;
		}

		if ( $count >= 8 ) {
			break;
		}

		++$count;
		$done[] = (string) $index;

		if ( mp_news_existing( $item['title'] ) ) {
			continue;
		}

		$content = sprintf(
			'<p>Материал опубликован в издании «%s».</p>',
			esc_html( $item['outlet'] )
		);

		$args = [
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_content' => $content,
			'post_excerpt' => sprintf( 'Публикация в издании «%s»', $item['outlet'] ),
		];

		if ( ! empty( $item['date'] ) ) {
			$date                  = $item['date'] . ' 12:00:00';
			$args['post_date']     = $date;
			$args['post_date_gmt'] = get_gmt_from_date( $date );
		}

		$id = wp_insert_post( $args );

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		$categories = [ (int) $root->term_id ];

		if ( $rubric ) {
			$categories[] = $rubric;
		}

		wp_set_post_categories( $id, $categories );
		update_post_meta( $id, '_mp_source_url', esc_url_raw( $item['url'] ) );
	}

	update_option( 'mp_pubs_done', $done );

	if ( count( $done ) >= count( $items ) ) {
		update_option( 'mp_pubs_version', MP_PUBS_VERSION );
	}
}
add_action( 'init', 'mp_import_pubs', 27 );
