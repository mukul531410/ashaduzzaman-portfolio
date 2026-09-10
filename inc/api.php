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
	add_action( 'rest_api_init', 'ashp_register_custom_rest_routes' );
}

/**
 * Register the public, validated contact endpoint and the whitelisted Home Page data endpoint.
 *
 * @return void
 */
function ashp_register_custom_rest_routes() {
	register_rest_route(
		'ashp/v1',
		'/contact',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'ashp_handle_contact_submission',
			'permission_callback' => 'ashp_allow_public_contact_submission',
		)
	);

	register_rest_route(
		'ashp/v1',
		'/home',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'ashp_get_home_page_settings',
			'permission_callback' => 'ashp_allow_public_home_settings',
		)
	);
}

/**
 * Allow the contact endpoint without requiring a logged-in WordPress user.
 *
 * @return bool
 */
function ashp_allow_public_contact_submission() {
	return true;
}

/**
 * Allow public reads of the non-sensitive Home Page settings.
 *
 * @return bool
 */
function ashp_allow_public_home_settings() {
	return true;
}

/**
 * Return only the Home Page fields consumed by the headless frontend.
 *
 * @return WP_REST_Response
 */
function ashp_get_home_page_settings() {
	$fields = array(
		'projects_section_title',
		'projects_content',
		'projects_archive_button_text',
		'testimonials_section_title',
		'testimonials_content',
		'articles_section_title',
		'articles_content',
		'articles_archive_button_text',
		'contact_section_title',
		'contact_section_description',
		'contact_info_title',
		'contact_info_description',
	);
	$data   = array();

	foreach ( $fields as $field ) {
		$value          = function_exists( 'get_field' ) ? get_field( $field, 12 ) : '';
		$data[ $field ] = is_string( $value ) ? $value : '';
	}

	return rest_ensure_response( $data );
}

/**
 * Validate, sanitize, and deliver a contact form submission.
 *
 * @param WP_REST_Request $request REST request.
 * @return WP_REST_Response|WP_Error
 */
function ashp_handle_contact_submission( WP_REST_Request $request ) {
	$name    = sanitize_text_field( (string) $request->get_param( 'name' ) );
	$email   = sanitize_email( (string) $request->get_param( 'email' ) );
	$message = sanitize_textarea_field( (string) $request->get_param( 'message' ) );

	if ( '' !== (string) $request->get_param( 'honeypot' ) ) {
		return new WP_Error( 'ashp_spam_rejected', 'Unable to process this submission.', array( 'status' => 400 ) );
	}

	if ( '' === $name || strlen( $name ) > 120 ) {
		return new WP_Error( 'ashp_invalid_name', 'Please provide a valid name.', array( 'status' => 400 ) );
	}

	if ( '' === $email || strlen( $email ) > 254 || ! is_email( $email ) ) {
		return new WP_Error( 'ashp_invalid_email', 'Please provide a valid email address.', array( 'status' => 400 ) );
	}

	if ( '' === $message || strlen( $message ) > 5000 ) {
		return new WP_Error( 'ashp_invalid_message', 'Please provide a message under 5000 characters.', array( 'status' => 400 ) );
	}

	$recipient = sanitize_email( (string) get_theme_mod( 'ashp_email' ) );
	if ( '' === $recipient || ! is_email( $recipient ) ) {
		return new WP_Error( 'ashp_missing_recipient', 'Contact email is not configured.', array( 'status' => 503 ) );
	}

	$subject = 'New Contact Form Message';
	$body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
	$headers = array( 'Reply-To: ' . $email );

	if ( ! wp_mail( $recipient, $subject, $body, $headers ) ) {
		return new WP_Error( 'ashp_mail_failed', 'Unable to send your message right now.', array( 'status' => 500 ) );
	}

	return new WP_REST_Response( array( 'success' => true ), 200 );
}
