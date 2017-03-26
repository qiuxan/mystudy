<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* theme filters
******************************************************************/

add_filter('the_content', 'sp_wpautop_control_filter', 9);

function sp_wpautop_control_filter( $content ) 
{
	global $post, $spdata;
	
	// Get the keys and values of the custom fields:
	$post_wpautop_value = get_post_meta( $post->ID, '_sp_wpautop', true );
	
	if ( in_array( $post_wpautop_value, array( 'true', '1' ) ) )
	  $remove_filter = true;
	elseif ( in_array( $post_wpautop_value, array( 'false', '0' ) ) )
	  $remove_filter = false;
	else
	  $remove_filter = false;
	
	if ( $remove_filter ) 
	{
	  remove_filter( 'the_content', '_wpautop' );
	  remove_filter( 'the_excerpt', '_wpautop' );
	}
	
	return $content;
}

// removes gallery inline styles when using [gallery] shortcode.  Styles are in the CSS file where they should be.
function sp_remove_gallery_css( $css ) 
{
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
//add_filter( 'gallery_style', 'sp_remove_gallery_css' );

// set RSS url
function sp_custom_feed_link( $output, $feed ) 
{
	global $spdata;
	
	$feed_url = $spdata[THEME_SHORTNAME . 'rss_url'];
	$feed_array = array(
	'rss' => $feed_url,
	'rss2' => $feed_url,
	'atom' => $feed_url,
	'rdf' => $feed_url,
	'comments_rss2' => ''
	);
	$feed_array[$feed] = $feed_url;
	$output = $feed_array[$feed];
	
	return $output;
}

function sp_other_feed_links( $link ) 
{
	global $spdata;
	
	$feed_url = $spdata[THEME_SHORTNAME . 'rss_url'];
	
	return $link;
}

function sp_custom_feed_links() 
{
	global $spdata;
	
	if ( isset( $spdata[THEME_SHORTNAME . 'rss_url'] ) && $spdata[THEME_SHORTNAME . 'rss_url'] != '' ) 
	{
		add_filter( 'feed_link','sp_custom_feed_link', 1, 2 );
		add_filter( 'category_feed_link', 'sp_other_feed_links' );
		add_filter( 'author_feed_link', 'sp_other_feed_links' );
		add_filter( 'tag_feed_link','sp_other_feed_links' );
		add_filter( 'search_feed_link','sp_other_feed_links' );
		
	}
}
add_action( 'init', 'sp_custom_feed_links' );

// below 3 functions filters search query to include TAGS
function sp_search_where( $where )
{
  global $wpdb;

  if ( is_search() )
    $where .= "OR (t.name LIKE '%" . get_search_query() . "%' AND {$wpdb->posts}.post_status = 'publish')";
  
  return $where;
}

function sp_search_join( $join )
{
  global $wpdb;
  
  if ( is_search() )
    $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
  
  return $join;
}

function sp_search_groupby( $groupby )
{
  global $wpdb;

  // we need to group on post ID
  $groupby_id = "{$wpdb->posts}.ID";
  if ( ! is_search() || strpos( $groupby, $groupby_id ) !== false) 
  	return $groupby;

  // groupby was empty, use ours
  if ( ! strlen( trim( $groupby ) ) ) 
  	return $groupby_id;

  // not empty, append ours
  return $groupby . ", " . $groupby_id;
}

if ( ! is_admin() )
{
	//add_filter( 'posts_where', 'sp_search_where' );
	//add_filter( 'posts_join', 'sp_search_join' );
	//add_filter( 'posts_groupby', 'sp_search_groupby' );
}

// changes default login logo link to direct to site instead of wordpress.org
function sp_login_url()
{
	return home_url(); 
}

add_filter( 'login_headerurl', 'sp_login_url' );

// the following adds a class to the parent menu element that contains a child
add_filter( 'nav_menu_css_class', 'sp_check_for_submenu', 10, 2 );
function sp_check_for_submenu( $classes, $item ) 
{
    global $wpdb;
    
	$has_children = $wpdb->get_var( "SELECT COUNT(meta_id) FROM {$wpdb->postmeta} WHERE meta_key='_menu_item_menu_item_parent' AND meta_value='" . $item->ID . "'" );
    if ( $has_children > 0 ) array_push( $classes, 'has_children' ); // adds the class has_children
    
	return $classes;
}

// adds a wrapper around inserted videos so fitVids can hook in
function sp_add_video_wrapper( $html, $data, $url ) 
{
	$output = '<div class="video">' . $html . '</div>';
	return $output;	
}
add_filter( 'oembed_dataparse', 'sp_add_video_wrapper', 90, 3 );

add_filter( 'widget_title', 'sp_preserve_widget_title', 11 );
// this is a hack to preserve the title layout even if no title is set
// so it will not break the design with stray tags
function sp_preserve_widget_title( $title ) 
{
	return $title . ' ';
}

?>