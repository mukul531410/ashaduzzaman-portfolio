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

	add_filter( 'upload_mimes', 'ashp_allow_svg_upload' );
}

/**
 * Allow SVG file uploads.
 *
 * @param array $mimes Existing allowed MIME types.
 * @return array Modified allowed MIME types.
 */
function ashp_allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}

/**
 * Provide attachment metadata for SVG so thumbnails render in the admin.
 *
 * @param array  $response  Attachment data for JS.
 * @param object $attachment Attachment post object.
 * @param array  $meta      Attachment metadata.
 * @return array Modified attachment data.
 */
function ashp_svg_attachment_metadata( $response, $attachment, $meta ) {
	$file = get_attached_file( $attachment->ID );
	if ( $file && strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) === 'svg' ) {
		$response['url']    = wp_get_attachment_url( $attachment->ID );
		$response['icon']   = $response['url'];
		$response['sizes']  = array(
			'thumbnail' => array(
				'url' => $response['url'],
			),
			'medium'    => array(
				'url' => $response['url'],
			),
			'large'     => array(
				'url' => $response['url'],
			),
			'full'      => array(
				'url' => $response['url'],
			),
		);
		$response['image']  = array(
			'attr' => array( 'src' => $response['url'] ),
		);
	}
	return $response;
}

add_filter( 'wp_prepare_attachment_for_js', 'ashp_svg_attachment_metadata', 10, 3 );

/**
 * Return SVG URL for image size lookups so Featured Image preview works.
 *
 * @param array|false $image  Image data or false.
 * @param int         $attach Attachment ID.
 * @param string|int  $size   Requested image size.
 * @return array|false
 */
function ashp_svg_image_src( $image, $attach, $size ) {
	$file = get_attached_file( $attach );
	if ( $file && strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) === 'svg' ) {
		$url = wp_get_attachment_url( $attach );
		return array( $url, 0, 0, false );
	}
	return $image;
}

add_filter( 'wp_get_attachment_image_src', 'ashp_svg_image_src', 10, 4 );
