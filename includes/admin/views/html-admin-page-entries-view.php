<?php
/**
 * Admin View: Edit trackers
 *
 * @package EverestForms/Admin/Entries/Views
 */

defined( 'ABSPATH' ) || exit;

$hide_empty = isset( $_COOKIE['everest_forms_entry_hide_empty'] ) && 'true' === $_COOKIE['everest_forms_entry_hide_empty'] ;

?>
<div class="wrap everest-forms">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'View Entry', 'everest-forms' ); ?></h1>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=evf-entries&amp;form_id=' . $form_id ) ); ?>" class="page-title-action"><?php esc_html_e( 'Back to All Entries', 'everest-forms' ); ?></a>
	<hr class="wp-header-end">
	<div class="everest-forms-entry">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!-- Entry Fields metabox -->
				<div id="post-body-content" style="position: relative;">
					<div id="everest-forms-entry-fields" class="postbox">
						<h2 class="hndle">
							<span><?php printf( __( '%s : Entry # %s', 'everest-forms' ), esc_html( _draft_or_post_title( $form_id ) ), absint( $entry_id ) ); ?></span>
							<a href="#" class="everest-forms-empty-field-toggle">
								<?php echo $hide_empty ? esc_html__( 'Show Empty Fields', 'everest-forms' ) : esc_html__( 'Hide Empty Fields', 'everest-forms' ); ?>
							</a>
						</h2>
						<div class="inside">
							<table class="wp-list-table widefat fixed striped posts">
								<tbody>
								<?php
									$entrymeta = apply_filters( 'everest_forms_entry_single_data', $entrymeta );

									if ( empty( $entry ) ) {
										// Whoops, no fields! This shouldn't happen under normal use cases.
										echo '<p class="no-fields">' . esc_html__( 'This entry does not have any fields.', 'everest-forms' ) . '</p>';
									} else {
										// Display the fields and their values.
										foreach ( $entrymeta as $key => $field ) {
											$field_value = apply_filters( 'everest_forms_html_field_value', wp_strip_all_tags( $field['meta_value'] ) );
											$field_class = empty( $field_value ) ? ' empty' : '';
											$field_style = $hide_empty && empty( $field_value ) ? 'display:none;' : '';

											echo '<div class="everest-forms-entry-field ' . $field_class . '" style="' . $field_style . '">';

												// Field name.
												echo '<tr class="field-name"><th><strong>';
													/* translators: %d - field ID. */
													echo ! empty( $field['name'] ) ? wp_strip_all_tags( $field['name'] ) : sprintf( esc_html__( 'Field ID #%d', 'everest-forms' ), absint( $key ) );
												echo '</strong></th></tr>';

												// Field value.
												echo '<tr class="field-value"><td>';
													echo ! empty( $field_value ) ? nl2br( make_clickable( $field_value ) ) : esc_html__( 'Empty', 'everest-forms' );
												echo '</td></tr>';

											echo '</div>';
										}
									}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- Entry Details metabox -->
				<div id="postbox-container-1" class="postbox-container">
					<div id="everest-forms-entry-details" class="postbox">
						<h2 class="hndle">
							<span><?php esc_html_e( 'Entry Details' , 'everest-forms' ); ?></span>
						</h2>
						<div class="inside">
							<div class="everest-forms-entry-details-meta">
								<p class="everest-forms-entry-id">
									<span class="dashicons dashicons-admin-network"></span>
									<?php esc_html_e( 'Entry ID:', 'everest-forms' ); ?>
									<strong><?php echo absint( $entry_id ); ?></strong>
								</p>

								<p class="everest-forms-entry-date">
									<span class="dashicons dashicons-calendar"></span>
									<?php esc_html_e( 'Submitted:', 'everest-forms' ); ?>
									<strong><?php echo date_i18n( esc_html__( 'M j, Y @ g:ia' ), strtotime( $entry->date_created ) + ( get_option( 'gmt_offset' ) * 3600 ) ); ?> </strong>
								</p>

								<?php if ( ! empty( $entry->user_id ) && 0 !== $entry->user_id ) : ?>
									<p class="everest-forms-entry-user">
										<span class="dashicons dashicons-admin-users"></span>
										<?php
										esc_html_e( 'User:', 'everest-forms' );
										$user      = get_userdata( $entry->user_id );
										$user_name = esc_html( ! empty( $user->display_name ) ? $user->display_name : $user->user_login );
										$user_url = esc_url(
											add_query_arg(
												array(
													'user_id' => absint( $user->ID ),
												),
												admin_url( 'user-edit.php' )
											)
										);
										?>
										<strong><a href="<?php echo $user_url; ?>"><?php echo $user_name; ?></a></strong>
									</p>
								<?php endif; ?>

								<?php if ( ! empty( $entry->user_ip_address ) ) : ?>
									<p class="everest-forms-entry-ip">
										<span class="dashicons dashicons-location"></span>
										<?php esc_html_e( 'User IP:', 'everest-forms' ); ?>
										<strong><?php echo esc_html( $entry->user_ip_address ); ?></strong>
									</p>
								<?php endif; ?>

								<?php if ( apply_filters( 'everest_forms_entry_details_sidebar_details_status', false, $entry ) ) : ?>
									<p class="everest-forms-entry-status">
										<span class="dashicons dashicons-category"></span>
										<?php esc_html_e( 'Status:', 'everest-forms' ); ?>
										<strong><?php echo ! empty( $entry->status ) ? ucwords( sanitize_text_field( $entry->status ) ) : esc_html__( 'Completed', 'everest-forms' ); ?></strong>
									</p>
								<?php endif; ?>

								<?php do_action( 'everest_forms_entry_details_sidebar_details', $entry, $entrymeta ); ?>
							</div>

							<div id="major-publishing-actions">
								<div id="delete-action">
									<a class="submitdelete" aria-label="<?php echo esc_attr__( 'Delete form entry', 'everest-forms' ); ?>" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array(
										'delete' => $entry_id,
									), admin_url( 'admin.php?page=evf-entries&amp;form_id=' . $form_id ) ), 'delete-entry' ) ); ?>"><?php esc_html_e( 'Delete Entry', 'everest-forms' ); ?></a>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
