<?php

function webula_get_next_posts( $current_page = 2 ) {
	/**
	 * An array of posts data
	 *
	 * @var array
	 */
	$out = [];

	/**
	 * WP_Query arguments for the archive page
	 *
	 * @var array
	 */
	$query_args = [
		'posts_per_page' => 6,
		'paged'          => $current_page,
		'post_type'      => 'post',
		'post_status'    => 'publish',
	];

	/**
	 * The query object for the archive page
	 *
	 * @var WP_Query
	 */
	$posts_query = new WP_Query( $query_args );

	/**
	 * If there are no posts to display, return an error
	 */
	if ( ! $posts_query->have_posts() ) {
		return $out;
	}

	$i = 0;

	/**
	 * Loop through the posts and prepare the data
	 */
	while ( $posts_query->have_posts() ) {
		$posts_query->the_post();
		$out['posts'][ $i ] = [
			'title'         => [
				'header' => get_the_title(),
			],
			'post_link'     => get_the_permalink(),
			'post_category' => get_the_category()[0]->name,
			'post_excerpt'  => get_the_excerpt(),
			'post_date'     => get_the_date( 'F j, Y' ),
		];

		// Add image to the data
		if ( has_post_thumbnail() ) {
			$out['posts'][ $i ]['post_image'] = [
				'url'   => get_the_post_thumbnail_url( get_the_ID(), 'full' ),
				'title' => get_the_title(),
			];
		} else {
			$out['posts'][ $i ]['post_image'] = [
				'url'   => webula_get_asset( 'img/content/post/post.png' ),
				'title' => get_the_title(),
			];
		}

		// Add additional data for JS template rendering
		$out['posts'][ $i ]['args'] = [
			'img'  => $out['posts'][ $i ]['post_image']['url'],
			'date' => $out['posts'][ $i ]['post_date'],
			'text' => [
				'header' => $out['posts'][ $i ]['text']['header'],
				'desc'   => $out['posts'][ $i ]['post_excerpt'],
			],
			'link' => $out['posts'][ $i ]['post_link'],
		];

		++$i;
	}

	/**
	 * The next page number to retrieve
	 */
	$out['next_page'] = $current_page < intval( $posts_query->max_num_pages ) ? $current_page : 0;

	/**
	 * Reset post data
	 */
	wp_reset_postdata();

	return $out;
}

function webula_add_scripts() {
	if ( is_post_type_archive( 'posts' ) || is_home() ) {
		ob_start();
		get_template_part(
			'template-parts/card',
			'news',
			[
				'text' => [
					'header' => '%title%',
				],
				'link' => '%link%',
				'img'  => [
					'url'   => '%img%',
					'title' => '%title%',
				],
				'date' => '%date%',
			]
		);
		$post_cartd_tpl = ob_get_contents();
		ob_end_clean();

		$post_cartd_tpl = str_replace( 'http://%', '%', $post_cartd_tpl );

		$script_data['tpls']['postCard'] = $post_cartd_tpl;

		$script_data['next_posts'] = webula_get_next_posts();
	}

	if ( ! empty( $script_data ) ) {
		wp_localize_script(
			'webula-script',
			'ajaxdata',
			$script_data
		);
	}
}
add_action( 'wp_enqueue_scripts', 'webula_add_scripts', 11 );
