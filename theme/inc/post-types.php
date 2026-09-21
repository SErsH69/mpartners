<?php

function webula_register_cases_post_type() {
	$labels = [
		'name'          => 'Cases',
		'singular_name' => 'Case',
		'add_new_item'  => 'Add case',
		'add_new'       => 'Add case',
		'edit_item'     => 'Edit case',
		'update_item'   => 'Update case',
	];

	$supports = [
		'title',        // Post title
		'editor',       // Post content
		'author',       // Allows showing and choosing author
		'thumbnail',    // Allows feature images
		'revisions',    // Shows autosaved version of the posts
		'custom-fields', // Allows custom fields
	];

	$args = [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'menu_icon'          => 'dashicons-money-alt',
		'show_in_rest'       => true,
		'has_archive'        => true,
		'hierarchical'       => true,
		'rewrite'            => [
			'slug'       => 'cases',
			'with_front' => true,
		],
		'supports'           => $supports,
		'taxonomies'         => [ 'cases_category' ],
	];

	register_post_type( 'cases', $args );
}
add_action( 'init', 'webula_register_cases_post_type' );

function webula_register_cases_categories() {
	$labels = [
		'name'              => 'Cases Categories',
		'singular_name'     => 'Cases Category',
		'search_items'      => 'Search Cases Categories',
		'all_items'         => 'All Cases Categories',
		'parent_item'       => 'Parent Category',
		'parent_item_colon' => 'Parent Category:',
		'edit_item'         => 'Edit Cases Category',
		'update_item'       => 'Update Cases Category',
		'add_new_item'      => 'Add New Cases Category',
		'new_item_name'     => 'New Cases Category Name',
		'menu_name'         => 'Cases Categories',
	];

	$args = [
		'hierarchical'       => true,
		'labels'             => $labels,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'show_admin_column'  => true,
		'query_var'          => true,
		'rewrite'            => [ 'slug' => 'cases-category' ],
	];

	register_taxonomy( 'cases_category', [ 'cases' ], $args );
}
add_action( 'init', 'webula_register_cases_categories' );
