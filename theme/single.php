<?php
/**
 * Внутренняя страница материала.
 *
 * Блок под текстом зависит от раздела: у статей пресс-центра — вопросы и
 * карточка автора, у мероприятий и СМИ — тёмная полоса с названием.
 *
 * @package MPartners
 */

get_header();

while ( have_posts() ) :
	the_post();

	$mp_section = function_exists( 'mp_post_section' ) ? mp_post_section( get_the_ID() ) : 'press';

	get_template_part( 'template-parts/article/body' );

	if ( 'press' === $mp_section ) {
		get_template_part( 'template-parts/article/faq' );
		get_template_part( 'template-parts/article/author' );
	} else {
		get_template_part( 'template-parts/article/strip', null, [ 'section' => $mp_section ] );
	}

	get_template_part( 'template-parts/article/related' );
endwhile;

get_template_part( 'template-parts/sections/form' );

get_footer();
