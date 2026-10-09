<?php
/**
 * Template Name: СМИ о нас
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/press/list', null, [ 'section' => 'media' ] );
get_template_part( 'template-parts/sections/form' );

get_footer();
