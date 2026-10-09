<?php
/**
 * Редактирование контента сайта через ACF.
 *
 * Весь текст и все карточки живут в массивах `inc/*-data.php`. Этот файл
 * превращает их в поля ACF на страницах опций: строка — текстовое поле,
 * список — репитер, вложенный массив — группа. Поэтому новые блоки в данных
 * появляются в админке сами, без ручного описания полей.
 *
 * @package MPartners
 */

const MP_CONTENT_VERSION = '8';

/**
 * Редактируемые разделы контента.
 *
 * @return array
 */
function mp_content_groups() {
	return [
		'common'    => [
			'title'    => 'Общие настройки',
			'data'     => 'mp_common_data',
			'location' => 'options',
		],
		'home'      => [
			'title'    => 'Контент главной',
			'data'     => 'mp_home_page_data',
			'location' => 'front_page',
		],
		'practices' => [
			'title'     => 'Контент страницы «Практики»',
			'data'      => 'mp_practices_data',
			'templates' => [ 'page-practices.php' ],
			// Карточки практик живут в записях «Практики», а не здесь.
			'skip'      => [ 'cards' ],
		],
		'case'      => [
			'title'      => 'Контент страницы услуги',
			'data'       => 'mp_case_data',
			'post_types' => [ 'mp_practice' ],
			// Заголовок услуги — у самой записи.
			'skip'       => [ 'hero' ],
		],
		'lawyer'    => [
			'title'      => 'Карточка адвоката',
			'data'       => 'mp_lawyer_data',
			'post_types' => [ 'mp_lawyer' ],
			// Имя — заголовок записи, фото — изображение записи.
			'skip'       => [ 'name', 'photo', 'pubs' ],
		],
		'press'     => [
			'title'     => 'Контент раздела',
			'data'      => 'mp_press_section_data',
			'templates' => [ 'page-press.php' ],
		],
		'events'    => [
			'title'     => 'Контент раздела',
			'data'      => 'mp_events_section_data',
			'templates' => [ 'page-events.php' ],
		],
		'media'     => [
			'title'     => 'Контент раздела',
			'data'      => 'mp_media_section_data',
			'templates' => [ 'page-media.php' ],
		],
		'article'   => [
			'title'      => 'Контент материала',
			'data'       => 'mp_article_data',
			'post_types' => [ 'post' ],
			// Заголовок и дата — у самой записи.
			'skip'       => [ 'title', 'date' ],
		],
	];
}

/**
 * Страница, к которой привязан раздел контента.
 *
 * @param string $group Ключ раздела.
 * @return string|int 'option' или ID страницы (0, если страницы нет).
 */
function mp_content_target( $group ) {
	static $cache = [];

	if ( isset( $cache[ $group ] ) ) {
		return $cache[ $group ];
	}

	$groups = mp_content_groups();
	$meta   = isset( $groups[ $group ] ) ? $groups[ $group ] : [];

	if ( isset( $meta['location'] ) && 'options' === $meta['location'] ) {
		$cache[ $group ] = 'option';

		return 'option';
	}

	if ( isset( $meta['location'] ) && 'front_page' === $meta['location'] ) {
		$cache[ $group ] = (int) get_option( 'page_on_front' );

		return $cache[ $group ];
	}

	$current = function_exists( 'get_queried_object_id' ) ? (int) get_queried_object_id() : 0;

	if ( isset( $meta['post_types'] ) ) {
		$cache[ $group ] = ( $current && in_array( get_post_type( $current ), $meta['post_types'], true ) ) ? $current : 0;

		return $cache[ $group ];
	}

	$templates = isset( $meta['templates'] ) ? $meta['templates'] : [];

	// На самой странице берём её же значения — у каждого материала свои.
	if ( $current && in_array( (string) get_page_template_slug( $current ), $templates, true ) ) {
		$cache[ $group ] = $current;

		return $current;
	}

	$pages = get_posts(
		[
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'     => '_wp_page_template',
					'value'   => $templates,
					'compare' => 'IN',
				],
			],
		]
	);

	$cache[ $group ] = $pages ? (int) $pages[0] : 0;

	return $cache[ $group ];
}

