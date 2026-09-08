<?php
/**
 * Headless-specific cleanup hooks.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove unnecessary frontend output for a headless CMS.
 *
 * @return void
 */
function ashp_cleanup_headless_wp() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
	add_filter( 'wp_enqueue_scripts', 'ashp_dequeue_block_assets', 100 );
	add_filter( 'block_editor_settings_all', '__return_false' );
	add_filter( 'default_hidden_meta_boxes', '__return_empty_array' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'init', 'wp_schedule_update_checks' );

	add_filter( 'rest_enabled', '__return_true' );
	add_filter( 'rest_jsonp_enabled', '__return_false' );
}

/**
 * Dequeue block editor assets from the frontend.
 *
 * @param array<string, mixed> $styles Optional styles array.
 * @return array<string, mixed>
 */
function ashp_dequeue_block_assets( $styles ) {
	if ( is_admin() ) {
		return $styles;
	}

	wp_deregister_style( 'wp-block-library' );
	wp_deregister_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_script( 'wp-embed' );

	return $styles;
}
