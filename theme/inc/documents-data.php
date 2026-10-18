<?php
/**
 * Правоустанавливающие документы коллегии.
 *
 * Раздел был на прежнем сайте: документы лежали на Яндекс.Диске, теперь
 * отдаются с сайта.
 *
 * @package MPartners
 */

/**
 * Список документов: заголовок и файл в теме.
 *
 * @return array
 */
function mp_documents() {
	return [
		[
			'title' => 'Лист записи Единого государственного реестра юридических лиц',
			'file'  => 'doc-egrul.pdf',
		],
		[
			'title' => 'Свидетельство о государственной регистрации некоммерческой организации',
			'file'  => 'doc-registraciya.pdf',
		],
		[
			'title' => 'Свидетельство о постановке на учет в налоговом органе по месту нахождения',
			'file'  => 'doc-nalog.pdf',
		],
		[
			'title' => 'Устав Московской коллегии адвокатов «M-PARTNERS»',
			'file'  => 'doc-ustav.pdf',
		],
	];
}

/**
 * Адрес документа.
 *
 * @param string $file Имя файла в папке темы.
 * @return string
 */
function mp_document_url( $file ) {
	return get_template_directory_uri() . '/files/' . $file;
}

/**
 * Размер документа для подписи к ссылке.
 *
 * @param string $file Имя файла в папке темы.
 * @return string Например «4,1 МБ»; пусто, если файла нет.
 */
function mp_document_size( $file ) {
	$path = get_template_directory() . '/files/' . $file;

	if ( ! file_exists( $path ) ) {
		return '';
	}

	$mb = filesize( $path ) / 1048576;

	return str_replace( '.', ',', number_format( $mb, $mb < 10 ? 1 : 0, '.', ' ' ) ) . ' МБ';
}
