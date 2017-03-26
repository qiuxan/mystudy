<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* WOO Commerce functions
******************************************************************/

// Disable WooCommerce styles 
define( 'WOOCOMMERCE_USE_CSS', false );

// remove defaults
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display');
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail');

add_action( 'woocommerce_ordering', 'woocommerce_catalog_ordering', 20 );

function sp_woocommerce_widgets() 
{
	unregister_widget( 'WooCommerce_Widget_Cart' );
	register_widget( 'SP_WooCommerce_Widget_Cart' );
}

add_action( 'widgets_init', 'sp_woocommerce_widgets' );

// replace default image placeholder
add_filter( 'woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src' );

function custom_woocommerce_placeholder_img_src( $src ) 
{
	$src = get_template_directory_uri(). '/images/no-product-image.jpg';
	
	return $src;
}

add_action( 'woocommerce_before_main_content', 'sp_woo_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'sp_woo_after_content', 20 );
add_action( 'woocommerce_before_shop_loop_item_title', 'sp_woo_template_loop_product_thumbnail', 10 );
//add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);
add_action( 'woocommerce_before_subcategory_title', 'sp_woocommerce_subcategory_thumbnail');

// set number of related items to display
function woocommerce_output_related_products() 
{	
	if ( sp_isset_option( 'related_product_count', 'isset' ) ) 
	{
		$count = sp_isset_option( 'related_product_count', 'value' );	
	} 
	else 
	{
		$count = 3;
	}
	woocommerce_related_products( $count, 3 ); // Display 3 products in rows of 3
}

// set number of upsell items to display
add_action( 'woocommerce_after_single_product', 'woocommerce_output_upsells', 20);

if ( ! function_exists( 'woocommerce_output_upsells' ) ) 
{
	function woocommerce_output_upsells()
	{
		if ( sp_isset_option( 'upsell_product_count', 'isset' ) ) 
		{
			$count = sp_isset_option( 'upsell_product_count', 'value' );	
		} 
		else 
		{
			$count = 3;
		}
		woocommerce_upsell_display( $count, 3 ); // Display 3 products in rows of 3
	}
}


// sets the products per page
function sp_products_per_page() 
{
	if ( sp_isset_option( 'products_per_page', 'isset' ) ) 
	{ 
		$products_per_page = sp_isset_option( 'products_per_page', 'value' );
	} 
	else 
	{
		$products_per_page = 6;	
	}
	
	return $products_per_page;
}

add_filter( 'loop_shop_per_page', 'sp_products_per_page' );

/**
 * get list of product data within multiple categories	
 *
 * @since 2.0.3
 * @param array $category_id pass in the category ids
 * @param int $count pass in the limit to return
 * @return array object of product ids
 */ 
if ( ! function_exists( 'sp_woo_get_products' ) )
{ 
	function sp_woo_get_products( $category_ids = 1,  $count = 8, $rand = "false" ) 
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
			'post_type' => 'product',
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'orderby' => $rand,
			'meta_query' => array(
				array(
						'key' => '_visibility',
						'value' => array( 'catalog', 'visible' ),
						'compare' => 'IN'
				)
            ),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
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

function sp_woo_before_content() 
{	
	$layout = sp_page_layout();
	
	echo '<div id="container" class="group ' . $layout['orientation'] . '">';
	if ( $layout['sidebar_left'] ) 
	{
		get_sidebar( 'left' );	
	} 
	echo '<div id="content" role="main">';
}

function sp_woo_after_content() 
{
	$layout = sp_page_layout();
	
	echo '</div><!-- #content -->';
	if ( $layout['sidebar_right'] ) {
		get_sidebar( 'right' );	
	} 
	echo '</div><!-- #container -->';
}

function sp_woo_template_loop_product_thumbnail( $context = 'product_grid' ) 
{
		global $post, $woocommerce;

		switch ( $context ) :
			case 'product_grid' :
				$size = 'shop_catalog';
                // if less than 2.0
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
                    $image_width = wc_get_image_size( 'shop_catalog_image_width' );
                    $image_height = wc_get_image_size( 'shop_catalog_image_height' );
                } else {                    
                    $catalog_sizes = wc_get_image_size( 'shop_catalog' );
                    $image_width = $catalog_sizes['width'];
                    $image_height = $catalog_sizes['height'];
                }
				break;
				
			case 'quickview_main' :
				$size = 'shop_catalog';
                // if less than 2.0
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
                    $image_width = wc_get_image_size( 'shop_catalog_image_width' );
                    $image_height = wc_get_image_size( 'shop_catalog_image_height' );
                } else {                    
                    $catalog_sizes = wc_get_image_size( 'shop_catalog' );
                    $image_width = $catalog_sizes['width'];
                    $image_height = $catalog_sizes['height'];
                }
				break;
				
			case 'single_main' :
				$size = 'shop_single';
                // if less than 2.0
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
                    $image_width = wc_get_image_size( 'woocommerce_single_image_width' );
                    $image_height = wc_get_image_size( 'woocommerce_single_image_height' );
                } else {                    
                    $catalog_sizes = wc_get_image_size( 'shop_single' );
                    $image_width = $catalog_sizes['width'];
                    $image_height = $catalog_sizes['height'];
                }
				break;
				
			case 'related_product' :
				$size = 'shop_catalog';
				$image_width = sp_get_theme_init_setting( 'woo_related_product_image_size', 'width' );
				$image_height = sp_get_theme_init_setting( 'woo_related_product_image_size', 'height' );
				break;

			case 'upsell_product' :
				$size = 'shop_catalog';
				$image_width = sp_get_theme_init_setting( 'woo_upsell_product_image_size', 'width' );
				break;
				
			default :
				$size = 'shop_catalog';
                // if less than 2.0
                if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
                    $image_width = wc_get_image_size( 'shop_catalog_image_width' );
                    $image_height = wc_get_image_size( 'shop_catalog_image_height' );
                } else {                    
                    $catalog_sizes = wc_get_image_size( 'shop_catalog' );
                    $image_width = $catalog_sizes['width'];
                    $image_height = $catalog_sizes['height'];
                }
				break;
		endswitch;
		
		if ( has_post_thumbnail() ) { ?>
			<?php echo get_the_post_thumbnail($post->ID, $size, array('class' => "product_image $size", 'alt' => trim( strip_tags($attachment->post_title)), 'title' => trim( strip_tags($attachment->post_title)))); ?>
		<?php } else { ?>
			<?php echo get_the_post_thumbnail($post->ID, $size, array('src' => get_template_directory_uri().'/images/no-product-image.jpg', 'class' => 'no-image', 'alt' => 'No Image', 'title' => trim( strip_tags($attachment->post_title)))); ?>
		<?php }
	
}

