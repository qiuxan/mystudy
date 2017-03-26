<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* deprecated functions
******************************************************************/

/**
 * @deprecated since 1.0.7
 */
// shortcode to easily add grid column layout in a post or blog
function sp_grid_system_narrow( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'size' => false,
		'prefix' => false,
		'suffix' => false,
		'first' => false,
		'last' => false
	), $atts ) );

	if ( $size ) 
	{
		$class = "narrow_grid_$size";
		if ( $suffix ) $class .= " narrow_suffix_$suffix";
		if ( $prefix ) $class .= " narrow_prefix_$prefix";
		if ( $first ) $class .= " narrow_alpha";
		if ( $last ) $class .= " narrow_omega";
	}
	else 
	{
		$class = "";
		$last = true;
	}

	$output = "<div class=\"{$class}\"><p>" . do_shortcode( $content ) . "</p></div>\n";
	if ( $last ) $output .= "<div class=\"group\"></div>\n";

	return $output;
}
add_shortcode( 'sp_grid6', 'sp_grid_system_narrow' );
add_shortcode( 'sp_grid6a', 'sp_grid_system_narrow' );
add_shortcode( 'sp_grid6b', 'sp_grid_system_narrow' );

/**
 * @deprecated since 1.0.7
 */
// shortcode to easily add grid column layout in a post or blog
function sp_grid_system_wide( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'size' => false,
		'prefix' => false,
		'suffix' => false,
		'first' => false,
		'last' => false
	), $atts ) );

	if ( $size ) 
	{
		$class = "wide_grid_$size";
		if ( $suffix ) $class .= " wide_suffix_$suffix";
		if ( $prefix ) $class .= " wide_prefix_$prefix";
		if ( $first ) $class .= " wide_alpha";
		if ( $last ) $class .= " wide_omega";
	}
	else 
	{
		$class = "";
		$last = true;
	}

	$output = "<div class=\"{$class}\"><p>". do_shortcode( $content ) . "</p></div>\n";
	if ( $last ) $output .= "<div class=\"group\"></div>\n";

	return $output;
}
add_shortcode( 'sp_grid12', 'sp_grid_system_wide' );
add_shortcode( 'sp_grid12a', 'sp_grid_system_wide' );
add_shortcode( 'sp_grid12b', 'sp_grid_system_wide' );

/**
 * upload image
 *
 * @deprecated since 2.0.3
 */
function sp_theme_upload()
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
		//die('errors');
	}	
	
	$upload_dir = wp_upload_dir();

	if ( ! empty( $_FILES ) ) 
	{
		if( ! isset( $_POST['target'] ) || $_POST['target'] == '' )
		{
			$target = $upload_dir['basedir']  . '/sp-uploads';
		} 
		else 
		{
			$target = $upload_dir['basedir'] . '/sp-uploads' . $_POST['target'];
		}	
		$target = rtrim( $target, "/" );
		
		$check = false;
		if ( ! is_dir( $target ) )
		{		
			if ( ! mkdir( $target, 0777, true ) )
			{
				echo "errors";	
				exit;
			} 
			else 
			{
				$check = true; 	
			}
		} 
		else 
		{
			$check = true;	
		}
		if ( $check )
		{
			if ( move_uploaded_file( $_FILES['Filedata']['tmp_name'], $target . "/" . str_replace( " ", "-", basename( $_FILES['Filedata']['name'] ) ) ) ) 
			{
				if ( isset( $_POST['id'] ) ) 
				{
					echo $_POST['id'];
				} 
				else 
				{
					echo "done";	
				}
			} 
			else 
			{
				echo "errors";
			}
		}
	}
	exit;
}

/**
 * checks to see if domain is resolvable
 *
 * @param $string takes a valid URL of the domain to check
 * @return boolean
 * @deprecated since 2.1.1
 */
