<?php 
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* shortcodes
******************************************************************/

// adds shortcode capability in widgets
add_filter( 'widget_text', 'do_shortcode' );

// generate Google Plus 1 Button
// one argument passed in to determine the URL to be set to post or product or custom [sp_gplusone url=""]
function sp_gplusonebutton_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(  
         'url' => '',
		 'size' => '',
		 'count' => ''  
    ), $atts ) );  
		if ( $atts['url'] == "post" ) :
			$url = esc_url_raw( get_permalink() );
		elseif ( $atts['url'] == "product" ) :
			if ( function_exists( wpsc_the_product_permalink ) ) :
				$url = esc_url_raw( wpsc_the_product_permalink() );
			else :
				$url = esc_url_raw( get_permalink() );
			endif;
		endif;
		
		switch( $size ) :
			case 'small' :
				$size = ' data-size="small"';
			break;

			case 'medium' :
				$size = ' data-size="medium"';
			break;

			case 'tall' :
				$size = ' data-size="tall"';
			break;

			default :
				$size = ' data-size="standard"';
		endswitch;

		if ( $count == 'true' ) 
		{
			$count = '';
		} 
		elseif ( $count == 'false' ) 
		{
			$count = ' data-count="false"';	
		} 
		else 
		{
			$count = '';
		}
        $output = '<div class="gplusone">' . "\r\n";
        $output .= '<div class="g-plusone" data-href="' . $url . '" ' . $size . ' ' . $count . '></div>';
		
		// check if action has already been added
		if ( ! has_action( 'wp_footer', 'sp_gplusone_script' ) ) 
		{
			add_action( 'wp_footer', 'sp_gplusone_script' ); 
		}
		
        $output .= "</div><!--close gplusone-->\r\n";
		
		return $output;
}
add_shortcode( 'sp_gplusone', 'sp_gplusonebutton_shortcode' );

// gplusone script function
function sp_gplusone_script()
{ ?>
	<script type="text/javascript">
	  (function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>		
<?php }

// generate FB Like button
// one argument passed in to determine the URL to be set to post or product or custom [sp_fblike url=""]
function sp_fblikebutton_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(  
         'url' => ''  
    ), $atts ) );  
		if ( $atts['url'] == "post" ) :
			$url = urlencode( get_permalink() );
		elseif ( $atts['url'] == "product" ) :
			if ( function_exists( wpsc_the_product_permalink ) ) :
				$url = urlencode( wpsc_the_product_permalink() );
			else :
				$url = urlencode( get_permalink() );
			endif;
		endif;
        $output = '<div class="fb-like" data-href="' . $url . '" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>' . "\r\n";
		
		return $output;
}
add_shortcode( 'sp_fblike','sp_fblikebutton_shortcode' );

// generate Twitter Tweet button
function sp_tweetbutton_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(  
         'url' => ''  
    ), $atts ) );  
		
		if ( $url == "" ) :
			$url = get_permalink();
		endif;
		
		$output = '<div class="tweet_sc">' . "\r\n";
		$output .= '<a href="'.esc_url( __('http://twitter.com/share', 'sp')).'" class="twitter-share-button" data-url="' . $url . '" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';	
		$output .= '</div><!--close tweet_sc-->' . "\r\n";
		
		return $output;
}
add_shortcode( 'sp_tweet', 'sp_tweetbutton_shortcode' );

// shortcode to output the site root URL [sp_home_path]
function sp_home() 
{
	return home_url() . "/";
}
add_shortcode( 'sp_home_path', 'sp_home' );

// shortcode to output the current theme URL [sp_theme_path]
function sp_theme() 
{
	return get_template_directory_uri() . "/";
}
add_shortcode( 'sp_theme_path', 'sp_theme' );

