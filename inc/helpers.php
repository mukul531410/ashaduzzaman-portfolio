<?php
/**
 * Helper functions for the theme.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return a sanitized asset URL.
 *
 * @param string $path Asset path.
 * @return string
 */
function ashp_asset_url( $path ) {
	$path = trim( $path );
	if ( '' === $path ) {
		return '';
	}

	return esc_url( ASHP_THEME_URL . 'assets/' . ltrim( $path, '/' ) );
}

/**
 * Return the versioned asset URL.
 *
 * @param string $path Asset path.
 * @return string
 */
function ashp_versioned_asset_url( $path ) {
	$path = trim( $path );
	if ( '' === $path ) {
		return '';
	}

	return esc_url( ASHP_THEME_URL . 'assets/' . ltrim( $path, '/' ) . '?ver=' . ASHP_THEME_VERSION );
}
