<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* framework class
******************************************************************/

// load cofiguration items
require_once( get_template_directory() . '/sp-framework/functions/config.php' );
	
// load xml2array function
require_once( get_template_directory() . '/sp-framework/functions/xml2array.php' );

// load database functions
require_once( get_template_directory() . '/sp-framework/functions/database.php' );

// load utility functions
require_once( get_template_directory() . '/sp-framework/functions/utils.php' );	

// load maintenance functions
require_once( get_template_directory() . '/sp-framework/functions/maintenance.php' );

// load custom post types
require_once( get_template_directory() . '/sp-framework/functions/custom-post-types.php' );

// load custom widgets
require_once( get_template_directory() . '/sp-framework/functions/class-widgets.php' );

// load custom login
require_once( get_template_directory() . '/sp-framework/functions/custom-login.php' );

// load pagination
require_once( get_template_directory() . '/sp-framework/functions/pagination.php' );

// load shortcode and tags
require_once( get_template_directory() . '/sp-framework/functions/shortcodes-tags.php' );

// load custom styles
require_once( get_template_directory() . '/sp-framework/functions/custom-styles.php' );

// load header-footer scripts
require_once( get_template_directory() . '/sp-framework/functions/header-footer-scripts.php' );

// load facebook like scripts
require_once( get_template_directory() . '/sp-framework/functions/facebook-like-script.php' );

// load social media icons
require_once( get_template_directory() . '/sp-framework/functions/social-media-icons.php' );

// include theme post enhancements
if ( file_exists( get_template_directory() . '/sp-posts-comments.php' ) )
	require_once( get_template_directory() . '/sp-posts-comments.php' );

// include widgets
if ( file_exists( get_template_directory() . '/sp-widgets.php' ) )
	require_once( get_template_directory() . '/sp-widgets.php' );

// include theme menus
if ( file_exists( get_template_directory() . '/sp-menu.php' ) )
	require_once( get_template_directory() . '/sp-menu.php' );

// include WPEC also bought function
if ( class_exists( 'WP_eCommerce' ) )
{
	if ( file_exists( get_template_directory() . '/sp-also-bought.php' ) )
		require_once( get_template_directory() . '/sp-also-bought.php' );
}

// include WPEC functions
if ( class_exists( 'WP_eCommerce' ) )
{
	if ( file_exists( get_template_directory() . '/sp-framework/functions/wpec-functions.php' ) )
		require_once( get_template_directory() . '/sp-framework/functions/wpec-functions.php' );
}

// include WOO Commerce functions
if ( class_exists( 'woocommerce' ) )
{
	if ( file_exists( get_template_directory() . '/sp-framework/functions/woo-functions.php' ) )
		require_once( get_template_directory() . '/sp-framework/functions/woo-functions.php' );
}

// load theme setup
require_once( get_template_directory() . '/sp-framework/functions/theme-setup.php' );

// load theme filters
require_once( get_template_directory() . '/sp-framework/functions/theme-filters.php' );

// load theme scripts
require_once( get_template_directory() . '/sp-framework/functions/theme-scripts.php' );

// load deprecated functions
require_once( get_template_directory() . '/sp-framework/functions/deprecated-functions.php' );

// load custom meta boxes
require_once( get_template_directory() . '/sp-framework/functions/custom-meta-boxes.php' );
		
// load backend ajax functions
require_once( get_template_directory() . '/sp-framework/functions/backend-ajax.php' );

// load google fonts
require_once( get_template_directory() . '/sp-framework/functions/google-fonts.php' );

// load backend control panel modules
require_once( get_template_directory() . '/sp-framework/functions/control-panel-modules.php' );

// load backend control panel
require_once( get_template_directory() . '/sp-framework/functions/control-panel.php' );
		
?>