/**
 * Все страницы, к которым привязан раздел контента.
 *
 * @param string $group Ключ раздела.
 * @return array Список 'option' или ID страниц.
 */
function mp_content_targets( $group ) {
	$groups = mp_content_groups();
	$meta   = isset( $groups[ $group ] ) ? $groups[ $group ] : [];

	if ( isset( $meta['location'] ) || isset( $meta['post_types'] ) ) {
		$target = mp_content_target( $group );

		return $target ? [ $target ] : [];
	}

	return get_posts(
		[
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'     => '_wp_page_template',
					'value'   => isset( $meta['templates'] ) ? $meta['templates'] : [],
					'compare' => 'IN',
				],
			],
		]
	);
}

/**
 * Человеческие названия для известных ключей.
 *
 * @param string $key Array key.
 * @return string
 */
function mp_acf_label( $key ) {
	$known = [
		'title'       => 'Заголовок',
		'subtitle'    => 'Подзаголовок',
		'text'        => 'Текст',
		'excerpt'     => 'Описание',
		'label'       => 'Подпись',
		'note'        => 'Примечание',
		'image'       => 'Изображение',
		'photo'       => 'Фото',
		'href'        => 'Ссылка',
		'date'        => 'Дата',
		'name'        => 'Имя',
		'role'        => 'Должность',
		'email'       => 'E-mail',
		'phone'       => 'Телефон',
		'category'    => 'Рубрика',
		'items'       => 'Элементы',
		'cards'       => 'Карточки',
		'members'     => 'Участники',
		'filters'     => 'Рубрики',
		'more'        => 'Кнопка «ещё»',
		'cta'         => 'Кнопка',
		'submit'      => 'Кнопка отправки',
		'consent'     => 'Согласие',
		'fields'      => 'Поля формы',
		'form'        => 'Форма',
		'hero'        => 'Первый экран',
		'benefits'    => 'Преимущества',
		'blocks'      => 'Блоки',
		'result'      => 'Результат',
		'timeline'    => 'Биография по годам',
		'pubs'        => 'Публикации',
		'spec'        => 'Специализация',
		'registry'    => 'Реестр',
		'sections'    => 'Разделы текста',
		'faq'         => 'Вопросы и ответы',
		'author'      => 'Автор',
		'related'     => 'Блок «Читайте также»',
		'strips'      => 'Полоса с названием',
		'lead'        => 'Широкая карточка',
		'year'        => 'Год',
		'type'        => 'Тип блока',
		'value'       => 'Значение',
		'contact'     => 'Ник или ссылка',
		'messenger'   => 'Мессенджер',
		'channel'     => 'Способ связи',
		'channels'    => 'Способы связи',
		'socials'     => 'Соцсети',
		'phones'      => 'Телефоны',
		'icon'        => 'Иконка',
		'all'         => 'Кнопка «все»',
		'badge'       => 'Метка',
		'lead'        => 'Широкая карточка',
		'reading'     => 'Время чтения',
		'toc'         => 'Оглавление',
		'contacts'    => 'Контакты',
		'number'      => 'Номер',
		'link'        => 'Ссылка',
		'card'        => 'Визитка',
		'button'      => 'Кнопка',
		'title_wide'  => 'Заголовок (широкий вариант)',
		'spec_title'  => 'Заголовок специализации',
		'size'        => 'Размер',
		'theme'       => 'Оформление',
		'rows_desktop' => 'Ряды (десктоп)',
		'order_tablet' => 'Порядок (планшет)',
		'order_mobile' => 'Порядок (мобилка)',
	];

	if ( isset( $known[ $key ] ) ) {
		return $known[ $key ];
	}

	return ucfirst( str_replace( '_', ' ', (string) $key ) );
}

/**
 * Список ли это (0,1,2…), а не словарь.
 *
 * @param array $value Array to test.
 * @return bool
 */
function mp_is_list( array $value ) {
	return [] === $value || array_keys( $value ) === range( 0, count( $value ) - 1 );
}

