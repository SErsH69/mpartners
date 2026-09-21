<?php
/**
 * Inline SVG icons exported from the Figma source.
 *
 * Fills/strokes are normalised to `currentColor` so a single sprite works on
 * both the light and the dark surfaces of the layout.
 *
 * @package MPartners
 */

/**
 * Read a standalone SVG shipped with the theme (theme/svg/<name>.svg).
 *
 * @param string $name File slug.
 * @return string
 */
function mp_get_svg_file( $name ) {
	static $cache = [];

	if ( isset( $cache[ $name ] ) ) {
		return $cache[ $name ];
	}

	$path = get_template_directory() . '/svg/' . $name . '.svg';

	$cache[ $name ] = file_exists( $path ) ? trim( (string) file_get_contents( $path ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	return $cache[ $name ];
}

/**
 * Return the raw markup for an icon.
 *
 * @param string $name Icon slug.
 * @return string
 */
function mp_get_icon( $name ) {
	static $icons = null;

	if ( null === $icons ) {
		$icons = [
			'logo'      => mp_get_svg_file( 'logo' ),
			'shield'    => '<svg viewBox="0 0 17 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M11.4348 9.1109C11.7242 8.82444 11.7321 8.35184 11.4526 8.05532C11.173 7.75881 10.7117 7.75066 10.4223 8.03712L7.91419 10.52L6.57987 9.18783C6.29168 8.9001 5.83041 8.90624 5.5496 9.20153C5.2688 9.49683 5.27479 9.96946 5.56298 10.2572L7.40358 12.0948C7.68564 12.3764 8.13503 12.3773 8.41825 12.097L11.4348 9.1109Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.438 0.174282C8.83323 -0.0580941 8.16677 -0.0580939 7.562 0.174282L1.73343 2.41384C0.690822 2.81445 0 3.83556 0 4.97663L0 10.5647C0 12.6571 1.41047 14.6164 2.89945 16.1308C4.4222 17.6794 6.19525 18.9299 7.23051 19.6055C8.00231 20.1092 8.973 20.1345 9.7681 19.6587C10.8142 19.0325 12.5901 17.8644 14.1136 16.3335C15.6164 14.8233 17 12.8333 17 10.5647V4.97664C17 3.83557 16.3092 2.81445 15.2666 2.41384L9.438 0.174282ZM8.07364 1.57226C8.34853 1.46663 8.65147 1.46663 8.92636 1.57226L14.7549 3.81181C15.2289 3.99394 15.5429 4.45807 15.5429 4.97664V10.5647C15.5429 12.2173 14.5144 13.8395 13.0933 15.2675C11.6931 16.6747 10.0336 17.7707 9.03314 18.3695C8.71443 18.5602 8.32946 18.5525 8.01329 18.3462C7.0151 17.6948 5.3415 16.5107 3.92595 15.0711C2.47663 13.5971 1.45715 12.0144 1.45715 10.5647L1.45714 4.97663C1.45714 4.45806 1.77106 3.99394 2.24506 3.81181L8.07364 1.57226Z" fill="currentColor"/></svg>',
			'info'      => '<svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M0.77027 9.5C0.77027 5.38477 0.77027 3.32715 2.04871 2.04871C3.32715 0.77027 5.38477 0.77027 9.5 0.77027C13.6152 0.77027 15.6729 0.77027 16.9513 2.04871C18.2297 3.32715 18.2297 5.38477 18.2297 9.5C18.2297 13.6152 18.2297 15.6729 16.9513 16.9513C15.6729 18.2297 13.6152 18.2297 9.5 18.2297C5.38477 18.2297 3.32715 18.2297 2.04871 16.9513C0.77027 15.6729 0.77027 13.6152 0.77027 9.5Z" stroke="currentColor" stroke-width="1.5"/><path d="M9.49982 13.1758H9.50808" stroke="currentColor" stroke-width="2.05405" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.5 10.419L9.5 4.90545" stroke="currentColor" stroke-width="1.54054" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'question'  => '<svg viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="8.5" cy="8.5" r="7.75" stroke="currentColor" stroke-width="1.5"/><path d="M6.8 6.2c0-.94.76-1.7 1.7-1.7s1.7.76 1.7 1.7c0 .68-.4 1.02-.94 1.4-.5.35-.76.62-.76 1.2v.7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8.5 12.8h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'menu'      => '<svg viewBox="0 0 26 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M0 1.20321C0 0.538695 0.447715 0 1 0H25C25.5523 0 26 0.538695 26 1.20321C26 1.86772 25.5523 2.40642 25 2.40642H1C0.447715 2.40642 0 1.86772 0 1.20321Z" fill="currentColor"/><path d="M0 8.79679C0 8.13228 0.447715 7.59358 1 7.59358H25C25.5523 7.59358 26 8.13228 26 8.79679C26 9.4613 25.5523 10 25 10H1C0.447715 10 0 9.4613 0 8.79679Z" fill="currentColor"/></svg>',
			'close'     => '<svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M1.5 1.5 18.5 18.5M18.5 1.5 1.5 18.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>',
			'search'    => '<svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M8.10666 14.6933C11.7444 14.6933 14.6933 11.7444 14.6933 8.10666C14.6933 4.46895 11.7444 1.52 8.10666 1.52C5.37749 1.52 3.036 3.17986 2.03664 5.54514C1.90423 5.85852 1.60715 6.08 1.26694 6.08C0.754541 6.08 0.379464 5.59501 0.56841 5.11872C1.75792 2.12025 4.68469 0 8.10666 0C12.5838 0 16.2133 3.62948 16.2133 8.10666C16.2133 10.2056 15.4157 12.1181 14.107 13.5578C14.1339 13.5762 14.1599 13.5964 14.1849 13.6186L18.7449 17.672C19.0586 17.9508 19.0869 18.4312 18.808 18.7449C18.5292 19.0586 18.0488 19.0869 17.7351 18.808L13.1751 14.7547C13.1098 14.6967 13.0569 14.63 13.0166 14.5578C11.6539 15.5965 9.95231 16.2133 8.10666 16.2133C6.98954 16.2133 5.9252 15.9874 4.95685 15.5787C4.11416 15.223 4.45509 14.1867 5.36976 14.1867C5.50119 14.1867 5.63096 14.2137 5.75372 14.2606C6.48443 14.5402 7.27766 14.6933 8.10666 14.6933Z" fill="currentColor"/><path d="M1.01333 8.36C0.593597 8.36 0.253333 8.70026 0.253333 9.12C0.253333 9.53973 0.593597 9.88 1.01333 9.88H5.06666C5.4864 9.88 5.82666 9.53973 5.82666 9.12C5.82666 8.70026 5.4864 8.36 5.06666 8.36H1.01333Z" fill="currentColor"/><path d="M0.253333 12.16C0.253333 11.7403 0.593597 11.4 1.01333 11.4H8.10666C8.5264 11.4 8.86666 11.7403 8.86666 12.16C8.86666 12.5797 8.5264 12.92 8.10666 12.92H1.01333C0.593597 12.92 0.253333 12.5797 0.253333 12.16Z" fill="currentColor"/></svg>',
			'messenger' => '<svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12.0536 0H12.0194H10.2087H6.9464C1.2255 0 0 1.2274 0 6.9464V12.0536C0 17.7745 1.2274 19 6.9464 19H10.2087H12.0194H12.0536C17.7745 19 19 17.7726 19 12.0536V6.9464C19 1.2255 17.7726 0 12.0536 0Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9.73156 5.85749C7.84255 5.75842 6.36842 7.0692 6.04335 9.12079C5.77416 10.8198 6.25144 12.89 6.65968 12.9942C6.83283 13.0384 7.24951 12.7197 7.55267 12.4338C7.60966 12.38 7.69584 12.371 7.76268 12.4119C8.23525 12.7008 8.77029 12.918 9.36014 12.9489C11.2994 13.0506 13.018 11.5328 13.1196 9.59338C13.2212 7.65392 11.6709 5.95918 9.73156 5.85749ZM6.56827 15.7068C6.49513 15.655 6.39436 15.6691 6.33306 15.7345C5.51378 16.6083 3.41705 17.2212 3.32101 16.0286C3.32101 15.0942 3.11113 14.3068 2.88005 13.4398C2.59704 12.378 2.28223 11.1969 2.28223 9.48023C2.28223 5.38672 5.63942 2.3082 9.61991 2.3082C13.6004 2.3082 16.7213 5.53703 16.7213 9.51998C16.7213 13.5029 13.5011 16.6522 9.65758 16.6522C8.294 16.6522 7.6323 16.4602 6.56827 15.7068Z" fill="var(--icon-knockout, #f3ede1)"/></svg>',
			'mail'      => '<svg viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M16.3929 0H0.607143C0.446119 0 0.291689 0.0622564 0.177828 0.173073C0.0639666 0.28389 0 0.43419 0 0.590909V12.4091C0 12.5658 0.0639666 12.7161 0.177828 12.8269C0.291689 12.9377 0.446119 13 0.607143 13H16.3929C16.5539 13 16.7083 12.9377 16.8222 12.8269C16.936 12.7161 17 12.5658 17 12.4091V0.590909C17 0.43419 16.936 0.28389 16.8222 0.173073C16.7083 0.0622564 16.5539 0 16.3929 0ZM8.87263 7.55736C8.76608 7.63798 8.63496 7.68174 8.5 7.68174C8.36504 7.68174 8.23392 7.63798 8.12737 7.55736L2.18382 3.05832L2.92909 2.12543L8.5 6.3423L14.0709 2.12543L14.8162 3.05832L8.87263 7.55736Z" fill="currentColor"/></svg>',
			'telegram'  => '<svg viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M4.09062 8.69L6.20566 13.9967L8.9582 11.2333L13.6764 15L17 0L0 7.11L4.09062 8.69ZM12.1424 4.30333L6.93945 9.07L6.29199 11.5233L5.09336 8.51667L12.1424 4.30333Z" fill="currentColor"/></svg>',
			'plus'      => '<svg viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M9.32258 0.822581C9.32258 0.368282 8.9543 0 8.5 0C8.0457 0 7.67742 0.368282 7.67742 0.822581V7.67742H0.822581C0.368282 7.67742 0 8.0457 0 8.5C0 8.9543 0.368282 9.32258 0.822581 9.32258H7.67742V16.1774C7.67742 16.6317 8.0457 17 8.5 17C8.9543 17 9.32258 16.6317 9.32258 16.1774V9.32258H16.1774C16.6317 9.32258 17 8.9543 17 8.5C17 8.0457 16.6317 7.67742 16.1774 7.67742H9.32258V0.822581Z" fill="currentColor"/></svg>',
			'arrow-ne'  => '<svg viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M10 0.507433C10 0.227185 9.77281 0 9.49257 0L4.92567 0C4.64542 0 4.41824 0.227186 4.41824 0.507433C4.41824 0.78768 4.64542 1.01487 4.92567 1.01487L8.98513 1.01487L8.98513 5.07433C8.98513 5.35458 9.21232 5.58176 9.49257 5.58176C9.77281 5.58176 10 5.35458 10 5.07433L10 0.507433ZM0.358771 9.64123L0.71758 10L9.85138 0.866242L9.49257 0.507433L9.13376 0.148624L0 9.28242L0.358771 9.64123Z" fill="currentColor"/></svg>',
			'arrow-se'  => '<svg viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M10 9.49257C10 9.77281 9.77281 10 9.49257 10H4.92567C4.64542 10 4.41824 9.77281 4.41824 9.49257C4.41824 9.21232 4.64542 8.98513 4.92567 8.98513H8.98513V4.92567C8.98513 4.64542 9.21232 4.41824 9.49257 4.41824C9.77281 4.41824 10 4.64542 10 4.92567V9.49257ZM0.358771 0.358771L0.71758 0L9.85138 9.13376L9.49257 9.49257L9.13376 9.85138L0 0.71758L0.358771 0.358771Z" fill="currentColor"/></svg>',
			'arrow-right' => '<svg viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M1.5 1 7.5 7l-6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'arrow-left'  => '<svg viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M8.5 1 2.5 7l6 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'polygon'   => '<svg viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 7L0 14L2.85714 7L0 0L12 7Z" fill="currentColor"/></svg>',
			'pin'       => '<svg viewBox="0 0 13 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M8.19995 8.66699C7.19995 8.66699 6.19995 8.66699 5.19995 8.66699C5.22495 9.04255 5.24995 9.4181 5.27495 9.79366C5.72495 16.5537 6.17495 23.3137 6.62495 30.0737C6.64995 30.4492 6.67495 30.8248 6.69995 31.2003C6.72495 30.8248 6.74995 30.4492 6.77495 30.0737C7.22495 23.3137 7.67495 16.5537 8.12495 9.79366C8.14995 9.4181 8.17495 9.04255 8.19995 8.66699Z" fill="currentColor"/><circle cx="6.5" cy="6.5" r="6" fill="currentColor" stroke="currentColor"/></svg>',
			'video'     => '<svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><rect x="0.75" y="0.75" width="17.5" height="17.5" rx="4.5" stroke="currentColor" stroke-width="1.5"/><path d="M7.6 6.2 12.8 9.5 7.6 12.8V6.2Z" fill="currentColor"/></svg>',
		];
	}

	return $icons[ $name ] ?? '';
}

/**
 * Echo an icon.
 *
 * @param string $name  Icon slug.
 * @param string $class Optional wrapper class.
 */
function mp_icon( $name, $class = '' ) {
	$svg = mp_get_icon( $name );

	if ( ! $svg ) {
		return;
	}

	if ( $class ) {
		$svg = preg_replace( '/^<svg /', '<svg class="' . esc_attr( $class ) . '" ', $svg, 1 );
	}

	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted inline SVG.
}
