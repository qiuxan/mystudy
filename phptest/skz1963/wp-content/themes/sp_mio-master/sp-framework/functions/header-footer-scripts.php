<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* header and footer scripts
******************************************************************/

global $spdata;

/**
 * google analytics code insert
 *
 * @return string of JS code
 */
function sp_add_ga() 
{
	global $spdata;
	
	echo stripslashes( $spdata[THEME_SHORTNAME . 'ga'] ) . "\r\n"; 
}

if ( isset( $spdata[THEME_SHORTNAME . 'ga'] ) && $spdata[THEME_SHORTNAME . 'ga'] != '' ) 
{
	add_action( 'wp_head', 'sp_add_ga', 12 );
}

/**
 * custom head scripts
 *
 * @return string of JS code
 */
function sp_custom_head_scripts() 
{
	global $spdata;
	
	$output = '';
	$output .= '<script type="text/javascript">';
	$output .= stripslashes( strip_tags( $spdata[THEME_SHORTNAME . 'custom_head_scripts'], $allowed_tags = '<b><i><sup><sub><em><strong><u><br><p><div><a><section><aside>' ) );
	$output .= '</script>' . "\r\n";
	echo $output;

}

if ( isset( $spdata[THEME_SHORTNAME . 'custom_head_scripts'] ) && $spdata[THEME_SHORTNAME . 'custom_head_scripts'] != '' ) 
{
	add_action( 'wp_head', 'sp_custom_head_scripts', 13 );
}

/**
 * custom footer scripts
 *
 * @return string of JS code
 */
function sp_custom_footer_scripts() 
{
	global $spdata;
	
	$output = '';
	$output .= '<script type="text/javascript">';
	$output .= stripslashes( strip_tags( $spdata[THEME_SHORTNAME . 'custom_footer_scripts'], $allowed_tags = '<b><i><sup><sub><em><strong><u><br><p><div><a><section><aside>' ) );
	$output .= '</script>' . "\r\n";
	
	echo $output;
	
}

if ( isset( $spdata[THEME_SHORTNAME . 'custom_footer_scripts'] ) && $spdata[THEME_SHORTNAME . 'custom_footer_scripts'] != '' ) 
{
	add_action( 'wp_footer', 'sp_custom_footer_scripts' );
}

/**
 * if meta information set put in head
 *
 * @return string meta code
 */
function sp_seo() 
{
	global $spdata;
	
	if ( isset( $spdata[THEME_SHORTNAME . 'seo_keywords'] ) && $spdata[THEME_SHORTNAME . 'seo_keywords'] != '' ) 
	{
		echo "\r\n" . '<meta name="keywords" content="' . $spdata[THEME_SHORTNAME . 'seo_keywords'] . '" />' . "\r\n";
	}
	
	if ( isset( $spdata[THEME_SHORTNAME . 'seo_description'] ) && $spdata[THEME_SHORTNAME . 'seo_description'] != '' ) 
	{
		echo "\r\n" . '<meta name="description" content="' . $spdata[THEME_SHORTNAME . 'seo_description'] . ' " />' . "\r\n";
	}
}
add_action( 'wp_head', 'sp_seo' );

/**
 * favicon
 *
 * @return string meta code
 */
function sp_favicon() 
{ 
	global $spdata;
	
	$output = '';
	if ( isset( $spdata[THEME_SHORTNAME . 'favicon'] ) && $spdata[THEME_SHORTNAME . 'favicon'] != '' )
	{ 
		$fav_url = $spdata[THEME_SHORTNAME . 'favicon'];
		if ( is_ssl() )
			$fav_url = str_replace( 'http', 'https', $fav_url );
		
		$output = '<link rel="icon" type="image/x-icon" href="' . $fav_url . '" />' . "\r\n";
	}
	else
	{		
		$output = '<link rel="icon" type="image/x-icon" href="' . get_template_directory_uri() . '/images/favicon.ico" />' . "\r\n";
	}
	
	echo $output;
}
add_action( 'wp_head', 'sp_favicon', 5 );

// custom backend WP login
function sp_backend_custom_login() 
{ 
			global $spdata;

            if ( isset( $spdata[THEME_SHORTNAME . 'backend_login_logo'] ) && $spdata[THEME_SHORTNAME . 'backend_login_logo'] != '' ) :
					$logo = $spdata[THEME_SHORTNAME . 'backend_login_logo'];
			else :
				if ( isset( $spdata[THEME_SHORTNAME . 'skins'] ) && $spdata[THEME_SHORTNAME . 'skins'] != '1' && $spdata[THEME_SHORTNAME . 'skins'] != '' )
				{
					if ( file_exists( get_template_directory() . '/skins/images/skin' . $spdata[THEME_SHORTNAME . 'skins'] . '/logo.png' ) )
					{
						$logo = get_template_directory_uri() . '/skins/images/skin' . $spdata[THEME_SHORTNAME . 'skins'] . '/logo.png';
					} 
					else 
					{
						$logo = get_template_directory_uri() . '/images/logo.png';	
					}
				}
				else
				{
					$logo = get_template_directory_uri() . '/images/logo.png';
				}
			endif;
	if ( isset( $spdata[THEME_SHORTNAME . 'skins'] ) && $spdata[THEME_SHORTNAME . 'skins'] != '1' && $spdata[THEME_SHORTNAME . 'skins'] != '' )
	{
		$style = 'sp-custom-login_skin_' . $spdata[THEME_SHORTNAME . 'skins'] . '.css';
	}
	else 
	{
		$style = 'sp-custom-login.css';	
	}
	echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/sp-custom-login/' . $style . '" />'; 
	echo '<style type="text/css">
		h1 a { background-image:url(' . $logo . ') !important; }
	</style>';
}
add_action( 'login_head', 'sp_backend_custom_login' );

?>