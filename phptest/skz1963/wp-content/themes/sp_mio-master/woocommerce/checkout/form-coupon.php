<?php
/**
 * Checkout Coupon Form
 * @package 	WooCommerce/Templates
 * @version     2.2
 */
if ( get_option( 'woocommerce_enable_coupons' ) == 'no' || get_option( 'woocommerce_enable_coupon_form_on_checkout' ) == 'no' ) return;

$info_message = apply_filters('woocommerce_checkout_coupon_message', __('Have a coupon?', 'sp'));
?>

<p class="woocommerce_info"><?php echo $info_message; ?> <a href="#" class="showcoupon"><?php _e('Click here to enter your code', 'sp'); ?></a></p>

<form class="checkout_coupon" method="post">

	<p class="form-row form-row-first">
		<input name="coupon_code" class="input-text" placeholder="<?php _e('Coupon code', 'sp'); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="button" name="apply_coupon" value="<?php _e('Apply Coupon', 'sp'); ?>" />
	</p>
	
	<div class="group"></div>
</form>