function sp_is_site_up( $domain )
{
	// check, if a valid url is provided
	if( !filter_var( $domain, FILTER_VALIDATE_URL ) )
	{
		return false;
	}
	
	if ( ! function_exists( 'curl_init' ) ) 
	{
		return false;	
	}
	
	// initialize curl
	$curlInit = curl_init( $domain );
	curl_setopt( $curlInit, CURLOPT_CONNECTTIMEOUT, 1 );
	curl_setopt( $curlInit, CURLOPT_HEADER, true );
	curl_setopt( $curlInit, CURLOPT_NOBODY, true );
	curl_setopt( $curlInit, CURLOPT_RETURNTRANSFER, true );
	
	// get response
	$response = curl_exec( $curlInit );
	
	// close the curl connection
	curl_close( $curlInit );
	
	if ( $response ) return true;
	
	return false;
}

/*
 * function to get the product images from WPEC
 * @deprecated since 2.1.1
 */
function sp_wpsc_the_product_image( $product_id = '' ) 
{
	if ( empty( $product_id ) )
		$product_id = get_the_ID();
	
	$product = get_post( $product_id );

	if ( $product->post_parent > 0 )
		$product_id = $product->post_parent;

	$attached_images = (array)get_posts( array(
				'post_type' => 'attachment',
				'numberposts' => 1,
				'post_status' => null,
				'post_parent' => $product_id,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			) );

	$attached_image = wp_get_attachment_image_src( $attached_images[0]->ID, 'full' );
	
	$image_src = $attached_image[0];
	
	// run image url string check for multisite
	$image_src = sp_check_ms_image( $image_src );
	
	return $image_src;
}

/**
 * get list of product data within specific category	
 *
 * @since 2.0.3
 * @param int $category_id pass in the category id
 * @param int $count pass in the limit to return
 * @return array object of product ids
 * @deprecated since 2.1.1
 */ 
if ( ! function_exists( 'sp_get_products' ) )
{
	function sp_get_products( $category_id = 1,  $count = 8 ) 
	{
		global $wpdb;
		
		$sql = "SELECT term_taxonomy_id 
				FROM {$wpdb->prefix}term_taxonomy 
				WHERE term_id = {$category_id}";
				
		$category_id = $wpdb->get_var( $sql );
		
		// checks to see if nothing was returned, if so bailout now
		if ( $category_id == null )	
			return null;	
		
		$sql = "SELECT p.id 
				FROM {$wpdb->prefix}posts p 
				LEFT JOIN {$wpdb->prefix}term_relationships tr 
				ON p.id = tr.object_id 
				WHERE tr.term_taxonomy_id = {$category_id} 
				AND p.post_type = 'wpsc-product' 
				AND p.post_status = 'publish' 
				LIMIT {$count}";
	
		$product_ids = $wpdb->get_results( $sql );
		
		if ( $product_ids == null )
			return null;
			
		foreach ( $product_ids as $product_id ) 
		{
			$products[] = get_post( $product_id->id );			
		}
		
		// returns the products as stdClass Objects
		return $products;
	}
}

// below 2 functions are overloaded to display a custom display template for the homepage
// Template tags
/**
 * wpsc display products function
 * @return string - html displaying one or more products
 * @deprecated since 2.1.1
 */