// new grid shortcode
add_shortcode( 'sp_grid', 'sp_grid_shortcode' );
function sp_grid_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
	'span' => 'one_half',
	'last' => false
	), $atts ) );
	
	$span = str_replace( "-", "_", $span );
	if ( $last ) $last = 'last';
	$output = '';
	$output .= '<div class="sc-grid ' . $span . ' ' . $last . '"><p>' . do_shortcode( $content ) . '</p></div>' . "\r\n";
	if ( $last ) $output .= '<div class="group"></div>' . "\r\n";
	
	return $output;
}
// change default WP image with caption containers to html5 figure tags
add_shortcode( 'wp_caption', 'sp_img_caption_shortcode' );
add_shortcode( 'caption', 'sp_img_caption_shortcode' );

function sp_img_caption_shortcode( $attr, $content = null ) 
{

extract( shortcode_atts( array(
'id'    => '',
'align'    => 'alignnone',
'width'    => '',
'caption' => ''
), $attr ) );

if ( 1 > ( int ) $width || empty( $caption ) )
return $content;

if ( $id ) $idtag = 'id="' . esc_attr( $id ) . '" ';

  return '<figure ' . $idtag . 'aria-describedby="figcaption_' . $id . '" class="'.$align.'">'
  . do_shortcode( $content ) . '<figcaption id="figcaption_' . $id . '">' . $caption . '</figcaption></figure>';
}

// shortcode to display a contact email link
function sp_contact_email_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array( 'email' => get_option( 'admin_email' ) ), $atts ) );
	$output = '<a href="mailto:' . $email . '" title="' . $email . '">' . $email . '</a>';
	
	return $output;
	
}
add_shortcode( 'sp_email', 'sp_contact_email_shortcode' );

//shortcode to display horizontal rule with optional colors
function sp_hr_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array( 'color' => '#ADA394' ), $atts ) );
	$color = str_replace( "#", "", $color );
	$output = '<hr class="shortcode_line" style="border-color:#' . $color . ';" />' . "\r\n";
	
	return $output;
}
add_shortcode( 'sp_hr', 'sp_hr_shortcode' );

// back to top shortcode
add_shortcode( 'sp_btt', 'sp_back_to_top' );

function sp_back_to_top( $atts, $content = null ) 
{
	$output = '<div class="btt group"><a href="#top" title="' . __( 'Back to Top', 'sp' ) . '">' . __( 'Back to Top', 'sp' ) . ' <span class="icon">&uarr;</span></a></div>';
	
	return $output;
}

// custom gallery shortcode
//add_shortcode( 'gallery', 'sp_shortcode_gallery' );
function sp_shortcode_gallery( $atts, $content = null ) 
{

	global $post, $wp_locale;
	extract( shortcode_atts( array(
		'order'		=> 'ASC',
		'orderby'	=> 'menu_order ID',
		'id'		=> $post->ID,
		'columns'	=> 5,
		'size'		=> 'thumbnail',
		'type'		=> '',
		'width'		=> '',
		'height'	=> '',
		'exclude'	=> ''
	), $atts ) );

	$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
	$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
	
	if ( empty( $attachments ) ) return '';

	$columns = intval( $columns );
	$float = is_rtl() ? 'alignright' : 'alignleft';
	$w = $width ? "width='{$width}' " : '';
	$h = $height ? "height='{$height}' " : '';

	$i = 0; $output = '';
	foreach ( $attachments as $id => $attachment ) 
	{

		$clear = false;
		$link = wp_get_attachment_image_src( $id, $size, false );

		$output .= "
			<a class='{$type} lightbox preview_link' href='{$attachment->guid}' data-rel='prettyPhoto[{$post->ID}]' title='{$attachment->post_title}'>
				<img src='{$link[0]}' class='gallery {$float}' alt='{$attachment->post_title}' {$w}{$h}/>
			</a>";

		if ( $columns > 0 && ++$i % $columns == 0 ) 
		{
			$output .= '<br class="group" />';
			$clear = true;
		}
	}

	if ( ! $clear ) $output .= "<br class='group' />";

	return $output;
}

