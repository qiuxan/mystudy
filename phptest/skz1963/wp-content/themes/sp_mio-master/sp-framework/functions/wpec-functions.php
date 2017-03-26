<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* WPEC functions
******************************************************************/
/**
 * get list of product objects from selected categories
 *
 * @since 2.0.3
 * @param array $category_id pass in the category ids
 * @param int $count pass in the limit to return
 * @return array object of product ids
 */ 
if ( ! function_exists( 'sp_wpec_get_products' ) )
{ 
	function sp_wpec_get_products( $category_ids = 1,  $count = 8, $rand = "false" ) 
	{
		if ( ! is_array( $category_ids ) )
			$category_ids = (array)$category_ids;
			
		if ( in_array( '0', $category_ids ) ) 
			return null;

		// check if random is true
		if ( $rand == "true" )
		{
			$rand = 'rand';
		}
		else
		{
			$rand = 'ASC';
		}

		$args = array(
			'post_type' => 'wpsc-product',
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'orderby' => $rand,
			'tax_query' => array(
				array(
					'taxonomy' => 'wpsc_product_category',
					'field' => 'id',
					'terms' => $category_ids,
					'operator' => 'IN'
				)
			)
		);
		$products = new WP_Query( $args );	
		
		// returns the products as stdClass Objects
		return $products;
	}
}

// remove "from" on pricing
function wpsc_remove_from_for_variations( $from ) 
{
	return '';
}
add_filter( 'wpsc_product_variation_text', 'wpsc_remove_from_for_variations' );

// add WPEC body class to all shop pages
$products_page = get_option( 'product_list_url' );
$products_page = substr( $products_page, '0', '-1' );
$products_page = substr( $products_page, strripos( $products_page, "/" ) + 1 );  
if ( strpos( $_SERVER['REQUEST_URI'], $products_page ) >= 1 ) 
{
	add_filter( 'body_class', 'wpsc' );
	function wpsc( $classes ) 
	{
	// add 'class-name' to the $classes array
	$classes[] = 'wpsc';
	// return the $classes array
	return $classes;
	}
}

// overload product description display content function
if ( ! function_exists( 'sp_wpsc_the_product_description' ) )
{
	function sp_wpsc_the_product_description() 
	{
		$content = get_the_content( __( 'More Info &gt;', 'sp' ) );
		return do_shortcode(wpautop( $content, 1 ) );
	}
}

// overload additional product description display content function
if ( ! function_exists( 'sp_wpsc_the_product_additional_description' ) )
{
	function sp_wpsc_the_product_additional_description() 
	{
		global $post;
	
		if ( !empty( $post->post_excerpt ) )
			return do_shortcode( wpautop( $post->post_excerpt, 1 ) );
		else
			return false;
	}
}

// load ajax functions to WP
if ( is_admin() ) :
	add_action( 'wp_ajax_nopriv_sp_add_to_cart', 'sp_add_to_cart' );
	add_action( 'wp_ajax_sp_add_to_cart', 'sp_add_to_cart' );
	add_action( 'wp_ajax_nopriv_sp_product_rate', 'sp_product_rate' );
	add_action( 'wp_ajax_sp_product_rate', 'sp_product_rate' );
	add_action( 'wp_ajax_nopriv_sp_variation_image_swap', 'sp_variation_image_swap' );
	add_action( 'wp_ajax_sp_variation_image_swap', 'sp_variation_image_swap' );

endif;

// add to cart ajax function
if ( ! function_exists( 'sp_add_to_cart' ) )
{
	function sp_add_to_cart() 
	{
		$nonce = $_POST['ajaxCustomNonce'];
		if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
		{
			 die ( 'Busted!' );
		}
		$count = wpsc_cart_item_count();
		if ( $count == 0 ) 
		{
			$count = 1;	
		}
		$total = wpsc_cart_total_widget();
		$response = array( 'count' => $count, 'total' => $total );
		echo json_encode( $response );
		exit;
	}
}

