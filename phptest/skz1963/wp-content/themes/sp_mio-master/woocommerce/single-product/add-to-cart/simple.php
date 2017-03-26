<?php
/**
 * Simple Product Add to Cart
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */
 
global $woocommerce, $product;

if ( ! $product->is_purchasable() ) return;
?>

<?php 
	// Availability
	$availability = $product->get_availability();
	
	if ($availability['availability']) :
		echo apply_filters( 'woocommerce_stock_html', '<p class="stock '.$availability['class'].'">'.$availability['availability'].'</p>', $availability['availability'] );
    endif;
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action('woocommerce_before_add_to_cart_form'); ?>
	
	<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype='multipart/form-data'>

	 	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	 	<?php 
	 		if ( ! $product->is_sold_individually() ) 
	 			woocommerce_quantity_input( array( 'min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity() ) ); 
	 	?>

        <div class="woo_buy_button_container group">
            <div class="input-button-buy"><span><button type="submit" class="single_add_to_cart_button button alt" data-product_id="<?php echo $product->id; ?>"><?php echo apply_filters('single_add_to_cart_text', __('add to cart', 'sp'), $product->product_type); ?></button></span>
            </div><!--close input-button-buy-->
            <div class="loading_animation">
                <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
                
            </div><!--close woo_loading_animation-->                                        
        
		</div><!--close woo_buy_button_container-->

	 	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

	</form>
	
	<?php do_action('woocommerce_after_add_to_cart_form'); ?>
	
<?php endif; ?>