<?php
/**
 * Внутренняя страница материала.
 *
 * @package MPartners
 */

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/article/body' );
	get_template_part( 'template-parts/article/strip', null, [ 'section' => 'media' ] );
	get_template_part( 'template-parts/article/related' );
endwhile;

get_template_part( 'template-parts/sections/form' );

get_footer();
