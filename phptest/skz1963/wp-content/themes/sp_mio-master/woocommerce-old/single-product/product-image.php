<?php
/**
 * Single Product Image
 */

global $post, $woocommerce, $product;

// if less than 2.0
if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {	
	$image_width = get_option( 'woocommerce_single_image_width' );
	$image_height = get_option( 'woocommerce_single_image_height' );
} else {			
	$image_sizes = $woocommerce->get_image_size( 'shop_single' );
	$image_width = $image_sizes['width'];
	$image_height = $image_sizes['height'];
}

?>
<div class="imagecol images">
	<?php if ( has_post_thumbnail() ) : ?>

		<a data-rel="prettyPhoto[<?php echo $post->ID; ?>]" href="<?php echo sp_get_image($post->ID); ?>" class="zoom thickbox preview_link" title="<?php the_title_attribute(); ?>" data-id="<?php echo $post->ID; ?>" onclick="return false;">
        <img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="product_image attachment-shop_single wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo sp_timthumb_format('single_main', sp_get_image($post->ID), $image_width, $image_height); ?>" />
        </a>

	<?php else : ?>
	
        <a data-rel="prettyPhoto[<?php echo $post->ID; ?>]" class="zoom thickbox preview_link" href="<?php echo get_template_directory_uri(); ?>/images/no-product-image.jpg" title="<?php the_title_attribute(); ?>" data-id="<?php echo $post->ID; ?>" onclick="return false;">
        <img class="no-image" alt="No Image" title="<?php the_title_attribute(); ?>" src="<?php echo sp_timthumb_format('single_main', get_template_directory_uri().'/images/no-product-image.jpg', $image_width, $image_height); ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
        </a>
	
	<?php endif; ?>            
  <?php	
	  global $main_image_height; 
	  $main_image_height = $image_height;   
      do_action('woocommerce_product_thumbnails');
  ?>
</div><!--close imagecol-->