function woocommerce_pagination()
{
	// return SP version of pagination
	sp_pagination();	
}

function sp_woocommerce_subcategory_thumbnail( $category )
{
	global $woocommerce;
	
	$image_width = sp_get_theme_init_setting( 'woo_product_category_image_size', 'width' );
	$image_height = sp_get_theme_init_setting( 'woo_product_category_image_size', 'height' );
	
	$thumbnail_id  = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, 'full'  );
		$image = $image[0];
	} else {
		$image = woocommerce_placeholder_img_src();
	}
	// context was product_image for timthumb
	echo '<img src="' . $image . '" alt="' . $category->name . '" width="' . $image_width . '" height="' . $image_height . '" />';
}

// function to display product gallery thumbnails
function sp_woocommerce_product_gallery( $count = 3 )
{
	global $post, $woocommerce;
	
	// get image sizes
	//$image_thumb_width = get_option( 'woocommerce_thumbnail_image_width' );
	//$image_thumb_height = get_option( 'woocommerce_thumbnail_image_height' );
	$image_thumb_width = 110;
	$image_thumb_height = 70;

	$featured_image = sp_get_image( $post->ID );
		
	$attachments = get_posts( array(
		'post_type' 	=> 'attachment',
		'numberposts' 	=> $count,
		'post_status' 	=> null,
		'post_parent' 	=> $post->ID,
		'post__not_in'	=> array( get_post_thumbnail_id() ),
		'post_mime_type'=> 'image',
		'orderby'		=> 'menu_order',
		'order'			=> 'ASC'
	) );
	if ($attachments) { ?>
		<div class="slider-gallery">
            <ul class="group">
                <?php
				$i = 1;
                foreach ( $attachments as $key => $attachment ) { ?>
                <li>
                <?php
                    $link = wp_get_attachment_url( $attachment->ID );
    
					// display the featured image thumbnail first
					if ( $i <= 1 ) {
						// was single_gallery context for timthumb
						echo '<img src="'. $featured_image .'" alt="'.get_the_title( $attachment->ID ).'" width="'.$image_thumb_width.'" height="'.$image_thumb_height.'" />';
					} else {
		
						if ( get_post_meta( $attachment->ID, '_woocommerce_exclude_image', true ) == 1 ) 
							continue;
						
						// was single_gallery context for timthumb
						echo '<img src="'. $link .'" alt="'.get_the_title( $attachment->ID ).'" width="'.$image_thumb_width.'" height="'.$image_thumb_height.'" />';
					}
					$i++;
                }
                ?>
                </li>				
           </ul>
		</div><!--close slider-gallery-->
	<?php        
	}	
}

