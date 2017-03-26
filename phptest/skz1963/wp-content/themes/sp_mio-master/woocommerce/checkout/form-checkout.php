<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php global $woocommerce; $woocommerce_checkout = $woocommerce->checkout(); ?>

	<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery(".slide1").show();
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-119px 0");
			jQuery(".progress_wrapper .info").addClass('act');
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: "-119px"},600, function() {
				jQuery(".progress_wrapper .info").addClass('act');	
			});
		}
		
	});
	</script>
<div class="progress_wrapper top">

 <span class="lines"></span>
 <div class="progress_bar"></div><!--close progress_bar-->	
    <ul>
        <li class="cart act"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->

<?php wc_print_notices(); ?>

<?php do_action('woocommerce_before_checkout_form');

// If checkout registration is disabled and not logged in, the user cannot checkout
if (get_option('woocommerce_enable_signup_and_login_from_checkout')=="no" && get_option('woocommerce_enable_guest_checkout')=="no" && !is_user_logged_in()) :
	echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'sp'));
	return;
endif;

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>

<form name="checkout" method="post" class="checkout group" action="<?php echo esc_url( $get_checkout_url ); ?>">
	
	<?php if (sizeof($woocommerce_checkout->checkout_fields)>0) : ?>
	
	<div class="col2-set group" id="customer_details">
		<div class="col-1 group">

			<?php do_action('woocommerce_checkout_billing'); ?>
						
		</div>
		<div class="col-2 group">
		
			<?php do_action('woocommerce_checkout_shipping'); ?>
					
		</div>
	</div>
	
	<h3 id="order_review_heading"><?php _e('Your order', 'sp'); ?></h3>
	
	<?php endif; ?>

	<?php do_action('woocommerce_checkout_order_review'); ?>
	
</form>

<?php do_action('woocommerce_after_checkout_form'); ?>
<div class="group"></div>
