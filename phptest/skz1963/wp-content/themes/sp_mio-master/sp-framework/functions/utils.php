<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* utility functions
******************************************************************/

/**
 * clean data array
 *
 * @param $string takes in the string to be cleaned
 * @return string returns cleaned string
 */
function sp_a_clean( $string = '' )
{		
	// converts everything to lowercase and replace spaces with hyphens
	$string = strtolower( str_replace( ' ', '-', $string ) );
  
	return $string;
}

/**
 * checks the current version of theme/framework against XML 
 */
function sp_check_version() 
{
	global $notification, $spthemeinfo;
	
	$file = wp_remote_get( 'http://splashingpixels.com/wp-content/themes/sp/versions/versions.xml' );
	$versions = wp_remote_retrieve_body( $file );
	
	if ( isset( $versions ) && $versions != '' ) 
	{
		// converts the versions xml to an array
		$versions = sp_xml2array( $versions );	
	
		// get the theme info of the parent
		$theme_name = substr( get_template_directory(), untrailingslashit(strrpos( get_template_directory(), '/' ) +1) );

		if ( function_exists( 'wp_get_theme' ) ) 
		{
			$theme = wp_get_theme( $theme_name ); // function since WP 3.4
		}
		else 
		{
			$theme = wp_get_theme( get_template_directory() . '/style.css' ); // deprecated function since 3.4
		}

		if ( is_array( $versions['versions'] ) ) 
		{
			foreach( $versions['versions']['item'] as $item ) 
			{
				$cur = trim( $item['value'] );

				if ( $item['_attr']['name'] == strtolower( trim( $theme['Name'] ) ) ) 
				{
					// proceed only if theme version is less than current XML version
					if ( $theme['Version'] < $cur ) 
					{
						// build the notification HTML to display above SP theme control panel
						$notification .= '<p class="update">' . sprintf( __( 'There is a new version of your theme available (' . '%s' . '). Please visit', 'sp' ), $item['value'] ) . ' <a href="' . esc_url( 'http://splashingpixels.com/my-account/' ) . '" title="Splashing Pixels" target="_blank">Splashing Pixels</a> to download. If your theme is not listed, please <a href="' . esc_url( 'http://splashingpixels.com/my-account/' ) . '">contact support</a>.  <a href="' . esc_url( 'http://splashingpixels.com/wp-content/themes/sp/versions/' . strtolower( trim( $spthemeinfo['Name'] ) ) . '_changelog.txt' ) . '" title="Theme Changelog" target="_blank">(ChangeLog)</a>  (' . __( 'To disable this message permanently', 'sp' ) . ' <a href="#" class="hide_msg" title="Hide Message">' . __( 'click here', 'sp' ) . '</a>)</p>';
					}
				}
				if ( $item['_attr']['name'] == 'framework' ) 
				{
					// proceed only if framework version is less than current XML version
					if (SP_FRAMEWORK_VERSION < $cur) 
					{
						// build the notification HTML to display above SP theme control panel
						$notification .= '<p class="update">' . sprintf( __( 'There is a new version of SP Framework available (' . '%s' . '). Please visit', 'sp' ), $item['value'] ) . ' <a href="' . esc_url( 'http://splashingpixels.com/my-account/' ) . '" title="Splashing Pixels" target="_blank">Splashing Pixels</a> ' . __( 'to download.  If your theme is not listed, you probably purchased it elsewhere.  In that case, please download it from where you purchased it.', 'sp' ) . ' <a href="' . esc_url( 'http://splashingpixels.com/wp-content/themes/sp/versions/framework_changelog.txt' ) . '" title="Theme Changelog" target="_blank">' . __( '(ChangeLog)', 'sp' ) . '</a> ' . __( '(To disable this message permanently', 'sp' ) . ' <a href="#" class="hide_msg" title="Hide Message">' . __( 'click here', 'sp' ) . '</a>)</p>';
					}
				}
		
			}
		}
	}
}

/**
 * checks for SSL and returns proper protocol
 *
 * @return string returns proper protocol
 */
