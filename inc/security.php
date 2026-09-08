<?php
/**
 * Security-related hooks and filters.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register security-focused hooks.
 *
 * @return void
 */
function ashp_register_security_hooks() {
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter( 'pings_open', '__return_false' );
	add_filter( 'comments_open', '__return_false', 20, 2 );
	add_filter( 'pre_option_default_comment_status', '__return_empty_string' );
	add_filter( 'the_content_feed', '__return_false' );

	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}
