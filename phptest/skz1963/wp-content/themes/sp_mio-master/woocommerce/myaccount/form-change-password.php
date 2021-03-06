<?php
/**
 * Change Password Form
 */
 
global $woocommerce;
?>

<?php wc_print_notices(); ?>

<form action="<?php echo esc_url( get_permalink(woocommerce_get_page_id('change_password')) ); ?>" method="post" class="change-password">

	<p class="form-row form-row-first">
		<label for="password_1"><?php _e('New password', 'sp'); ?> <span class="required">*</span></label>
		<input type="password" class="input-text" name="password_1" id="password_1" />
	</p>
	<p class="form-row form-row-last">
		<label for="password_2"><?php _e('Re-enter new password', 'sp'); ?> <span class="required">*</span></label>
		<input type="password" class="input-text" name="password_2" id="password_2" />
	</p>
	<div class="group"></div>
	
	<p><input type="submit" class="button" name="change_password" value="<?php _e('Save', 'sp'); ?>" /></p>
	
	<?php wp_nonce_field('change_password')?>
	<input type="hidden" name="action" value="change_password" />
	
</form>