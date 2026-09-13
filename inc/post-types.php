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

/**
 * Register the Skills custom post type for headless consumption.
 *
 * @return void
 */
function ashp_register_skills_post_type() {
	$labels = array(
		'name'                  => _x( 'Skills', 'Post type general name', 'ashaduzzaman-portfolio' ),
		'singular_name'         => _x( 'Skill', 'Post type singular name', 'ashaduzzaman-portfolio' ),
		'menu_name'             => __( 'Skills', 'ashaduzzaman-portfolio' ),
		'name_admin_bar'        => __( 'Skill', 'ashaduzzaman-portfolio' ),
		'add_new'               => __( 'Add New', 'ashaduzzaman-portfolio' ),
		'add_new_item'          => __( 'Add New Skill', 'ashaduzzaman-portfolio' ),
		'new_item'              => __( 'New Skill', 'ashaduzzaman-portfolio' ),
		'edit_item'             => __( 'Edit Skill', 'ashaduzzaman-portfolio' ),
		'view_item'             => __( 'View Skill', 'ashaduzzaman-portfolio' ),
		'all_items'             => __( 'All Skills', 'ashaduzzaman-portfolio' ),
		'search_items'          => __( 'Search Skills', 'ashaduzzaman-portfolio' ),
		'not_found'             => __( 'No skills found.', 'ashaduzzaman-portfolio' ),
		'not_found_in_trash'    => __( 'No skills found in trash.', 'ashaduzzaman-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'rest_base'          => 'skills',
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'skills' ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'          => 'dashicons-tag',
		'menu_position'      => 5,
		'capability_type'    => 'post',
		'query_var'          => true,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,
	);

	register_post_type( 'skill', $args );
}

/**
 * Register the Testimonials custom post type for headless consumption.
 *
 * @return void
 */
function ashp_register_testimonials_post_type() {
	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post type general name', 'ashaduzzaman-portfolio' ),
		'singular_name'         => _x( 'Testimonial', 'Post type singular name', 'ashaduzzaman-portfolio' ),
		'menu_name'             => __( 'Testimonials', 'ashaduzzaman-portfolio' ),
		'name_admin_bar'        => __( 'Testimonial', 'ashaduzzaman-portfolio' ),
		'add_new'               => __( 'Add New', 'ashaduzzaman-portfolio' ),
		'add_new_item'          => __( 'Add New Testimonial', 'ashaduzzaman-portfolio' ),
		'new_item'              => __( 'New Testimonial', 'ashaduzzaman-portfolio' ),
		'edit_item'             => __( 'Edit Testimonial', 'ashaduzzaman-portfolio' ),
		'view_item'             => __( 'View Testimonial', 'ashaduzzaman-portfolio' ),
		'all_items'             => __( 'All Testimonials', 'ashaduzzaman-portfolio' ),
		'search_items'          => __( 'Search Testimonials', 'ashaduzzaman-portfolio' ),
		'not_found'             => __( 'No testimonials found.', 'ashaduzzaman-portfolio' ),
		'not_found_in_trash'    => __( 'No testimonials found in trash.', 'ashaduzzaman-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'rest_base'          => 'testimonials',
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'testimonials' ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_icon'          => 'dashicons-admin-comments',
		'menu_position'      => 5,
		'capability_type'    => 'post',
		'query_var'          => true,
		'show_in_admin_bar'  => true,
		'show_in_nav_menus'  => true,
	);

	register_post_type( 'testimonial', $args );
}

/**
 * Remove manual testimonial creation from the admin UI.
 *
 * Programmatic creation via wp_insert_post() still works.
 *
 * @return void
 */
function ashp_remove_testimonial_admin_menus() {
	global $submenu;

	if ( isset( $submenu['edit.php?post_type=testimonial'] ) ) {
		foreach ( $submenu['edit.php?post_type=testimonial'] as $index => $item ) {
			if ( 'post-new.php?post_type=testimonial' === $item[2] ) {
				unset( $submenu['edit.php?post_type=testimonial'][ $index ] );
			}
		}
	}
}

/**
 * Redirect testimonial creation attempts back to the list table.
 *
 * @return void
 */
function ashp_prevent_testimonial_creation() {
	if ( isset( $_GET['post_type'] ) && 'testimonial' === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_redirect( admin_url( 'edit.php?post_type=testimonial' ) );
		exit;
	}
}

/**
 * Replace the normal testimonial edit screen with a read-only detail view.
 *
 * @param string   $screen   Current admin screen ID.
 * @param string   $context  Meta box context.
 * @param WP_Post  $post     Post object.
 * @return void
 */
function ashp_testimonial_readonly_detail_screen( $screen, $context, $post ) {
	if ( 'testimonial' !== $post->post_type ) {
		return;
	}

	remove_post_type_support( 'testimonial', 'editor' );
	remove_meta_box( 'authordiv', 'testimonial', 'normal' );
	remove_meta_box( 'submitdiv', 'testimonial', 'normal' );
	remove_meta_box( 'slugdiv', 'testimonial', 'normal' );

	add_meta_box(
		'ashp_testimonial_detail',
		'Testimonial Details',
		'ashp_testimonial_detail_meta_box',
		'testimonial',
		'normal',
		'default'
	);
}

/**
 * Render read-only testimonial detail meta box.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function ashp_testimonial_detail_meta_box( $post ) {
	$company = get_field( 'company_name', $post->ID );
	$designation = get_field( 'designation', $post->ID );
	$rating = get_field( 'rating', $post->ID );
	?>
	<table class="form-table" style="width:100%;">
		<tr>
			<th style="width:25%;"><?php esc_html_e( 'Name', 'ashaduzzaman-portfolio' ); ?></th>
			<td><?php echo esc_html( $post->post_title ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Company', 'ashaduzzaman-portfolio' ); ?></th>
			<td><?php echo esc_html( $company ?: '' ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Designation', 'ashaduzzaman-portfolio' ); ?></th>
			<td><?php echo esc_html( $designation ?: '' ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Rating', 'ashaduzzaman-portfolio' ); ?></th>
			<td><?php echo esc_html( $rating ?: '' ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Content', 'ashaduzzaman-portfolio' ); ?></th>
			<td><div style="background:#fff;padding:12px;border:1px solid #ccd0d4;white-space:pre-wrap;"><?php echo esc_html( $post->post_content ); ?></div></td>
		</tr>
	</table>
	<?php
}

add_action( 'init', 'ashp_register_project_post_type' );
add_action( 'init', 'ashp_register_skills_post_type' );
add_action( 'init', 'ashp_register_testimonials_post_type' );
add_action( 'admin_menu', 'ashp_remove_testimonial_admin_menus' );
add_action( 'load-post-new.php', 'ashp_prevent_testimonial_creation' );
add_action( 'do_meta_boxes', 'ashp_testimonial_readonly_detail_screen', 10, 3 );
