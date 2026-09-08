<?php
/**
 * REST API and headless compatibility helpers.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register additional REST API support for headless usage.
 *
 * @return void
 */
function ashp_register_rest_api_support() {
	add_filter( 'rest_pre_serve_request', '__return_true' );
	add_filter( 'rest_authentication_errors', '__return_true' );
}
