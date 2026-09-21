<?php
use CACFBlocks\Factory\BlockFactory;

require_once get_template_directory() . '/autoloader.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/analytics.php';
require_once get_template_directory() . '/inc/acf-options.php';
require_once get_template_directory() . '/inc/ajax.php';
require_once get_template_directory() . '/inc/pictures.php';
require_once get_template_directory() . '/inc/speed.php';
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/mail.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/home-data.php';
require_once get_template_directory() . '/inc/contact.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once get_template_directory() . '/inc/wp-cli.php';
}

add_action(
	'acf/init',
	function () {
		try {
			$block = BlockFactory::create_block( 'spacer-block' );
			$block->register_all();
		} catch ( Exception $e ) {
			error_log( 'Error registering spacer block: ' . $e->getMessage() );
		}
	}
);

/**
 * Sets up theme defaults and registers support for WordPress features.
 */
function webula_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]
	);

	register_nav_menus(
		[
			'menu-top'    => __( 'Header Menu', 'm-partners' ),
			'menu-footer' => __( 'Footer Menu', 'm-partners' ),
		]
	);
}
add_action( 'after_setup_theme', 'webula_setup' );

/**
 * Set the content width in pixels.
 */
function webula_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'webula_content_width', 1200 );
}
add_action( 'after_setup_theme', 'webula_content_width', 0 );

/**
 * Register theme assets.
 */
function webula_register_assets() {
	wp_register_script( 'webula-script', webula_get_asset( 'main.js' ), [], null, true );
	wp_register_style( 'webula-style', webula_get_asset( 'main.css' ), [], null );
}
add_action( 'init', 'webula_register_assets', 0 );

/**
 * Enqueue frontend assets.
 */
function webula_main_scripts() {
	wp_enqueue_script( 'webula-script' );
	wp_enqueue_style( 'webula-style' );
}
add_action( 'wp_enqueue_scripts', 'webula_main_scripts', 11 );

/**
 * Enqueue editor styles.
 */
function webula_enqueue_editor_styles() {
	wp_enqueue_style( 'webula-style' );
}
add_action( 'enqueue_block_editor_assets', 'webula_enqueue_editor_styles' );

add_filter( 'xmlrpc_enabled', '__return_false' );

add_filter( 'block_categories_all', 'webula_add_block_category', 10, 2 );

/**
 * Add a dedicated category for custom starter blocks.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function webula_add_block_category( $categories ) {
	$custom_category = [
		[
			'slug'  => 'custom-blocks',
			'title' => __( 'Custom Blocks', 'webula-starter' ),
			'icon'  => null,
		],
	];

	return array_merge( $custom_category, $categories );
}
