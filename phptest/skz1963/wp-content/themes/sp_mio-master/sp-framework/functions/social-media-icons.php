<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* social media icons
******************************************************************/

/**
 * function to output social media icon tags
 * 
 * @return string HTML
 */
if ( ! function_exists( 'sp_social_media_script' ) ) 
{
	function sp_social_media_script() 
	{
		global $spdata;
		
		$output = '';
		$output .= '<ul>' . "\r\n";	
		if ( isset( $spdata[THEME_SHORTNAME . 'facebook_enable'] ) && $spdata[THEME_SHORTNAME . 'facebook_enable'] == 'true' ) 
		{
			$output .= '<li class="facebook"><a href="' . $spdata[THEME_SHORTNAME . 'facebook_account'] . '" title="' . __( 'Join Us on Facebook', 'sp' ) . '">Facebook</a></li>' . "\r\n";
		}
		if ( isset( $spdata[THEME_SHORTNAME . 'twitter_enable'] ) && $spdata[THEME_SHORTNAME . 'twitter_enable'] == 'true' ) 
		{
			$output .= '<li class="twitter"><a href="' . $spdata[THEME_SHORTNAME . 'twitter_account'] . '" title="' . __( 'Follow our Tweets', 'sp' ) . '">Twitter</a></li>' . "\r\n";	
		}
		if ( isset( $spdata[THEME_SHORTNAME . 'pinterest_enable'] ) && $spdata[THEME_SHORTNAME . 'pinterest_enable'] == 'true' )  
		{
			$output .= '<li class="pinterest"><a href="' . $spdata[THEME_SHORTNAME . 'pinterest_account'] . '" title="' . __( 'Follow our Pins', 'sp' ) . '">Pinterest</a></li>' . "\r\n";	
		}
		if ( isset( $spdata[THEME_SHORTNAME . 'flickr_enable'] ) && $spdata[THEME_SHORTNAME . 'flickr_enable'] == 'true' ) 
		{
			$output .= '<li class="flickr"><a href="' . $spdata[THEME_SHORTNAME . 'flickr_account'] . '" title="' . __( 'Checkout our Flickr Photos', 'sp' ) . '">Flickr</a></li>' . "\r\n";	
		}
		if ( isset( $spdata[THEME_SHORTNAME . 'gplus_enable'] ) && $spdata[THEME_SHORTNAME . 'gplus_enable'] == 'true' ) 
		{
			$output .= '<li class="gplus"><a href="' . $spdata[THEME_SHORTNAME . 'gplus_account'] . '" title="' . __( 'Checkout our Google Plus Profile', 'sp' ) . '">Google Plus</a></li>' . "\r\n";	
		}
		if ( isset( $spdata[THEME_SHORTNAME . 'youtube_enable'] ) && $spdata[THEME_SHORTNAME . 'youtube_enable'] == 'true' ) 
		{
			$output .= '<li class="youtube"><a href="' . $spdata[THEME_SHORTNAME . 'youtube_account'] . '" title="' . __( 'Checkout our YouTube Videos', 'sp' ) . '">YouTube</a></li>' . "\r\n";	
		}		
		if ( isset( $spdata[THEME_SHORTNAME . 'rss_enable'] ) && $spdata[THEME_SHORTNAME . 'rss_enable'] == 'true' ) 
		{
			$output .= '<li class="rss"><a href="' . get_bloginfo('rss2_url') . '" title="' . __( 'Get Fed on our Feeds' , 'sp' ) . '">RSS</a></li>' . "\r\n";	
		}
			$output .= '</ul>' . "\r\n";
		echo $output;
	}
}

?>