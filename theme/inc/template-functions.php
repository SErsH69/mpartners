<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Opti
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function webula_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'webula_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function webula_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'webula_pingback_header' );

function webula_remove_svg_filters() {
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'init', 'webula_remove_svg_filters' );

function webula_cookie_banner() {
	get_template_part( 'template-parts/banner', 'cookie' );
}
add_action( 'webula_after_footer', 'webula_cookie_banner' );

// Add a new column to the post/page list
function webula_add_blocks_column( $columns ) {
	$columns['blocks_used'] = 'Р‘Р»РѕРєРё';
	return $columns;
}
add_filter( 'manage_posts_columns', 'webula_add_blocks_column' );
add_filter( 'manage_pages_columns', 'webula_add_blocks_column' );

// Output the content of the new column
function webula_display_blocks_column( $column, $post_id ) {
	if ( 'blocks_used' === $column ) {
		// Get the post content
		$post_content = get_post_field( 'post_content', $post_id );

		// Parse the blocks
		$blocks = parse_blocks( $post_content );

		// Create an array to hold the block names
		$block_names = [];

		// Loop through the blocks and add the block names to the array
		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) ) {
				$block_names[] = $block['blockName'];
			}
		}

		// Output the block names as a comma-separated list
		echo implode( '<br> ', $block_names );
	}
}
add_action( 'manage_posts_custom_column', 'webula_display_blocks_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'webula_display_blocks_column', 10, 2 );
