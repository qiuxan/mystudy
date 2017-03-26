<?php
/**
 * Single Product Thumbnails
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

global $post, $woocommerce, $product, $main_image_height;

$attachment_ids = $product->get_gallery_attachment_ids();

// get image sizes
// if less than 2.0
if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
	$image_thumb_width = get_option( 'woocommerce_thumbnail_image_width' );
	$image_thumb_height = get_option( 'woocommerce_thumbnail_image_height' );
} else {
	$image_sizes = wc_get_image_size( 'shop_thumbnail' );
	$image_thumb_width = $image_sizes['width'];
	$image_thumb_height = $image_sizes['height'];
}

if ( sp_isset_option( 'show_gallery', 'boolean', 'true' ) ) {
	$featured_image = sp_get_image( $post->ID );
	
	if ( $attachment_ids ) { ?>
		<div class="wpcart_gallery group">
        <?php
		$i = 1;
		foreach ( $attachment_ids as $attachment_id ) {
			$link = wp_get_attachment_url( $attachment_id );
			
			if ( ! sp_is_single_product_page() ) {
	
				$sizes = sp_quickview_image_size( $link );
				$image_width = $sizes['image_width'];
				$image_height = $sizes['image_height'];

			} else {
				
				// if less than 2.0
				if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {	
					$image_width = get_option( 'woocommerce_single_image_width' );
					$image_height = get_option( 'woocommerce_single_image_height' );
				} else {			
					$image_sizes = wc_get_image_size( 'shop_single' );
					$image_width = $image_sizes['width'];
					$image_height = $image_sizes['height'];
				}
				
			}
			// display the featured image thumbnail first
			if ( $i <= 1 ) {

				if ( ! sp_is_single_product_page() ) {
					
					$sizes = sp_quickview_image_size( $featured_image );
					$image_width = $sizes['image_width'];
					$image_height = $sizes['image_height'];
					
				} else {
					
					// if less than 2.0
					if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {	
						$image_width = get_option( 'woocommerce_single_image_width' );
						$image_height = get_option( 'woocommerce_single_image_height' );
					} else {			
						$image_sizes = wc_get_image_size( 'shop_single' );
						$image_width = $image_sizes['width'];
						$image_height = $image_sizes['height'];
					}
					
				}
				
				if ( sp_isset_option( 'woo_image_swap', 'boolean', 'true' ) ) {
						echo '<a href="'.$featured_image.'" title="'.get_the_title( $attachment->ID ).'" class="thickbox preview_link" data-src="'.$featured_image.'" data-rel="prettyPhoto['.$post->ID.']" onclick="return false;">'
						.get_the_post_thumbnail( $post->ID, array($image_thumb_width,$image_thumb_height), array( 'class' => 'sp-attachment-thumbnails' ) ).
						'</a>';
					} else {
						echo '<a href="'.$featured_image.'" title="'.get_the_title( $attachment->ID ).'" class="thickbox preview_link" data-src="'.$featured_image.'" data-rel="prettyPhoto['.$post->ID.']" onclick="return false;">'
						.get_the_post_thumbnail( $post->ID, array($image_thumb_width,$image_thumb_height), array( 'class' => '' ) ).
						'</a>';
					}
				}

				if ( get_post_meta( $attachment->ID, '_woocommerce_exclude_image', true ) == 1 ) 
					continue;
				
				if ( sp_isset_option( 'woo_image_swap', 'boolean', 'true' ) ) {	
					echo '<a href="'.$link.'" title="'.get_the_title( $attachment->ID ).'" class="thickbox preview_link" data-src="'.$link .'" data-rel="prettyPhoto['.$post->ID.']" onclick="return false;">'					
					
					.wp_get_attachment_image( $attachment_id, array($image_thumb_width,$image_thumb_height),'', array( 'class' => 'sp-attachment-thumbnails' ) ).
					'</a>';
				} else {
					echo '<a href="'.$link.'" title="'.get_the_title( $attachment->ID ).'" class="thickbox preview_link" data-src="'.$link .'" data-rel="prettyPhoto['.$post->ID.']" onclick="return false;">'
					.wp_get_attachment_image( $attachment_id, array($image_thumb_width,$image_thumb_height),'', array( 'class' => '' ) ).
					
					'</a>';
				}
			$i++;
		}
		?>
		</div><!--close wpcart_gallery-->
<?php        
	}
}