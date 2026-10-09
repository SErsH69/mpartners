<?php
/**
 * Template Name: Мероприятия
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/press/list', null, [ 'section' => 'events' ] );
get_template_part( 'template-parts/sections/form' );

get_footer();
