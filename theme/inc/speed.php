<?php

// clean up WordPress output
function webula_remove_some_scripts() {

	// remove extra WordPress stuff
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}
add_action( 'init', 'webula_remove_some_scripts' );

// remove wp-embed.min.js
function webula_deregister_scripts() {
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'webula_deregister_scripts' );
add_action( 'wp_head', 'webula_deregister_scripts' );

// function to defer or async some js scripts in a WP_Hook
function webula_async_scripts( $tag, $handle, $src ) {

	$async = [];
	if ( in_array( $handle, $async ) ) {
		return str_replace( ' src=', ' async="async" src=', $tag );
	}

	$defer = [
		'webula-script',
		'consent',
		'slider-block-script',
		'form-block-script',
	];

	if ( in_array( $handle, $defer ) ) {
		return str_replace( ' src=', ' defer="defer" src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'webula_async_scripts', 10, 3 );

/**
 * Retrieves the hashed file name of an asset from the manifest.
 *
 * @param string $filename The original filename to be hashed.
 * @return string The hashed filename if found in the manifest, otherwise the original filename.
 */
function webula_get_asset_hashed_filename( string $filename ): string {
	$manifest = webula_get_assets_manifest();
	return get_hashed_filename_from_manifest( $manifest, $filename );
}

/**
 * Retrieves the hashed file name of an asset from the manifest.
 *
 * @param array $manifest The asset manifest.
 * @param string $filename The original filename to be hashed.
 * @return string The hashed filename if found in the manifest, otherwise the original filename.
 */
function get_hashed_filename_from_manifest( array $manifest, string $filename ): string {
	if ( array_key_exists( $filename, $manifest ) ) {
		return '/dist/' . str_replace( 'auto/', '', $manifest[ $filename ] );
	}
	return '/' . $filename;
}


/**
 * Returns assets mapping array from manifest files.
 *
 * @return array The associative array containing the asset mappings.
 */
function webula_get_assets_manifest(): array {
	// Define the path to the manifest file.
	$manifest_path = get_template_directory() . '/dist/manifest.json';

	// Initialize an empty array as the default manifest.
	$manifest = [];

	// Check if the manifest file exists.
	if ( file_exists( $manifest_path ) ) {
		// Decode the JSON contents of the manifest file into an associative array.
		$manifest = json_decode( file_get_contents( $manifest_path ), true );
	}

	// Return the manifest array.
	return $manifest;
}

function webula_get_asset( string $filename ): string {
	$url = get_template_directory_uri() . webula_get_asset_hashed_filename( $filename );

	if ( preg_match( '/\.(jpg|jpeg|png|webp|gif)$/i', $filename ) ) {
		// apply jetpack CDN
		return apply_filters( 'jetpack_photon_url', $url );
	}

	return $url;
}

add_filter( 'jetpack_sharing_counts', '__return_false', 99 );
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );
