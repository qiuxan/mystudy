<?php
/**
 * The template for displaying related products content within loops.
 *
 */
 
global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) 
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) 
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibilty
if ( ! $product->is_visible() ) 
	return; 

// Increase loop count
$woocommerce_loop['loop']++;
?>
<li class="product related_grid_item <?php 
	if ( $woocommerce_loop['loop'] % $woocommerce_loop['columns'] == 0 ) 
		echo 'last'; 
	elseif ( ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] == 0 ) 
		echo 'first'; 
	?>">
    
		<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        	<a href="<?php echo the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php
				do_action( 'woocommerce_before_shop_loop_item_title', 'related_product' ); 
			?>
            </a>             
            <h2 class="prodtitle"><a href="<?php echo the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>      
            <div class="price_display"> 
                 <?php echo $product->get_price_html(); ?>
            </div><!--close price_display-->	
             
        <input type="hidden" value="<?php echo $post->ID; ?>" class="hidden-id product-id" />
    <?php //do_action('woocommerce_after_shop_loop_item_title'); ?> 
    
</li>