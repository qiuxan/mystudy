<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* database functions
******************************************************************/

global $sptheme_config, $spdata, $spthemeinfo;

/**
 * get the theme config XML data
 *
 * @return array
 */
function sp_get_sptheme_config()
{		
	$file = fopen( get_template_directory() . '/config.xml', 'r' );
	$config = fread( $file, filesize( get_template_directory() . '/config.xml' ) );	
	fclose( $file );
	$config = sp_xml2array( $config );
	$config = $config['config'];
  
	return $config;
}
$sptheme_config = sp_get_sptheme_config();

/**
 * save the theme data
 * 
 * @return boolean
 */
function sp_save_data( $data = array() ) 
{
	global $spthemeinfo;

	// be sure to encode and serialize the data
	return update_option( $spthemeinfo['Name'] . "_sp_data", base64_encode( maybe_serialize( $data ) ) ); 
	
}

/**
 * get the theme data
 * 
 * @return array
 */
function sp_get_data() 
{
	global $spthemeinfo, $spdata;
	
	// gets the data from option table
	$data = get_option( $spthemeinfo['Name'] . "_sp_data" ); 
	
	// decodes and unserialize for use
	$data = maybe_unserialize( base64_decode( $data ) );
	
	// check if it is an array and if there are atleast 1 option record
	if ( is_array( $data ) && count( $data ) >= 1 ) 
	{
		// loop through the options as key/value pair
		foreach ( $data as $name => $value ) 
		{
			if ( is_array( $value ) ) 
			{
				foreach ( $value as $k => $v ) 
				{
					$data[$name][$k] = stripslashes( $v );	
				}
			} 
			else 
			{
				$data[$name] = stripslashes( $value );	
			}
		}
	} 
	return $data;	
}
$spdata = sp_get_data();

/**
 * get init theme setting from XML
 * 
 * @return string
 */
function sp_get_theme_init_setting( $item = '', $option = '' ) 
{
	global $sptheme_config;
	
	return $sptheme_config['init'][$item]['_attr'][$option];
}

/**
 * update option function for init settings
 * 
 */
