<?php

/**
 * Retrieves the contents of an SVG file from the theme directory and returns it as a string, with whitespace minimized.
 *
 * This function is designed to inline SVG content directly into HTML documents, improving performance by eliminating additional HTTP requests for small SVG files. It constructs the full path to the SVG file by appending the provided path to the theme directory path, optionally adding a version hash for cache busting. Before returning the SVG content, the function removes new lines, carriage returns, and tabs to minimize the size of the output. If the specified SVG file does not exist at the constructed path, the function returns an empty string.
 *
 * @param string $svg_path The relative path from the theme directory to the SVG file. This path may include a specific naming convention that is resolved by webula_get_asset_hashed_filename to account for versioned filenames.
 * @return string The content of the SVG file as a single-line string, or an empty string if the file does not exist.
 */
function webula_inline_svg( string $svg_path ): string {
	$svg_path = get_template_directory() . webula_get_asset_hashed_filename( $svg_path );

	if ( ! file_exists( $svg_path ) ) {
		return '';
	}

	$svg = file_get_contents( $svg_path );
	$svg = preg_replace( '/\n/', '', $svg );
	$svg = preg_replace( '/\r/', '', $svg );
	$svg = preg_replace( '/\t/', '', $svg );

	return $svg;
}

function webula_rawurlencode_svg( string $svg_file ): string {
	$svg = webula_inline_svg( $svg_file );
	if ( empty( $svg ) ) {
		return '';
	}

	return 'data:image/svg+xml,' . rawurlencode( $svg );
}

/**
 * Processes an image URL to potentially modify it through Jetpack's Photon CDN, based on the file extension.
 *
 * This function checks if the given image URL ends with a common image file extension (jpg, jpeg, png, webp, gif). If so, it attempts to apply the 'jetpack_photon_url' filter, which is typically used to modify the image URL to route it through Jetpack's Photon CDN for optimization and faster delivery. This function is useful when there's a need to ensure that image assets are served efficiently, taking advantage of Jetpack's CDN capabilities without manually checking each URL. If the URL does not match the specified extensions, it is returned unmodified.
 *
 * @param string $url The original URL of the image.
 * @return string The potentially modified URL after applying the 'jetpack_photon_url' filter, or the original URL if no modification is applicable.
 */
function webula_img( $url ) {
	if ( preg_match( '/\.(jpg|jpeg|png|webp|gif)$/i', $url ) ) {
		// apply jetpack CDN
		return apply_filters( 'jetpack_photon_url', $url );
	}

	return $url;
}

/**
 * Outputs a responsive HTML picture element with defined sources for desktop and mobile breakpoints.
 *
 * This function serves as a convenience wrapper for webula_get_picture, directly echoing the HTML output it generates. It facilitates the creation of responsive images in web pages, allowing different image resources to be specified for desktop and mobile devices based on a CSS breakpoint. The function supports customization through various parameters, including image sources, sizes, and CSS classes, to ensure the images are displayed appropriately across device types. This approach is especially useful in responsive web design, enhancing user experience by optimizing image loading for different screen sizes.
 *
 * @param array $desktop_img An associative array containing the desktop image's URL and optionally its alt text, width, and height.
 * @param array $mobile_img Optional. An associative array for the mobile image's URL and optionally its alt text, width, and height. Default is an empty array.
 * @param string $img_class Optional. A base CSS class to be applied to the picture and img elements, enhancing styling control. Default is an empty string.
 * @param array $desk_size Optional. An array specifying the width and height for the desktop image, for explicit size control. Default is an empty array.
 * @param array $mob_size Optional. An array specifying the width and height for the mobile image, for explicit size control. Default is an empty array.
 * @param string $breakpoint Optional. The CSS media query breakpoint at which the image source switches between mobile and desktop. Default is '46.5rem'.
 * @return void
 */
function webula_picture( array $desktop_img, array $mobile_img = [], string $img_class = '', array $desk_size = [], array $mob_size = [], string $breakpoint = '46.5rem', string $loading = 'lazy', int $quality = 90, int $mobile_quality = 90 ): void {

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo webula_get_picture( $desktop_img, $mobile_img, $img_class, $desk_size, $mob_size, $breakpoint, $loading, $quality, $mobile_quality );
}

