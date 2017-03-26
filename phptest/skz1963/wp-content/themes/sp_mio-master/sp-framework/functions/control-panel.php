<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* admin theme control panel (theme options)
******************************************************************/

/**
 * checks the versions on load
 *
 */
// checks only on admin side
if ( is_admin() ) 
{
	if ( function_exists( 'sp_check_version' ) ) 
	{
		sp_check_version();
	}
}

/**
 * run the admin options function if permission allows
 *
 */
if ( current_user_can( 'manage_options' ) ) 
{
	add_action( 'admin_menu', 'sp_admin_menu' );	
}

/**
 * load admin css and scripts
 *
 */
if ( is_admin() )
{
	add_action('admin_enqueue_scripts', 'sp_admin_css_javascripts');
}

function sp_admin_css_javascripts() 
{
	// enqueue jquery ui
	//wp_deregister_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	//wp_enqueue_script( apply_filters( 'sp_jquery_ui', 'sp_jquery_ui' ), sp_ssl_http() . 'ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js', null, '1.8.23' );
	// enqueue jquery timepicker
	wp_enqueue_script( apply_filters( 'sp_jquery_timepicker', 'sp_jquery_timepicker' ), trailingslashit( FRAMEWORK_URL ) . 'js/jquery-timepicker.min.js', null, '1.1.1', true );
	// enqueue admin styles
	wp_enqueue_style( 'sp_admin_style', trailingslashit( FRAMEWORK_URL ) . 'css/admin.css');	

	// loads only if it is on a SP theme options page
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'sp' ) 
	{
		// enqueue thickbox style
		wp_enqueue_style('thickbox');
		// enqueue colorpicker style		
		wp_enqueue_style( 'sp_colopicker_style', trailingslashit( FRAMEWORK_URL ) . 'colorpicker/css/colorpicker.css');
		// enqueue colorpicker
		wp_enqueue_script( 'sp_colorpicker', trailingslashit( FRAMEWORK_URL ) . 'colorpicker/js/colorpicker.js', array( 'jquery' ) );
		// enqueue jquery chosen
		wp_enqueue_script( 'sp_chosen', trailingslashit( FRAMEWORK_URL ) . 'js/chosen.jquery.min.js', array( 'jquery' ), '0.9.10' );
		// enqueue thickbox for media
		wp_enqueue_script('thickbox');
		// enqueue media upload
		wp_enqueue_script('media-upload');
		// enqueue sp panel admin scripts
		wp_enqueue_script( 'sp_admin_js', trailingslashit( FRAMEWORK_URL ) . 'js/sp-admin.js', array( 'jquery' ), SP_FRAMEWORK_VERSION );
		// localize a namespace for ajax functions
		$ssl = 'http';
		if ( FORCE_SSL_ADMIN || FORCE_SSL_LOGIN ) 
		{
			$ssl = 'https';	
		}		
		$upload_path = wp_upload_dir();
		$localized_vars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajaxCustomNonce' => wp_create_nonce( 'ajax_custom_nonce'),
		 	'framework_url' => FRAMEWORK_URL,
			'theme_url' => trailingslashit( get_template_directory_uri() ),
			'site_url' => trailingslashit( home_url() ),
			'wp_url' => trailingslashit( site_url() ),
			'sp_upload_url' => trailingslashit( $upload_path['baseurl'] ) . 'sp-uploads' ,
			'insert_media' => __( 'insert media', 'sp' ),
			'theme_name' => THEME_SHORTNAME,
			'settings_import_msg' => __( 'This will overwrite all your settings.  Are you sure you want to import the theme settings from your last exported state?', 'sp' ),
			'hide_notification_msg' => __( 'If you disable this message, you will no longer receive update notices for any future updates available.  Are you sure?', 'sp' ),
			'reset_settings_msg' => __( 'Are you sure you want to reset to theme default settings?', 'sp' ),
			'reset_cache_msg' => __( 'Are you sure you want to empty the image cache folder?', 'sp' ),
			'clear_stars_ratings_msg' => __( 'Are you sure you want to clear ALL product star ratings?', 'sp' ),
			'media_uploaded_msg' => __( 'Your image is uploaded, please click on "Save All"', 'sp' )
		);
		
		wp_localize_script( apply_filters( 'sp_admin_js', 'sp_admin_js' ), 'sp_theme', $localized_vars );			
	}
	// loads if it is in WP backend general
	else
	{
		// enqueue general admin scripts
		wp_enqueue_script( 'sp_general_admin_js', trailingslashit( FRAMEWORK_URL ) . 'js/general-admin.js', array( 'jquery' ), SP_FRAMEWORK_VERSION );
		wp_localize_script( 'sp_general_admin_js', 'sp_admin_general', array( 'portfolio_template_msg' => __( 'You have selected a Portfolio Template.  To enable portfolio page options, please save the entry once with publish or save draft.', 'sp' ), 'wpec_move_themes_msg' => __( 'This function has been disabled as it is not necessary with SP themes', 'sp' ) ) );
	}

}

