<?php
/**
 * Custom post type registration.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Projects custom post type for headless consumption.
 *
 * @return void
 */
function ashp_register_project_post_type() {
	$labels = array(
		'name'                  => _x( 'Projects', 'Post type general name', 'ashaduzzaman-portfolio' ),
		'singular_name'         => _x( 'Project', 'Post type singular name', 'ashaduzzaman-portfolio' ),
		'menu_name'             => __( 'Projects', 'ashaduzzaman-portfolio' ),
		'name_admin_bar'        => __( 'Project', 'ashaduzzaman-portfolio' ),
		'add_new'               => __( 'Add New', 'ashaduzzaman-portfolio' ),
		'add_new_item'          => __( 'Add New Project', 'ashaduzzaman-portfolio' ),
		'new_item'              => __( 'New Project', 'ashaduzzaman-portfolio' ),
		'edit_item'             => __( 'Edit Project', 'ashaduzzaman-portfolio' ),
		'view_item'             => __( 'View Project', 'ashaduzzaman-portfolio' ),
		'all_items'             => __( 'All Projects', 'ashaduzzaman-portfolio' ),
		'search_items'          => __( 'Search Projects', 'ashaduzzaman-portfolio' ),
		'not_found'             => __( 'No projects found.', 'ashaduzzaman-portfolio' ),
		'not_found_in_trash'    => __( 'No projects found in trash.', 'ashaduzzaman-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'rest_base'          => 'projects',
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'projects' ),
		'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		'menu_icon'          => 'dashicons-portfolio',
		'menu_position'      => 5,
		'capability_type'    => 'post',
		'query_var'          => true,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,
	);

	register_post_type( 'project', $args );
}
