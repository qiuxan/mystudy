<?php
// displays also bought items
function sp_wpsc_also_bought( $product_id ) {
	/*
	 * Displays products that were bought aling with the product defined by $product_id
	 * most of it scarcely needs describing
	 */
	global $wpdb;

	if ( get_option( 'wpsc_also_bought' ) == 0 ) {
		//returns nothing if this is off
		return '';
	}
	

	// to be made customiseable in a future release
	$also_bought_limit = 4;
	$element_widths = 96;
	$image_height = sp_get_theme_init_setting('wpec_also_bought_image_size','height');
	$image_width = sp_get_theme_init_setting('wpec_also_bought_image_size','width');
	
	$output = '';
	$also_bought = $wpdb->get_results( $wpdb->prepare( "SELECT `" . $wpdb->posts . "`.* FROM `" . WPSC_TABLE_ALSO_BOUGHT . "`, `" . $wpdb->posts . "` WHERE `selected_product`='" . $product_id . "' AND `" . WPSC_TABLE_ALSO_BOUGHT . "`.`associated_product` = `" . $wpdb->posts . "`.`id` AND `" . $wpdb->posts . "`.`post_status` IN('publish','protected') AND `" . $wpdb->posts . "`.`post_type` IN ('wpsc-product') ORDER BY `" . WPSC_TABLE_ALSO_BOUGHT . "`.`quantity` DESC LIMIT $also_bought_limit", $product_id ), ARRAY_A );
	if ( count( $also_bought ) > 0 ) {
		$output .= '<div class="wpsc_also_bought group">';
		if (sp_isset_option( 'cross_sales_title', 'isset' )) {
			$cross_sales_title = sp_isset_option( 'cross_sales_title', 'value' );
		}
		$output .= '<h2>' . sprintf( __( '%s', 'sp' ), $cross_sales_title ) . "</h2>";		
		$output .= "<ul>";
		foreach ( (array)$also_bought as $also_bought_data ) {
			$output .= '<li class="wpsc_also_bought_item">';
			if ( get_option( 'show_thumbnails' ) == 1 ) {
				if ( sp_check_ms_image(wpsc_the_product_thumbnail(96,96,$also_bought_data['ID']))) {
					$output .= "<a href='" . get_permalink($also_bought_data['ID']) . "' class=''  data-rel='" . str_replace( " ", "_", get_the_title($also_bought_data['ID']) ) . "'>";
					$image_src = sp_wpsc_the_product_image($also_bought_data['ID']);
					$output .= get_the_post_thumbnail( $also_bought_data['ID'], array($image_width,$image_height), array( 'class' => 'product_image' ) );
					$output .= "</a>";
				} else {
					$output .= "<a href='" . get_permalink($also_bought_data['ID']) . "' class=''  data-rel='" . str_replace( " ", "_", get_the_title($also_bought_data['ID']) ) . "'>";
					$image_src = sp_wpsc_the_product_image($also_bought_data['ID']);
					$output .= '<img src="'.get_template_directory_uri().'/images/no-product-image.jpg" class="product_image" alt="'.get_the_title($also_bought_data['ID']).'">';
					$output .= "</a>";
				}
			}

			$output .= "<a class='wpsc_product_name' href='" . get_permalink($also_bought_data['ID']) . "'>" . get_the_title($also_bought_data['ID']) . "</a>";
			$price = get_product_meta($also_bought_data['ID'], 'price', true);
			$special_price = get_product_meta($also_bought_data['ID'], 'special_price', true);
			if(!empty($special_price)){
				$output .= '<span class="oldprice">' . wpsc_currency_display( $price ) . '</span>';
				$output .= wpsc_currency_display( $special_price );
			} else {
				$output .= wpsc_currency_display( $price );
			}
			$output .= "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
	return $output;
}
?>