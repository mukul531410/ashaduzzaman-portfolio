<?php
/**
 * Ashaduzzaman Portfolio theme.
 *
 * @package AshaduzzamanPortfolio
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ASHP_THEME_VERSION' ) ) {
	define( 'ASHP_THEME_VERSION', '1.0.0' );
}

if ( ! defined( 'ASHP_THEME_PATH' ) ) {
	define( 'ASHP_THEME_PATH', trailingslashit( get_template_directory() ) );
}

if ( ! defined( 'ASHP_THEME_URL' ) ) {
	define( 'ASHP_THEME_URL', trailingslashit( get_template_directory_uri() ) );
}

require_once ASHP_THEME_PATH . 'inc/helpers.php';
require_once ASHP_THEME_PATH . 'inc/setup.php';
require_once ASHP_THEME_PATH . 'inc/cleanup.php';
require_once ASHP_THEME_PATH . 'inc/enqueue.php';
require_once ASHP_THEME_PATH . 'inc/post-types.php';
require_once ASHP_THEME_PATH . 'inc/api.php';
require_once ASHP_THEME_PATH . 'inc/security.php';
require_once ASHP_THEME_PATH . 'inc/acf-fields.php';

/**
 * Initialize the theme.
 *
 * @return void
 */
function ashp_initialize_theme() {
	ashp_setup_theme();
	ashp_cleanup_headless_wp();
	ashp_register_project_post_type();
	ashp_register_headless_theme_support();
	ashp_register_rest_api_support();
	ashp_register_security_hooks();
}

add_action( 'after_setup_theme', 'ashp_initialize_theme' );
add_action( 'wp_enqueue_scripts', 'ashp_enqueue_theme_assets' );
add_action( 'admin_enqueue_scripts', 'ashp_enqueue_admin_assets' );
