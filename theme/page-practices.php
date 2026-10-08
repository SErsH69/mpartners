<?php
/**
 * Template Name: Практики
 *
 * Страница «Практики уголовно-правовой защиты бизнеса» по макету Figma
 * «Адаптивы»: 1920 / 1280 / 640 / 375.
 *
 * @package MPartners
 */

get_header();

foreach ( [ 'hero', 'cards', 'cta' ] as $mp_part ) {
	get_template_part( 'template-parts/practices/' . $mp_part );
}

get_footer();
