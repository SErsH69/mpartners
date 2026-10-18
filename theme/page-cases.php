<?php
/**
 * Template Name: Выигранные дела
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/press/list', null, [ 'section' => 'cases' ] );
get_template_part( 'template-parts/sections/form' );

get_footer();