// color button shortcodes
add_shortcode( 'sp_button', 'sp_button_shortcode' );
function sp_button_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'color' => 'default', 
		'url' => '#', 
		'target' => 'same', 
		'type' => 'normal', // 4 types normal, normal-rounded, big, big-rounded
		'title' => '', 
		'position' => '' ),$atts ) );
		
	if ( $target == 'same' ) 
	{
		$target = '_self';
	} 
	else 
	{
		$target = '_blank';	
	}
	$custom_color = '';
	if ( preg_match( '/#/', $color ) ) 
	{
		if ( preg_match( '/big/', $type ) )
		{
			$custom_color = 'style="background:url(' . get_template_directory_uri() . '/images/button-overlay-big.png) repeat-x scroll center center ' . $color . ';"';	
		}
		else
		{
			$custom_color = 'style="background:url(' . get_template_directory_uri() . '/images/button-overlay.png) repeat-x scroll center center ' . $color . ';"';	
		}
		$color = '';
	} 
	else 
	{
		$color = strtolower( $color );	
	}
	$output = '<a href="' . $url . '" title="' . $title . '" target="' . $target . '" class="sc-button ' . $color . ' ' . $position . ' ' . $type . '" ' . $custom_color . '>' . $title . '</a>';
	
	return $output;
}

// pretty photo lightbox wrap shortcode
add_shortcode( 'sp_lightbox', 'sp_lightbox_shortcode' );
function sp_lightbox_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array( 'title' => '', 'group' => '', 'url' => '', 'thumbnail' => '', 'theme' => '', 'slideshow' => '', 'show_social' => '' ), $atts ) );
	$content = strip_tags( $content, '<img>' );
	if ( $group == '' ) 
	{
		$group = $url;	
	}
	if ( $theme == '' ) 
	{
		$theme = 'pp_default';	
	}
	if ( $slideshow == '' ) 
	{
		$slideshow = 'false';	
	}
	if ( $show_social == '' ) 
	{
		$show_social == 'false';	
	}
	$source_url = parse_url( $url );
	if ( $source_url['host'] == 'www.youtube.com' || $source_url['host'] == 'youtube.com' ) 
	{
		parse_str( $source_url['query'], $query );
		if ( isset($query['v'] ) && $query['v'] != '' ) 
		{
			$id = $query['v'];
		} 
		else 
		{
			$path = explode( "/", $source_url['path'] );
			$id = $path[count( $path )-1];
		}
		// if thumbnail is on, get first frame of video
		if ( $thumbnail == "large" || $thumbnail == "small" ) 
		{
			if ( $thumbnail == "large" ) 
			{
				$size = "hqdefault.jpg";
			} 
			else 
			{
				$size = "1.jpg";
			}
			$output = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" data-rel="prettyPhoto[' . $group . ']" class="lightbox-wrap"><img src="http://img.youtube.com/vi/' . $id . '/' . $size . '" alt="' . esc_attr( $title ) . '" /></a>';
			$output .= '<input type="hidden" value="' . $theme . '" class="sc_lightbox_theme" />';
			$output .= '<input type="hidden" value="' . $slideshow . '" class="sc_lightbox_slideshow" />';
			$output .= '<input type="hidden" value="' . $show_social . '" class="sc_lightbox_show_social" />';	
		} 
		else 
		{
			$output = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" data-rel="prettyPhoto[' . $group . ']" class="lightbox-wrap">' . do_shortcode( $content ) . '</a>';	
			$output .= '<input type="hidden" value="' . $theme . '" class="sc_lightbox_theme" />';
			$output .= '<input type="hidden" value="' . $slideshow . '" class="sc_lightbox_slideshow" />';
			$output .= '<input type="hidden" value="' . $show_social . '" class="sc_lightbox_show_social" />';	

		}
	} 
	elseif ( $source_url['host'] == 'www.vimeo.com' || $source_url['host'] == 'vimeo.com' || $source_url['host'] == 'player.vimeo.com' ) 
	{
		if ( isset( $query['clip_id'] ) && $query['clip_id'] != '' ) 
		{
			$id = $query['clip_id'];
		} 
		else 
		{
			$path = explode( "/", $source_url['path'] );
			$id = $path[( count( $path )-1)];
		}
		// if thumbnail is on, get first frame of video
		if ( $thumbnail == "large" || $thumbnail == "small" ) 
		{
			if ( function_exists( 'file_get_contents' ) ) 
			{
				if ( $thumbnail == "large" ) 
				{
					$size = "thumbnail_large";
				} 
				else 
				{
					$size = "thumbnail_small";
				}				
				$hash = unserialize( @file_get_contents( "http://vimeo.com/api/v2/video/" . $id . ".php" ) );
				if ( isset($hash[0]) && $hash[0] != '' ) 
				{
					$image = $hash[0][$size];	
				}
			}
			$output = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" data-rel="prettyPhoto[' . $group . ']" class="lightbox-wrap"><img src="' . $image . '" alt="' . $title . '" /></a>';	
			$output .= '<input type="hidden" value="' . $theme . '" class="sc_lightbox_theme" />';
			$output .= '<input type="hidden" value="' . $slideshow . '" class="sc_lightbox_slideshow" />';
			$output .= '<input type="hidden" value="' . $show_social . '" class="sc_lightbox_show_social" />';	

		} 
		else 
		{
			$output = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" data-rel="prettyPhoto[' . $group . ']" class="lightbox-wrap">' . do_shortcode( $content ) . '</a>';	
			$output .= '<input type="hidden" value="' . $theme . '" class="sc_lightbox_theme" />';
			$output .= '<input type="hidden" value="' . $slideshow . '" class="sc_lightbox_slideshow" />';
			$output .= '<input type="hidden" value="' . $show_social . '" class="sc_lightbox_show_social" />';	

		}		
	} 
	else 
	{
		$output = '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" data-rel="prettyPhoto[' . $group . ']" class="lightbox-wrap">' . do_shortcode( $content ) . '</a>';
		$output .= '<input type="hidden" value="' . $theme . '" class="sc_lightbox_theme" />';
		$output .= '<input type="hidden" value="' . $slideshow . '" class="sc_lightbox_slideshow" />';
		$output .= '<input type="hidden" value="' . $show_social . '" class="sc_lightbox_show_social" />';	
	}
	
	return $output;
}