/**
 * Generates a responsive HTML picture element for a given set of images, with sources for desktop and mobile breakpoints.
 *
 * This function constructs a picture element that includes different images for desktop and mobile devices based on a specified breakpoint. It allows for the specification of different sizes for both desktop and mobile images to ensure that the most appropriate image is loaded for the user's screen size, optimizing load times and bandwidth usage. The function also supports adding custom CSS classes to the picture and img elements within. This is particularly useful for responsive web design, where image dimensions and resolutions might vary significantly across devices.
 *
 * @param array $desktop_img An associative array containing the desktop image's URL and optionally its alt text, width, and height.
 * @param array $mobile_img An associative array containing the mobile image's URL and optionally its alt text, width, and height.
 * @param string $img_class Optional. A base CSS class to be added to the picture and img elements. The picture element will have '-picture' appended to this base class. Default is an empty string.
 * @param array $desk_size Optional. An array containing the width and height for the desktop image. Default is an empty array.
 * @param array $mob_size Optional. An array containing the width and height for the mobile image. Default is an empty array.
 * @param string $breakpoint Optional. The CSS breakpoint at which to switch between the mobile and desktop images. Default is '46.5rem'.
 * @return string The complete picture element as a string, ready to be used in HTML output.
 */
function webula_get_picture( array $desktop_img, array $mobile_img, string $img_class = '', array $desk_size = [], array $mob_size = [], string $breakpoint = '46.5rem', string $loading = 'lazy', int $quality = 90, int $mobile_quality = 90 ): string {
	$container_class = $img_class ? ' class="' . esc_attr( $img_class ) . '-picture"' : '';
	$image_class     = $img_class ? ' class="' . esc_attr( $img_class ) . '"' : '';

	if ( ! empty( $img_class ) ) {
		$img_class = ' class="' . esc_attr( $img_class ) . '"';
	}

	if ( empty( $desk_size ) ) {
		$desk_size = [ 0, 0 ];
	}

	if ( empty( $mob_size ) ) {
		$mob_size = [ 0, 0 ];
	}

	$loading_attr = '';
	if ( ! empty( $loading ) ) {
		$loading_attr = ' loading="' . esc_attr( $loading ) . '"';
	}

	$out  = '';
	$out .= '<picture' . $container_class . '>';
	$out .= '<source media="(max-width: '
		. $breakpoint
		. ')" srcset="'
		. html_entity_decode( esc_url( webula_get_image_url( $mobile_img, $mob_size[0], $mob_size[1], $mobile_quality ) ) )
		. '"><source media="(min-width: '
		. $breakpoint
		. ')" srcset="'
		. html_entity_decode( esc_url( webula_get_image_url( $desktop_img, $desk_size[0], $desk_size[1], $quality ) ) )
		. '"><img src="'
		. html_entity_decode( esc_url( webula_get_image_url( $desktop_img, 0, 0, $quality ) ) )
		. '" alt="'
		. esc_attr( $desktop_img['alt'] )
		. '" width="'
		. ( $desk_size[0] ? intval( $desk_size[0] ) : 'auto' )
		. '" height="'
		. ( $desk_size[1] ? intval( $desk_size[1] ) : 'auto' )
		. '"'
		. $img_class
		. $loading_attr
		. '></picture>';

	return $out;
}

/**
 * Echoes the HTML img element generated for a given image, with optional customization for size, CSS class, and additional attributes.
 *
 * This function is a wrapper for webula_get_image, directly outputting the HTML img tag it generates. It supports resizing the image through width and height parameters, adding a custom CSS class, and including extra HTML attributes in the img tag. This utility function is particularly useful for theme and plugin developers who need to output image elements dynamically and with various modifications, while ensuring the output is appropriately escaped and safe for use in a web context.
 *
 * @param array $image An associative array containing the image's URL and optionally its alt text, width, and height.
 * @param string $image_class Optional. A string of CSS class(es) to add to the img element. Default is an empty string.
 * @param int $width Optional. The desired width of the image in pixels. Default is 0, indicating no specific resizing.
 * @param int $height Optional. The desired height of the image in pixels. Default is 0, indicating no specific resizing.
 * @param string $extra Optional. A string of additional HTML attributes to include in the img tag. Defaults to adding lazy loading attributes if left empty.
 * @return void
 */
function webula_image( array $image, string $image_class = '', int $width = 0, int $height = 0, string $extra = '' ): void {

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo webula_get_image( $image, $image_class, $width, $height, $extra );
}

/**
 * Generates an HTML img element for a given image, optionally resizing it and adding custom attributes.
 *
 * This function constructs an img tag for the specified image. It allows for optional resizing by specifying width and height parameters. Additional HTML attributes can be added via the $extra parameter. The function ensures that all attributes are properly escaped for security. If the image URL is not provided or is empty, an empty string is returned, resulting in no img tag being output. This function is ideal for dynamically generating image elements in themes or plugins where image attributes might need to be modified or extended.
 *
 * @param array $image An associative array containing the image's URL and optionally its alt text, width, and height.
 * @param string $image_class Optional. CSS class(es) to add to the img element. Default is an empty string.
 * @param int $width Optional. The desired width of the image in pixels. Default is 0, indicating no resizing.
 * @param int $height Optional. The desired height of the image in pixels. Default is 0, indicating no resizing.
 * @param string $extra Optional. Additional HTML attributes to include in the img tag. Default is an empty string, which adds default attributes for lazy loading.
 * @return string The complete img element as a string, ready to be used in HTML output.
 */
