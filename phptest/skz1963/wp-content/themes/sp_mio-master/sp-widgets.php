<?php 
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* widgets
******************************************************************/

// registers the available widget areas.
function sp_widgets_init() {
	global $wpdb;
	
	// check to see if sitewide sidebar widget is turned on
	if ( sp_isset_option( 'sitewide_widget_enable', 'boolean', 'true' ) )
	{
		$sidebars = array( 'Left', 'Right' );
		foreach ( $sidebars as $sidebar ) 
		{
			register_sidebar( array(
				'name' =>  sprintf( __( 'Sidebar (' . '%s' . ') Sitewide', 'sp' ), $sidebar ),
				'id' => 'sidebar-' . strtolower( $sidebar ) . '-sitewide',
				'description' => sprintf( __( 'Sidebar (' . '%s' . ') Sitewide', 'sp' ), $sidebar ),
				'before_widget' => '<div id="%1$s" class="widget-container %2$s group">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			) );
		}
	}
	// custom page widgets
	if ( sp_isset_option( 'custom_page_widget', 'value' ) && is_array( sp_isset_option( 'custom_page_widget', 'value' ) ) ) 
	{
		$page_ids = sp_isset_option( 'custom_page_widget', 'value' );
		$sidebars = array( 'Left', 'Right' );
		foreach ( $page_ids as $page ) 
		{
			foreach( $sidebars as $sidebar ) 
			{
				if ( $page != 0 ) 
				{
					register_sidebar( array(
						'name' => sprintf( __( 'Sidebar (' . '%s' . ') Page: ' . get_the_title( $page ), 'sp' ), $sidebar ),
						'id' => 'sidebar-' . strtolower( $sidebar ) . '-page-' . $page,
						'description' => sprintf( __( 'Sidebar (' . '%s' . ') Page: ' . get_the_title( $page ), 'sp' ), $sidebar ),
						'before_widget' => '<div id="%1$s" class="widget-container %2$s group">',
						'after_widget' => '</div>',
						'before_title' => '<h3 class="widget-title">',
						'after_title' => '</h3>',
					) );
				}
			}
		}
	}
	
	// custom blog category widgets
	if ( sp_isset_option( 'custom_blog_category_widget', 'isset' ) && is_array( sp_isset_option( 'custom_blog_category_widget', 'value' ) ) ) 
	{
		$cat_ids = sp_isset_option( 'custom_blog_category_widget', 'value' );
		$sidebars = array( 'Left', 'Right' );
		foreach ( $cat_ids as $cat ) 
		{ 
			foreach( $sidebars as $sidebar ) 
			{ 
				if ( $cat != 0 ) 
				{
					$cat_obj = get_term( $cat, 'category' );
					register_sidebar( array(
						'name' => sprintf( __( 'Sidebar (' . '%1$s' . ') Blog Category: %2$s', 'sp' ), $sidebar, $cat_obj->name ),
						'id' => 'sidebar-' . strtolower( $sidebar ) . '-blog-category-' . $cat,
						'description' => sprintf( __( 'Sidebar (' . '%1$s' . ') Blog Category: %2$s' , 'sp' ), $sidebar, $cat_obj->name ),
						'before_widget' => '<div id="%1$s" class="widget-container %2$s group">',
						'after_widget' => '</div>',
						'before_title' => '<h3 class="widget-title">',
						'after_title' => '</h3>',
					) );
				}
			}
		}
	}
	
	// custom product category widgets
	if ( sp_isset_option( 'custom_product_category_widget', 'isset' ) && is_array( sp_isset_option( 'custom_product_category_widget', 'value' ) ) ) 
	{
		$cat_ids = sp_isset_option( 'custom_product_category_widget', 'value' );
		$sidebars = array( 'Left', 'Right' );
		foreach ( $cat_ids as $cat ) 
		{
			foreach( $sidebars as $sidebar ) 
			{
				if ( $cat != 0 ) 
				{
					$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$cat}'";
					$categories[$cat] = $wpdb->get_var( $sql );
					foreach ( $categories as $k => $v ) 
					{
						register_sidebar( array(
							'name' => sprintf( __( 'Sidebar (' . '%1$s' . ') Product Cat: %2$s', 'sp' ), $sidebar, $v ),
							'id' => 'sidebar-' . strtolower( $sidebar ) . '-product-category-' . $k,
							'description' => sprintf( __( 'Sidebar (' . '%1$s' . ') Product Cat: %2$s', 'sp' ), $sidebar, $v ),
							'before_widget' => '<div id="%1$s" class="widget-container %2$s group">',
							'after_widget' => '</div>',
							'before_title' => '<h3 class="widget-title">',
							'after_title' => '</h3>',
						) );
					}
				}
			}
		}
	}
	
	// Footer widget area
	$footer_widgets = sp_isset_option( 'footer_widget', 'value' );
	if ( $footer_widgets != '' ) 
	{
		for ( $i = 1; $i <= $footer_widgets; $i++ ) 
		{
			register_sidebar( array(
				'name' => sprintf( __( 'Footer Widget %d', 'sp' ), $i ),
				'id' => 'footer-widget-' . $i,
				'description' => sprintf( __( 'Footer Widget Area %d', 'sp' ), $i ),
				'before_widget' => '<div id="%1$s" class="widget-container %2$s group">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			) );
		}
	}

	// site top widget area
	register_sidebar( array(
		'name' => __( 'Site Top Widget (Promotions)', 'sp' ),
		'id' => 'site-top-widget',
		'description' => __( 'Site Top Widget Area for promotional items to be used in conjunction with SP Promotional Widget', 'sp' ),
		'before_widget' => '<div id="%1$s" class="site-top-widget-wrapper %2$s group"><div class="container">',
		'after_widget' => '</div></div>',
	) );

	// site bottom widget area
	register_sidebar( array(
		'name' => __( 'Site Bottom Widget (Promotions)', 'sp' ),
		'id' => 'site-bottom-widget',
		'description' => __( 'Site Bottom Widget Area for promotional items to be used in conjunction with SP Promotional Widget', 'sp' ),
		'before_widget' => '<div id="%1$s" class="site-bottom-widget-wrapper %2$s group"><div class="container">',
		'after_widget' => '</div></div>',
	) );

	// page top widget area
	register_sidebar( array(
		'name' => __( 'Page Top Widget (Promotions)', 'sp' ),
		'id' => 'page-top-widget',
		'description' => __( 'Page Top Widget Area for promotional items to be used in conjunction with SP Promotional Widget', 'sp' ),
		'before_widget' => '<div id="%1$s" class="page-top-widget-wrapper %2$s group"><div class="container">',
		'after_widget' => '</div></div>',
	) );

	// page bottom widget area
	register_sidebar( array(
		'name' => __( 'Page Bottom Widget (Promotions)', 'sp' ),
		'id' => 'page-bottom-widget',
		'description' => __( 'Page Bottom Widget Area for promotional items to be used in conjunction with SP Promotional Widget', 'sp' ),
		'before_widget' => '<div id="%1$s" class="page-bottom-widget-wrapper %2$s group"><div class="container">',
		'after_widget' => '</div></div>',
	) );

}
add_action( 'widgets_init', 'sp_widgets_init' );

?>