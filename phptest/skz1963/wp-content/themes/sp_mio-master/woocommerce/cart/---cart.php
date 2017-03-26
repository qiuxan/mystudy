<?php
/**
 * Cart Page
 */
 
global $woocommerce;
?>


<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post" class="woo_cart">
<?php do_action( 'woocommerce_before_cart_table' ); ?>
	<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery(".slide1").show();
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-263px 0");
			jQuery(".progress_wrapper .cart").addClass('act');
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: "-263px"},600, function() {
				jQuery(".progress_wrapper .cart").addClass('act');	
			});
		}
		
	});
	</script>

<div class="progress_wrapper top">

 <span class="lines"></span>
 <div class="progress_bar"></div><!--close progress_bar-->	
    <ul>
        <li class="cart"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->
<div class="yourtotal"><p><?php _e('Sub-Total:', 'sp'); ?><?php echo $woocommerce->cart->get_cart_subtotal(); ?></p></div>
<?php wc_print_notices(); ?>
<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name"><?php _e('Product', 'sp'); ?></th>
			<th class="product-price"><?php _e('Price', 'sp'); ?></th>
			<th class="product-quantity"><?php _e('Quantity', 'sp'); ?></th>
			<th class="product-subtotal"><?php _e('Total', 'sp'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>
		
		<?php
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">
						<!-- Remove from cart link -->
						<td class="product-remove">
							<?php 
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'sp') ), $cart_item_key ); 
							?>
						</td>
						
						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php 
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
								printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail ); 
							?>
						</td>
						
						<!-- Product Name -->
						<td class="product-name">
							<?php 
								if ( ! $_product->is_visible() || ( $_product instanceof WC_Product_Variation && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );
														
								// Meta data
								echo $woocommerce->cart->get_item_data( $values );
                   				
                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __('Available on backorder', 'sp') . '</p>';
							?>
						</td>
						
						<!-- Product price -->
						<td class="product-price">
							<?php 							
								$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
							
								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key ); 
							?>
						</td>
						
						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php 
								if ( $_product->is_sold_individually() ) {
									$product_quantity = '1';
								} else {
									$data_min = apply_filters( 'woocommerce_cart_item_data_min', '', $_product );
									$data_max = ( $_product->backorders_allowed() ) ? '' : $_product->get_stock_quantity();
									$data_max = apply_filters( 'woocommerce_cart_item_data_max', $data_max, $_product ); 
									
									$product_quantity = sprintf( '<div class="quantity"><input name="cart[%s][qty]" data-min="%s" data-max="%s" value="%s" size="4" title="Qty" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $data_min, $data_max, esc_attr( $values['quantity'] ) );
								}
								
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key ); 					
							?>
						</td>
						
						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php 
								echo apply_filters( 'woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key ); 
							?>
						</td>
					</tr>
					<?php
				}
			}
		}
		
		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( get_option( 'woocommerce_enable_coupons' ) == 'yes' ) { ?>
					<div class="coupon">
					
						<label for="coupon_code"><?php _e('Coupon', 'sp'); ?>:</label> <input name="coupon_code" class="input-text" id="coupon_code" value="" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e('Apply Coupon', 'sp'); ?>" />
						
						<?php do_action('woocommerce_cart_coupon'); ?>
						
					</div>
				<?php } ?>

				<input type="submit" class="update button" name="update_cart" value="<?php _e('Update Cart', 'sp'); ?>" /> 
				
				<?php wp_nonce_field('cart') ?>
			</td>
		</tr>
		
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
<div class="cart-collaterals group">
	
	<?php do_action('woocommerce_cart_collaterals'); ?>
	
	<?php woocommerce_cart_totals(); ?>
	
	<?php woocommerce_shipping_calculator(); ?>
    <a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>" class="checkout-button alt"><span><?php _e('Proceed to Checkout &rarr;', 'sp'); ?></span></a>
    <?php do_action('woocommerce_proceed_to_checkout'); ?>
	
</div>