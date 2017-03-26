<?php
/**
 * The User Account Theme.
 *
 * Displays everything within the user account.  Hopefully much more useable than the previous implementation.
 *
 * @todo This basically shows 'screens' for each of the following: Purchase History, Your Details, Downloads.  Could argue that these should be separate templates.
 *
 * @package WPSC
 * @since WPSC 3.8
 */
global $current_tab; ?>

<div class="myaccount">
	<?php if ( is_user_logged_in() ) : ?>
		<div class="user-profile-links">

			<?php $default_profile_tab = apply_filters( 'wpsc_default_user_profile_tab', 'purchase_history' ); ?>
			<?php $current_tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : $default_profile_tab; ?>

			<?php wpsc_user_profile_links(); ?>

			<?php do_action( 'wpsc_additional_user_profile_links', '|' ); ?>

		</div>

		<?php do_action( 'wpsc_user_profile_section_' . $current_tab ); ?>

	<?php else : ?>

		<?php _e( 'You must be logged in to use this page. Please use the form below to login to your account.', 'wpsc' ); ?>

		<?php echo sp_display_login(); ?>

	<?php endif; ?>
</div><!--close myaccount-->
