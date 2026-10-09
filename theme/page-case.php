<?php
/**
 * Template Name: Дело
 *
 * Страница одного дела по макету Figma «Адаптивы»: 1920 / 1280 / 640 / 375.
 *
 * @package MPartners
 */

get_header();

foreach ( [ 'hero', 'details', 'team' ] as $mp_part ) {
	get_template_part( 'template-parts/case/' . $mp_part );
}

// Нижний блок «Как мы решаем сложные ситуации» — тот же, что на главной.
get_template_part( 'template-parts/sections/practice' );

get_footer();
