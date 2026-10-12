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
	get_template_part( 'template-parts/landing/scope' );
	get_template_part( 'template-parts/landing/stages' );
	get_template_part( 'template-parts/landing/clients' );
	get_template_part( 'template-parts/landing/results' );
	get_template_part( 'template-parts/landing/consult' );
	get_template_part( 'template-parts/landing/team' );
	get_template_part( 'template-parts/sections/practice' );
	get_template_part( 'template-parts/landing/deck' );
	get_template_part( 'template-parts/sections/reviews' );
	get_template_part( 'template-parts/landing/ratings' );
	get_template_part( 'template-parts/landing/faq' );
	get_template_part( 'template-parts/landing/analysis' );
	get_template_part( 'template-parts/landing/related' );
endwhile;

get_footer();
