<?php
/**
 * Template Name: Пресс-центр
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/press/list', null, [ 'section' => 'press' ] );
get_template_part( 'template-parts/sections/form' );

get_footer();