/**
 * Описание одного поля ACF по значению из данных.
 *
 * @param string $path  Уникальный путь (идёт в ключ поля).
 * @param string $name  Имя поля для ACF.
 * @param mixed  $value Значение по умолчанию.
 * @param string $label Заголовок поля; по умолчанию — из имени.
 * @return array
 */
function mp_acf_field( $path, $name, $value, $label = '' ) {
	$field = [
		'key'   => 'field_mp_' . substr( md5( $path ), 0, 18 ),
		'label' => '' !== $label ? $label : mp_acf_label( $name ),
		'name'  => $name,
	];

	if ( is_bool( $value ) ) {
		return $field + [
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => $value ? 1 : 0,
		];
	}

	if ( is_array( $value ) ) {
		if ( mp_is_list( $value ) ) {
			$shape = [];

			foreach ( $value as $item ) {
				if ( is_array( $item ) ) {
					$shape += $item;
				}
			}

			if ( $shape ) {
				$sub = [];

				foreach ( $shape as $key => $sample ) {
					$sub[] = mp_acf_field( $path . '.' . $key, $key, $sample );
				}
			} else {
				$sub = [ mp_acf_field( $path . '.value', 'value', reset( $value ) ) ];
			}

			return $field + [
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить',
				'sub_fields'   => $sub,
			];
		}

		$sub = [];

		foreach ( $value as $key => $item ) {
			$sub[] = mp_acf_field( $path . '.' . $key, $key, $item );
		}

		return $field + [
			'type'       => 'group',
			'layout'     => 'block',
			'sub_fields' => $sub,
		];
	}

	if ( in_array( $name, [ 'image', 'photo' ], true ) ) {
		return $field + [
			'type'          => 'image',
			'return_format' => 'url',
			'preview_size'  => 'medium',
			'instructions'  => sprintf( 'Если оставить пустым, останется картинка из макета (%s).', (string) $value ),
		];
	}

	if ( is_bool( $value ) ) {
		return $field + [
			'type' => 'true_false',
			'ui'   => 1,
		];
	}

	$text = (string) $value;
	$long = mb_strlen( $text ) > 90 || false !== strpos( $text, "\n" );

	// Значение из макета записывается при первичном заполнении, а
	// `default_value` здесь опасен: ACF подставлял бы его в каждую пустую
	// строку репитера — так «широкими» становились все карточки подряд.
	return $field + [
		'type' => $long ? 'textarea' : 'text',
		'rows' => $long ? 4 : null,
	];
}

/**
 * Ищет строку макета, соответствующую сохранённой: сначала по смысловому
 * ключу (номер, заголовок), иначе по позиции в списке.
 *
 * @param array $default Default list.
 * @param mixed $row     Saved row.
 * @param int   $index   Row position.
 * @return array|null
 */
function mp_default_row( $default, $row, $index ) {
	if ( ! is_array( $row ) ) {
		return null;
	}

	foreach ( [ 'number', 'title', 'label', 'name' ] as $key ) {
		if ( ! isset( $row[ $key ] ) || '' === $row[ $key ] ) {
			continue;
		}

		foreach ( $default as $item ) {
			if ( is_array( $item ) && isset( $item[ $key ] ) && $item[ $key ] === $row[ $key ] ) {
				return $item;
			}
		}
	}

	return isset( $default[ $index ] ) && is_array( $default[ $index ] ) ? $default[ $index ] : null;
}

/**
 * Готовит значение к записи в ACF: простой список строк репитер хранит
 * строками `['value' => …]`.
 *
 * @param mixed $value Value from the data files.
 * @return mixed
 */
