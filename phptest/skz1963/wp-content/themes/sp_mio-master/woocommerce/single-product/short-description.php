<?php
/**
 * Single Product Short Description
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $post;

if ( ! $post->post_excerpt ) return;
?>
<div itemprop="description" class="short-description">
	<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
</div>