<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* configuration
******************************************************************/
global $spthemeinfo;

/**
 * gets the theme information from the stylesheet specs
 *
 * @return array
 */
if ( function_exists( 'wp_get_theme' ) ) 
{
	$spthemeinfo = wp_get_theme(); // function since WP 3.4
}
else 
{
	$spthemeinfo = wp_get_theme( get_template_directory() . '/style.css' ); // deprecated function since 3.4
}

/**
 * define framework version
 *
 * @return constant
 */					
define( 'SP_FRAMEWORK_VERSION', '2.1.4' );	   

/**
 * define theme shortname
 *
 * @return constant
 */					
define( 'THEME_SHORTNAME', "sp_" . strtolower( preg_replace( '~\s.*~', '', $spthemeinfo['Name'] ) . "_" ) );

/**
 * define wordpress installed url
 *
 * @return constant
 */					
define( 'WP_URL', trailingslashit( site_url() ) );

/**
 * define site url
 *
 * @return constant
 */					
define( 'SITE_URL', trailingslashit( home_url() ) );

/**
 * define theme url
 *
 * @return constant
 */							
define ( 'THEME_URL', trailingslashit( get_template_directory_uri() ) );

/**
 * define framework url
 *
 * @return constant
 */							
define ( 'FRAMEWORK_URL', trailingslashit( get_template_directory_uri() . '/sp-framework/' ) );

/**
 * define upload path url
 *
 * @return constant
 */			
$upload_path = wp_upload_dir();

define ( 'SP_UPLOAD_URL', trailingslashit( $upload_path['baseurl'] ) . '/sp-uploads/' );

/**
 * load default theme settings on theme activation
 *
 */
add_action( 'after_switch_theme', 'sp_load_defaults' );

// function is run before init
if ( ! function_exists( 'sp_load_defaults' ) ) 
{
	function sp_load_defaults() 
	{ 
		global $wpdb, $spthemeinfo, $sptheme_config;
		
		$data_exists = get_option( $spthemeinfo['Name'] . '_sp_data' );
		if ( ! $data_exists ) 
		{
			sp_init_db(); // ajax function

			sp_update_options_function(); // update options table with defaults		

			// clear the widgets area
			$widgets = get_option( 'sidebars_widgets' );

			unset( $widgets['site-top-widget'] );
			unset( $widgets['site-bottom-widget'] );
			unset( $widgets['page-top-widget'] );
			unset( $widgets['page-bottom-widget'] );
			unset( $widgets['sidebar-left-sitewide'] );
			unset( $widgets['sidebar-right-sitewide'] );	

			update_option( 'sidebars_widgets', $widgets );		
		}
	}
}
?>