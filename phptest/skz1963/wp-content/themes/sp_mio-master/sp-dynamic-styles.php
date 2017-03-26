<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* dynamic code
******************************************************************/
if ( class_exists( 'WP_eCommerce' ) ) 
{

$image_width = get_option('product_image_width');
$single_image_width  = get_option( 'single_view_image_width' );
$post_thumbnail_width =	get_option('thumbnail_size_w');

/*$output .= '.product_grid_display .grid_product_info {width:'.$image_width.'px;}';
$output .= '.product_grid_display .product_grid_item {width:'.$image_width.'px;}';
$output .= '.default_product_display .productcol {width:'.(640 - $image_width).'px;}';
$output .= '#container.onecolumn #content .default_product_display .productcol {width:'.(920 - $image_width).'px;}';
$output .= '.product_grid_item .grid_more_info {width:'.$image_width.'px;}';
$output .= '.single_product_display .productcol {width:'.(900 - $single_image_width).'px;}';
$output .= '.single_product_display .wpcart_gallery {width:'.($single_image_width + 5).'px;}'; */
//$output .= '#content_container article.post .title-container {width:'.(553 - $post_thumbnail_width).'px;}';
//$output .= '#content_container #container.onecolumn #content article.post .title-container {width:'.(823 - $post_thumbnail_width).'px;}';
$output .= '.single_product_display .imagecol {width:' . ( $single_image_width + 5 ) . 'px;}';
}

if ( class_exists( 'woocommerce' ) )
{
	$image_width  = get_option( 'shop_catalog_image_size' );
	$post_image_width = get_option('thumbnail_size_w');
}

?>