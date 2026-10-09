<?php
/**
 * Настройки SEO: заголовки, Yoast и базовые опции сайта.
 *
 * Всё задаётся из темы, чтобы одинаково разворачивалось на любом сервере.
 *
 * @package MPartners
 */

const MP_SEO_VERSION = '2';

/**
 * Заголовок сайта и описание — из контактов в админке.
 */
function mp_seo_site_options() {
	$tagline = trim( str_replace( "\n", ' ', (string) mp_data( 'contacts.tagline', '' ) ) );

	if ( get_option( 'blogname' ) !== 'M-PARTNERS' ) {
		update_option( 'blogname', 'M-PARTNERS' );
	}

	if ( $tagline && get_option( 'blogdescription' ) !== $tagline ) {
		update_option( 'blogdescription', $tagline );
	}
}

/**
 * Разделы, которые не нужны в поиске: архивы по датам, авторам и вложения.
 *
 * @return array
 */
function mp_seo_yoast_titles() {
	return [
		'separator'                   => 'sc-mdash',
		'title-home-wpseo'            => '%%sitename%% %%sep%% %%sitedesc%%',
		'title-search-wpseo'          => 'Поиск: %%searchphrase%% %%page%% %%sep%% %%sitename%%',
		'title-404-wpseo'             => 'Страница не найдена %%sep%% %%sitename%%',
		'title-archive-wpseo'         => '%%date%% %%page%% %%sep%% %%sitename%%',
		'title-author-wpseo'          => '%%name%% %%sep%% %%sitename%%',
		'metadesc-home-wpseo'         => 'Коллегия адвокатов M-PARTNERS: уголовная защита бизнеса, собственников и руководителей на любой стадии — от превенции рисков до суда и обжалования.',

		// Карточки услуг и адвокатов нужны в поиске, служебные архивы — нет.
		'noindex-author-wpseo'        => true,
		'noindex-archive-wpseo'       => true,
		'disable-date'                => true,
		'disable-author'              => true,
		'disable-post_format'         => true,
		'disable-attachment'          => true,
		'noindex-tax-post_tag'        => true,

		'company_or_person'           => 'company',
		'company_name'                => 'Коллегия адвокатов M-PARTNERS',
		'website_name'                => 'M-PARTNERS',

		'title-mp_practice'           => '%%title%% %%sep%% %%sitename%%',
		'title-ptarchive-mp_practice' => 'Услуги %%page%% %%sep%% %%sitename%%',
		'title-mp_lawyer'             => '%%title%% %%sep%% %%sitename%%',
		'title-ptarchive-mp_lawyer'   => 'Адвокаты %%page%% %%sep%% %%sitename%%',
	];
}

/**
 * Разовая настройка Yoast: тексты по-русски и профили в соцсетях.
 */
function mp_seo_configure() {
	if ( get_option( 'mp_seo_version' ) === MP_SEO_VERSION ) {
		return;
	}

	mp_seo_site_options();

	if ( ! get_option( 'wpseo_titles' ) ) {
		return; // Yoast ещё не установлен — попробуем в следующий раз.
	}

	$titles = array_merge( (array) get_option( 'wpseo_titles' ), mp_seo_yoast_titles() );
	update_option( 'wpseo_titles', $titles );

	$social = (array) get_option( 'wpseo_social', [] );
	$links  = [];

	foreach ( (array) mp_data( 'contacts.socials', [] ) as $item ) {
		if ( ! empty( $item['href'] ) && '#' !== $item['href'] ) {
			$links[] = $item['href'];
		}
	}

	$social['other_social_urls'] = array_values( array_unique( $links ) );
	update_option( 'wpseo_social', $social );

	$main                                = (array) get_option( 'wpseo', [] );
	$main['tracking']                    = false;
	$main['enable_xml_sitemap']          = true;
	$main['should_redirect_after_install_free'] = false;
	$main['first_time_install']          = false;
	update_option( 'wpseo', $main );

	// Для статичной главной Yoast берёт заголовок страницы, а не шаблон
	// «title-home», поэтому прописываем его прямо у страницы.
	$front = (int) get_option( 'page_on_front' );

	if ( $front ) {
		update_post_meta( $front, '_yoast_wpseo_title', 'Уголовная защита бизнеса и руководителей %%sep%% %%sitename%%' );
		update_post_meta(
			$front,
			'_yoast_wpseo_metadesc',
			'Коллегия адвокатов M-PARTNERS: защита бизнеса, собственников и руководителей по уголовным делам — от превенции рисков и доследственной проверки до суда и обжалования.'
		);
	}

	update_option( 'mp_seo_version', MP_SEO_VERSION );
}
add_action( 'admin_init', 'mp_seo_configure', 40 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	add_action( 'init', 'mp_seo_configure', 40 );
}