// product rate ajax function
if ( ! function_exists( 'sp_product_rate' ) )
{
	function sp_product_rate() 
	{
		global $wpdb;
		
		$response = '';
		$nonce = $_POST['ajaxCustomNonce'];
		$rate = mysql_real_escape_string( trim( $_POST['rate'] ) );
		$id = mysql_real_escape_string( trim($_POST['id'] ) );
		$ip = $_SERVER['REMOTE_ADDR'];
		$current_time = time();
		if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
		{
			 die ( 'Busted!' );
		}
		$sql = "SELECT COUNT(id) FROM {$wpdb->prefix}wpsc_product_rating WHERE ipnum = '$ip' AND productid = '$id'";
		$get_result = $wpdb->get_var( $sql ); 
		if ( $get_result != 0 )  
		{
			$sql = "SELECT AVG(rated) FROM {$wpdb->prefix}wpsc_product_rating WHERE productid = '$id'";
			$current_rating = $wpdb->get_var( $sql ); 
			$current_rating = floor( $current_rating );
			$response .= '<span class="star-rating-control">';
			for ( $i = 1; $i < 6; $i++ ) 
			{
				$checked = '';	
				if ( $current_rating >= 1 ) 
				{
					$checked = 'star-rating-on';
				}
				$response .= '<div class="wpec-star-rating rater-0 star star-rating-applied star-rating-readonly ' . $checked . '">';
				$response .= '<a title="'.$i.'">'.$i.'</a></div>';
				$current_rating = $current_rating - 1;
			}
			
			$response .= '</span><p class="message">' . __( 'Sorry, you already rated!', 'sp' ) . '</p>';
			echo $response;
		} 
		else 
		{
			$sql = "INSERT INTO {$wpdb->prefix}wpsc_product_rating (ipnum,productid,rated,time) VALUES ('$ip','$id','$rate','$current_time')"; 
			$wpdb->query( $sql );
			$sql = "SELECT AVG(rated) FROM {$wpdb->prefix}wpsc_product_rating WHERE productid = '$id'";
			$current_rating = $wpdb->get_var( $sql ); 
			$current_rating = floor( $current_rating );
			$response .= '<span class="star-rating-control">';
			for ( $i = 1; $i < 6; $i++ ) 
			{
					
				$checked = '';	
				if ($current_rating >= 1) {
					$checked = 'star-rating-on';
				}
				$response .= '<div class="wpec-star-rating rater-0 star star-rating-applied star-rating-readonly ' . $checked . '">';
				$response .= '<a title="'.$i.'">'.$i.'</a></div>';
				$current_rating = $current_rating - 1;
			}
				$response .= '</span><p class="message">' . __( 'Thanks for rating!', 'sp' ) . '</p>';		
			echo $response;
		}

		exit;
	}
}

// displays html for star_rating
if ( ! function_exists( 'sp_product_rating' ) )
{
	function sp_product_rating( $product_id ) 
	{
		global $wpdb;
		$sql = "SELECT AVG(rated) FROM {$wpdb->prefix}wpsc_product_rating WHERE productid = '$product_id'";
		$current_rating = $wpdb->get_var( $sql ); 
		$current_rating = floor( $current_rating );
		$output = '<div class="product_rating ' . $product_id . '">';
		for ( $i = 1; $i < 6; $i++ ) 
		{	
			$checked = '';	
			if ( $i == $current_rating ) 
			{
				$checked = 'checked="checked"';
			}
			$output .= '<input name="star' . $product_id . '" type="radio" class="star" ' . $checked . ' value="'.$i.'" />';
		}
			$output .= '<input type="hidden" name="id" value="' . $product_id . '" />';
		$output .= '</div>';
		return $output;
	}
}

// variation image swap
if ( ! function_exists( 'sp_variation_image_swap' ) )
{
	function sp_variation_image_swap() 
	{
		$nonce = $_POST['ajaxCustomNonce'];
		if ( ! wp_verify_nonce($nonce, 'ajax_custom_nonce' ) ) 
		{
			 die ( 'Busted!' );
		}
		$var_ids = $_POST['var_ids'];
		$product_id = absint( $_POST['product_id'] );
		if ( function_exists( 'wpsc_get_child_object_in_terms' ) ) 
		{
			// check if featured is turn off on product level
			$option = get_post_meta( $product_id, 'sp_image_swap', true );
			if ( $option == '1' ) 
			{
				echo false;
				exit;	
			}		
			$obj_id = wpsc_get_child_object_in_terms( $product_id, $var_ids, 'wpsc-variation' );	
		} 
		else 
		{
			echo false;
			exit;	
		}
		// get the variation image
		$attach_id = get_post_meta( $obj_id, '_thumbnail_id', true );
		$image = wp_get_attachment_image_src( $attach_id, 'full' );
		$alt = get_post_meta( $attach_id, '_wp_attachment_image_alt', true );

		if ( ! isset( $alt ) || empty( $alt ) )
			$alt = get_the_title( $attach_id );
		
		if ( $image ) 
		{
			$output = array( 'image_src' => $image[0], 'image_alt' => $alt );
			$output = json_encode( $output );
			echo $output;
		} 
		else 
		{
			echo false;
		}
		exit;
	}
}

