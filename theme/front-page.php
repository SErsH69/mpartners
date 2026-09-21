<?php
/**
 * Front page — «Главная» composition from Figma.
 *
 * @package MPartners
 */

get_header();

foreach (
	[
		'hero',
		'advantages',
		'help',
		'industries',
		'quiz',
		'experience',
		'awards',
		'practice',
		'reviews',
		'blog',
		'form',
		'team',
		'geography',
	] as $mp_section
) {
	get_template_part( 'template-parts/sections/' . $mp_section );
}

get_footer();