// display raw code shortcode
add_shortcode( 'sp_code', 'sp_shortcode_code' );
function sp_shortcode_code( $atts, $content = null ) 
{
	$content = str_replace( "</p>", "\n", $content );
	$content = str_replace( "<p>", '', $content );
	$content = esc_attr( $content );
	$content = str_replace( "\n", "<br/>", trim( $content ) );
	$content = preg_replace( '/\[([^\]]*)\]/imu', '<strong>[</strong>$1<strong>]</strong>', $content );
	
	return "<code>" . $content . "</code>\n";
}

// list shortcode
add_shortcode( 'sp_list','sp_list_shortcode' );
function sp_list_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'type' => 'disc'
		),
		$atts ) );	
		$type = strtolower( $type );
		switch( $type ) 
		{
			case 'bullets' : 
				$class = 'disc';
				break;
			
			case 'lower-alpha' :
				$class = 'lower-alpha';
				break;
			
			case 'decimal' :
				$class = 'decimal';
				break;
				
			case 'none' :
				$class = 'none';
				break;
				
			case 'circle' :
				$class = 'circle';
				break;
				
			case 'upper-alpha' :
				$class = 'upper-alpha';
				break;
				
			case 'upper-roman' :
				$class = 'upper-roman';
				break;
				
			case 'lower-roman' :
				$class = 'lower-roman';
				break;
				
			case 'katakana' :
				$class = 'katakana';
				break;
				
			default :
				$class = 'disc';
		}
		$content = strip_tags( $content, '<strong><img><a>' );
		$content = do_shortcode( $content );
		$content = explode( "~", $content );
		
		$output = '';
		$output .= '<ul class="list_sc ' . $class . '">' . "\r\n";
		foreach( $content as $item ) 
		{
			$output .= '<li>' . $item . '</li>' . "\r\n";
		}
		$output .= '</ul>' . "\r\n";
		
		return $output;
}