/**
 * admin menu
 *
 */
function sp_admin_menu() 
{
		global $spthemeinfo;		
		
		add_theme_page( 'sp', $spthemeinfo['Name'], 'manage_options', 'sp', 'sp_panel', trailingslashit( FRAMEWORK_URL ) . 'images/favicon.png', 56);
		add_submenu_page( 'sp', $spthemeinfo['Name'], 'Theme Settings', 'manage_options', 'sp', 'sp_panel' );			
}

/**
 * admin panel
 *
 */
function sp_panel() 
{ 
	global $wpdb, $spthemeinfo, $spdata, $sptheme_config, $notification;	 
?>
    <div class="wrap">
    	<div class="notification"><?php if ( ! isset( $spdata[THEME_SHORTNAME . 'hide_msg'] ) || $spdata[THEME_SHORTNAME . 'hide_msg'] == '' || $spdata[THEME_SHORTNAME . 'hide_msg'] == 'false' ){ echo $notification; } ?></div>
		<form method="post" enctype="multipart/form-data" class="main-form" id="sp-panel">	
			<div class="alert"></div>
        	<?php if ( function_exists( 'wp_nonce_field' ) ) wp_nonce_field( 'sp-theme-option-save' ); ?>
            <?php if ( function_exists( 'wp_nonce_field' ) ) wp_nonce_field( 'sp-theme-option-reset' ); ?>  
            <input type="hidden" name="action" value="save" />
            <div id="content">
            	<div id="header" class="group">
                	<div id="logo">
                    	<h1><?php echo $spthemeinfo['Name']; ?></h1>
                        <small>v<?php echo $spthemeinfo['Version']; ?></small>
                    </div><!--close logo-->
                    <div id="nav">
                        <ul id="tabs">
                            <li class="general"><a href="#general" title="General Settings"><?php _e( 'GENERAL', 'sp' ); ?><br /><span><?php _e( 'settings', 'sp' ); ?></span></a></li>
                            <li class="styling"><a href="#styling" title="Styling Options"><?php _e( 'STYLING', 'sp' ); ?><br /><span><?php _e( 'options', 'sp' ); ?></span></a></li>
                            <?php 
							// check if WPEC is active
							if ( class_exists( 'WP_eCommerce' ) ) 
							{ ?>
                            <li class="wpec"><a href="#wpec" title="WPEC Settings">WPEC<br /><span><?php _e( 'settings', 'sp' ); ?></span></a></li>
                            <?php } ?>
                            <?php 
							// check if WOO Commerce is active
							if ( class_exists( 'woocommerce' ) ) 
							{ ?>
                            <li class="woo"><a href="#woo" title="WooCommerce Settings">WOO<br /><span><?php _e( 'settings', 'sp' ); ?></span></a></li>
                            <?php } ?>
                            <li class="help"><a href="#help" title="Help"><?php _e( 'HELP?', 'sp' ); ?></a></li>
                        </ul><!--close tabs-->
                    </div><!--close nav-->
                    <div id="header-meta" class="group">
                    	<input name="save" type="submit" value="SAVE ALL" class="save" />
                        <small>SP Framework v<?php echo SP_FRAMEWORK_VERSION; ?></small>
                    </div><!--close header-meta-->
                </div><!--close header-->
                <div id="general" class="panel group">
                	<ul class="side-nav">
                    	<?php 
							foreach ( $sptheme_config['tab']['general']['panel'] as $panel ) 
							{
								echo '<li><a href="#general-' . sp_a_clean( $panel['_attr']['title'] ) . '" title="">' . $panel['_attr']['title'] . '</a></li>';	
							}
						?>
                    </ul><!--side-nav-->
                    
                    <?php
						foreach ( $sptheme_config['tab']['general']['panel'] as $panel ) 
						{
							echo '<div id="general-' . sp_a_clean( $panel['_attr']['title'] ) . '" class="option">';
							if ( is_array( $panel['wrapper'] ) ) 
							{
								foreach ( $panel['wrapper'] as $wrapper ) 
								{
									// check to see the context of the options if it is woo or wpec plugin
									$context = isset( $wrapper['_attr']['context'] ) ? $wrapper['_attr']['context'] : '';

									if ( $context == 'wpec' && ! class_exists( 'WP_eCommerce' ) ) 
									{
										// do nothing	
									}
									elseif ( $context == 'woo' && ! class_exists( 'woocommerce' ) )
									{
										// do nothing
									}
									elseif ( $context == 'wpec_woo' && ( ! class_exists( 'woocommerce' ) && ! class_exists( 'WP_eCommerce' ) ) )
									{
										// do nothing	
									}
									else 
									{
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '<fieldset>'."\r\n";
											echo '<legend>' . $wrapper['_attr']['title'] . '</legend>' . "\r\n";
										}
										if ( isset( $wrapper['module'] ) ) 
										{
											if ( is_array( $wrapper['module'] ) ) 
											{
												foreach ( $wrapper['module'] as $module ) 
												{
													if ( isset( $module['_attr'] ) ) 
													{
														if ( $module['_attr']['type'] != '' ) 
														{
															echo call_user_func( 'sp_' . $module['_attr']['type'], $module );
														}
													}
												} // end 3rd foreach loop
											}
										}
										
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '</fieldset>' . "\r\n";
										}									
									}
																										
								} // end 2nd foreach loop
							}
							echo '</div><!--close option-->';
						} // end 1st foreach loop
					?>
                </div><!--close general-->
                
                <div id="styling" class="panel group">
                	<ul class="side-nav">
                    	<?php 
							foreach ( $sptheme_config['tab']['styling']['panel'] as $panel ) 
							{
								echo '<li><a href="#styling-' . sp_a_clean( $panel['_attr']['title']).'" title="">' . $panel['_attr']['title'] . '</a></li>';	
							}
						?>
                    </ul><!--side-nav-->
                    
                    <?php
						foreach ( $sptheme_config['tab']['styling']['panel'] as $panel ) 
						{
							echo '<div id="styling-' . sp_a_clean( $panel['_attr']['title'] ) . '" class="option">';
							if ( is_array( $panel['wrapper'] ) ) 
							{
								foreach ( $panel['wrapper'] as $wrapper ) 
								{
									// check to see the context of the options if it is woo or wpec plugin
									$context = isset( $wrapper['_attr']['context'] ) ? $wrapper['_attr']['context'] : '';

									if ( $context == 'wpec' && ! class_exists( 'WP_eCommerce' ) ) 
									{
										// do nothing	
									}
									elseif ( $context == 'woo' && ! class_exists( 'woocommerce' ) )
									{
										// do nothing
									}
									elseif ( $context == 'wpec_woo' && ( ! class_exists( 'woocommerce' ) && ! class_exists( 'WP_eCommerce' ) ) )
									{
										// do nothing	
									}
									else 
									{
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '<fieldset>'."\r\n";
											echo '<legend>' . $wrapper['_attr']['title'] . '</legend>' . "\r\n";
										}
										if ( isset( $wrapper['module'] ) ) 
										{
											if ( is_array( $wrapper['module'] ) ) 
											{
												foreach ( $wrapper['module'] as $module ) 
												{
													if ( isset( $module['_attr'] ) ) 
													{
														if ( $module['_attr']['type'] != '' ) 
														{
															echo call_user_func( 'sp_' . $module['_attr']['type'], $module );
														}
													}
												} // end 3rd foreach loop
											}
										}
										
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '</fieldset>' . "\r\n";
										}									
									}
								} // end 2nd foreach loop
							}
							echo '</div><!--close option-->';
						} // end 1st foreach loop
					?>
                </div><!--close styling-->
                
                <?php 
				// check to see if WPEC is active
				if ( class_exists( 'WP_eCommerce' ) ) 
				{ ?>
                <div id="wpec" class="panel group">
                	<ul class="side-nav">
                    	<?php 
							foreach ( $sptheme_config['tab']['wpec']['panel'] as $panel ) 
							{
								echo '<li><a href="#wpec-' . sp_a_clean( $panel['_attr']['title'] ) . '" title="">' . $panel['_attr']['title'] . '</a></li>';	
							}
						?>
                    </ul><!--side-nav-->
                    
                    <?php
						foreach ( $sptheme_config['tab']['wpec']['panel'] as $panel ) 
						{
							echo '<div id="wpec-' . sp_a_clean( $panel['_attr']['title'] ) . '" class="option">';
							if ( is_array( $panel['wrapper'] ) ) 
							{
								foreach ( $panel['wrapper'] as $wrapper ) 
								{
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '<fieldset>' . "\r\n";
											echo '<legend>' . $wrapper['_attr']['title'] . '</legend>' . "\r\n";
										}
										
										if ( isset( $wrapper['module'] ) ) 
										{
											if ( is_array( $wrapper['module'] ) ) 
											{
												foreach ( $wrapper['module'] as $module ) 
												{
													if ( isset( $module['_attr']['type'] ) ) 
													{
														if ( $module['_attr']['type'] != '' ) 
														{
															echo call_user_func( 'sp_' . $module['_attr']['type'], $module );
														}
													}
												} // end 3rd foreach loop
											}
										}
										
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '</fieldset>' . "\r\n";
										}									
								} // end 2nd foreach loop
							}
							echo '</div><!--close option-->';
						} // end 1st foreach loop
					?>
                </div><!--close wpec-->
				<?php } // close WPEC check ?>
                <?php 
				// check to see if WOO is active
				if ( class_exists( 'woocommerce' ) ) 
				{ ?>
                <div id="woo" class="panel group">
                	<ul class="side-nav">
                    	<?php 
							foreach ( $sptheme_config['tab']['woo']['panel'] as $panel ) 
							{
								echo '<li><a href="#woo-' . sp_a_clean( $panel['_attr']['title'] ) . '" title="">' . $panel['_attr']['title'] . '</a></li>';	
							}
						?>
                    </ul><!--side-nav-->
                    
                    <?php
						foreach ( $sptheme_config['tab']['woo']['panel'] as $panel ) 
						{
							echo '<div id="woo-' . sp_a_clean( $panel['_attr']['title'] ) . '" class="option">';
							if ( is_array( $panel['wrapper'] ) ) 
							{
								foreach ( $panel['wrapper'] as $wrapper ) 
								{
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '<fieldset>' . "\r\n";
											echo '<legend>' . $wrapper['_attr']['title'] . '</legend>' . "\r\n";
										}
										
										if ( isset( $wrapper['module'] ) ) 
										{
											if ( is_array( $wrapper['module'] ) ) 
											{
												foreach ( $wrapper['module'] as $module ) 
												{
													if ( isset($module['_attr']['type'] ) ) 
													{
														if ( $module['_attr']['type'] != '' ) 
														{
															echo call_user_func( 'sp_' . $module['_attr']['type'], $module );
														}
													}
												} // end 3rd foreach loop
											}
										}
										
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '</fieldset>' . "\r\n";
										}									
								} // end 2nd foreach loop
							}
							echo '</div><!--close option-->';
						} // end 1st foreach loop
					?>
                </div><!--close woo-->
				<?php } // close WOO check ?>

                <div id="help" class="panel group">
                	<ul class="side-nav">
                    	<?php 
							foreach ( $sptheme_config['tab']['help']['panel'] as $panel ) 
							{
								if ( isset( $panel['_attr'] ) ) 
								{
									echo '<li><a href="#help-' . sp_a_clean( $panel['_attr']['title'] ) . '" title="">' . $panel['_attr']['title'] . '</a></li>';	
								}
							}
						?>
                    </ul><!--side-nav-->
                    
                    <?php
						foreach ( $sptheme_config['tab']['help']['panel'] as $panel ) 
						{
							if ( isset( $panel['_attr']['title'] ) ) 
							{
								echo '<div id="help-' . sp_a_clean( $panel['_attr']['title'] ) . '" class="option">';
							}
							if ( isset( $panel['wrapper'] ) ) 
							{
								if ( is_array( $panel['wrapper'] ) ) 
								{							
									foreach ( $panel['wrapper'] as $wrapper ) 
									{
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{
											echo '<fieldset>' . "\r\n";
											echo '<legend>' . $wrapper['_attr']['title'] . '</legend>' . "\r\n";
										}
										
										if ( isset( $wrapper['module'] ) ) 
										{
											if ( is_array( $wrapper['module'] ) ) 
											{
												foreach ( $wrapper['module'] as $module ) 
												{
													// check to see the context of the options if it is woo or wpec plugin
													$context = isset( $module['_attr']['context'] ) ? $module['_attr']['context'] : '';
				
													if ( $context == 'wpec' && ! class_exists( 'WP_eCommerce' ) ) 
													{
														// do nothing	
													}
													elseif ( $context == 'woo' && ! class_exists( 'woocommerce' ) )
													{
														// do nothing
													}
													else 
													{
														if ( isset( $module['_attr']['type'] ) ) 
														{
															if ( $module['_attr']['type'] != '' ) 
															{
																echo call_user_func( 'sp_' . $module['_attr']['type'], $module );
															}
														}
													}
												} // end 3rd foreach loop
											}
										}
										if ( isset( $wrapper['_attr']['title'] ) ) 
										{ 
											echo '<a href="'.esc_url( __('http://www.splashingpixels.com/my-account/', 'sp')).'" target="_blank">' . __( 'For more support, please go to our site support forums.', 'sp' ) . '</a>';
											
											echo '</fieldset>' . "\r\n"; 
										}	
									} // end 2nd foreach loop
								} 
							}
							if ( isset( $panel['_attr']['title'] ) ) 
							{
								echo '</div><!--close option-->';
							}
						} // end 1st foreach loop
					?>
                </div><!--close help-->
            </div><!--close content-->
            <div id="footer-meta" class="group">
            	<input name="save" type="submit" value="SAVE ALL" class="save" />
                <a href="http://www.splashingpixels.com" title="Splashing Pixels" target="_blank" class="logo-link">Splashing Pixels</a>
            </div><!--close footer-meta-->
        </form>
    </div><!--close wrap-->	
<?php
}
?>