<?php
/**
 * Asset enqueuing for the headless theme.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function ashp_enqueue_theme_assets() {
	wp_enqueue_style(
		'ashp-style',
		ashp_asset_url( 'css/style.css' ),
		array(),
		ASHP_THEME_VERSION
	);
}

/**
 * Enqueue admin assets.
 *
 * @param string $hook_suffix The current admin page.
 * @return void
 */
function ashp_enqueue_admin_assets( $hook_suffix ) {
	if ( 'post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix ) {
		return;
	}

	wp_enqueue_style(
		'ashp-admin-style',
		ashp_asset_url( 'css/admin.css' ),
		array(),
		ASHP_THEME_VERSION
	);
}
