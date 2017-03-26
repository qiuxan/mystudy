<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* custom post types
******************************************************************/

add_action( 'init', 'sp_custom_post_types' );
function sp_custom_post_types() {
	// media post type
	$args = array(
			'labels' => array( 'name' => 'SP Framework' ),
			'show_ui' => false,
			'query_var' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'supports' => array( 'editor', 'title' ), 
			'can_export' => true,
			'public' => true,
			'show_in_nav_menus' => false
			);
  	register_post_type( 'sp_post', $args );
  
	// sp_portfolio post type 
	$labels = array(
		'name' => _x( 'Portfolio Entries', 'post type general name', 'sp' ),
		'singular_name' => _x( 'Portfolio Entry', 'post type singular name', 'sp' ),
		'add_new' => _x( 'Add New', 'portfolio', 'sp' ),
		'add_new_item' => __( 'Add New Portfolio Entry', 'sp' ),
		'edit_item' => __( 'Edit Portfolio Entry', 'sp' ),
		'new_item' => __( 'New Portfolio Entry', 'sp' ),
		'view_item' => __( 'View Portfolio Entry', 'sp' ),
		'search_items' => __( 'Search Portfolio Entries', 'sp' ),
		'not_found' =>  __( 'No Portfolio Entries found', 'sp' ),
		'not_found_in_trash' => __( 'No Portfolio Entries found in Trash', 'sp' ), 
		'parent_item_colon' => ''
	);
		
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'query_var' => true,
		'show_in_nav_menus'=> false,
		'supports' => array( 'title', 'thumbnail', 'excerpt', 'editor', 'comments' ),
		'taxonomies' => array( 'portfolio_categories' )
	);
	
	register_post_type( 'portfolio-entries', $args );
	
	// registers the portfolio categories
	register_taxonomy( "portfolio_categories", 
		array( "portfolio-entries" ), 
		array(	"hierarchical" => true, 
		"label" => "Portfolio Categories", 
		"singular_label" => "Portfolio Category", 
		"query_var" => true
	) );  
	
	// registers tags for portfolio entries
	register_taxonomy( "portfolio_tags", 
		array( "portfolio-entries" ), 
		array( "hierarchical" => false, 
		"label" => "Portfolio Tags", 
		"singular_label" => "Portfolio Tag", 
		"query_var" => true
	) );  
}

#portfolio_columns, <-  register_post_type then append _columns
add_filter("manage_edit-portfolio-entries_columns", "sp_modify_columns");
add_action("manage_posts_custom_column",  "sp_custom_columns");

function sp_modify_columns( $columns )
{
	$newcolumns = array(
		'cb' => '<input type="checkbox" />',
		'thumb column-comments' => 'Image',
		'title' => 'Title',
		'portfolio_categories' => 'Categories'
	);
	
	$columns= array_merge( $newcolumns, $columns );
	
	return $columns;
}

function sp_custom_columns( $column )
{
	global $post;
	
	switch ( $column )
	{
		case "thumb column-comments" :
		if ( has_post_thumbnail( $post->ID ) )
		{
			echo get_the_post_thumbnail( $post->ID, 'portfolio_list_image' );
		}
		break;
			
		case "portfolio_categories" :
			echo get_the_term_list($post->ID, 'portfolio_categories', '', ', ','' );
		break;
	}
}

?>