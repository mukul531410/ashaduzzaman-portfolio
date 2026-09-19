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
	$logo    = esc_url_raw( (string) get_theme_mod( 'ashp_logo', '' ) );
	$site_icon = esc_url_raw( (string) get_site_icon_url() );

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
		'email'      => $email,
		'phone'      => $phone,
		'address'    => $address,
		'logo'       => $logo,
		'site_icon'  => $site_icon,
		'social'     => $social,
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
		'availability',
		'hero_title',
		'sub_title',
		'hero_content',
		'upwork_link',
		'freelancer_link',
		'booking_link',
		'resume_upload',
		'picture',
		'section_title',
		'highlight_title',
		'about_me',
		'service_items',
		'technical_skills_section_title',
		'technical_skills_content',
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
	$field_output_map = array(
		'technical_skills_section_title' => 'skills_section_title',
		'technical_skills_content'       => 'skills_section_description',
	);

	foreach ( $fields as $field ) {
		$output_key = $field_output_map[ $field ] ?? $field;

		if ( 'service_items' === $field ) {
			$items = array();

			for ( $i = 1; $i <= 4; $i++ ) {
				$item = get_field( 'service_item_' . $i, 12 );

				if ( is_array( $item ) ) {
					$icon_url = '';

					if ( is_array( $item['icon'] ) && ! empty( $item['icon']['url'] ) ) {
						$icon_url = $item['icon']['url'];
					}

					$items[] = array(
						'number' => (string) ( $item['number_of_serve'] ?? '' ),
						'title'  => (string) ( $item['title_of_service'] ?? '' ),
						'icon'   => $icon_url,
					);
				}
			}

			$data[ $output_key ] = $items;
			continue;
		}

		$value = function_exists( 'get_field' ) ? get_field( $field, 12 ) : '';

		if ( in_array( $field, array( 'resume_upload', 'picture' ), true ) && is_array( $value ) ) {
			$data[ $output_key ] = ! empty( $value['url'] ) ? $value['url'] : '';
		} elseif ( is_string( $value ) ) {
			$data[ $output_key ] = $value;
		} else {
			$data[ $output_key ] = '';
		}
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

	$recipient   = sanitize_email( (string) get_theme_mod( 'ashp_email', '' ) );
	$recipient = empty($recipient) ? 'mukul.ashad@gmail.com' : sanitize_email($recipient);

	$contact_id = ashp_save_contact_message( $name, $email, $message );

	if ( ! $contact_id ) {
		return new WP_Error( 'ashp_save_failed', 'Unable to save your message right now.', array( 'status' => 500 ) );
	}

	$website = '';
	if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		$website = esc_url_raw( (string) $_SERVER['HTTP_REFERER'] );
	} elseif ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$website = 'https://' . sanitize_text_field( (string) $_SERVER['HTTP_HOST'] );
	}
	$submission_time = current_time( 'mysql' );

	$subject = 'New Contact Form Message - WPServicesHub';
	$body    = "Visitor Name: {$name}\nVisitor Email: {$email}\nWebsite: {$website}\nSubmission Time: {$submission_time}\n\nMessage:\n{$message}";
	$headers = array(
		'From: WPServicesHub <info@wpserviceshub.com>',
		'Reply-To: ' . $email,
	);

	$mail_sent = wp_mail( $recipient, $subject, $body, $headers );

	if ( ! $mail_sent ) {
		error_log( 'Contact form mail failure: wp_mail() returned false for submission #' . $contact_id );
		return new WP_REST_Response(
			array(
				'success'      => false,
				'message'      => 'Your message was received, but email delivery failed.',
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

