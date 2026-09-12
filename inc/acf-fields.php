<?php
/**
 * ACF field group registration.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF local field groups for Projects, Home Page, and Testimonials.
 *
 * @return void
 */
function ashp_register_project_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key' => 'group_ashp_projects',
			'title' => 'Project Fields',
			'fields' => array(
				array(
					'key' => 'field_ashp_project_type',
					'label' => 'Project Type',
					'name' => 'project_type',
					'type' => 'text',
					'instructions' => 'Project type or category, e.g. Web App, Landing Page.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_ashp_project_description',
					'label' => 'Project Description',
					'name' => 'project_description',
					'type' => 'textarea',
					'instructions' => 'Short description shown on project cards. If left empty, the excerpt will be used.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'A short summary of the project for cards and listings.',
					'maxlength' => '',
					'rows' => 3,
				),
				array(
					'key' => 'field_ashp_technologies',
					'label' => 'Technology',
					'name' => 'built_with',
					'type' => 'text',
					'instructions' => 'Comma-separated list of technologies used for the project.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'React, Tailwind, WordPress',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_ashp_live_link',
					'label' => 'Live Link',
					'name' => 'live_link',
					'type' => 'url',
					'instructions' => 'URL to the live project website.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://example.com',
				),
				array(
					'key' => 'field_ashp_github_link',
					'label' => 'Github Link',
					'name' => 'github_link',
					'type' => 'url',
					'instructions' => 'URL to the project repository.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'https://github.com/example/repo',
				),
				array(
					'key' => 'field_ashp_project_outcome',
					'label' => 'Project Outcome',
					'name' => 'project_outcome',
					'type' => 'textarea',
					'instructions' => 'Short outcome or result of the project, shown on the project detail page.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => 'The project improved X, Y, and Z.',
					'maxlength' => '',
					'rows' => 3,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'project',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => 'Fields used by the React frontend for project data.',
		)
	);
}



/**
 * Register ACF field group for Testimonials.
 *
 * @return void
 */
function ashp_register_testimonials_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key' => 'group_ashp_testimonials',
			'title' => 'Testimonial Fields',
			'fields' => array(
				array(
					'key' => 'field_ashp_testimonial_company',
					'label' => 'Company Name',
					'name' => 'company_name',
					'type' => 'text',
					'instructions' => 'The company name for this testimonial.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_ashp_testimonial_designation',
					'label' => 'Designation',
					'name' => 'designation',
					'type' => 'text',
					'instructions' => 'The designation or role of the person.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_ashp_testimonial_rating',
					'label' => 'Rating',
					'name' => 'rating',
					'type' => 'number',
					'instructions' => 'Rating from 1 to 5 for this testimonial.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => 1,
					'max' => 5,
					'step' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'testimonial',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => 'Fields for testimonials used by the frontend.',
		)
	);
}

add_action( 'acf/init', 'ashp_register_project_acf_fields' );
add_action( 'acf/init', 'ashp_register_testimonials_acf_fields' );