if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
	// custom cart widget (overwriting mainly the display only)
	class SP_WooCommerce_Widget_Cart extends WooCommerce_Widget_Cart {
		/** @see WP_Widget */
		function widget( $args, $instance ) {
			global $woocommerce;

			extract($args);
			if ( !empty($instance['title']) ) $title = $instance['title']; else $title = __('Cart', 'sp');
			$title = apply_filters('widget_title', $title, $instance, $this->id_base);
			$hide_if_empty = (isset($instance['hide_if_empty']) && $instance['hide_if_empty']) ? '1' : '0';

			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;

			echo '<ul class="cart_list product_list_widget ';
			if ($hide_if_empty) echo 'hide_cart_widget_if_empty';
			echo '">';
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0) :
				foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product = $cart_item['data'];
					if ( $_product->exists() && $cart_item['quantity'] > 0 ) :
						echo '<li><a href="' . get_permalink( $cart_item['product_id'] ) . '">';

						echo $_product->get_image();

						echo apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) . '</a>';

		   				echo $woocommerce->cart->get_item_data( $cart_item );

						echo '<span class="quantity">' . $cart_item['quantity'] . ' &times; ' . woocommerce_price( $_product->get_price() ) . '</span></li>';
					endif;
				endforeach;
			else:
				echo '<li class="empty">' . __( 'Your shopping cart is empty', 'sp' ) . '</li>';
			endif;
			echo '</ul>';

			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) :
				echo '<p class="total"><strong>' . __( 'Subtotal', 'sp' ) . ':</strong> ' . $woocommerce->cart->get_cart_total() . '</p>';

				do_action( 'woocommerce_widget_shopping_cart_before_buttons' );

				echo '<p class="buttons"><a href="' . $woocommerce->cart->get_cart_url() . '" class="viewcart-button"><span>' . __( 'View Cart &rarr;', 'sp' ) . '</span></a></p>';
			endif;
			echo $after_widget;

			if ( $hide_if_empty && sizeof( $woocommerce->cart->get_cart() ) == 0 ) 
			{
				$inline_js = "
					jQuery('.hide_cart_widget_if_empty').closest('.widget').hide();
					jQuery('body').bind('adding_to_cart', function(){
						jQuery(this).find('.hide_cart_widget_if_empty').closest('.widget').fadeIn();
					});
				";

				$woocommerce->add_inline_js( $inline_js );
			}
		}
	}
} else {
	// custom cart widget (overwriting mainly the display only)
	class SP_WooCommerce_Widget_Cart extends WP_Widget {
		var $woo_widget_cssclass;
		var $woo_widget_description;
		var $woo_widget_idbase;
		var $woo_widget_name;

		/**
		 * constructor
		 *
		 * @access public
		 * @return void
		 */
		function SP_WooCommerce_Widget_Cart() {

			/* Widget variable settings. */
			$this->woo_widget_cssclass 		= 'woocommerce widget_shopping_cart';
			$this->woo_widget_description 	= __( "Display the user's Cart in the sidebar.", 'sp' );
			$this->woo_widget_idbase 		= 'woocommerce_widget_cart';
			$this->woo_widget_name 			= __( 'WooCommerce Cart', 'sp' );

			/* Widget settings. */
			$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

			/* Create the widget. */
			$this->WP_Widget( 'shopping_cart', $this->woo_widget_name, $widget_ops );
		}		

		/** @see WP_Widget */
		function widget( $args, $instance ) {
			global $woocommerce;

			extract($args);
			if ( !empty($instance['title']) ) $title = $instance['title']; else $title = __('Cart', 'sp');
			$title = apply_filters('widget_title', $title, $instance, $this->id_base);
			$hide_if_empty = (isset($instance['hide_if_empty']) && $instance['hide_if_empty']) ? '1' : '0';

			echo $before_widget;
			if ( $title ) echo $before_title . $title . $after_title;

			echo '<ul class="cart_list product_list_widget ';
			if ($hide_if_empty) echo 'hide_cart_widget_if_empty';
			echo '">';
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0) :
				foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product = $cart_item['data'];
					if ( $_product->exists() && $cart_item['quantity'] > 0 ) :
						echo '<li><a href="' . get_permalink( $cart_item['product_id'] ) . '">';

						echo $_product->get_image();

						echo apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) . '</a>';

		   				echo $woocommerce->cart->get_item_data( $cart_item );

						echo '<span class="quantity">' . $cart_item['quantity'] . ' &times; ' . woocommerce_price( $_product->get_price() ) . '</span></li>';
					endif;
				endforeach;
			else:
				echo '<li class="empty">' . __( 'Your shopping cart is empty', 'sp' ) . '</li>';
			endif;
			echo '</ul>';

			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) :
				echo '<p class="total"><strong>' . __( 'Subtotal', 'sp' ) . ':</strong> ' . $woocommerce->cart->get_cart_total() . '</p>';

				do_action( 'woocommerce_widget_shopping_cart_before_buttons' );

				echo '<p class="buttons"><a href="' . $woocommerce->cart->get_cart_url() . '" class="viewcart-button"><span>' . __( 'View Cart &rarr;', 'sp' ) . '</span></a></p>';
			endif;
			echo $after_widget;

			if ( $hide_if_empty && sizeof( $woocommerce->cart->get_cart() ) == 0 ) 
			{
				$inline_js = "
					jQuery('.hide_cart_widget_if_empty').closest('.widget').hide();
					jQuery('body').bind('adding_to_cart', function(){
						jQuery(this).find('.hide_cart_widget_if_empty').closest('.widget').fadeIn();
					});
				";

				$woocommerce->add_inline_js( $inline_js );
			}
		}
	}
}
/** 
 * function to get product rating - must use in the loop!
 * 
 * @since 2.1.1
 * @return string
 */
