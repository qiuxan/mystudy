<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 */
 
global $woocommerce;

$customer_id = get_current_user_id();

$args = array(
    'numberposts'     => $recent_orders,
    'meta_key'        => '_customer_user',
    'meta_value'	  => $customer_id,
    'post_type'       => 'shop_order',
    'post_status'     => 'publish' 
);
$customer_orders = get_posts($args);

if ($customer_orders) :
?>
<div class="my-account-wrap">
	<table class="shop_table my_account_orders">
	
		<thead>
			<tr>
				<th class="order-number"><span class="nobr"><?php _e('Order', 'sp'); ?></span></th>
				<th class="order-shipto"><span class="nobr"><?php _e('Ship to', 'sp'); ?></span></th>
				<th class="order-total"><span class="nobr"><?php _e('Total', 'sp'); ?></span></th>
				<th class="order-status" colspan="2"><span class="nobr"><?php _e('Status', 'sp'); ?></span></th>
			</tr>
		</thead>
		
		<tbody><?php
			foreach ($customer_orders as $customer_order) :
				$order = new WC_Order();
				
				$order->populate( $customer_order );
				
				$status = get_term_by('slug', $order->status, 'shop_order_status');
				
				?><tr class="order">
					<td class="order-number">
						<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink(woocommerce_get_page_id('view_order'))) ); ?>"><?php echo $order->get_order_number(); ?></a> &ndash; <time title="<?php echo esc_attr( strtotime($order->order_date) ); ?>"><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></time>
					</td>
					<td class="order-shipto"><address><?php if ($order->get_formatted_shipping_address()) echo $order->get_formatted_shipping_address(); else echo '&ndash;'; ?></address></td>
					<td class="order-total"><?php echo $order->get_formatted_order_total(); ?></td>
					<td class="order-status">
						<span class="<?php echo strtolower( $status->name ); ?>"><?php echo ucfirst( __( $status->name, 'sp' ) ); ?></span>
						<?php if (in_array($order->status, array('pending', 'failed'))) : ?>
							<a href="<?php echo esc_url( $order->get_cancel_order_url() ); ?>" class="cancel" title="<?php _e('Click to cancel this order', 'sp'); ?>">(<?php _e('Cancel', 'sp'); ?>)</a>
						<?php endif; ?>
					</td>
					<td class="order-actions">
												
						<?php if (in_array($order->status, array('pending', 'failed'))) : ?>
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'sp'); ?></a>
						<?php endif; ?>
						
						<a href="<?php echo esc_url( add_query_arg('order', $order->id, get_permalink(woocommerce_get_page_id('view_order'))) ); ?>" class="button"><?php _e('View', 'sp'); ?></a>

					</td>
				</tr><?php
			endforeach;
		?></tbody>
	
	</table>
    </div>
<?php
else :
?>
	<p><?php _e('You have no recent orders.', 'sp'); ?></p>
<?php
endif;
?>
