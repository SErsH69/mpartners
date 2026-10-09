<?php
/**
 * Template Name: Карточка адвоката
 *
 * Страница адвоката по макету Figma «Адаптивы»: 1920 / 1280 / 640 / 375.
 *
 * @package MPartners
 */

get_header();

foreach ( [ 'profile', 'publications' ] as $mp_part ) {
	get_template_part( 'template-parts/lawyer/' . $mp_part );
}

get_footer();
