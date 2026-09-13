<?php
/**
 * Contact Message CPT and admin experience.
 *
 * @package AshaduzzamanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Contact Message custom post type.
 *
 * @return void
 */
function ashp_register_contact_message_post_type() {
	$labels = array(
		'name'                  => _x( 'Contact Messages', 'Post type general name', 'ashaduzzaman-portfolio' ),
		'singular_name'         => _x( 'Contact Message', 'Post type singular name', 'ashaduzzaman-portfolio' ),
		'menu_name'             => __( 'Contact Messages', 'ashaduzzaman-portfolio' ),
		'name_admin_bar'        => __( 'Contact Message', 'ashaduzzaman-portfolio' ),
		'add_new'               => __( 'Add New', 'ashaduzzaman-portfolio' ),
		'add_new_item'          => __( 'Add New Contact Message', 'ashaduzzaman-portfolio' ),
		'new_item'              => __( 'New Contact Message', 'ashaduzzaman-portfolio' ),
		'edit_item'             => __( 'View Contact Message', 'ashaduzzaman-portfolio' ),
		'view_item'             => __( 'View Contact Message', 'ashaduzzaman-portfolio' ),
		'all_items'             => __( 'All Contact Messages', 'ashaduzzaman-portfolio' ),
		'search_items'          => __( 'Search Contact Messages', 'ashaduzzaman-portfolio' ),
		'not_found'             => __( 'No contact messages found.', 'ashaduzzaman-portfolio' ),
		'not_found_in_trash'    => __( 'No contact messages found in trash.', 'ashaduzzaman-portfolio' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
		'show_in_rest'       => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => false,
		'has_archive'        => false,
		'rewrite'            => false,
		'supports'           => array( 'title', 'editor' ),
		'menu_icon'          => 'dashicons-email-alt',
		'menu_position'      => 25,
		'capability_type'    => 'post',
		'query_var'          => false,
		'show_in_admin_bar'  => false,
		'can_export'         => false,
	);

	register_post_type( 'contact_message', $args );
}

/**
 * Add custom columns to the Contact Messages list table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function ashp_contact_message_list_columns( array $columns ) {
	$new_columns = array();
	$new_columns['cb']          = $columns['cb'];
	$new_columns['title']       = __( 'Name', 'ashaduzzaman-portfolio' );
	$new_columns['email']       = __( 'Email', 'ashaduzzaman-portfolio' );
	$new_columns['status']      = __( 'Status', 'ashaduzzaman-portfolio' );
	$new_columns['date']        = $columns['date'];

	return $new_columns;
}

/**
 * Populate custom columns for Contact Messages.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 * @return void
 */
function ashp_contact_message_list_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'email':
			$email = get_post_meta( $post_id, '_ashp_contact_email', true );
			echo esc_html( $email );
			break;
		case 'status':
			$status = get_post_meta( $post_id, '_ashp_contact_status', true );
			echo esc_html( $status ?: 'new' );
			break;
	}
}

/**
 * Make the Status column sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function ashp_contact_message_sortable_columns( array $columns ) {
	$columns['status'] = 'status';

	return $columns;
}

/**
 * Handle status column ordering by joining post meta.
 *
 * @param string $orderby  Order by clause.
 * @param WP_Query $query  WP_Query instance.
 * @return string
 */
function ashp_contact_message_orderby( $orderby, $query ) {
	global $pagenow;

	if ( ! is_admin() || 'edit.php' !== $pagenow || 'contact_message' !== get_current_screen()->post_type ) {
		return $orderby;
	}

	if ( 'status' === $query->get( 'orderby' ) ) {
		$orderby = "meta_value";
	}

	return $orderby;
}

/**
 * Add status filter dropdown to Contact Messages admin.
 *
 * @param string $post_type Current post type.
 * @return void
 */
