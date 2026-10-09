<?php
/**
 * Лендинг услуги.
 *
 * @package MPartners
 */

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/landing/page-hero' );
	get_template_part( 'template-parts/landing/cases' );
	get_template_part( 'template-parts/landing/cta' );
endwhile;

get_footer();
