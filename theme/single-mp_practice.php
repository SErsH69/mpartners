<?php
/**
 * Услуга: страница одной практики.
 *
 * @package MPartners
 */

get_header();

while ( have_posts() ) :
	the_post();

	foreach ( [ 'hero', 'details', 'team' ] as $mp_part ) {
		get_template_part( 'template-parts/case/' . $mp_part );
	}
endwhile;

// Нижний блок «Как мы решаем сложные ситуации» — тот же, что на главной.
get_template_part( 'template-parts/sections/practice' );

get_footer();
