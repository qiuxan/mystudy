<?php
/**
 * Thankyou Page
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */
 
global $woocommerce;
?>
<div class="thankyou">

<?php if ($order) : ?>
	<?php if (in_array($order->status, array('failed'))) : ?>
				
		<h3><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'sp'); ?></h3>

		<p><?php
			if (is_user_logged_in()) :
				_e('Please attempt your purchase again or go to your account page.', 'sp');
			else :
				_e('Please attempt your purchase again.', 'sp');
			endif;
		?></p>
				
		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'sp') ?></a>
			<?php if (is_user_logged_in()) : ?>
			<a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('myaccount')) ); ?>" class="button pay"><?php _e('My Account', 'sp'); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>
	<script type="text/javascript">
	jQuery(window).load(function() {
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-13px 0");
			jQuery(".progress_wrapper .final").addClass("act");
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: '-13px 0'},600, function() {
				jQuery(".progress_wrapper .final").addClass("act");	
			});
		}
	});
	</script>

<div class="progress_wrapper top">

 <span class="lines"></span>
 <div class="progress_bar"></div><!--close progress_bar-->	
    <ul>
        <li class="cart act"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info act"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->

<div class="wrap">
		<h3><?php _e('Thank you. Your order has been received.', 'sp'); ?></h3>
				
		<ul class="order_details group">
			<li class="order">
				<?php _e('Order:', 'sp'); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e('Date:', 'sp'); ?>
				<strong><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></strong>
			</li>
			<li class="total">
				<?php _e('Total:', 'sp'); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ($order->payment_method_title) : ?>
			<li class="method">
				<?php _e('Payment method:', 'sp'); ?>
				<strong><?php 
					echo $order->payment_method_title;
				?></strong>
			</li>
			<?php endif; ?>
		</ul>
				
	<?php endif; ?>
		
	<div class="order-instruction"><?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?></div>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>
<div id="status-bg">
	<span class="cart active"><span><?php _e('Your Cart','sp'); ?></span></span>
    <span class="info active"><span><?php _e('Info','sp'); ?></span></span>
    <span class="final active"><span><?php _e('Final','sp'); ?></span></span>
</div><!--close status-bg-->
	
	<h3><?php _e('Thank you. Your order has been received.', 'sp'); ?></h3>
<?php endif; ?>
</div><!--close thankyou-->	
