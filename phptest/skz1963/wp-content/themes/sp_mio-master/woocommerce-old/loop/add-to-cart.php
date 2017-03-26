<?php
/**
 * Loop Add to Cart
 */
 
global $product; 

if( $product->get_price() === '' && $product->product_type != 'external' ) return;
?>

<?php if ( ! $product->is_in_stock() ) : ?>
		
	<a href="<?php echo apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->id ) ); ?>" class=""><span><?php echo apply_filters( 'out_of_stock_add_to_cart_text', __( 'Details', 'sp' ) ); ?></span></a>

<?php else : ?>
<div class="woo_buy_button_container">	
	<?php 
	
		switch ( $product->product_type ) {
			case "variable" :
				$link 	= apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
				$label 	= apply_filters( 'variable_add_to_cart_text', __('Select options', 'sp') );
			break;
			case "grouped" :
				$link 	= apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
				$label 	= apply_filters( 'grouped_add_to_cart_text', __('View options', 'sp') );
			break;
			case "external" :
				$link 	= apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
				$label 	= apply_filters( 'external_add_to_cart_text', __('Details', 'sp') );
			break;
			default :
				$link 	= apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
				$label 	= apply_filters( 'add_to_cart_text', __('Add to cart', 'sp') );
			break;
		}
	
		printf('<a href="%s" rel="nofollow" data-product_id="%s" class="single_add_to_cart_button product_type_%s"><span>%s</span></a>', $link, $product->id, $product->product_type, $label);

	?>
    <div class="loading_animation">
    	<img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
    </div><!--close woo_loading_animation-->                                        
</div><!--close woo_buy_button_container-->
<?php endif; ?>