function mp_content_to_acf( $value ) {
	if ( ! is_array( $value ) || [] === $value ) {
		return $value;
	}

	if ( mp_is_list( $value ) ) {
		// ACF пропускает подполя, которых нет в строке, и оставляет в них
		// прежние значения — поэтому каждую строку дополняем всеми ключами.
		$shape = [];

		foreach ( $value as $item ) {
			if ( is_array( $item ) && ! mp_is_list( $item ) ) {
				foreach ( $item as $key => $sample ) {
					if ( ! array_key_exists( $key, $shape ) ) {
						$shape[ $key ] = is_bool( $sample ) ? false : '';
					}
				}
			}
		}

		return array_map(
			function ( $item ) use ( $shape ) {
				if ( ! is_array( $item ) ) {
					return [ 'value' => $item ];
				}

				return mp_content_to_acf( mp_is_list( $item ) ? $item : array_merge( $shape, $item ) );
			},
			$value
		);
	}

	foreach ( $value as $key => $item ) {
		// Поле «Изображение» хранит ID вложения — имя файла из макета туда
		// писать нельзя, иначе ACF вернёт пустоту вместо картинки.
		if ( mp_is_image_key( $key ) && ! is_array( $item ) ) {
			$value[ $key ] = '';

			continue;
		}

		$value[ $key ] = mp_content_to_acf( $item );
	}

	return $value;
}

/**
 * Поле с таким именем выводится как «Изображение».
 *
 * @param string $key Field key.
 * @return bool
 */
function mp_is_image_key( $key ) {
	return in_array( (string) $key, [ 'image', 'photo' ], true );
}

/**
 * Обратное преобразование: строки репитера снова становятся простым списком.
 *
 * @param mixed $value Value from ACF.
 * @return mixed
 */
function mp_content_from_acf( $value ) {
	if ( ! is_array( $value ) || [] === $value ) {
		return $value;
	}

	if ( mp_is_list( $value ) ) {
		$plain = true;

		foreach ( $value as $item ) {
			if ( ! is_array( $item ) || [ 'value' ] !== array_keys( $item ) ) {
				$plain = false;
				break;
			}
		}

		if ( $plain ) {
			return array_column( $value, 'value' );
		}

		return array_map( 'mp_content_from_acf', $value );
	}

	foreach ( $value as $key => $item ) {
		$value[ $key ] = mp_content_from_acf( $item );
	}

	return $value;
}

/**
 * Страницы опций и поля для каждого раздела контента.
 */
function mp_register_content_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	foreach ( mp_content_groups() as $group => $meta ) {
		if ( ! function_exists( $meta['data'] ) ) {
			continue;
		}

		$fields = [];
		$skip   = isset( $meta['skip'] ) ? $meta['skip'] : [];

		foreach ( call_user_func( $meta['data'] ) as $key => $value ) {
			if ( in_array( $key, $skip, true ) ) {
				continue;
			}

			$fields[] = mp_acf_field( $group . '.' . $key, $group . '_' . $key, $value, mp_acf_label( $key ) );
		}

		if ( ! $fields ) {
			continue;
		}

		$location = [];

		if ( isset( $meta['location'] ) && 'options' === $meta['location'] ) {
			if ( function_exists( 'acf_add_options_page' ) ) {
				acf_add_options_page(
					[
						'page_title' => $meta['title'],
						'menu_title' => $meta['title'],
						'menu_slug'  => 'mp-settings',
						'icon_url'   => 'dashicons-admin-generic',
						'position'   => 23,
					]
				);
			}

			$location[] = [
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'mp-settings',
				],
			];
		} elseif ( isset( $meta['location'] ) && 'front_page' === $meta['location'] ) {
			$location[] = [
				[
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				],
			];
		} elseif ( isset( $meta['post_types'] ) ) {
			foreach ( $meta['post_types'] as $type ) {
				$location[] = [
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => $type,
					],
				];
			}
		} else {
			foreach ( $meta['templates'] as $template ) {
				$location[] = [
					[
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => $template,
					],
				];
			}
		}

		acf_add_local_field_group(
			[
				'key'      => 'group_mp_' . $group,
				'title'    => $meta['title'],
				'fields'   => $fields,
				'location' => $location,
				'style'    => 'default',
			]
		);
	}
}
add_action( 'acf/init', 'mp_register_content_fields' );

/**
 * Первый раз заполняет поля значениями из макета, чтобы редактор открывал
 * готовый контент, а не пустые поля.
 */