function sp_update_options_function()
{
	global $sptheme_config;
	
	/*** WPEC plugin options ****/
	// single product view image sizes
	if ( isset( $sptheme_config['init']['wpec_single_view_image_size'] ) ) 
	{
		update_option( 'single_view_image_height', sp_get_theme_init_setting( 'wpec_single_view_image_size', 'height' ) );
		update_option( 'single_view_image_width', sp_get_theme_init_setting( 'wpec_single_view_image_size', 'width' ) );
	}
	
	// product grid/default view image sizes
	if ( isset( $sptheme_config['init']['wpec_product_image_size'] ) )
	{
		update_option( 'product_image_height', sp_get_theme_init_setting( 'wpec_product_image_size', 'height' ) );
		update_option( 'product_image_width', sp_get_theme_init_setting( 'wpec_product_image_size', 'width' ) );
	}
	
	// image crop option (not sure if this is still needed)
	if ( isset( $sptheme_config['init']['wpec_image_crop'] ) )
	{
		update_option( 'wpsc_crop_thumbnails', sp_get_theme_init_setting( 'wpec_image_crop', 'crop' ) );
	}
	
	// product gallery thumbnail size (gold cart)
	if ( isset( $sptheme_config['init']['wpec_product_gallery_size'] ) )
	{
		update_option( 'wpsc_gallery_image_height', sp_get_theme_init_setting( 'wpec_product_gallery_size', 'height' ) );
		update_option( 'wpsc_gallery_image_width', sp_get_theme_init_setting( 'wpec_product_gallery_size', 'width' ) );
	}
	
	// product group/category thumbnail size
	if ( isset( $sptheme_config['init']['wpec_product_category_size'] ) )
	{
		update_option( 'category_image_height', sp_get_theme_init_setting( 'wpec_product_category_size', 'height' ) );
		update_option( 'category_image_width', sp_get_theme_init_setting( 'wpec_product_category_size', 'width' ) );
	}
	
	// set the default WPEC pagination settings
	if ( isset( $sptheme_config['init']['wpec_pagination'] ) )
	{
		update_option( 'use_pagination', sp_get_theme_init_setting( 'wpec_pagination', 'enable' ) );
		update_option( 'wpsc_products_per_page', sp_get_theme_init_setting( 'wpec_pagination', 'per_page' ) );
		update_option( 'wpsc_page_number_position', sp_get_theme_init_setting( 'wpec_pagination', 'position' ) );
	}
	
	// turn on add to cart for grid view
	if ( isset( $sptheme_config['init']['wpec_gridview_addtocart'] ) )
		update_option( "display_addtocart", sp_get_theme_init_setting( 'wpec_gridview_addtocart', 'enable' ) );

	/*** WOO COMMERCE plugin options ****/
	// product grid view image sizes
	if ( isset( $sptheme_config['init']['woo_product_image_size'] ) )
	{
		// if less than 2.0
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {			
			update_option( 'woocommerce_catalog_image_width', sp_get_theme_init_setting( 'woo_product_image_size', 'width' ) );
			update_option( 'woocommerce_catalog_image_height', sp_get_theme_init_setting( 'woo_product_image_size', 'height' ) );
		} else {
			update_option( 'shop_catalog_image_size', array( 'width' => sp_get_theme_init_setting( 'woo_product_image_size', 'width' ), 'height' => sp_get_theme_init_setting( 'woo_product_image_size', 'height' ), 'crop' => (bool)sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) ) );
		}
	}
	
	// single product view image sizes
	if ( isset( $sptheme_config['init']['woo_single_view_image_size'] ) )
	{
		// if less than 2.0
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {			
			update_option( 'woocommerce_single_image_width', sp_get_theme_init_setting( 'woo_single_view_image_size', 'width' ) ); 
			update_option( 'woocommerce_single_image_height', sp_get_theme_init_setting( 'woo_single_view_image_size', 'height' ) );
		} else {
			update_option( 'shop_single_image_size', array( 'width' => sp_get_theme_init_setting( 'woo_single_view_image_size', 'width' ), 'height' => sp_get_theme_init_setting( 'woo_single_view_image_size', 'height' ), 'crop' => (bool)sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) ) );
		}
	}
	
	// product gallery thumbnail image sizes
	if ( isset( $sptheme_config['init']['woo_product_thumbnail_size'] ) )
	{
		// if less than 2.0
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {				
			update_option( 'woocommerce_thumbnail_image_width', sp_get_theme_init_setting( 'woo_product_thumbnail_size', 'width' ) );
			update_option( 'woocommerce_thumbnail_image_height', sp_get_theme_init_setting( 'woo_product_thumbnail_size', 'height' ) );
		} else {
			update_option( 'shop_thumbnail_image_size', array( 'width' => sp_get_theme_init_setting( 'woo_product_thumbnail_size', 'width' ), 'height' => sp_get_theme_init_setting( 'woo_product_thumbnail_size', 'height' ), 'crop' => (bool)sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) ) );
		}
	}
	
	// image crop settings
	if ( isset( $sptheme_config['init']['woo_image_crop'] ) )
	{
		// if less than 2.0
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {			
			update_option( 'woocommerce_thumbnail_image_crop', sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) );
			update_option( 'woocommerce_single_image_crop', sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) );
			update_option( 'woocommerce_catalog_image_crop', sp_get_theme_init_setting( 'woo_image_crop', 'crop' ) );	  
		}
	}
	
	/**** WP Blog image options ****/
	
	// set the default post thumbnail size
	if ( isset( $sptheme_config['init']['set_post_thumbnail_size'] ) )
	{
		update_option( 'thumbnail_size_w', sp_get_theme_init_setting( 'set_post_thumbnail_size', 'width' ) );
		update_option( 'thumbnail_size_h', sp_get_theme_init_setting( 'set_post_thumbnail_size', 'height' ) );	
		
		// set the image crop option for blog featured images (not sure if this is still needed)
		update_option( 'thumbnail_crop', sp_get_theme_init_setting( 'set_post_thumbnail_size', 'crop' ) );
	}	

	// set the medium and large image sizes to zero to prevent WP from auto generating images
	update_option( 'medium_size_w', 0 );
	update_option( 'medium_size_h', 0 );
	update_option( 'large_size_w', 0 );
	update_option( 'large_size_h', 0 );

	// setup permalinks
	update_option( 'permalink_structure', '/%category%/%postname%/' );	

	// set to show avatars
	update_option( 'show_avatars', '1' );

	set_post_thumbnail_size( sp_get_theme_init_setting( 'set_post_thumbnail_size', 'width' ), sp_get_theme_init_setting( 'set_post_thumbnail_size', 'height' ), sp_get_theme_init_setting( 'set_post_thumbnail_size', 'crop' ) );

	// flush permalink cache
	flush_rewrite_rules();

	// loop through any additional pages that need to be added 
	$add_page_count = count( $sptheme_config['init']['add_page'] ); // check how many pages to be added
	
	foreach ( $sptheme_config['init']['add_page'] as $page )
	{
		if ( $add_page_count > 1 ) 
		{
			$new_page_title = $page['_attr']['name'];
			$new_page_content = sprintf( __( '%s', 'sp' ), $page['_attr']['content'] );
			$new_page_template = $page['_attr']['file']; //ex. template-custom.php. Leave blank if you don't want a custom page template.
		}
		else 
		{
			$new_page_title = $page['name'];
			$new_page_content = sprintf( __( '%s', 'sp' ), $page['content'] );
			$new_page_template = $page['file']; //ex. template-custom.php. Leave blank if you don't want a custom page template.
		}
		$page_check = get_page_by_title( $new_page_title );
		$new_page = array(
			'post_type' => 'page',
			'post_title' => $new_page_title,
			'post_content' => $new_page_content,
			'post_status' => 'publish',
			'post_author' => 1,
		);
		if ( ! isset( $page_check->ID ) )
		{
			$new_page_id = wp_insert_post( $new_page );
			if ( ! empty( $new_page_template ) )
			{
				update_post_meta( $new_page_id, '_wp_page_template', $new_page_template );
			}
		}
	}	
	
	// change show on front page to home
	$page = get_page_by_title( 'sp home' );
	update_option( 'page_on_front', $page->ID );		

	// change show on front to page
	update_option( 'show_on_front', 'page' );
}

/**
 * initialize the database for the first time called by config
 *
 */
function sp_init_db() 
{
	global $spthemeinfo, $sptheme_config;
	

	add_option( $spthemeinfo['Name'] . "_sp_data", '', '', 'yes' );

	/**
	* get theme specific config xml
	*
	******************************************************************/

		$data = array();
		if ( isset( $sptheme_config['tab'] ) ) 
		{
			foreach ( $sptheme_config['tab'] as $tabs ) 
			{
				if ( isset( $tabs['panel'] ) )
				{
					foreach ( $tabs['panel'] as $panels ) 
					{
						if ( isset( $panels['wrapper'] ) ) 
						{
							foreach ( $panels['wrapper'] as $wrappers ) 
							{
								if ( isset( $wrappers['module'] ) ) 
								{
									foreach ( $wrappers['module'] as $module ) 
									{
										if ( isset( $module['_attr']['id'] ) && isset( $module['_attr']['std'] )  )
											$data[THEME_SHORTNAME . $module['_attr']['id']] = $module['_attr']['std'];
									}
								}
							}
						}
					}			
				}
			}
		}
		return sp_save_data( $data );
}
?>