function ashp_contact_message_status_filter( $post_type ) {
	if ( 'contact_message' !== $post_type ) {
		return;
	}

	$current = isset( $_GET['ashp_contact_status'] ) ? sanitize_text_field( wp_unslash( $_GET['ashp_contact_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	?>
	<select name="ashp_contact_status" id="ashp_contact_status">
		<option value=""><?php esc_html_e( 'All Statuses', 'ashaduzzaman-portfolio' ); ?></option>
		<option value="new" <?php selected( $current, 'new' ); ?>><?php esc_html_e( 'New', 'ashaduzzaman-portfolio' ); ?></option>
		<option value="read" <?php selected( $current, 'read' ); ?>><?php esc_html_e( 'Read', 'ashaduzzaman-portfolio' ); ?></option>
		<option value="replied" <?php selected( $current, 'replied' ); ?>><?php esc_html_e( 'Replied', 'ashaduzzaman-portfolio' ); ?></option>
		<option value="archived" <?php selected( $current, 'archived' ); ?>><?php esc_html_e( 'Archived', 'ashaduzzaman-portfolio' ); ?></option>
		<option value="spam" <?php selected( $current, 'spam' ); ?>><?php esc_html_e( 'Spam', 'ashaduzzaman-portfolio' ); ?></option>
	</select>
	<?php
}

/**
 * Filter Contact Messages query by status dropdown.
 *
 * @param WP_Query $query Current query.
 * @return void
 */
function ashp_contact_message_status_filter_query( $query ) {
	global $pagenow;

	if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	if ( ! $post_type || ( is_array( $post_type ) && ! in_array( 'contact_message', $post_type, true ) ) || 'contact_message' !== $post_type ) {
		return;
	}

	if ( isset( $_GET['ashp_contact_status'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['ashp_contact_status'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$status = sanitize_text_field( wp_unslash( $_GET['ashp_contact_status'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$query->set( 'meta_key', '_ashp_contact_status' );
		$query->set( 'meta_value', $status );
	}
}

/**
 * Add Reply by Email link to Contact Message edit screen.
 *
 * @param array $actions Existing action links.
 * @param WP_Post $post Post object.
 * @return array
 */
function ashp_contact_message_row_actions( $actions, $post ) {
	if ( 'contact_message' !== $post->post_type ) {
		return $actions;
	}

	$email = get_post_meta( $post->ID, '_ashp_contact_email', true );
	if ( ! $email ) {
		return $actions;
	}

	$actions['reply_email'] = sprintf(
		'<a href="mailto:%s">%s</a>',
		esc_attr( $email ),
		esc_html__( 'Reply by Email', 'ashaduzzaman-portfolio' )
	);

	return $actions;
}

/**
 * Add status field to Contact Message Quick Edit form.
 *
 * @param string $column_name Column name.
 * @param string $post_type   Post type slug.
 * @return void
 */
function ashp_contact_message_quick_edit( $column_name, $post_type ) {
	if ( 'contact_message' !== $post_type || 'status' !== $column_name ) {
		return;
	}

	$current = isset( $_GET['ashp_contact_status'] ) ? sanitize_text_field( wp_unslash( $_GET['ashp_contact_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<fieldset class="implicit-fieldset">
		<div class="inline-edit-group">
			<label class="alignleft">
				<span class="title"><?php esc_html_e( 'Status', 'ashaduzzaman-portfolio' ); ?></span>
				<select id="ashp-quick-edit-status" name="ashp_contact_status">
					<?php foreach ( array( 'new', 'read', 'replied', 'archived', 'spam' ) as $status ) : ?>
						<option value="<?php echo esc_attr( $status ); ?>">
							<?php echo esc_html( ucfirst( $status ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Populate Quick Edit status dropdown with current value when opening Quick Edit.
 *
 * @return void
 */
function ashp_contact_message_quick_edit_js() {
	$screen = get_current_screen();
	if ( ! $screen || 'edit-contact_message' !== $screen->id ) {
		return;
	}
	?>
	<script type="text/javascript">
	(function($) {
		$(document).on('click', '.editinline', function() {
			var status = $(this).closest('tr').find('td.column-status').text().trim().toLowerCase();
			$('#ashp-quick-edit-status').val(status || 'new');
		});
	})(jQuery);
	</script>
	<?php
}

/**
 * Save Contact Message status from Quick Edit or regular edit screen.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function ashp_save_contact_message_status( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['ashp_contact_status'] ) ) {
		return;
	}

	$status = sanitize_text_field( wp_unslash( $_POST['ashp_contact_status'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$allowed = array( 'new', 'read', 'replied', 'archived', 'spam' );

	if ( in_array( $status, $allowed, true ) ) {
		update_post_meta( $post_id, '_ashp_contact_status', $status );
	}
}

/**
 * Initialize Contact Message status editing admin hooks.
 *
 * @return void
 */
function ashp_initialize_contact_message_status_admin() {
	add_action( 'quick_edit_custom_box', 'ashp_contact_message_quick_edit', 10, 2 );
	add_action( 'admin_footer', 'ashp_contact_message_quick_edit_js' );
	add_action( 'save_post_contact_message', 'ashp_save_contact_message_status' );
}
