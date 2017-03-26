<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* theme setup
******************************************************************/

if ( ! function_exists( 'sp_setup' ) ) 
{
	function sp_setup() 
	{	
		// add theme support menus
		if ( function_exists( 'add_theme_support' ) ) 
		{
			// add menus
			add_theme_support( 'menus' );
			
			// Add default posts and comments RSS feed links to head
			add_theme_support( 'automatic-feed-links' );
			
			// add post thumbnails to posts and pages
			add_theme_support( 'post-thumbnails', array( 'post', 'page', 'portfolio-entries', 'wpsc-product', 'product' ) );
			
			// add theme support for woocommerce
			add_theme_support( 'woocommerce' );
			
			// add post format since WP 3.1
			//add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );
		}
		
		// add editor styles
		add_editor_style();
		
		// Register custom dynamic menus
		register_nav_menus( sp_navMenus() );
		
		// removes the WP version from site
		function sp_remove_version() 
		{
			return '';
		}
		add_filter( 'the_generator', 'sp_remove_version' );
		
		add_image_size( 'single_image', sp_get_theme_init_setting( 'post_single_image_size', 'width' ), sp_get_theme_init_setting( 'post_single_image_size', 'height' ), sp_get_theme_init_setting( 'post_single_image_size', 'crop' ) );
		
		add_image_size( 'related_image', sp_get_theme_init_setting( 'post_related_image_size', 'width' ), sp_get_theme_init_setting( 'post_related_image_size', 'height' ), sp_get_theme_init_setting( 'post_related_image_size', 'crop' ) );
		
		add_image_size( 'portfolio_list_image', 50, 50, true );

		if ( ! isset( $content_width ) ) $content_width = 960;
		
	} // end setup function
}

add_action( 'after_setup_theme', 'sp_setup' );

if ( ! function_exists( 'sp_navMenus' ) )
{
	/**
	 * grab theme init settings to generate nav menu
	 *  
	 * @return array of all listing menus in the XML config file
	 */
	function sp_navMenus() 
	{
		global $sptheme_config;
		
		$menu = array();
		if ( is_array( $sptheme_config['init']['nav_menu'] ) ) 
		{
			foreach ( $sptheme_config['init']['nav_menu'] as $nav ) 
			{
					$menu[$nav['_attr']['name']] = sprintf( __( '%s', 'sp' ), $nav['_attr']['title'] );	
			}
		}
		
		return $menu;
	}
}
?>