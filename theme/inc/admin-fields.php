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

const MP_CONTENT_VERSION = '3';

/**
 * Редактируемые разделы контента.
 *
 * @return array
 */
function mp_content_groups() {
	return [
		'home'      => [
			'title' => 'Главная',
			'data'  => 'mp_home_data',
		],
		'practices' => [
			'title' => 'Практики',
			'data'  => 'mp_practices_data',
		],
		'case'      => [
			'title' => 'Страница дела',
			'data'  => 'mp_case_data',
		],
		'lawyer'    => [
			'title' => 'Карточка адвоката',
			'data'  => 'mp_lawyer_data',
		],
		'press'     => [
			'title' => 'Пресс-центр, мероприятия, СМИ',
			'data'  => 'mp_press_data',
		],
		'article'   => [
			'title' => 'Внутренняя страница раздела',
			'data'  => 'mp_article_data',
		],
	];
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

	$text = (string) $value;
	$long = mb_strlen( $text ) > 90 || false !== strpos( $text, "\n" );

	return $field + [
		'type'          => $long ? 'textarea' : 'text',
		'rows'          => $long ? 4 : null,
		'default_value' => $text,
	];
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
		return array_map(
			function ( $item ) {
				return is_array( $item ) ? mp_content_to_acf( $item ) : [ 'value' => $item ];
			},
			$value
		);
	}

	foreach ( $value as $key => $item ) {
		$value[ $key ] = mp_content_to_acf( $item );
	}

	return $value;
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

		$slug = 'mp-content-' . $group;

		if ( function_exists( 'acf_add_options_sub_page' ) ) {
			acf_add_options_sub_page(
				[
					'page_title'  => $meta['title'],
					'menu_title'  => $meta['title'],
					'menu_slug'   => $slug,
					'parent_slug' => 'mp-content',
				]
			);
		}

		$fields = [];

		foreach ( call_user_func( $meta['data'] ) as $key => $value ) {
			$fields[] = mp_acf_field( $group . '.' . $key, $group . '_' . $key, $value, mp_acf_label( $key ) );
		}

		acf_add_local_field_group(
			[
				'key'      => 'group_mp_' . $group,
				'title'    => $meta['title'],
				'fields'   => $fields,
				'location' => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => $slug,
						],
					],
				],
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

	foreach ( mp_content_groups() as $group => $meta ) {
		if ( ! function_exists( $meta['data'] ) ) {
			continue;
		}

		foreach ( call_user_func( $meta['data'] ) as $key => $value ) {
			$name = $group . '_' . $key;

			$current = get_field( $name, 'option' );

			// Пустой репитер ACF отдаёт как false, пустая группа — как массив
			// с пустыми ключами, поэтому проверяем все «пустые» варианты.
			if ( null === $current || '' === $current || false === $current || [] === $current ) {
				update_field( $name, mp_content_to_acf( $value ), 'option' );
			}
		}
	}

	update_option( 'mp_content_version', MP_CONTENT_VERSION );
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

	// Список целиком заменяется сохранённым — иначе нельзя убрать карточку.
	if ( mp_is_list( $default ) ) {
		return $saved;
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

	if ( function_exists( 'get_field' ) ) {
		foreach ( $data as $key => $default ) {
			$saved = get_field( $group . '_' . $key, 'option' );

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
