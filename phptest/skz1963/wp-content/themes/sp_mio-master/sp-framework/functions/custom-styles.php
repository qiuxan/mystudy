<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* control panel and dynamic styles
******************************************************************/

if ( ! function_exists( 'sp_cp_dynamic_styles' ) ) 
{
	function sp_cp_dynamic_styles() 
	{
		global $spdata, $sptheme_config;
		
		$output = '';
		$font = '';
		$font_array = array();

		$output .= "\r\n<!--CONTROL PANEL AND DYNAMIC STYLES-->\r\n" . '<style type="text/css">' . "\r\n";
		
		// include theme widgets
		if ( file_exists( get_template_directory() . '/sp-dynamic-styles.php' ) ) 
		{
			require_once( get_template_directory() . '/sp-dynamic-styles.php' );
		}
		
		if ( is_array( $sptheme_config['tab'] ) ) 
		{
			foreach ( $sptheme_config['tab'] as $tabs ) 
			{
				foreach ( $tabs['panel'] as $panels ) 
				{
					if ( isset( $panels['wrapper'] ) ) 
					{
						if ( is_array( $panels['wrapper'] ) ) 
						{
							foreach ( $panels['wrapper'] as $wrappers ) 
							{
								if ( isset( $wrappers['module'] ) ) 
								{
									if ( is_array( $wrappers['module'] ) ) 
									{
										foreach ( $wrappers['module'] as $module ) 
										{
											if ( isset( $module['_attr']['property'] ) ) 
											{
												switch ( $module['_attr']['property'] ) 
												{
													case 'font-size' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';
																
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . str_replace('px', '', $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) . 'px' . $important . ';}' . "\r\n";
														}
													break;
						
													case 'color' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{		
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';	
																																		
															$output .= $module['_attr']['handle'] . ' {color:' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . $important . ';}' . "\r\n";
														}
													break;
													
													case 'text-hover' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{		
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';	
																																		
															$output .= $module['_attr']['handle'] . ':hover {color:' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . $important . ';}' . "\r\n";
														}
													break;
													
													case 'font-weight' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' ) 
														{		
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';
																			
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . $important . ';}' . "\r\n";
														}
													break;
													
													case 'font-style' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' ) 
														{		
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';
																			
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . $important . ';}' . "\r\n";
														}
													break;

													case 'text-decoration' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' ) 
														{				
															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';
																	
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . $important . ';}' . "\r\n";
														}
													break;
													
													case 'font-family' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{	
															$option = sp_google_fonts_array();

															$important = '';
															if ( isset ( $module['_attr']['specificity'] ) && $module['_attr']['specificity'] != '' )
																$important = ' !important';
																					
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':"' . $option[$spdata[THEME_SHORTNAME . $module['_attr']['id']]] . '",san-serif' . $important . ';}' . "\r\n";
															$font_array[] = $option[$spdata[THEME_SHORTNAME . $module['_attr']['id']]];	
														}
													break;
													
													case 'background-image' :
														if ( isset($spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' && isset( $spdata[THEME_SHORTNAME . 'custom_background'] ) ) 
														{							
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':url(' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ');}' . "\r\n";
														}
													break;
	
													case 'background-position' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' && isset( $spdata[THEME_SHORTNAME . 'custom_background'] ) ) 
														{							
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';}' . "\r\n";
														}
													break;
	
													case 'background-repeat' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' && isset( $spdata[THEME_SHORTNAME . 'custom_background'] ) ) 
														{							
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';}' . "\r\n";
														}
													break;
	
													case 'background-attachment' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '0' && isset( $spdata[THEME_SHORTNAME . 'custom_background'] ) ) 
														{							
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';}' . "\r\n";
														}
													break;
	
													case 'background-color' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{			
															if ( empty($spdata[THEME_SHORTNAME . 'custom_background']) ) {				
																$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';}' . "\r\n";

																// remove background image
																$output .= $module['_attr']['handle'] . ' {background-image:none;}' . "\r\n";
															} else {
																$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';}' . "\r\n";
															}
														}
													break;
													
													case 'opacity' :
														if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
														{							
															$output .= $module['_attr']['handle'] . ' {' . $module['_attr']['property'] . ':' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . ';filter: alpha(opacity = ' . ( $spdata[THEME_SHORTNAME . $module['_attr']['id']] * 100 ) . ');}' . "\r\n";
														}
													break;
													
													default:
													break;	
												}
											}
										}
									}
								}
							}
						}
					}
				}			
			}
			
		}
		// isolated to process all google fonts on one http request
		if ( isset( $font_array ) && ! empty( $font_array ) )
		{
			$font_string = '';
			
			foreach ( $font_array as $font )
			{
				$font_string .= $font . "|";	
			}
			$font_string = rtrim( $font_string, "|" );
			$font = '<link rel="stylesheet" type="text/css" href="' . sp_ssl_http() . 'fonts.googleapis.com/css?family=' . $font_string . '">' . "\r\n";

		}
		$output .= '</style>' . "\r\n" . $font . "<!--END CONTROL PANEL AND DYNAMIC STYLES-->\r\n";
		echo $output;
	}
}
if ( ! is_admin() ) 
{
	add_action( 'wp_head', 'sp_cp_dynamic_styles' );	
}
?>