// check user loggedin shortcode (if logged in, return content)
add_shortcode( 'sp_check_login', 'sp_check_login_shortcode' );
function sp_check_login_shortcode( $atts, $content = null ) 
{
	if( is_user_logged_in() ) 
	{
		return do_shortcode( $content );
	}
}

// blockquote shortcode
add_shortcode( 'sp_quotes', 'sp_blockquote_shortcode' );
function sp_blockquote_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'style' => 'large',
		'text_align' => 'center'
	),
	$atts ) );
	return '<blockquote class="sc-quotes ' . $style . '" style="text-align:' . $text_align . '"><p>' . do_shortcode( $content ) . '</p></blockquote>';
}

// display Google map shortcode
add_shortcode( 'sp_map', 'sp_google_map_shortcode' );
function sp_google_map_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array( 'width' => '500px', 'height' => '300px', 'zoom' => 15, 'address' => '1600 Amphitheatre Parkway Mountain View, CA 94043'), $atts ) );
	$num = rand( 0, 10000 );
	add_action( 'wp_footer', 'sp_google_map' );
	return '<script type="text/javascript">
		jQuery(function($) {
			initialize("' . $address . '",' . $num . ',' . $zoom . ');
		});
		</script>
		<div id="sp_map_' . $num . '" style="display:block; width:' . $width . '; height:' . $height . ';" class="sp-google-map">&nbsp;</div>';
}

// function to add google map js script
function sp_google_map(){
	echo '<script type="text/javascript"
				src="http://maps.googleapis.com/maps/api/js?sensor=false">
			</script>
			<script type="text/javascript">var map;
			  function initialize(address, num, zoom) {
				var geo = new google.maps.Geocoder(),
				latlng = new google.maps.LatLng(-34.397, 150.644),
				myOptions = {
				  \'zoom\': zoom,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				},
				map = new google.maps.Map(document.getElementById("sp_map_" + num), myOptions);
				
				geo.geocode( { \'address\': address}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map, 
						position: results[0].geometry.location
					});
				  } else {
					// status
				  }
				});
			  }			
			  </script>';
}

// tabs
add_shortcode( 'sp_tabs', 'sp_tabs_shortcode' );
function sp_tabs_shortcode( $atts, $content = null ) 
{
	extract( shortcode_atts( array(
		'tab_titles' => 'Tab1',
		'style' => 'light',
		'width' => 'auto',
		'id' => '1'
		),
		$atts ) );
		
	if ( $style != 'light' && $style != 'dark' ) 
	{
		$style = 'light';	
	}
	$tab_titles = explode( ",", str_replace( " ", "-", str_replace( ", ", ", ", $tab_titles ) ) );
	$content = do_shortcode( $content );
	$content = explode( "####", $content );
	
	$width = 'style="width:' . $width . ';"';
	$output = '';
	$output .= '<div id="sp-tabs' . $id . '" class="' . $style . ' sp-tab" ' . $width . '>' . "\r\n";
	$output .= '<ul class="tab-list">' . "\r\n";
	foreach ( $tab_titles as $title ) 
	{
		$output .= '<li>' . "\r\n";
		$output .= '<a href="#' . $title . '">' . str_replace( "-", " ", $title ) . '</a>' . "\r\n";
		$output .= '</li>' . "\r\n";
	}
	$output .= '</ul>' . "\r\n";		
	
	$i = 0;
	foreach ( $tab_titles as $title ) 
	{
		$output .= '<div id="' . $title . '" class="tabs-container ui-tabs-hide"><p>' . "\r\n";
		$output .= $content[$i];
		$output .= '</p></div>' . "\r\n";
		$i++;
	}
	$output .= '<script type="text/javascript">' . "\r\n";
	$output .= 'jQuery(document).ready(function() {' . "\r\n";
	$output .= 'jQuery("#sp-tabs' . $id . '").tabs({' . "\r\n";
	$output .= 'create: function() { jQuery(".sp-tab").show();}'."\r\n";
	$output .= '});' . "\r\n";
	$output .= '});'."\r\n"; 
	$output .= '</script>' . "\r\n";
	$output .= '</div>' . "\r\n";	
	return $output;
}

