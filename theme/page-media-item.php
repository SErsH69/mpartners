<?php
/**
 * Template Name: Публикация в СМИ
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/article/body' );

get_template_part( 'template-parts/article/strip', null, [ 'section' => 'media' ] );

get_template_part( 'template-parts/article/related' );
get_template_part( 'template-parts/sections/form' );

get_footer();