function webula_get_image( array $image, string $image_class = '', int $width = 0, int $height = 0, string $extra = '' ): string {
	// Check if the image URL is set and not empty
	if ( ! isset( $image['url'] ) || empty( $image['url'] ) ) {
		return '';
	}

	// Get the image URL with width and height if specified
	$image['url'] = webula_get_image_url( $image, $width, $height );

	// Initialize srcset and sizes attributes
	$srcset = '';
	$sizes  = '';

	// Check if the image is not an SVG
	if ( ! preg_match( '/\.svg$/i', $image['url'] ) ) {
		// Get srcset and sizes if the image has an attachment ID
		if ( isset( $image['id'] ) && ! empty( $image['id'] ) ) {
			$srcset = wp_get_attachment_image_srcset( $image['id'], 'full' );
			$sizes  = wp_get_attachment_image_sizes( $image['id'], 'full' );
		}
	}

	// Prepare image class if set
	$image_class_att = '';
	if ( $image_class ) {
		$image_class_att = esc_attr( $image_class );
	}

	// Set extra attributes for lazy loading and decoding if none are provided
	if ( empty( $extra ) ) {
		$extra = ' fetchpriority="low" decoding="async" loading="lazy"';
	}

	// check for dominant color
	$color = webula_get_image_color( $image['id'] );

	// Generate the image HTML
	return webula_build_image(
		src: $image['url'],
		alt: $image['alt'] ?? '',
		width: $width ? $width : $image['width'],
		height: $height ? $height : $image['height'],
		class: $image_class_att,
		srcset: $srcset,  // Pass srcset only if not SVG
		sizes: $sizes,   // Pass sizes only if not SVG
		extra: $extra,
		color: $color ? '#' . $color : '',
	);
}

function webula_get_tpl_retina_image( string $image ): string {
	// Check if the image is an SVG
	if ( strpos( $image, '.svg' ) !== false ) {
		return ''; // Return empty string if it's an SVG
	}

	// Get the image path from the theme directory
	$image_path = get_template_directory() . webula_get_asset_hashed_filename( $image );

	// Log error if the image doesn't exist in the theme
	if ( ! file_exists( $image_path ) ) {
		error_log( "Image not found: $image" );
		return ''; // Return empty string if the image is not found
	}

	// Append @2x to the image name for retina
	$retina_image = preg_replace( '/\.(jpg|jpeg|png|gif)$/i', '@2x.$1', $image );

	// Get the retina image path
	$retina_image_path = get_template_directory() . webula_get_asset_hashed_filename( $retina_image );

	// Log error if the retina version of the image doesn't exist
	if ( ! file_exists( $retina_image_path ) ) {
		error_log( "Retina image not found: $retina_image" );
		return ''; // Return empty string if the retina image is not found
	}

	// Return the retina image file name
	return $retina_image;
}

function webula_tpl_image( string $image, $args = [], string $alt = '' ): void {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo webula_get_tpl_image( $image, $args, $alt );
}

function webula_get_tpl_image( string $image, $args = [], string $alt = '' ): string {
	// Get the retina image if available
	$retina_img = webula_get_tpl_retina_image( $image );

	// Initialize srcset and sizes strings
	$srcset = '';
	$sizes  = '';

	// If a retina image is available, construct the srcset and sizes
	if ( $retina_img ) {
		$srcset = webula_get_asset( $image ) . ' 1x, ' . webula_get_asset( $retina_img ) . ' 2x';
	}

	// Add these attributes to the $args array
	$args['src']    = webula_get_asset( $image );  // The main image URL
	$args['srcset'] = $srcset;   // Retina and standard image srcset
	$args['sizes']  = $sizes;    // Responsive sizes attribute

	if ( ! empty( $alt ) ) {
		$args['alt'] = $alt;
	}

	// Pass the constructed arguments to the image builder function
	return webula_build_image( ...$args );
}

function webula_build_image(
	string $src = '',
	string $alt = '',
	string $class = '',
	int $width = 0,
	int $height = 0,
	string $srcset = '',
	string $sizes = '',
	string $extra = 'fetchpriority="low" decoding="async" loading="lazy"',
	string $color = ''
): string {

	// Check if the image is an SVG
	$is_svg = preg_match( '/\.svg$/i', $src );

	// Build the img tag
	$img_tag = '<img src="' . html_entity_decode( esc_url( $src ) ) . '"';

	// Add alt attribute if present
	if ( ! empty( $alt ) ) {
		$img_tag .= ' alt="' . esc_attr( $alt ) . '"';
	}

	// Add class if present
	if ( ! empty( $class ) ) {
		$img_tag .= ' class="' . esc_attr( $class ) . '"';
	}

	// Add width and height attributes
	if ( ! empty( $width ) && ! empty( $height ) ) {
		$img_tag .= ' width="' . intval( $width ) . '" height="' . intval( $height ) . '"';
	}

	// Only add srcset and sizes if it's not an SVG
	if ( ! $is_svg && ! empty( $srcset ) ) {
		$img_tag .= ' srcset="' . esc_attr( $srcset ) . '"';
	}

	if ( ! $is_svg && ! empty( $sizes ) ) {
		$img_tag .= ' sizes="' . esc_attr( $sizes ) . '"';
	}

	// Add any extra attributes like lazy loading, decoding, etc.
	$img_tag .= ' ' . $extra;

	// Close the img tag
	$img_tag .= ' />';

	return $img_tag;
}

