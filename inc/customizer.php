<?php
/**
 * Customizer registration and settings.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Global Website Settings section in Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 * @return void
 */
function ashp_register_global_settings_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section(
        'ashp_global_website_settings',
        array(
            'title'       => __( 'Global Website Settings', 'ashaduzzaman-portfolio' ),
            'priority'    => 30,
            'description' => __( 'Configure global website data such as logo, contact info, social media, and footer copyright.', 'ashaduzzaman-portfolio' ),
        )
    );

    // Logo Setting
    $wp_customize->add_setting(
        'ashp_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'sanitize_js_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'ashp_logo',
            array(
                'label'      => __( 'Website Logo', 'ashaduzzaman-portfolio' ),
                'section'    => 'ashp_global_website_settings',
                'settings'   => 'ashp_logo',
                'priority'   => 10,
            )
        )
    );

    // Phone Setting
    $wp_customize->add_setting(
        'ashp_phone',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'sanitize_js_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        array(
            'label'      => __( 'Phone Number', 'ashaduzzaman-portfolio' ),
            'section'    => 'ashp_global_website_settings',
            'settings'   => 'ashp_phone',
            'type'       => 'tel',
            'priority'   => 20,
        )
    );

    // Email Setting
    $wp_customize->add_setting(
        'ashp_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
            'sanitize_js_callback' => 'sanitize_email',
        )
    );
    $wp_customize->add_control(
        array(
            'label'      => __( 'Email Address', 'ashaduzzaman-portfolio' ),
            'section'    => 'ashp_global_website_settings',
            'settings'   => 'ashp_email',
            'type'       => 'email',
            'priority'   => 30,
        )
    );

    // Address Setting
    $wp_customize->add_setting(
        'ashp_address',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'sanitize_js_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        array(
            'label'      => __( 'Address', 'ashaduzzaman-portfolio' ),
            'section'    => 'ashp_global_website_settings',
            'settings'   => 'ashp_address',
            'type'       => 'textarea',
            'priority'   => 40,
        )
    );

    // Social Media Settings
    $social_profiles = array(
        'facebook'   => __( 'Facebook', 'ashaduzzaman-portfolio' ),
        'twitter'    => __( 'Twitter', 'ashaduzzaman-portfolio' ),
        'instagram'  => __( 'Instagram', 'ashaduzzaman-portfolio' ),
        'linkedin'   => __( 'LinkedIn', 'ashaduzzaman-portfolio' ),
        'github'     => __( 'GitHub', 'ashaduzzaman-portfolio' ),
    );

    $priority = 50;
    foreach ( $social_profiles as $slug => $label ) {
        $setting_name = 'ashp_' . $slug;
        
        $wp_customize->add_setting(
            $setting_name,
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'sanitize_js_callback' => 'esc_url_raw',
            )
        );
        $wp_customize->add_control(
            array(
                'label'      => $label,
                'section'    => 'ashp_global_website_settings',
                'settings'   => $setting_name,
                'type'       => 'url',
                'priority'   => $priority,
            )
        );
        $priority += 10;
    }

    // Footer Copyright Text Setting
    $wp_customize->add_setting(
        'ashp_footer_copyright',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'sanitize_js_callback' => 'wp_kses_post',
        )
    );
    $wp_customize->add_control(
        array(
            'label'      => __( 'Footer Copyright Text', 'ashaduzzaman-portfolio' ),
            'section'    => 'ashp_global_website_settings',
            'settings'   => 'ashp_footer_copyright',
            'type'       => 'text',
            'priority'   => 110,
        )
    );
}
add_action( 'customize_register', 'ashp_register_global_settings_section' );