if ( ! function_exists( 'sp_wpsc_product_variation_price_available' ) )
{
	function sp_wpsc_product_variation_price_available( $product_id )
	{
		global $wpdb;
		
		$sql = $wpdb->prepare( "
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price'
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id );
	
		$price = (float) $wpdb->get_var( $sql );
		
		$sql = $wpdb->prepare("
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id);
		
		$special_price = (float) $wpdb->get_var( $sql );
		
		if ( ! empty( $special_price ) && $special_price < $price ) {
			$price = $special_price;
		}
		$price = wpsc_currency_display($price, array('display_as_html' => false));
		return $price;
	}
}

if ( ! function_exists( 'sp_check_variations' ) )
{
	function sp_check_variations( $product_id )
	{
		global $wpdb;
		
		$sql = $wpdb->prepare( "
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price'
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id );
	
		$price = (float) $wpdb->get_var( $sql );
		
		$sql = $wpdb->prepare("
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id);
		
		$special_price = (float) $wpdb->get_var( $sql );
		
		if ( ! empty( $special_price ) && $special_price < $price ) {
			$price = $special_price;
		}
		return $price;
	}
}

// checks if price is on special
if ( ! function_exists( 'sp_wpsc_product_on_special' ) )
{
	function sp_wpsc_product_on_special( $product_id = '' ) 
	{
		global $wpsc_query, $wpdb;
		
		$price =  get_product_meta( $product_id, 'price', true );
		$sql = $wpdb->prepare( "
			SELECT pm.meta_value
			FROM {$wpdb->posts} AS p
			INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_price'
			INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
			WHERE
				p.post_type = 'wpsc-product'
				AND
				p.post_parent = %d
			ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
			LIMIT 1
		", $product_id );
	
		$variation = $wpdb->get_var( $sql );
		// don't rely on product sales price if it has variations
		if ($variation != null ) {
			$sql = $wpdb->prepare("
				SELECT MIN(pm.meta_value)
				FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->postmeta} AS pm ON pm.post_id = p.id AND pm.meta_key = '_wpsc_special_price' AND pm.meta_value != '0' AND pm.meta_value != ''
				INNER JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = p.id AND pm2.meta_key = '_wpsc_stock' AND pm2.meta_value != '0'
				WHERE
					p.post_type = 'wpsc-product'
					AND
					p.post_parent = %d
				ORDER BY CAST(pm.meta_value AS DECIMAL(10, 2)) ASC
				LIMIT 1
			", $product_id );
			$special_price = (int) $wpdb->get_var( $sql );
		} else {
			$special_price = get_product_meta( $product_id, 'special_price', true );
		}
	
		if ( ($special_price > 0) && (($price - $special_price) > 0) )
			return true;
		else
			return false;
	
	}
}

// displays the normal price
if ( ! function_exists( 'sp_wpsc_product_normal_price' ) )
{
	function sp_wpsc_product_normal_price( $product_id = '', $forRSS = false ) 
	{
		global $wpsc_query, $wpdb, $wpsc_variations;
		if ( is_object( $wpsc_variations ) && count( $wpsc_variations->first_variations ) > 0 ) 
		{
			//select the variation ID with lovest price
			$product_id = $wpdb->get_var('SELECT `posts`.`id` FROM ' . $wpdb->posts . ' `posts` JOIN ' . $wpdb->postmeta . ' `postmeta` ON `posts`.`id` = `postmeta`.`post_id` WHERE `posts`.`post_parent` = ' . $product_id . ' AND `posts`.`post_type` = "wpsc-product" AND `posts`.`post_status` = "inherit" AND `postmeta`.`meta_key`="_wpsc_price" ORDER BY (`postmeta`.`meta_value`)+0 ASC LIMIT 1');
			$from = ' from ';
		} 
		else 
		{
			$from = '';
		}
		$from = apply_filters( 'wpsc_product_variation_text', $from );
		$price = get_product_meta( $product_id, 'price', true );
		if( $forRSS )
			$output = $from.wpsc_currency_display( $price, array( 'display_as_html' => false ) );
		else
			$output = $from.wpsc_currency_display( $price );
		return $output;
	}
}

// displays products price
if ( ! function_exists( 'sp_wpsc_the_product_price' ) )
{
	function sp_wpsc_the_product_price( $no_decimals = false, $product_id ) 
	{
		global $wpsc_query, $wpsc_variations, $wpdb;
		$has_variation = '';
		$has_variation = sp_check_variations( $product_id );
		if ( $has_variation != '0') 
		{
			//select the variation ID with lowest price
			$output = sp_wpsc_product_variation_price_available( $product_id );
			$from = ' from ';
		} 
		else 
		{
			$from = '';
			$full_price = get_post_meta( $product_id, '_wpsc_price', true );
			$special_price = get_post_meta( $product_id, '_wpsc_special_price', true );
			$price = $full_price;
			
			if ( ( $full_price > $special_price ) && ( $special_price > 0 ) )
				$price = $special_price;
				
			if ( $no_decimals == true )
				$price = array_shift( explode( ".", $price ) );
			
			$args = array(
				'display_as_html' => false,
				'display_decimal_point' => !$no_decimals
			);	
			$output = wpsc_currency_display( $price,$args );
		}
	
		
		//if product has variations - add 'from'
		$from = apply_filters( 'wpsc_product_variation_text', $from );
		if ( isset( $wpsc_variations->first_variations ) && count( $wpsc_variations->first_variations ) > 0 && !empty( $from ) )
			$output = sprintf( __( ' from %s', 'sp' ), $output );
			
		return $output;
	}
}

// displays the fancy popup on add to cart
// first remove the current filter
remove_action( 'wpsc_theme_footer', 'wpsc_fancy_notifications' );
remove_filter( 'the_content', 'add_to_cart_shortcode', 12 );
function sp_add_to_cart_shortcode( $content = '' ) 
{
	//exit($content);
	static $fancy_notification_output = false;
	if ( preg_match_all( "/\[add_to_cart=([\d]+)\]/", $content, $matches ) ) 
	{
		foreach ( $matches[1] as $key => $product_id ) 
		{
			$original_string = $matches[0][$key];
			$output = wpsc_add_to_cart_button( $product_id, true );
			$content = str_replace( $original_string, $output, $content );
		}
	}
	if ( ! $fancy_notification_output ) 
	{
		//$content .= fancy_notifications();
		//$fancy_notification_output = true;
	}
	return $content;
}
add_filter( 'the_content', 'sp_add_to_cart_shortcode', 12 );
if ( ! function_exists( 'sp_fancy_notifications' ) )
{
	function sp_fancy_notifications() 
	{
		$output = "";
		if ( get_option( 'fancy_notifications' ) == 1 ) 
		{
			$output = "";
			$output .= "<div id='fancy_notification' class='group'>\r\n";
			$output .= "  <div id='loading_animation'>\r\n";
			$output .= '<img id="fancy_notificationimage" title="Loading" alt="Loading" src="' . get_template_directory_uri() . '/images/ajax-loader.gif" />' . "\r\n";
			$output .= "  </div>\r\n";
			$output .= "  <div id='fancy_notification_content' class='group'>\r\n";
			$output .= "  </div>\r\n";
			$output .= "</div>\r\n";
		}
		
		return $output;
	}
}

// displays the grid view and default view options
if ( ! function_exists( 'sp_product_view' ) ) :
	function sp_product_view() 
	{
		global $sp_view_mode;
		
		$_SERVER['REQUEST_URI'] = remove_query_arg( 'view_type' );
		$user_view = $sp_view_mode;
		$default = '';
		$grid = '';
		if ( $user_view == 'default' ) 
		{
			$default = 'active';
		} 
		elseif ( $user_view == 'grid' ) 
		{
			$grid = 'active';	
		}
		$output = '<div class="product_views group"><div class="wrap">';
		$output .= '<a href="'.esc_url( add_query_arg( 'view_type', 'grid' ) ).'"  title="'.__( 'Grid View', 'sp' ).'" class="grid '.$grid.'">'.__( 'Grid', 'sp' ).'</a>';
		$output .= '<a href="'.esc_url( add_query_arg( 'view_type', 'default' ) ).'" title="'.__( 'Default View', 'sp' ).'" class="default '.$default.'">'.__( 'Default', 'sp' ).'</a>';		
		$output .= '</div><input type="hidden" name="view_type" value="'.esc_attr( $user_view ).'"></div>';
		return $output;
		
	}
endif;

// overload grid view
function sp_view_mode() 
{
		global $sp_view_mode;
		
		if ( function_exists( 'wpsc_get_customer_meta' ) ) {
			$customer_view = wpsc_get_customer_meta( 'display_type' );
			
			$sp_view_mode = ( sp_isset_option( 'product_view', 'isset' ) ) ? sp_isset_option( 'product_view', 'value' ) : 'grid';

			if ( ! empty( $_REQUEST['view_type'] ) && in_array( $_REQUEST['view_type'], array( 'list', 'grid', 'default' ) ) )
				$sp_view_mode = $_REQUEST['view_type'];
			elseif ( ! empty( $_COOKIE['sp_view_mode'] ) )
				$sp_view_mode = $_COOKIE['sp_view_mode'];
			elseif ( ! empty( $customer_view ) )
				$sp_view_mode = $customer_view;

			$sp_view_mode_cookie_lifetime = apply_filters( 'sp_view_mode_cookie_lifetime', 30000000 );
			setcookie('sp_view_mode', $sp_view_mode, time() + $sp_view_mode_cookie_lifetime, '/', $_SERVER['SERVER_NAME'] );

			wpsc_update_customer_meta( 'display_type', $sp_view_mode );
		} else {
			$sp_view_mode = ( sp_isset_option( 'product_view', 'isset' ) ) ? sp_isset_option( 'product_view', 'value' ) : 'grid';
		
			if ( ! empty( $_REQUEST['view_type'] ) && in_array( $_REQUEST['view_type'], array( 'list', 'grid', 'default' ) ) )
				$sp_view_mode = $_REQUEST['view_type'];
			elseif ( ! empty( $_COOKIE['sp_view_mode_' . COOKIEHASH] ) )
				$sp_view_mode = $_COOKIE['sp_view_mode_' . COOKIEHASH];
			elseif ( ! empty( $_SESSION['wpsc_display_type'] ) )
				$sp_view_mode = $_SESSION['wpsc_display_type'];
			
			$sp_view_mode_cookie_lifetime = apply_filters( 'sp_view_mode_cookie_lifetime', 30000000 );
			setcookie('sp_view_mode_' . COOKIEHASH, $sp_view_mode, time() + $sp_view_mode_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
			$_SESSION['wpsc_display_type'] = $sp_view_mode;
		}
}

if ( get_option( 'show_search' ) != '1' ) 
{
	add_action( 'init', 'sp_view_mode' );
} 
else 
{
	if ( get_option( 'show_advanced_search' ) != '1' ) 
	{
		add_action( 'init', 'sp_view_mode' );
	}
}

// modified function to display product additional images in carousel slider
if ( ! function_exists( 'sp_display_gallery' ) )
{	 
	function sp_display_gallery( $product_id, $invisible = false, $numberposts = 3, $image_width, $image_height ) 
	{
		global $wpdb, $wp_query;
		$output ='';
		$siteurl = get_option( 'siteurl' );
		/* No GD? No gallery.	 No gallery option? No gallery.	 Range variable set?  Apparently, no gallery. */
		if ( ! isset( $_GET['range'] ) && function_exists( "getimagesize" ) ) 
		{
				$output = '';
				$product_name = get_the_title( $product_id );
				$output .= "<div class='slider-gallery'><ul class='group'>";
				$args = array(
					'post_type'      => 'attachment',
					'post_parent'    => $product_id,
					'post_mime_type' => 'image',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
					'numberposts'    => $numberposts
				); 
				$attachments = get_posts($args);
				$image = '';
				if ( count( $attachments ) > 1 ) 
				{
					foreach ( $attachments as $post ) 
					{
						//setup_postdata( $post ); - don't think this is needed
						$image_src = wp_get_attachment_image_src( $post->ID, 'full' );
	
						$image .= '<li>';
						$image .= '<img src="' . $image_src[0]. '" width="' . $image_width . '" height="' . $image_height . '" alt="' . $product_name . '" />';
						
					$image .= '</li>';
					} // close for loop
				}
				$output .= $image;
				$output .= "</ul></div>";
		} //closes if gallery setting condition
		return $output;
	}
}

if ( ! function_exists( 'sp_main_display_gallery' ) )
{
	function sp_main_display_gallery( $product_id, $main_image_height = 347) 
	{
		global $wpdb;
		$output ='';

		$siteurl = get_option( 'siteurl' );

		$output = '';
		$product_name = get_the_title( $product_id );
		$output .= "<div class='wpcart_gallery group'>";
		$args = array(
			'post_type'      => 'attachment',
			'post_parent'    => $product_id,
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'numberposts'    => -1,
			'post_status'    => null
		); 
		$attachments = get_posts( $args );
		$featured_img = get_post_meta( $product_id, '_thumbnail_id' );
		$thumbnails = array();
							
		if ( count( $attachments) > 1 ) 
		{
			foreach ( $attachments as $post ) 
			{
				$image_width = get_option( 'wpsc_gallery_image_width' );
				$image_height = get_option( 'wpsc_gallery_image_height' );
				$alt = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );

				if ( empty( $alt ) )
					$alt = $product_name;
				
				//setup_postdata( $post ); - don't think this is needed
				$size = is_single() ? 'full' : 'product-thumbnails'; // change to full by sp
				$preview_link = wp_get_attachment_image_src( $post->ID, 'full' );
				$link = wp_get_attachment_link( $post->ID, 'gold-thumbnails' );
				$link = preg_replace('/src=".*"/', 'src="'.$preview_link[0]. '" class="sp-attachment-thumbnails" alt="'. esc_attr( $alt ) . '"', $link ); 
				$link = preg_replace( '/width="[0-9]*"\sheight="[0-9]*"/', 'width="' . $image_width . '" height="' . $image_height . '"', $link );
			// custom SP
			global $wp_query;
			$image_width = '';
			$image_height = '';
			if ( sp_isset_option( 'wpec_image_swap', 'boolean', 'true' ) ) 
			{
				
				$sizes = sp_quickview_image_size( sp_get_image( $post->ID) );
				$image_width = $sizes['image_width'];
				$image_height = $sizes['image_height'];
					
				if ($wp_query->is_single) {
					$image_height = get_option( 'single_view_image_height' );
					$image_width = get_option( 'single_view_image_width' );
														
				$link = str_replace( 'a href' , 'a data-src="'.$preview_link[0]. '" class="thickbox preview_link" onclick="return false" data-rel="prettyPhoto[' . $product_id . ']" href' , $link );
				} else {
				$link = str_replace( 'a href' , 'a data-src="'.$preview_link[0]. '" class="thickbox preview_link" onclick="return false" data-rel="prettyPhoto[' . $product_id . ']" href' , $link );
				}
		} else {
			$link = str_replace( 'a href' , 'a data-src="' . $preview_link[0] . '" class="thickbox preview_link" data-rel="prettyPhoto[' . $product_id . ']" href' , str_replace( 'sp-attachment-thumbnails', '', $link ) );
		}
				
				// always display the featured thumbnail first
				if ( in_array( $post->ID, $featured_img ) )
					array_unshift( $thumbnails, $link );
				else
					$thumbnails[] = $link;
			}
		}
		$output .= implode( "\n", $thumbnails );
		$output .= "</div>";
				 
		return $output; 
	}
}

/** 
 * overload function for google checkout
 *
*/
remove_action('wpsc_before_form_of_shopping_cart', 'wpsc_google_checkout_page');
function sp_wpsc_google_checkout_page(){
	global $wpsc_gateway;
	$script = "<script type='text/javascript'>
	 				jQuery(document).ready(
  						function()
 						 {
	 						jQuery('div#wpsc_shopping_cart_container h2').hide();
	 						jQuery('div#wpsc_shopping_cart_container .wpsc_cart_shipping').hide();
 							jQuery('.wpsc_checkout_forms').hide();
							jQuery('a.step2').hide();
							jQuery('.slide2').hide();
							jQuery('.slide1').show();
	 					});
	 			</script>";
	$options = get_option('payment_gateway');
	if(in_array('google', (array)get_option('custom_gateway_options'))){
		$options = 'google';
	}

	if($options == 'google' && isset($_SESSION['gateway'])){
		unset($_SESSION['gateway']);
		echo $script;
 		echo '<div class="google-checkout">';
		gateway_google(true);
		echo '</div>';
	}
}

add_action('wpsc_before_form_of_shopping_cart_for_google', 'sp_wpsc_google_checkout_page');

?>