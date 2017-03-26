<?php
/**
 * SP FRAMEWORK FILE - DO NOT EDIT!
 * 
 * if you want to add your own functions, create a file called custom_functions.php inside your theme root folder and put your functions in there
 *
 * include all the functions
 ******************************************************************/

// Make theme available for translation
add_action( 'after_setup_theme', 'sp_load_textdomain' );
function sp_load_textdomain() {
	load_theme_textdomain( 'sp', get_template_directory() . '/languages' );
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
}

/**
 * checks to see if custom_functions is used and if so check if child theme is active
 ******************************************************************/
if ( is_child_theme() ) 
{
	if ( is_file( get_stylesheet_directory() . '/custom_functions.php' ) ) 
	{
		require_once( get_stylesheet_directory() . '/custom_functions.php' );
	}
} 
else 
{
	if ( is_file( get_template_directory() . '/custom_functions.php' ) ) 
	{
		require_once( get_template_directory() . '/custom_functions.php' );
	}	
}

require_once( get_template_directory() . '/sp-framework/sp-framework.php' );