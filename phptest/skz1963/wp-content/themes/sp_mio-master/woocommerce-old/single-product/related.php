<?php
/**
 * Related Products
 */

global $product, $woocommerce_loop;

$related = $product->get_related(); 

if ( sizeof($related) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $posts_per_page,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= $columns;

if ( $products->have_posts() ) : ?>

	<div class="related products group">
		<?php if (sp_isset_option('related_product_title', 'isset')) {
            echo '<h2 class="section-title">' . sp_isset_option( 'related_product_title', 'value' ) . '</h2>';
        } else {
            echo '<h2 class="section-title">' . __('Related Products', 'sp') . '</h2>';	
        };
		?>		
		<ul class="products">
			
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
		
				<?php woocommerce_get_template_part( 'content', 'related' ); ?>
	
			<?php endwhile; // end of the loop. ?>
				
		</ul>
		
	</div>
	
<?php endif; 

wp_reset_query();
