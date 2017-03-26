<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* facebook html5 like button script
******************************************************************/

/**
 * facebook js script for like button
 *
 * @return string
 */
if ( ! function_exists('sp_facebook_script' ) ) 
{
	function sp_facebook_script() 
	{
		global $spdata;
		
		if ( isset( $spdata[THEME_SHORTNAME . 'facebook_like_button'] ) && ( $spdata[THEME_SHORTNAME . 'facebook_like_button'] == "true" ) || ( function_exists( 'wpsc_show_fb_like' ) ? wpsc_show_fb_like() : false ) )
		{
			$output = '';
			$output .= '<div id="fb-root"></div>' . "\r\n";
			$output .= '<script>(function(d, s, id) {' . "\r\n";
			$output .= 'var js, fjs = d.getElementsByTagName(s)[0];' . "\r\n";
			$output .= 'if (d.getElementById(id)) {return;}' . "\r\n";
			$output .= 'js = d.createElement(s); js.id = id;' . "\r\n";
			$output .= 'js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";' . "\r\n";
			$output .= 'fjs.parentNode.insertBefore(js, fjs);' . "\r\n";
			$output .= '}(document, "script", "facebook-jssdk"));</script>' . "\r\n";
			
			return $output;
		}
		else
		{
			return;	
		}
	}
}

?>