<?php
/**
 * External Add to Cart
 */
?>
<?php do_action('woocommerce_before_add_to_cart_button'); ?>
        <div class="woo_buy_button_container group">
			<a href="<?php echo $product_url; ?>" rel="nofollow" class="external-button alt add_to_cart_button"><span><?php echo apply_filters('single_add_to_cart_text', $button_text, 'external'); ?></span></a>
            <div class="loading_animation">
                <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
            </div><!--close loading_animation-->                                        
       
		</div><!--close wpsc_buy_button_container-->

<?php do_action('woocommerce_after_add_to_cart_button'); ?>