/**
 * Outputs the URL of an image with optional resizing parameters, ensuring the URL is safely escaped for use in HTML context.
 *
 * This function is a wrapper around webula_get_image_url, which fetches a potentially modified URL for an image based on desired width and height. It then escapes the URL for safe use within HTML using esc_url. This ensures that the output is secure for use in HTML attributes, such as in an img tag's src attribute. If the image does not exist or no URL is provided in the $image array, nothing is output.
 *
 * @param array $image An associative array containing at least an 'url' key with the image URL.
 * @param int $width Optional. The desired width of the image in pixels. Default is 0, indicating no resizing.
 * @param int $height Optional. The desired height of the image in pixels. Default is 0, indicating no resizing.
 * @return void
 */
function webula_image_url( array $image, int $width = 0, int $height = 0 ): void {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo html_entity_decode( esc_url( webula_get_image_url( $image, $width, $height ) ) );
}

/**
 * Retrieves the modified URL of an image with optional resizing parameters.
 *
 * This function takes an array representing an image and optionally resizes it
 * by adding query arguments to its URL for width, height, and cropping. It's designed
 * to work with a specific image processing system that understands these parameters.
 * If either width or height is not specified, the original image URL is returned without
 * modification.
 *
 * @param array $image An associative array containing at least an 'url' key with the image URL.
 * @param int $width Optional. The desired width of the image. Default is 0, indicating no resizing.
 * @param int $height Optional. The desired height of the image. Default is 0, indicating no resizing.
 * @return string The image URL, possibly modified with resizing query arguments. If the URL
 *                is not set or is empty in the input array, an empty string is returned.
 */
function webula_get_image_url( array $image, int $width = 0, int $height = 0, int $quality = 90 ): string {
	if ( ! isset( $image['url'] ) || empty( $image['url'] ) ) {
		return '';
	}

	if ( ! preg_match( '/\.svg$/i', $image['url'] ) ) {
		$image['url'] = apply_filters( 'jetpack_photon_url', $image['url'] );
	}

	$parsed_url      = parse_url( $image['url'] );
	$existing_params = [];
	if ( isset( $parsed_url['query'] ) ) {
		parse_str( $parsed_url['query'], $existing_params );
	}

	$args = [];
	if ( $width > 0 && $height > 0 ) {
		if ( ! isset( $existing_params['w'] ) ) {
			$args['w'] = $width;
		}
		if ( ! isset( $existing_params['h'] ) ) {
			$args['h'] = $height;
		}
		$args['fit'] = 'crop';
	}

	if ( $quality > 0 && $quality <= 100 ) {
		$args['quality'] = $quality;
	}

	if ( ! empty( $args ) ) {
		$image['url'] = add_query_arg( $args, $image['url'] );
	}

	return $image['url'];
}

/**
 * Modifies the image quality parameter in a URL.
 *
 * This function specifically targets URLs processed by Jetpack's Photon module, allowing
 * the modification of the image quality parameter. It's hooked into the 'jetpack_photon_url'
 * filter to apply this modification globally for images processed through Photon.
 *
 * @param string $url The URL to the image being processed. Default is an empty string.
 * @param array $args Existing query arguments appended to the image URL. Default is an empty array.
 * @param array $scheme Optional. The scheme to use for the image URL. Default is an empty array.
 * @return string The modified image URL with the updated quality parameter.
 */
function webula_change_img_quality( string $url = '', array $args = [], array $scheme = [] ): string {
	if ( stripos( $url, 'quality=10' ) !== false ) {
		$args['quality'] = 10;
	}

	$args['quality'] = 90;
	return add_query_arg( 'quality', $args['quality'], $url );
}
add_filter( 'jetpack_photon_url', 'webula_change_img_quality', 20, 3 );

function webula_get_image_color( $attachment_id ) {
	if ( ! function_exists( 'dominant_color_get_dominant_color' ) ) {
		return '';
	}

	$color = dominant_color_get_dominant_color( $attachment_id );

	if ( empty( $color ) ) {
		return '';
	}

	return $color;
}