function mp_seed_content_fields() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	if ( get_option( 'mp_content_version' ) === MP_CONTENT_VERSION ) {
		return;
	}

	$pending = false;

	foreach ( mp_content_groups() as $group => $meta ) {
		if ( ! function_exists( $meta['data'] ) ) {
			continue;
		}

		$targets = mp_content_targets( $group );
		$skip    = isset( $meta['skip'] ) ? $meta['skip'] : [];

		if ( ! $targets ) {
			// Страницы с таким шаблоном ещё нет — попробуем в следующий раз.
			$pending = true;

			continue;
		}

		foreach ( $targets as $target ) {
			foreach ( call_user_func( $meta['data'] ) as $key => $value ) {
				if ( in_array( $key, $skip, true ) ) {
					continue;
				}

				$name    = $group . '_' . $key;
				$current = get_field( $name, $target );

				// Пустой репитер ACF отдаёт как false, пустая группа — как
				// массив с пустыми ключами: проверяем все «пустые» варианты.
				if ( null === $current || '' === $current || false === $current || [] === $current ) {
					update_field( $name, mp_content_to_acf( $value ), $target );
				}
			}
		}
	}

	if ( ! $pending ) {
		update_option( 'mp_content_version', MP_CONTENT_VERSION );
	}
}
add_action( 'acf/init', 'mp_seed_content_fields', 20 );

/**
 * Рекурсивно накладывает сохранённые значения на значения из макета.
 *
 * @param mixed $default Defaults from the data files.
 * @param mixed $saved   Values from ACF.
 * @return mixed
 */
function mp_merge_content( $default, $saved ) {
	if ( null === $saved || '' === $saved || [] === $saved || false === $saved ) {
		return $default;
	}

	if ( ! is_array( $default ) || ! is_array( $saved ) ) {
		return $saved;
	}

	// Список целиком задаётся сохранённым — иначе нельзя убрать карточку, —
	// но каждую строку дополняем данными из макета: пустое поле строки
	// (например незагруженная картинка) должно остаться макетным.
	if ( mp_is_list( $default ) ) {
		$result = [];

		foreach ( $saved as $index => $row ) {
			$base     = mp_default_row( $default, $row, $index );
			$result[] = null === $base ? $row : mp_merge_content( $base, $row );
		}

		return $result;
	}

	$result = $default;

	foreach ( $saved as $key => $value ) {
		$result[ $key ] = array_key_exists( $key, $default )
			? mp_merge_content( $default[ $key ], $value )
			: $value;
	}

	return $result;
}

/**
 * Контент раздела: значения из админки поверх значений из макета.
 *
 * @param string $group Ключ раздела.
 * @return array
 */
function mp_content( $group ) {
	static $cache = [];

	if ( isset( $cache[ $group ] ) ) {
		return $cache[ $group ];
	}

	$groups = mp_content_groups();

	if ( ! isset( $groups[ $group ] ) || ! function_exists( $groups[ $group ]['data'] ) ) {
		return [];
	}

	$data = call_user_func( $groups[ $group ]['data'] );

	$target = mp_content_target( $group );

	if ( $target && function_exists( 'get_field' ) ) {
		foreach ( $data as $key => $default ) {
			$saved = get_field( $group . '_' . $key, $target );

			if ( null !== $saved ) {
				$data[ $key ] = mp_merge_content( $default, mp_content_from_acf( $saved ) );
			}
		}
	}

	$cache[ $group ] = $data;

	return $data;
}

/**
 * Значение из контента раздела по точечному пути.
 *
 * @param string $group    Ключ раздела.
 * @param string $path     Точечный путь.
 * @param mixed  $fallback Значение, если пути нет.
 * @return mixed
 */
function mp_content_get( $group, $path, $fallback = '' ) {
	$value = mp_content( $group );

	if ( '' === $path ) {
		return $value;
	}

	foreach ( explode( '.', $path ) as $key ) {
		if ( ! is_array( $value ) || ! array_key_exists( $key, $value ) ) {
			return $fallback;
		}

		$value = $value[ $key ];
	}

	return $value;
}

/**
 * Шаблоны страниц, у которых весь контент — в полях.
 *
 * @return array
 */