function sp_ssl_http()
{	
	if ( is_ssl() ) 
	{
		return 'https://';
	}
	else 
	{
		return 'http://';
	}
}

/**
 * checks browser user agent and inject CSS classes to HTML tag
 *
 */
function sp_get_browser_agent() 
{
	// if no user agent, quit here
	if( empty( $_SERVER['HTTP_USER_AGENT'] ) ) 
		return false;
	
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = '';
	$ub = 'Unknown';
	
	// get the platform
	if ( preg_match( '!linux!i' , $u_agent ) ) 
	{
		$platform = 'linux';
	} 
	elseif ( preg_match( '!macintosh|mac os x!i', $u_agent ) ) 
	{
		if ( strpos( $u_agent, 'iPad' ) ) 
		{
			$platform = 'iPad';
		} 
		else 
		{
			$platform = 'mac';
		}
	} 
	elseif ( preg_match( '!windows|win32!i', $u_agent ) ) 
	{
		$platform = 'windows';
	}
	
	// get the useragent of the browser
	if( preg_match( '!MSIE!i', $u_agent ) && !preg_match( '!Opera!i', $u_agent ) ) 
	{
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	} 
	elseif( preg_match( '!Firefox!i', $u_agent ) ) 
	{
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	} 
	elseif( preg_match( '!Chrome!i', $u_agent ) ) 
	{
		$bname = 'Google Chrome';
		$ub = "Chrome";
	} 
	elseif( preg_match( '!Safari!i', $u_agent ) ) 
	{
		$bname = 'Apple Safari';
		$ub = "Safari";
	} 
	elseif( preg_match( '!Opera!i', $u_agent ) ) 
	{
		$bname = 'Opera';
		$ub = "Opera";
	} 
	elseif( preg_match( '!Netscape!i', $u_agent ) ) 
	{
		$bname = 'Netscape';
		$ub = "Netscape";
	}
	
	// get the version number
	$known = array( 'Version', $ub, 'other' );
	$pattern = '#(?<browser>' . join( '|', $known ) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	
	if ( ! @preg_match_all( $pattern, $u_agent, $matches ) ) 
	{
		// continue do nothing
	}
	
	// count the number of browser matches
	$i = count( $matches['browser'] );
	if ( $i != 1 ) 
	{
		if ( strripos( $u_agent, "Version" ) < strripos( $u_agent, $ub ) )
		{
			$version= $matches['version'][0];
		} 
		else 
		{
			$version= $matches['version'][1];
		}
	} 
	else 
	{
		$version= $matches['version'][0];
	}
	
	// check if version number exists and not null
	if ( $version == null || $version == "" ) 
	{
		$version = "?";
	}
	
	$mainVersion = $version;
	if ( strpos( $version, '.' ) !== false ) 
	{
		$mainVersion = explode( '.', $version );
		$mainVersion = $mainVersion[0];
	}
	
	return strtolower( $ub . " " . $ub . $mainVersion . " " . $platform );
}

/**
 * add CSS class to widgets
 *
 */
function sp_widget_form_extend( $instance, $widget ) 
{
	if ( ! isset( $instance['classes'] ) )
		$instance['classes'] = null;

	$output = "<p>\r\n";
	$output .= "<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Additional Classes <small>(separate with spaces)</small></label>\r\n";
	$output .= "<input type='text' name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat' value='{$instance['classes']}'/>\r\n";
	$output .= "</p>\r\n";

	echo $output;
	return $instance;
}
add_filter('widget_form_callback', 'sp_widget_form_extend', 10, 2);

function sp_widget_update( $instance, $new_instance ) 
{
	$instance['classes'] = $new_instance['classes'];
	return $instance;
}
add_filter( 'widget_update_callback', 'sp_widget_update', 10, 2 );