if ( ! function_exists( 'sp_wpsc_display_products_page' ) )
{
	function sp_wpsc_display_products_page( $query ) 
	{
		global $wpdb, $wpsc_query, $wp_query;
		static $count = 0;
		$count++;
		remove_filter( 'the_title', 'wpsc_the_category_title' );
		if ( $count > 10 )
			exit( 'fail' );
	
		// If the data is coming from a shortcode parse the values into the args variable, 
		// I did it this was to preserve backwards compatibility
		if ( ! empty( $query ) )
		{	
			$args['post_type'] = 'wpsc-product';
			if ( ! empty( $query['product_id'] ) && is_array( $query['product_id'] ) )
			{
				$args['post__in'] = $query['product_id'];
			}
			elseif ( isset( $query['product_id'] ) && is_string( $query['product_id'] ) )
			{
				$args['post__in'] = (array)$query['product_id'];
			}
			if ( ! empty( $query['old_product_id'] ) )
			{
				$post_id = wpsc_get_the_new_id( $query['old_product_id'] );
				$args['post__in'] = (array)$post_id;
			}
			if ( ! empty( $query['price'] ) && 'sale' != $query['price'] )
			{
				$args['meta_key'] = '_wpsc_price';
				$args['meta_value'] = $query['price'];
			}
			elseif ( ! empty( $query['price'] ) && 'sale' == $query['price'] )
			{
				$args['meta_key'] = '_wpsc_special_price';
				$args['meta_compare'] = '>=';
				$args['meta_value'] = '1';
			}
			if ( ! empty( $query['product_name'] ) )
			{
				$args['pagename'] = $query['product_name'];
			}
			if ( ! empty( $query['category_id'] ) )
			{
	
				$term = get_term( $query['category_id'], 'wpsc_product_category' );
				$id = wpsc_get_meta( $query['category_id'], 'category_id', 'wpsc_old_category' );
				if ( ! empty( $id ) )
				{
					$term = get_term( $id, 'wpsc_product_category' );
					$args['wpsc_product_category'] = $term->slug;
					$args['wpsc_product_category__in'] = $term->term_id;
				}
				else
				{
					$args['wpsc_product_category'] = $term->slug;
					$args['wpsc_product_category__in'] = $term->term_id;
				}
			}
			if ( ! empty( $query['category_url_name'] ) )
			{
				$args['wpsc_product_category'] = $query['category_url_name'];
			}
			if ( ! empty( $query['sort_order'] ) )
			{
				$args['orderby'] = $query['sort_order'];
			}
			if ( ! empty( $query['order'] ) )
			{
				$args['order'] = $query['order'];
			}
			if ( ! empty( $query['limit_of_items'] ) && '1' == get_option( 'use_pagination' ) )
			{
				$args['posts_per_page'] = $query['limit_of_items'];
			}	
			if ( ! empty( $query['number_per_page'] ) && '1' == get_option( 'use_pagination' ) )
			{
				$args['posts_per_page'] = $query['number_per_page'];
			}
			if ( '0' == get_option( 'use_pagination' ) )
			{
				$args['posts_per_page'] = $query['limit_of_items'];
			}
			if ( ! empty( $query['tag'] ) )
			{
				$args['product_tag'] = $query['tag'];
			}
		
			$temp_wpsc_query = new WP_Query( $args );
		}
		// swap the wpsc_query objects
		list( $wp_query, $temp_wpsc_query ) = array( $temp_wpsc_query, $wp_query ); 
		$GLOBALS['nzshpcrt_activateshpcrt'] = true;
		$display_type = 'default';
		ob_start();
			sp_wpsc_include_products_page_template( $display_type );
		$is_single = false;
		
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '\$', '$', $output );
		list( $temp_wpsc_query, $wp_query ) = array( $wp_query, $temp_wpsc_query ); // swap the wpsc_query objects back
		if ( $is_single == false ) 
		{
			$GLOBALS['post'] = $wp_query->post;
		} 
		return $output;
	}
}

// return the custom homepage grid view
function sp_wpsc_include_products_page_template( $display_type = 'default' )
{
	if ( file_exists( get_template_directory() . '/home-sp_wpsc-grid_view.php' ) )
	{
		get_template_part( 'home', 'sp_wpsc-grid_view' ); // this file is deprecated
	}
	else
	{
		get_template_part( 'sp', 'wpec-home-grid' );	
	}
}

// add in SP custom gravatar
function newgravatar ( $avatar_defaults ) 
{
	$myavatar = get_template_directory_uri() . '/sp-framework/images/sp_gravatar.png';
	$avatar_defaults[$myavatar] = "Splashing Pixels";
	return $avatar_defaults;
}
//add_filter( 'avatar_defaults', 'newgravatar' );

?>