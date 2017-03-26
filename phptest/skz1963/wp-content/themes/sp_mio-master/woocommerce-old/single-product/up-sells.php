<?php
/**
 * Up-sells
 */

global $product, $woocommerce_loop;

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) return;

$args = array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'posts_per_page' 		=> 4,
	'no_found_rows' 		=> 1,
	'orderby' 				=> 'rand',
	'post__in' 				=> $upsells
);

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>
	
	<div class="upsell products group">
        <?php if (sp_isset_option('upsell_product_title', 'isset')) {
            echo '<h2 class="section-title">' . sp_isset_option('upsell_product_title', 'value') . '</h2>';
        } else {
            echo '<h2 class="section-title">'.__('You May Also Like:', 'sp').'</h2>';	
        };
        ?>
		
		<ul class="products">
			
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
		
				<?php woocommerce_get_template_part( 'content', 'upsell' ); ?>
	
			<?php endwhile; // end of the loop. ?>
				
		</ul>
	</div>
	
<?php endif; 

wp_reset_query();