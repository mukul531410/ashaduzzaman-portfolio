<?php
/**
 * Theme setup and support registration.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme support needed by the headless site.
 *
 * @return void
 */
function ashp_register_headless_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'excerpt' );
	add_theme_support( 'editor-styles' );

	add_theme_support( 'responsive-embeds' );
}

/**
 * Set up theme defaults and navigation menus.
 *
 * @return void
 */
function ashp_setup_theme() {
	load_theme_textdomain( 'ashaduzzaman-portfolio', ASHP_THEME_PATH . 'languages' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'ashaduzzaman-portfolio' ),
		)
	);
}