function sp_get_star_rating()
{
	global $wpdb, $post;
	
	$output = '';
	$count = $wpdb->get_var("
		SELECT COUNT(meta_value) FROM $wpdb->commentmeta 
		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
		WHERE meta_key = 'rating'
		AND comment_post_ID = $post->ID
		AND comment_approved = '1'
		AND meta_value > 0
	");
	
	$rating = $wpdb->get_var("
		SELECT SUM(meta_value) FROM $wpdb->commentmeta 
		LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
		WHERE meta_key = 'rating'
		AND comment_post_ID = $post->ID
		AND comment_approved = '1'
	");
	
	if ( $count > 0 ) :
		
		$average = number_format($rating / $count, 2);
		
		$output .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		
		$output .= '<div class="star-rating" title="'.sprintf(__('Rated %s out of 5', 'sp'), $average).'"><span style="width:'.($average*16).'px"><span itemprop="ratingValue" class="rating">'.$average.'</span> '.__('out of 5', 'sp').'</span></div>';
		$output .= '</div>';
	else :
		$output .= '';
	endif;
	
	return $output;
}

// add thankyou class to body
$page_id = get_option( 'woocommerce_thanks_page_id' );
$page_url = get_permalink( $page_id );
$products_page = substr( $page_url, '0', '-1' );
$products_page = substr( $products_page, strripos( $products_page, "/" ) + 1 );  
if ( strpos( $_SERVER['REQUEST_URI'], 'order-received' ) >= 1 ) 
{
	add_filter( 'body_class', 'sp_add_woo_class' );
	function sp_add_woo_class( $classes ) 
	{
	// add 'class-name' to the $classes array
	$classes[] = 'woocommerce-thankyou';
	// return the $classes array
	return $classes;
	}
}

if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '>' ) ) {
	add_filter( 'woocommerce_product_tabs', 'sp_woo_tabs_filter' );
}

function sp_woo_tabs_filter( $tabs ) {
	if ( ! sp_isset_option( 'product_description_tab', 'isset' ) || sp_isset_option( 'product_description_tab', 'boolean', 'false' ) )
		unset( $tabs['description'] );

	if ( ! sp_isset_option( 'product_attribute_tab', 'isset' ) || sp_isset_option( 'product_attribute_tab', 'boolean', 'false' ) )	
		unset( $tabs['additional_information'] );

	if ( ! sp_isset_option( 'product_review_tab', 'isset' ) || sp_isset_option( 'product_review_tab', 'boolean', 'false' ) )
		unset( $tabs['reviews'] );

	return $tabs;
}

if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {
	if ( ! sp_isset_option( 'product_description_tab', 'isset' ) || sp_isset_option( 'product_description_tab', 'boolean', 'false' ) )
		remove_action( 'woocommerce_product_tabs', 'woocommerce_product_description_tab', 10);

	if ( ! sp_isset_option( 'product_attribute_tab', 'isset' ) || sp_isset_option( 'product_attribute_tab', 'boolean', 'false' ) )
		remove_action( 'woocommerce_product_tabs', 'woocommerce_product_attributes_tab', 20);

	if ( ! sp_isset_option( 'product_review_tab', 'isset' ) || sp_isset_option( 'product_review_tab', 'boolean', 'false' ) )
		remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab',30);
}
?>