function mp_content_templates() {
	$templates = [];

	foreach ( mp_content_groups() as $meta ) {
		if ( isset( $meta['templates'] ) ) {
			$templates = array_merge( $templates, $meta['templates'] );
		}
	}

	return $templates;
}

/**
 * На страницах с нашими шаблонами блочный редактор не нужен: содержимое
 * собирается из полей, а блоки только путают.
 *
 * @param bool   $enabled   Включён ли редактор блоков.
 * @param object $post      Запись.
 * @return bool
 */
function mp_disable_block_editor( $enabled, $post ) {
	if ( ! $post || 'page' !== $post->post_type ) {
		return $enabled;
	}

	if ( (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
		return false;
	}

	return in_array( (string) get_page_template_slug( $post->ID ), mp_content_templates(), true ) ? false : $enabled;
}
add_filter( 'use_block_editor_for_post', 'mp_disable_block_editor', 10, 2 );

/**
 * Текстовый редактор на таких страницах тоже лишний — шаблон его не выводит.
 */
function mp_hide_page_editor() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->id ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( ! $post_id ) {
		return;
	}

	$is_front = (int) get_option( 'page_on_front' ) === $post_id;

	if ( $is_front || in_array( (string) get_page_template_slug( $post_id ), mp_content_templates(), true ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
}
add_action( 'current_screen', 'mp_hide_page_editor' );

/**
 * Разовые замены в уже сохранённом контенте: данные из файлов темы правятся
 * свободно, а то, что лежит в полях админки, нужно обновлять отдельно.
 */
const MP_REPLACE_VERSION = '4';

/**
 * Что на что меняем.
 *
 * @return array
 */
function mp_content_replacements() {
	return [
		'8 800 350 40 15' => '8 800 101 42 47',
		'tel:88003504015' => 'tel:88001014247',
		'info@m-partners.ru' => 'bespalova@mpartners.law',
		'Заполните форму и мы поможем с решением вашей задачи и предоставим скидку 15%' => 'Нужна уголовная защита бизнеса, собственника или руководителя?',
		"Адвокаты коллегии сопровождают доверителей от первой консультации и доследственной проверки до защиты в суде.\nРаботаем проектными группами: объединяем опыт уголовной практики, знание процессуальных механизмов и понимание специфики бизнеса" => 'Коллегия адвокатов с многолетним опытом в уголовном праве. Все сотрудники бывшие следователи. Понимаем работу правоохранительных органов изнутри. Проводим уголовно-правовой аудит, помогаем снизить риски, защищаем на стадии проверки, следствия и в суде.',
	];
}

/**
 * Рекурсивная замена строк в значении поля.
 *
 * @param mixed $value Field value.
 * @param array $map   Replacements.
 * @return mixed
 */
function mp_replace_deep( $value, $map ) {
	if ( is_string( $value ) ) {
		return strtr( $value, $map );
	}

	if ( is_array( $value ) ) {
		foreach ( $value as $key => $item ) {
			$value[ $key ] = mp_replace_deep( $item, $map );
		}
	}

	return $value;
}

/**
 * Применяет замены ко всем группам контента.
 */
function mp_apply_content_replacements() {
	if ( ! function_exists( 'get_field' ) || get_option( 'mp_content_replace_version' ) === MP_REPLACE_VERSION ) {
		return;
	}

	$map = mp_content_replacements();

	foreach ( mp_content_groups() as $group => $meta ) {
		if ( ! function_exists( $meta['data'] ) ) {
			continue;
		}

		foreach ( mp_content_targets( $group ) as $target ) {
			foreach ( array_keys( (array) call_user_func( $meta['data'] ) ) as $key ) {
				$name  = $group . '_' . $key;
				$value = get_field( $name, $target );

				if ( null === $value || '' === $value || false === $value || [] === $value ) {
					continue;
				}

				$updated = mp_replace_deep( $value, $map );

				if ( $updated !== $value ) {
					update_field( $name, $updated, $target );
				}
			}
		}
	}

	update_option( 'mp_content_replace_version', MP_REPLACE_VERSION );
}
add_action( 'acf/init', 'mp_apply_content_replacements', 30 );
