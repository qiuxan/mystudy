<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* backend ajax functions
******************************************************************/

/**
 * load ajax listener to WP
 * 
 ******************************************************************/
if ( is_admin() )
{
	add_action( 'wp_ajax_sp_theme_save', 'sp_theme_save' );
	add_action( 'wp_ajax_sp_theme_upload', 'sp_theme_upload' );
	add_action( 'wp_ajax_sp_delete_image', 'sp_delete_image' );
	add_action( 'wp_ajax_sp_theme_reset', 'sp_theme_reset' );
	add_action( 'wp_ajax_sp_clear_cache_ajax', 'sp_clear_cache_ajax' );
	add_action( 'wp_ajax_sp_clear_star_ratings_ajax', 'sp_clear_star_ratings_ajax' );
	add_action( 'wp_ajax_sp_export_theme_settings_ajax', 'sp_export_theme_settings_ajax' );
	add_action( 'wp_ajax_sp_import_theme_settings_ajax', 'sp_import_theme_settings_ajax' );
	add_action( 'wp_ajax_sp_create_page_ajax', 'sp_create_page_ajax' );
	add_action( 'wp_ajax_sp_check_ms_image_ajax', 'sp_check_ms_image_ajax' );
}

/**
 * save data function
 *
 */
function sp_theme_save() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die ( 'errors' );
	}

	parse_str( $_POST['form_items'], $form_data );
	unset( $form_data['action'] );
	unset( $form_data['_wpnonce'] );
	unset( $form_data['_wp_http_referer'] );

	$saved = sp_save_data( $form_data );
	
	echo 'done';	
	exit;	
}

/**
 * delete image
 *
 */
function sp_delete_image() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die ( 'false' );
	}
		
	if ( is_writable( SP_UPLOAD_URL . $_POST['image'] ) ) 
	{
		if ( @unlink( SP_UPLOAD_URL . $_POST['image'] ) ) 
		{
			echo "true";
		} 
		else 
		{
			echo "false";	
		}
	} 
	else 
	{
		echo "false";	
	}
	exit;
}

/**
 * restore default settings
 *
 */
function sp_theme_reset() 
{
	global $sptheme_config, $wpdb;
	
	$data = array();
	if ( is_array( $sptheme_config['tab'] ) ) 
	{
		foreach ( $sptheme_config['tab'] as $tabs ) 
		{
			foreach ( $tabs['panel'] as $panels ) 
			{
				if ( is_array( $panels['wrapper'] ) ) 
				{
					foreach ( $panels['wrapper'] as $wrappers ) 
					{
						if ( is_array( $wrappers['module'] ) ) 
						{
							foreach ( $wrappers['module'] as $module ) 
							{
								$data[THEME_SHORTNAME . $module['_attr']['id']] = $module['_attr']['std'];
							}
						}
					}
				}
			}			
		}
	}
	
	sp_save_data( $data );
	sp_update_options_function();
	
	echo "done";
	exit;
}


/**
 * empty all product star ratings
 *
 */
function sp_clear_star_ratings_ajax() 
{
	global $wpdb;
	
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die( 'errors' );
	}
	
	$sql = "TRUNCATE TABLE `wp_wpsc_product_rating`";
	if ( $wpdb->query( $sql ) ) 
	{
		echo 'done';	
	} 
	else 
	{
		echo 'errors';	
	}
	exit;
}

/**
 * exports theme settings
 *
 */
function sp_export_theme_settings_ajax()
{
	global $spthemeinfo;
	
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die( 'errors' );
	}
	
	$settings = ( get_option( $spthemeinfo['Name'] . "_sp_data" ) ) ? get_option( $spthemeinfo['Name'] . "_sp_data" ) : ''; 
	$upload_dir = wp_upload_dir();
	$target = $upload_dir['basedir']  . '/sp-theme-settings/';
		
	$check = false;
	if ( ! is_dir( $target ) )
	{		
		if ( ! mkdir( $target, 0755, true ) )
		{
			echo "errors";	
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
	
	// if directory path exists or is created and settings are set and exists	
	if ( $check && ( isset( $settings ) && $settings != '' ) )
	{
		$file = @fopen( $target . 'sp-export-settings.txt', "wb" );
		
		if ( $file ) 
		{
			@fwrite( $file, $settings );
		}
		
		@fclose( $file );
		
		echo 'done';
		exit;
	}
	else
	{
		echo 'errors';
		exit;	
	}
	
}

/**
 * import theme settings
 *
 */
function sp_import_theme_settings_ajax()
{
	global $spthemeinfo;
	
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die( 'errors' );
	}

	$upload_dir = wp_upload_dir();
	$target = $upload_dir['basedir']  . '/sp-theme-settings/sp-export-settings.txt';
	
	$file = @fopen( $target, "rb" );
	if ( $file ) 
	{
		$settings = @fread( $file, filesize( $target ) );	
	}
	else
	{
		echo 'errors';
		exit;	
	}
	
	@fclose( $file );
	
	$update_settings = update_option( $spthemeinfo['Name'] . "_sp_data", $settings );

	if ( $update_settings && ( isset( $settings ) && $settings != '' ) )
	{
		echo 'done';
		exit;	
	}
	else
	{
		echo 'errors';
		exit;	
	}
}

/**
 * create page
 *
 */
function sp_create_page_ajax() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die( 'errors' );
	}
	
	$template = mysql_real_escape_string( $_POST['page_template'] );
	$new_page_title = mysql_real_escape_string( $_POST['page_title'] );

	$page_check = get_page_by_title( $new_page_title );
	$new_page = array(
		'post_type' => 'page',
		'post_title' => $new_page_title,
		'post_status' => 'publish',
		'post_author' => 1,
	);
	if ( ! isset( $page_check->ID ) )
	{
		$new_page_id = wp_insert_post( $new_page );
		if ( ! empty( $template ) )
		{
			update_post_meta( $new_page_id, '_wp_page_template', $template );
		}
	}
	
	echo 'done';
	exit;
}

function sp_check_ms_image_ajax()
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die( 'errors' );
	}
	$image = mysql_real_escape_string( $_POST['image'] );
	$image = sp_check_ms_image($image);
	
	echo $image;
	exit;
}
?>