// slider
add_shortcode( 'sp_slider', 'sp_slider_shortcode' );
function sp_slider_shortcode( $atts, $contents = null ) 
{
	extract( shortcode_atts( array(
		'width' => 650,
		'height' => 'auto',
		'speed' => '500', // this will be deprecated with transition_speed
		'transition_speed' => '500',
		'pause_on_hover' => 'false',
		'direction' => 'left',
		'visible_items' => '1',
		'autoplay' => 'true',
		'interval' => '4000',
		'effects' => 'directscroll',
		'circular' => 'true',
		'infinite' => 'true',
		'easing' => 'linear',
		'responsive' => 'true',
		'align' => 'center',
		'hide_nav' => 'false',
		'touchswipe' => 'false',
		'id' => '1'
		),
		$atts ) );

	if ( ! is_numeric( $width ) )
		$width = json_encode( $width );
	else
		$width = str_replace( 'px', '', $width );

	if ( ! is_numeric( $height ) )
		$height = json_encode( $height );
	else
		$height = str_replace( 'px', '', $height );

	if ( $hide_nav == "true" ) 
		$hide_nav = 'hide';
	else
		$hide_nav = '';

	// remove PX from height
	$height = str_replace( 'px', '', $height );
	
	$contents = do_shortcode( $contents );	
	$contents = explode( "####", trim( $contents ) );
	//$style = 'style="width:' . $width . ';"';
	$output = '';
	$output .= '<div id="sp-slider-' . $id . '" class="group sc-slider ' . $hide_nav . '">' . "\r\n";
	$output .= '<span class="sc-slider-left-arrow">&nbsp;</span>';
	$output .= '<span class="sc-slider-right-arrow">&nbsp;</span>';
	$output .= '<div class="slider">' . "\r\n";
	foreach ( $contents as $text ) {
		$output .= '<div class="slide"><p>' . "\r\n";
		$output .= $text;
		$output .= '</p></div>' . "\r\n";
	}
	$output .= '</div>' . "\r\n";
	$output .= '</div>' . "\r\n";
	
	$output .= '<script type="text/javascript">' . "\r\n";
	$output .= 'jQuery(document).ready(function() {' . "\r\n";
	$output .= 'jQuery("#sp-slider-' . $id . ' .slider").carouFredSel({' . "\r\n";
	$output .= 'items: {' . "\r\n";
	$output .= 'visible: 1,' . "\r\n";
	$output .= 'filter: ".slide",' . "\r\n";
	$output .= 'height: ' . $height . ',' . "\r\n";
	$output .= 'width: ' . $width . "\r\n";
	$output .= '},' . "\r\n";
	$output .= 'responsive: ' . $responsive . ',' . "\r\n";
    $output .= 'circular: ' . $circular . ',' . "\r\n";
	$output .= 'infinite: ' . $infinite . ',' . "\r\n";
	$output .= 'direction: ' . json_encode( $direction ) . ',' . "\r\n";
	$output .= 'align: ' . json_encode( $align ) . ',' . "\r\n";
	$output .= 'scroll: {' . "\r\n";
	$output .= 'items: 1,' . "\r\n";
	$output .= 'pauseOnHover: ' . $pause_on_hover . ',' . "\r\n";
	$output .= 'easing: ' . json_encode( $easing ) . ',' . "\r\n";
	$output .= 'duration: ' . $transition_speed . ',' . "\r\n";
	$output .= 'fx: ' . json_encode( $effects ) . ',' . "\r\n";
	$output .= 'swipe: ' . $touchswipe . "\r\n";
	$output .= '},' . "\r\n";
	$output .= 'pagination: {' . "\r\n";
	$output .= 'container: "#slide_menu"' . "\r\n";
	$output .= '},' . "\r\n";
	$output .= 'auto: {' . "\r\n";
	$output .= 'play: ' . $autoplay . ',' . "\r\n";
	$output .= 'timeoutDuration: ' . $interval . "\r\n";
	$output .= '},' . "\r\n";
	$output .= 'next: {' . "\r\n";
	$output .= 'button: "#sp-slider-' . $id . ' span.sc-slider-left-arrow"' . "\r\n";
	$output .= '},' . "\r\n";
	$output .= 'prev: {' . "\r\n";
	$output .= 'button: "#sp-slider-' . $id . ' span.sc-slider-right-arrow"' . "\r\n";
	$output .= '}' . "\r\n";
	$output .= '});' . "\r\n";
	$output .= '});' . "\r\n";
	$output .= '</script>' . "\r\n";
	
	return $output;
}

