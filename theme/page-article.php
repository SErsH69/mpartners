<?php
/**
 * Template Name: Статья
 *
 * @package MPartners
 */

get_header();

get_template_part( 'template-parts/article/body' );

get_template_part( 'template-parts/article/faq' );
get_template_part( 'template-parts/article/author' );

get_template_part( 'template-parts/article/related' );
get_template_part( 'template-parts/sections/form' );

get_footer();
