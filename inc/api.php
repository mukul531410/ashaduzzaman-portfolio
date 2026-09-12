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

	register_rest_route(
		'ashp/v1',
		'/global',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'ashp_get_global_settings',
			'permission_callback' => 'ashp_allow_public_global_settings',
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
 * Allow public reads of non-sensitive global Customizer settings.
 *
 * @return bool
 */
function ashp_allow_public_global_settings() {
	return true;
}

/**
 * Return public global website settings from the Customizer.
 *
 * @return WP_REST_Response
 */
function ashp_get_global_settings() {
	$email   = sanitize_email( (string) get_theme_mod( 'ashp_email', '' ) );
	$phone   = sanitize_text_field( (string) get_theme_mod( 'ashp_phone', '' ) );
	$address = wp_kses_post( (string) get_theme_mod( 'ashp_address', '' ) );

	$social_keys = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'github' );
	$social      = array();

	foreach ( $social_keys as $key ) {
		$raw = get_theme_mod( 'ashp_' . $key, '' );
		$url = esc_url_raw( (string) $raw );

		if ( '' !== $url ) {
			$social[ $key ] = $url;
		}
	}

	$data = array(
		'email'   => $email,
		'phone'   => $phone,
		'address' => $address,
		'social'  => $social,
	);

	return rest_ensure_response( $data );
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
 * Validate, sanitize, save, and deliver a contact form submission.
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

	if ( ! ashp_check_contact_rate_limit() ) {
		return new WP_Error( 'ashp_rate_limited', 'Too many submissions. Please try again later.', array( 'status' => 429 ) );
	}

	$recipient = sanitize_email( (string) get_theme_mod( 'ashp_email' ) );
	if ( '' === $recipient || ! is_email( $recipient ) ) {
		return new WP_Error( 'ashp_missing_recipient', 'Contact email is not configured.', array( 'status' => 503 ) );
	}

	$contact_id = ashp_save_contact_message( $name, $email, $message );

	if ( ! $contact_id ) {
		return new WP_Error( 'ashp_save_failed', 'Unable to save your message right now.', array( 'status' => 500 ) );
	}

	$subject = 'New Contact Form Message';
	$body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}";
	$headers = array( 'Reply-To: ' . $email );

	$mail_sent = wp_mail( $recipient, $subject, $body, $headers );

	if ( ! $mail_sent ) {
		return new WP_REST_Response(
			array(
				'success'      => true,
				'message'      => 'Your message has been received, but the email notification failed to send. We will still review your message.',
				'contact_id'   => $contact_id,
				'mail_warning' => true,
			),
			200
		);
	}

	return new WP_REST_Response(
		array(
			'success'    => true,
			'message'    => 'Your message has been sent successfully.',
			'contact_id' => $contact_id,
		),
		200
	);
}

/**
 * Save a contact submission to the database.
 *
 * @param string $name    Visitor name.
 * @param string $email   Visitor email.
 * @param string $message Visitor message.
 * @return int|false Post ID on success, false on failure.
 */
function ashp_save_contact_message( $name, $email, $message ) {
	$post_data = array(
		'post_title'  => $name,
		'post_content'=> $message,
		'post_status' => 'private',
		'post_type'   => 'contact_message',
	);

	$post_id = wp_insert_post( $post_data, true );

	if ( is_wp_error( $post_id ) ) {
		return false;
	}

	update_post_meta( $post_id, '_ashp_contact_email', sanitize_email( $email ) );
	update_post_meta( $post_id, '_ashp_contact_status', 'new' );

	return $post_id;
}

/**
 * Check whether the current client has exceeded the contact submission rate limit.
 *
 * Uses client IP address in a transient. Does not store IP permanently.
 *
 * @return bool True if the client is allowed to submit, false if rate limited.
 */
function ashp_check_contact_rate_limit() {
	$ip = ashp_get_client_ip();

	if ( ! $ip ) {
		return false;
	}

	$transient_key = 'ashp_contact_rate_limit_' . md5( $ip );
	$limit         = 3;
	$window        = 300; // 5 minutes in seconds.

	$attempts = (int) get_transient( $transient_key );

	if ( $attempts >= $limit ) {
		return false;
	}

	set_transient( $transient_key, $attempts + 1, $window );

	return true;
}

/**
 * Get the client IP address from server headers.
 *
 * @return string|null IP address or null if unavailable.
 */
function ashp_get_client_ip() {
	$headers = array( 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' );

	foreach ( $headers as $header ) {
		if ( ! empty( $_SERVER[ $header ] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) ); // phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders

			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}
	}

	return null;
}