// toggle content
add_shortcode( 'sp_toggle', 'sp_toggle_shortcode' );
function sp_toggle_shortcode( $atts, $contents = null ) 
{
	extract( shortcode_atts( array(
		'start_state' => 'closed',
		'title' => 'Toggle Content',
		'id' => ''
		),
		$atts ) );
	$contents = do_shortcode( $contents );	
	
	$output = '';
	$output .= '<div id="sp-toggle-' . $id . '" class="sp-toggle ' . $start_state . '">' . "\r\n";
	$output .= '<h3 class="toggle-content-title group">' . $title . '<span class="arrow">&nbsp;</span></h3>' . "\r\n";
	$output .= '<div class="content"><p>' . "\r\n";
	$output .= $contents;
	$output .= '</p></div>' . "\r\n";
	$output .= '</div>' . "\r\n";
	
	$output .= '<script type="text/javascript">' . "\r\n";
	$output .= 'jQuery(document).ready(function() {' . "\r\n";
	$output .= 'jQuery("#sp-toggle-' . $id . ' h3.toggle-content-title").click(function(e) {' . "\r\n";
	$output .= 'e.preventDefault();' . "\r\n";
	$output .= 'jQuery(this).parent().find(".content").slideToggle(function() {' . "\r\n";
	$output .= 'jQuery(this).parent().find(".toggle-content-title span.arrow").toggleClass("open");' . "\r\n";
	$output .= '});' . "\r\n";
	$output .= '});' . "\r\n";
	$output .= '});' . "\r\n";
	$output .= '</script>' . "\r\n";
	
	return $output;
}

// video player
add_shortcode( 'sp_video', 'sp_video_shortcode' );
function sp_video_shortcode( $atts, $contents = null ) 
{
	extract( shortcode_atts( array(
		'id' => 'sp-video-player',
		'width' => '300',
		'height' => '200',
		'controls' => 'true',
		'autoplay' => 'false',
		'preload' => 'auto',
		'poster_url' => '',
		'video_mp4' => '',
		'video_webm' => '',
		'video_ogg' => ''
		),
		$atts ) );
			
	$output = '';
	$output .= '<video id="' . $id . '" class="video-js vjs-default-skin" width="' . $width . '" height="' . $height . '" poster="' . $poster_url . '" data-setup=\'{"controls": ' .$controls . ', "preload": "' . $preload . '", "autoplay": ' . $autoplay . '}\'>' . "\r\n";
	if ( isset( $video_mp4 ) && $video_mp4 != '' )
	$output .= '<source src="' . $video_mp4 . '" type="video/mp4">' . "\r\n";
	
	if ( isset( $video_webm ) && $video_webm != '' )
	$output .= '<source src="' . $video_webm . '" type="video/webm">' . "\r\n";
	
	if ( isset( $video_ogg ) && $video_ogg != '' )
	$output .= '<source src="' . $video_ogg . '" type="video/ogg" />' . "\r\n";
	$output .= '</video>' . "\r\n";
	
	return $output;	
}

