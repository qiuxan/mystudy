<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* maintenance mode
******************************************************************/
function sp_maintenance() {
	global $spdata;
		
	if ( isset( $spdata[THEME_SHORTNAME . 'maintenance_enable'] ) && $spdata[THEME_SHORTNAME . 'maintenance_enable'] == "true"  ) 
	{
		$ips = array_map( 'trim', explode( "\n", $spdata[THEME_SHORTNAME . 'maintenance_ips'] ) );

		if ( isset( $spdata[THEME_SHORTNAME . 'maintenance_redirect_to'] ) && $spdata[THEME_SHORTNAME . 'maintenance_redirect_to'] == 'maintenance page' ) 
		{
			// if user is logged in, don't continue
			if ( is_user_logged_in() && current_user_can( 'manage_options' ) )
				return;
			
			// if page does not exist, don't continue
			if ( is_null( sp_get_page_id( 'maintenance' ) ) )
				return;

			if ( sp_check_ip( $ips ) == false && ! is_page( 'maintenance' ) ) 
			{
				$page_id = get_permalink( sp_get_page_id( 'maintenance' ) );
				if ( $page_id )
				{
					wp_redirect( $page_id  ); 
					exit;
				}
			}
		} 
		elseif ( isset( $spdata[THEME_SHORTNAME . 'maintenance_redirect_to'] ) && $spdata[THEME_SHORTNAME . 'maintenance_redirect_to'] == 'url') 
		{
			if ( is_user_logged_in() )
				return;
			
			if ( sp_check_ip( $ips ) == false && ( isset( $spdata[THEME_SHORTNAME . 'maintenance_url'] ) && $spdata[THEME_SHORTNAME . 'maintenance_url'] != '' ) ) 
			{
				wp_redirect( $spdata[THEME_SHORTNAME . 'maintenance_url'] ); 
				exit; 
			}
		}
	}
}

add_action( 'template_redirect', 'sp_maintenance', 0 );

/*
 * checks the ips against server
 *
 * @return boolean
 */
function sp_check_ip( $ips ) 
{
	
	if ( in_array( $_SERVER['REMOTE_ADDR'], $ips ) ) 
		return true;
		
	return false;
}

?>