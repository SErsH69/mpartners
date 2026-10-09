<?php
/**
 * Адвокат: страница одного адвоката.
 *
 * @package MPartners
 */

get_header();

while ( have_posts() ) :
	the_post();

	foreach ( [ 'profile', 'publications' ] as $mp_part ) {
		get_template_part( 'template-parts/lawyer/' . $mp_part );
	}
endwhile;

get_footer();