// audio player
add_shortcode( 'sp_audio', 'sp_audio_shortcode' );
function sp_audio_shortcode( $atts, $contents = null ) 
{
	extract( shortcode_atts( array(
		'id' => 'sp-audio-player',
		'url' => '',
		'preload' => 'auto'
		),
		$atts ) );
	
	$output = '';
	$output .= '<audio src="' . $url . '" preload="' . $preload . '" id="' . $id . '">' . "\r\n";
	$output .= '<script type="text/javascript">' . "\r\n";
	$output .= 'audiojs.events.ready(function() { var as = audiojs.createAll(); });';
	$output .= '</script>' . "\r\n";
	$output .= '</audio>' . "\r\n";
	
	return $output;
}

// informational boxes
add_shortcode( 'sp_box', 'sp_box_shortcode' );
function sp_box_shortcode( $atts, $contents = null )
{
	extract( shortcode_atts( array(
		'type' => 'info'
		),
		$atts ) );
			
	$contents = do_shortcode( $contents );
	
	$output = '';
	$output .= '<div class="sp-box ' . $type . '"><div class="box-content group">' . "\r\n";
	$output .= $contents;
	$output .= '</div></div>' . "\r\n";
	
	return $output;	
}

// dropcaps
add_shortcode( 'sp_dropcap', 'sp_dropcap_shortcode' );
function sp_dropcap_shortcode( $atts, $contents = null )
{
	extract( shortcode_atts( array(
		'size' => '60px',
		'color' => '#800000'
		),
		$atts ) );
	
	$size = str_replace( 'px', '', $size );
	$size = ( ( int ) $size < 50 ) ? 60 : $size;
	$size = $size . 'px';		
	$output = '';
	$output .= '<span class="sp-dropcap" style="font-size:' . $size . ';color:' . $color . ';">';
	$output .= $contents;
	$output .= '</span>' . "\r\n";
	
	return $output;	
}

function sp_do_shortcode_fix( $content ) 
{
    global $shortcode_tags;
    // save current shortcodes
    $old_shortcode_tags = $shortcode_tags;
    // remove all shortcodes, then re-add just our shortcodes
    remove_all_shortcodes();
    add_shortcode( 'sp_hr', $old_shortcode_tags['sp_hr'] );
    add_shortcode( 'sp_btt', $old_shortcode_tags['sp_btt'] );
	add_shortcode( 'sp_code', $old_shortcode_tags['sp_code'] );
	add_shortcode( 'sp_grid', $old_shortcode_tags['sp_grid'] );
	add_shortcode( 'sp_toggle', $old_shortcode_tags['sp_toggle'] );
	add_shortcode( 'sp_slider', $old_shortcode_tags['sp_slider'] );
	add_shortcode( 'sp_list', $old_shortcode_tags['sp_list'] );
	add_shortcode( 'sp_tabs', $old_shortcode_tags['sp_tabs'] );
	add_shortcode( 'sp_fblike', $old_shortcode_tags['sp_fblike'] );
	add_shortcode( 'sp_gplusone', $old_shortcode_tags['sp_gplusone'] );
	add_shortcode( 'sp_tweet', $old_shortcode_tags['sp_tweet'] );
	add_shortcode( 'sp_email', $old_shortcode_tags['sp_email'] );
	add_shortcode( 'sp_button', $old_shortcode_tags['sp_button'] );
	add_shortcode( 'sp_lightbox', $old_shortcode_tags['sp_lightbox'] );
	add_shortcode( 'sp_quotes', $old_shortcode_tags['sp_quotes'] );
	add_shortcode( 'sp_map', $old_shortcode_tags['sp_map'] );
	add_shortcode( 'sp_video', 'sp_video_shortcode' );
	add_shortcode( 'sp_audio', 'sp_audio_shortcode' );
	add_shortcode( 'sp_box', $old_shortcode_tags['sp_box'] );
	add_shortcode( 'sp_dropcap', 'sp_dropcap' );
    $content = do_shortcode( $content );
    // and now put back the original shortcodes
    $shortcode_tags = $old_shortcode_tags;
    return $content;
}
add_filter( 'the_content', 'sp_do_shortcode_fix', 9 );
?>