<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* custom logins frontend
******************************************************************/

// load ajax functions to WP
if ( is_admin() ) :
	add_action( 'wp_ajax_nopriv_sp_ajax_login', 'sp_ajax_login' );
	add_action( 'wp_ajax_sp_ajax_login', 'sp_ajax_login' );
	add_action( 'wp_ajax_nopriv_sp_ajax_lostpasswordform', 'sp_ajax_lostpasswordform' );
	add_action( 'wp_ajax_sp_ajax_lostpasswordform', 'sp_ajax_lostpasswordform' );
	add_action( 'wp_ajax_nopriv_sp_ajax_logout', 'sp_ajax_logout' );
	add_action( 'wp_ajax_sp_ajax_logout', 'sp_ajax_logout' );

endif;

// ajax WP login
function sp_ajax_login() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die ( 'Busted!' );
	}	
	$log = mysql_real_escape_string( trim( $_POST['log_name'] ) );
	$pwd = mysql_real_escape_string( trim( $_POST['pwd'] ) );	
	$rememberme = mysql_real_escape_string( trim( $_POST['rememberme'] ) );	
	$creds = array();
	$creds['user_login'] = $log;
	$creds['user_password'] = $pwd;
	if ( $rememberme == "true" ) 
	{
		$creds['remember'] = $rememberme;
	}
	$user = wp_signon( $creds, false );
	if ( is_wp_error( $user ) ) 
	{
	   $response = $user->get_error_message();
	   if ( $response == "" ) 
	   {
			$response = __( 'Please enter your username and password.', 'sp' );   
	   }
	} 
	else 
	{
		unset( $_SESSION['wpsc_checkout_saved_values'] );
		$response = get_permalink() . "?login=1";	
	}
	echo $response;
	exit;	
}

// ajax WP lost password form
function sp_ajax_lostpasswordform() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die ( 'Busted!' );
	}	
	require_once( ABSPATH . WPINC . '/registration.php' );
	$user_login = mysql_real_escape_string( trim( $_POST['user_login'] ) );
    // checks the login against the username first and then the email
	if ( username_exists( $user_login ) ) 
	{
		$redirect = get_permalink();
		$response = wp_lostpassword_url( $redirect );
	} 
	elseif ( email_exists( $user_login ) ) 
	{
		$redirect = get_permalink();
		$response = wp_lostpassword_url( $redirect );
	// if not found in both
	} 
	else 
	{
        $response = __( 'User does not exist!', 'sp' );
	}
	echo $response;
	exit;	
}

// ajax WP logout
function sp_ajax_logout() 
{
	$nonce = $_POST['ajaxCustomNonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax_custom_nonce' ) ) 
	{
	     die ('Busted!');
	}	
	//session_destroy();
	$redirect = get_permalink();
	wp_logout();	
	$response = $redirect;
	echo $response;
	exit;	
}

// display login form
function sp_display_login() 
{
		$output = '<div id="login_wrapper" class="group">' . "\r\n";
		$output .= '<div id="login_form">' . "\r\n";
			$output .= '<form name="loginform" id="ajax_loginform" action="' . home_url() . '/wp-login.php" method="post">' . "\r\n";
            	$output .='<p class="response"></p>' . "\r\n";
				$output .= '<p>' . "\r\n";
					$output .= '<label>' . __( 'Username:', 'sp' ) . '<br /><input type="text" name="log" id="log" class="text" value="" size="20" tabindex="1" /></label>' . "\r\n";
				$output .= '</p>' . "\r\n";

				$output .= '<p>' . "\r\n";
					$output .= '<label>' . __( 'Password:', 'sp' ).'<br /><input type="password" name="pwd" id="pwd" class="text" value="" size="20" tabindex="2" /></label>' . "\r\n";
				$output .= '</p>' . "\r\n";

				$output .= '<p>' . "\r\n";
					$output .= '<label>' . "\r\n";
						$output .= '<input name="rememberme" type="checkbox" id="rememberme" value="true" tabindex="3" />' . "\r\n";
						$output .= __( 'Remember me', 'sp' ) . '' . "\r\n";
					$output .= '</label>' . "\r\n";
				$output .= '</p>' . "\r\n";

				$output .= '<p>' . "\r\n";
					$output .= '<input type="submit" name="submit" id="login" value="' . esc_attr__( 'Login &raquo;', 'sp' ) . '" tabindex="4" />' . "\r\n";
					$output .= '<input type="hidden" name="redirect_to" value="' . get_option( 'user_account_url' ) . '" />' . "\r\n";
				$output .= '</p>' . "\r\n";
                $output .= '<div class="wpsc_loading_animation group">' . "\r\n";
                    $output .= '<img title="Loading" alt="Loading" src="'.get_template_directory_uri() . '/images/ajax-loader.gif" />' . "\r\n";
                    $output .= __( 'Checking Credentials', 'sp' ) . '' . "\r\n";
                $output .= '</div><!--close wpsc_loading_animation-->' . "\r\n";
			$output .= '</form>' . "\r\n";
	$output .= '</div><!--close login-->' . "\r\n";
    $output .= '<div id="forgot">' . "\r\n";
    		$redirect = $_SERVER['REQUEST_URI'];
			$output .= '<form name="lostpasswordform" id="ajax_lostpasswordform" action="' . urlencode( wp_lostpassword_url( $redirect ) ) . '" method="post">' . "\r\n";
				$output .= '<p>' . __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'sp' ) . '</p>' . "\r\n";
            	$output .= '<p class="response"></p>' . "\r\n";
				$output .= '<p>' . "\r\n";
					$output .= '<label>' . __( 'Username or E-mail:', 'sp' ) . '<br /><input type="text" class="text" name="user_login" id="user_login" value="" size="20" tabindex="1" /></label>' . "\r\n";
				$output .= '</p>' . "\r\n";

				$output .= '<p>' . "\r\n";
					$output .= '<input type="submit" name="submit" id="user_login_submit" value="' . esc_attr__( 'Get Password', 'sp' ) . '" tabindex="2" />' . "\r\n";
				$output .= '</p>' . "\r\n";
                $output .= '<div class="wpsc_loading_animation group">' . "\r\n";
                    $output .= '<img title="Loading" alt="Loading" src="' . get_template_directory_uri() . '/images/ajax-loader.gif" />' . "\r\n";
                    $output .= __( 'Checking Credentials', 'sp' );
                $output .= '</div><!--close wpsc_loading_animation-->' . "\r\n";
			$output .= '</form>' . "\r\n";
    $output .= '</div><!--close forgot-->' . "\r\n";
	$output .= '</div><!--close login_wrapper-->' . "\r\n";
	return $output;
}
?>