function sp_dynamic_sidebar_params( $params ) 
{
	global $wp_registered_widgets;
	
	$widget_id	= $params[0]['widget_id'];
	$widget_obj	= $wp_registered_widgets[$widget_id];
	$widget_opt	= get_option( $widget_obj['callback'][0]->option_name );
	$widget_num	= $widget_obj['params'][0]['number'];

	if ( isset( $widget_opt[$widget_num]['classes'] ) && ! empty( $widget_opt[$widget_num]['classes'] ) )
		$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$widget_opt[$widget_num]['classes']} ", $params[0]['before_widget'], 1 );

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'sp_dynamic_sidebar_params' );

// function to get url and strip down to page name
function sp_extract_url( $url ) 
{
	$last_slash_pos = strrpos( $url, "/" );
	$url = trim( $url, "/" );
	$url = substr( $url, strrpos( $url, "/" ) + 1, $last_slash_pos );
	
	return $url;
}

// function truncate text while retaining HTML tags
/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
function sp_truncate( $text, $length = 100, $ending = '...', $exact = true, $considerHtml = false ) 
{
	if ( $considerHtml ) 
	{
		// if the plain text is shorter than the maximum length, return the whole text
		if ( strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length ) 
		{
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
		$total_length = strlen( $ending );
		$open_tags = array();
		$truncate = '';
		foreach ( $lines as $line_matchings ) 
		{
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if ( ! empty( $line_matchings[1] ) ) 
			{
				// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
				if ( preg_match( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1] ) ) 
				{
					// do nothing
				// if tag is a closing tag (f.e. </b>)
				} 
				else if ( preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings ) )  
				{
					// delete tag from $open_tags list
					$pos = array_search( $tag_matchings[1], $open_tags );
					if ( $pos !== false ) 
					{
						unset( $open_tags[$pos] );
					}
				// if tag is an opening tag (f.e. <b>)
				} 
				else if ( preg_match( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings ) ) 
				{
					// add tag to the beginning of $open_tags list
					array_unshift( $open_tags, strtolower( $tag_matchings[1] ) );
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2] ) );
			if ( $total_length+$content_length > $length ) 
			{
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if ( preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE ) ) 
				{
					// calculate the real length of all entities in the legal range
					foreach ( $entities[0] as $entity ) 
					{
						if ( $entity[1] + 1 - $entities_length <= $left ) 
						{
							$left--;
							$entities_length += strlen( $entity[0] );
						} 
						else 
						{
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr( $line_matchings[2], 0, $left + $entities_length );
				// maximum lenght is reached, so get off the loop
				break;
			} 
			else 
			{
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if ( $total_length >= $length ) 
			{
				break;
			}
		}
	} 
	else 
	{
		if ( strlen( $text ) <= $length ) 
		{
			return $text;
		} 
		else 
		{
			$truncate = substr( $text, 0, $length - strlen( $ending ) );
		}
	}
	// if the words shouldn't be cut in the middle...
	if ( ! $exact ) 
	{
		// ...search the last occurance of a space...
		$spacepos = strrpos( $truncate, ' ' );
		if ( isset( $spacepos ) ) 
		{
			// ...and cut the text in this position
			$truncate = substr( $truncate, 0, $spacepos );
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if ( $considerHtml ) 
	{
		// close all unclosed html-tags
		foreach ( $open_tags as $tag ) 
		{
			$truncate .= '</' . $tag . '>';
		}
	}
	return stripslashes( $truncate );
}

/*
 * helper function to get the page id
 *
 * @return int
 */
function sp_get_page_id( $page_slug ) 
{
    $page = get_page_by_path( $page_slug );
    if ( $page ) 
	{
        return $page->ID;
    } 
	else 
	{
        return null;
    }
}

/*
 * helper function to check if a theme option is set and return the value
 *
 * @param $option string
 * @param $type string - type of test
 * @param $value - value to test against
 *
 * @return mixed boolean and value
 */
function sp_isset_option( $option = '', $type = 'boolean', $value = '' )
{
	global $spdata;
	
	switch ( $type ) :
		case 'value' :
			// returns the value if set
			if ( isset( $spdata[THEME_SHORTNAME . $option] ) && ( $spdata[THEME_SHORTNAME . $option] != '' ) )
			{
				return $spdata[THEME_SHORTNAME . $option];
			}
			else
			{
				return '';	
			}
		break;
		
		case 'boolean' :
			// returns boolean
			if ( isset( $spdata[THEME_SHORTNAME . $option] ) && ( $spdata[THEME_SHORTNAME . $option] == $value ) ) 
			{
				return true;
			}
			else
			{
				return false;	
			}
		break;

		case 'isset' :
			// returns boolean
			if ( isset( $spdata[THEME_SHORTNAME . $option] ) && ( $spdata[THEME_SHORTNAME . $option] != '' ) ) 
			{
				return true;
			}
			else
			{
				return false;	
			}
		break;

		default :
		
	endswitch;
}

/* 
 * function to check what template a page is using
 *
 * @return string
 */
function sp_check_page_template( $pageid = 'sp-home' )
{	
	$id = sp_get_page_id( $pageid );
	$template_name = str_replace('.php', '', get_post_meta( $id, '_wp_page_template', true ) ); //removes .php from the string
	
	return $template_name;	
}

/* 
 * function to check a multisite image url
 *
 * @since 2.1 
 * @return string
 */
function sp_check_ms_image($image) 
{
	global $blog_id, $wp_version; 
	
	// if multisite is not set exit out
	if ( ! is_multisite() || $blog_id == 1 ) 
		return $image;

	// if WP 3.5 or greater -- 3.5 puts network files in uploads/sites/ folder
	if ( $wp_version >= 3.5 )
		return $image;
		
	// check if it is the parent network
		
	$matches = array();
	preg_match( '/(?<=files).*/', $image, $matches ); //gets the end part of string after files
	$image_part = $matches[0];
	
	$output = network_site_url() . 'wp-content/blogs.dir/' . $blog_id . '/files' . $image_part;
	
	return $output;
	
}

/* 
 * function to get post images
 *
 * @since 2.1.1 
 * @return string
 */
function sp_get_image( $id = '' ) 
{
	if ( empty( $id ) )
		$id = get_the_ID();
	
	$post_thumbnail_id = get_post_thumbnail_id( $id );
	
	if ( $post_thumbnail_id ) 
	{
		$attached_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		
		$image_src = $attached_image[0];
		
		// run image url string check for multisite
		$image_src = sp_check_ms_image( $image_src );
		
		return $image_src;
	} 
	else
	{
		return get_template_directory_uri() . '/images/no-product-image.jpg';	
	}
}

/*
 * function to get the current page's id
 *
 * $since 2.1.1
 * @return int id
 */
function sp_cur_page_id()
{
	global $wp_query;
	
	if ( isset( $wp_query ) )
		$post_id = $wp_query->queried_object_id;
	

	return $post_id;
}

/*
 * function to get the current page's slug
 *
 * $since 2.1.1
 * @return string slug name
 */
function sp_cur_page_slug()
{	
	$slug = false;
	
	$post_id = sp_cur_page_id();
	
	$post = get_post( $post_id );
	
	if ( isset( $post ) )
		$slug = $post->post_name;
	
	return $slug;
}

/*
 * function to check if current page is a store page
 *
 * @since 2.1.1
 * @return boolean
 */
function sp_is_store_page()
{
	$is_store_page = false;
	
	if ( class_exists( 'WP_eCommerce' ) )
	{
		$product_list_url = get_option( 'product_list_url' );
		if ( isset( $product_list_url ) && $product_list_url != '' ) 
		{
			if ( substr_count( $_SERVER['REQUEST_URI'], sp_extract_url( $product_list_url ) ) )
			{
				$is_store_page = true;
			} 
			else 
			{
				$is_store_page = false;	
			}
		} 
		else 
		{
				$is_store_page = false;	
		}
	}
	
	if ( class_exists( 'woocommerce' ) )
	{
		if ( is_woocommerce() )
		{ 
			$is_store_page = true;	
		}
		else
		{ 
			$is_store_page = false;	
		}
	}
	
	return $is_store_page;
}

/*
 * function to check if current page is single product page (detail)
 *
 * @since 2.1.1
 * @return boolean
 */
function sp_is_single_product_page()
{ 
	$is_single_product_page = false;
	
	if ( class_exists( 'WP_eCommerce' ) )
	{
		if ( wpsc_is_single_product() ) 
			$is_single_product_page = true;
	}
	
	if ( class_exists( 'woocommerce' ) )
	{
		if ( is_product() )
			$is_single_product_page = true;	
	}
	
	return $is_single_product_page;
}

/*
 * function to check if current page is a checkout or transaction results page
 *
 * @since 2.1.1
 * @return boolean
 */
function sp_is_checkout_pages()
{ 
	$is_checkout_pages = false;
	
	if ( class_exists( 'WP_eCommerce' ) )
	{
		if ( is_page(sp_extract_url(get_option('shopping_cart_url', home_url()))) || is_page(sp_extract_url(get_option('transact_url', home_url()))) ) 
			$is_checkout_pages = true;
	}
	
	if ( class_exists( 'woocommerce' ) ) 
	{ 
		if ( is_cart() || is_checkout() || sp_cur_page_slug() == 'order-received' )
			$is_checkout_pages = true;	
	}
	
	return $is_checkout_pages;
}

/*
 * function to check if current page is using default layout
 *
 * @since 2.1.1
 * @return boolean
 */
function sp_is_default_layout()
{
	global $post;
	
	$is_default = true;
	
	// get the page layout settings from post meta
	$layout = get_post_meta( $post->ID, '_sp_page_layout', true );
	
	if ( ! empty( $layout ) && $layout != 'default' ) 
		$is_default = false;
	
	return $is_default;
}


/*
 * function to check the default layout override on the current page
 *
 * @since 2.1.1
 * @return mix booleans
 */
function sp_page_layout()
{	
	global $post;

	$context = 'page';
	$sidebar_left = false;
	$sidebar_right = false;
	$layout = 'default';
		
	// check for blog page
	if ( isset( $post ) && get_post_type( $post ) == 'post' )  {
		$context = 'blog';	
		if ( is_single() )
			$layout = get_post_meta( $post->ID, '_sp_post_layout', true );
	}
	
	// check for portfolio page
	if ( isset( $post ) && get_post_type( $post ) == 'portfolio-entries' ) {
		$context = 'page';	
		if ( is_single() ) {
			$layout = get_post_meta( $post->ID, '_sp_portfolio_layout', true );
		}
	}

	// check for page
	if ( isset( $post ) && get_post_type( $post ) == 'page' ) {
		// check for product page
		if ( sp_is_store_page() ) {
			$context = 'product';
		} else {
			$context = 'page';	
			$layout = get_post_meta( $post->ID, '_sp_page_layout', true );
		}		
	}

	// check for woocommerce product page
	if ( isset( $post ) && get_post_type( $post ) == 'product' ) {
		$context = 'product';	
		$layout = get_post_meta( $post->ID, '_sp_page_layout', true );
	}

	// check for wpec product page
	if ( isset( $post ) && get_post_type( $post ) == 'wpsc-product' ) {
		$context = 'product';	
		$layout = get_post_meta( $post->ID, '_sp_page_layout', true );
	}
			
	// if it is checkout pages quit early
	if ( sp_is_checkout_pages() )
	{  
		$orientation = 'no-sidebars'; 
		$sidebar_left = false;
		$sidebar_right = false;

		// return the 3 variables as arrays
		$vars = array( 'orientation' => $orientation, 'sidebar_left' => $sidebar_left, 'sidebar_right' => $sidebar_right );
		
		return $vars;
		
	}
	
	// if it is a single product page (detail) quit early
	if ( sp_is_single_product_page() ) 
	{ 
		$orientation = 'no-sidebars'; 
		$sidebar_left = false;
		$sidebar_right = false;

		// return the 3 variables as arrays
		$vars = array( 'orientation' => $orientation, 'sidebar_left' => $sidebar_left, 'sidebar_right' => $sidebar_right );
		
		return $vars;
		
	}
				
	// if it is not set to default
	if ( ! empty( $layout ) && $layout != 'default' ) 
	{ 
		if ( $layout == 'sidebar-left' ) 
		{
			$orientation = $layout;
			$sidebar_left = true;	
			$sidebar_right = false;
		} 
		elseif ( $layout == 'sidebar-right' ) 
		{
			$orientation = $layout;
			$sidebar_right = true;	
			$sidebar_left = false;
		} 
		elseif ( $layout == 'no-sidebars' ) 
		{
			$orientation = $layout;
			$sidebar_left = false;	
			$sidebar_right = false;
		} 
		elseif ( $layout == 'both-sidebars' ) 
		{
			$orientation = $layout;
			$sidebar_left = true;
			$sidebar_right = true;	
		}
	} 
	else
	{ 
		if ( sp_isset_option( $context . '_sidebar_orientation', 'isset' ) ) 
		{ 
			$orientation = sp_isset_option( $context . '_sidebar_orientation', 'value' );
			if ( $orientation == 'sidebar-left' ) 
			{
				$sidebar_left = true;
				$sidebar_right = false;	
			} 
			elseif ( $orientation == 'sidebar-right' ) 
			{
				$sidebar_left = false;
				$sidebar_right = true;
			} 
			elseif ( $orientation == 'both-sidebars' ) 
			{
				$sidebar_left = true;
				$sidebar_right = true;
			} 
			elseif ( $orientation == 'no-sidebars' ) 
			{
				$sidebar_left = false;
				$sidebar_right = false;	
			}
		} 
		else 
		{
			$orientation = 'sidebar-right';
			$sidebar_left = false;
			$sidebar_right = true;	
		}
	}
	
	// return the 3 variables as arrays
	$vars = array( 'orientation' => $orientation, 'sidebar_left' => $sidebar_left, 'sidebar_right' => $sidebar_right );

	return $vars;
}

/* function to check quickview image size
 *
 * @since 2.1.1
 * @param $file file to check
 * @return array string of image width and height
 */
function sp_quickview_image_size( $file = '' )
{
	// if file is empty, bail out
	if ( empty( $file ) )
		return null;
	
	// checks to make sure PHP config allows url fopen
	if ( ini_get( 'allow_url_fopen' ) ) 
	{
		if ( $imageinfo = @getimagesize( $file ) ) 
		{
			// calculate the best ratio to size on the image
			if ( $imageinfo[0] == $imageinfo[1] ) 
			{
				$image_width = '347';
				$image_height = '347';	
			} 
			elseif ( $imageinfo[0] < $imageinfo[1] || $imageinfo[0] > $imageinfo[1] ) 
			{
				$ratio = 347 / $imageinfo[0];	
				$image_height = round( $ratio * $imageinfo[1] );
				if ( $image_height > 550 ) 
				{
					$image_height = '550';
				} 								
				$image_width = '347';
			} 
			else 
			{
				$image_width = '347';
				$image_height = '347';	
			}
		}
	} 
	else 
	{
		$ch = curl_init();
		curl_setopt ( $ch, CURLOPT_URL, $file );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		
		$contents = curl_exec( $ch );
		curl_close( $ch );
		
		$new_image = ImageCreateFromString( $contents );
		imagejpeg( $new_image, 'temp.jpg', 100 );
		
		$imageinfo = @getimagesize( 'temp.jpg' );		

		if ( $imageinfo[0] == $imageinfo[1] ) 
		{
			$image_width = '347';
			$image_height = '347';	
		} 
		elseif ( $imageinfo[0] < $imageinfo[1] || $imageinfo[0] > $imageinfo[1] ) 
		{
			$ratio = 347 / $imageinfo[0];	
			$image_height = round( $ratio * $imageinfo[1] );
			if ( $image_height > 550 ) 
			{
				$image_height = '550';
			} 								
			$image_width = '347';
		} 
		else 
		{
			$image_width = '347';
			$image_height = '347';	
		}
	}
	
	return $sizes = array( 'image_width' => $image_width, 'image_height' => $image_height );
}
?>