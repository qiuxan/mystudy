<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* admin theme control panel modules
******************************************************************/

/**
* logo upload module
*
******************************************************************/
function sp_logo( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<input type="text" value="" name="image-path" /><span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>';
	$output .= '<a href="#" class="upload-link" title="Upload" onclick="return false">' . __( 'Upload', 'sp' );
	$output .= '<input type="file" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="' . THEME_SHORTNAME . $module['_attr']['id'] . '" class="image-upload" />' . "\r\n";
	$output .= '</a>' . "\r\n";
	$output .= '</p>' . "\r\n";
	$output .= '<p>' . __( 'Current Image:', 'sp' ) . '</p>' . "\r\n";
	$output .= '<div class="image-preview" id="queue"><img src="' . $spdata[THEME_SHORTNAME . $module['_attr']['id']] . '" style="padding:10px;" /></div>';
	$output .= '<p><button type="submit" class="button" name="remove_logo" value="">' . __( 'Remove current image', 'sp' ) . '</button></p>' . "\r\n";

	return $output;	
}

/**
* upload module
*
******************************************************************/
function sp_upload( $module = array() ) 
{
	global $spdata;
	
	$output = '<div class="image-wrapper">';
	$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<input type="text" value="' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) . '" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" class="image-path" /><span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>';
	$output .= '<a href="#" class="upload-link upload_' . $module['_attr']['id'] . '" title="Upload" onclick="return false">' . __( 'Upload', 'sp' );
	$output .= '<input type="file" name="file-' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="' . THEME_SHORTNAME . $module['_attr']['id'] . '" class="image-upload" style="display:none;" />' . "\r\n";
	$output .= '</a>' . "\r\n";
	$output .= '</p>' . "\r\n";
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{
		$display = 'display:block;';
	} 
	else 
	{
		$display = 'display:none;';	
	}
	$output .= '<p style="' . $display . '" class="cur-image current-image-' . THEME_SHORTNAME . $module['_attr']['id'] . '">' . __( 'Current Image:', 'sp' ) . '</p>' . "\r\n";
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' )
	{
			$image_size = @getimagesize( str_replace( ' ', '-', ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) ) );
	}
	
	if ( isset( $image_size ) ) 
	{
		if ( $image_size[0] >= 300 || $image_size[1] >= 150 ) 
		{
			$width = '300';
			$height = '150';	
		} 
		else 
		{
			$width = $image_size[0];
			$height = $image_size[1];
		}
		if ( $image_size != null ) 
		{
		$output .= '<div class="image-preview" id="queue-' . THEME_SHORTNAME . $module['_attr']['id'] . '"><img src="' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) . '" height="' . $height . '" width="' . $width . '" class="uploaded-image" style="' . $display . '" /></div>';
		}
	}
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{
		$display = 'display:block;';
	} 
	else 
	{
		$display = 'display:none;';
	}
	$output .= '<p><button type="submit" data-rel="' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) . '" title="' . substr( ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ), strrpos( ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ), '/' ) + 1 ) . '" class="remove-image button" id="delete-' . THEME_SHORTNAME . $module['_attr']['id'] . '" style="' . $display . '">' . __( 'Remove current image', 'sp' ) . '</button></p>' . "\r\n";
	$output .= '</div>';
	
	return $output;	
}

/**
* custom slider
*
******************************************************************/
function sp_custom_slider( $module = array() ) 
{
	global $spdata;
	
	$count = $spdata[THEME_SHORTNAME . 'homepage_custom_slide_count'];

	$output = '';
	for ( $i = 1; $i <= 10; $i++ ) 
	{
		$image_size = null;
		
		$output .= '<div class="homepage_featured_slide_' . $i . ' custom_slider' . ' image-wrapper">';
		$output .= '<p>';
		$output .= '<h2 class="large">' . $i . '</h2>';
		$output .= '<label>' . __( 'Slide Image', 'sp' ) . '</label>' . "\r\n";
		$output .= '<input type="text" value="' . $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] . '" name="' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '" class="image-path" /><span class="tooltip"><span class="tip">' . __( 'Upload the image you want to feature for this slide.', 'sp' ) . '</span></span>';
		$output .= '<a href="#" class="upload-link upload_' . 'homepage_custom_slide_' . $i . '" title="Upload" onclick="return false">' . __( 'Upload', 'sp' );
		$output .= '<input type="file" name="file-' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '" id="' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '" class="image-upload" style="display:none;" />' . "\r\n";
		$output .= '</a>' . "\r\n";
		$output .= '</p>' . "\r\n";
		if ( isset( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] ) && $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] != '' ) 
		{
			$display = 'display:block;';
		} 
		else 
		{
			$display = 'display:none;';	
		}
		$output .= '<p style="' . $display . '" class="cur-image current-image-' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '">' . __( 'Current Image:', 'sp' ) . '</p>' . "\r\n";
		if ( isset( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] ) && $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] != '' ) 
		{
				$image_size = @getimagesize( str_replace( ' ', '-', $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] ) );
		}
		
		if ( $image_size[0] >= 300 || $image_size[1] >= 150 ) 
		{
			$width = '300';
			$height = '150';	
		} 
		else 
		{
			$width = $image_size[0];
			$height = $image_size[1];
		} 
		if ( $image_size != null ) 
		{
		$output .= '<div class="image-preview" id="queue-' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '"><img src="' . sp_check_ms_image( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] ) .'" height="' . $height . '" width="' . $width . '" class="uploaded-image" style="' . $display . '" /></div>';
		}
		if ( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i] != '' ) 
		{
			$display = 'display:block;';
		} 
		else 
		{
			$display = 'display:none;';
		}
		$output .= '<p><button type="submit" title="' . substr( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i], strrpos( $spdata[THEME_SHORTNAME . 'homepage_custom_slide_' . $i], '/' ) + 1 ) . '" class="remove-image button" id="delete-' . THEME_SHORTNAME . 'homepage_custom_slide_' . $i . '" style="' . $display . '">' . __('Remove current image', 'sp' ) . '</button></p>' . "\r\n";
		$output .= '<p><label>' . __( 'Slide Title', 'sp' ) . '</label><input type="text" id="text-slide-title-' . $i . '" name="' . THEME_SHORTNAME . 'homepage_custom_slide_title_' . $i . '" value="' . $spdata[THEME_SHORTNAME . 'homepage_custom_slide_title_' . $i] . '" />';
		$output .= '<span class="tooltip"><span class="tip">' . __( 'Enter the title you want for this slide.', 'sp' ) . '</span></span></p>';
		$output .= '<p><label>' . __( 'Slide Text', 'sp' ) . '</label><textarea id="text-slide-text-' . $i . '" rows="6" name="' . THEME_SHORTNAME . 'homepage_custom_slide_text_' . $i . '">' . $spdata[THEME_SHORTNAME . 'homepage_custom_slide_text_' . $i] . '</textarea>';
		$output .= '<span class="tooltip"><span class="tip">' . __( 'Enter the description you want for this slide. (note: long text will be automatically trimmed to retain style dimensions)', 'sp' ) . '</span></span></p>';
		$output .= '<p><label>' . __( 'Slide Link', 'sp' ) . '</label><input id="text-slide-link-' . $i . '" type="text" name="' . THEME_SHORTNAME . 'homepage_custom_slide_link_' . $i . '" value="' . $spdata[THEME_SHORTNAME . 'homepage_custom_slide_link_' . $i] . '" />';
		$output .= '<span class="tooltip"><span class="tip">' . __( 'Enter the link you want the slide to direct to.', 'sp' ) . '</span></span></p>';
		$output .= '<p><label>'.__('Slide Link Text','sp').'</label><input type="text" id="text-slide-link-text-' . $i . '" name="' . THEME_SHORTNAME . 'homepage_custom_slide_link_text_' . $i . '" value="' . $spdata[THEME_SHORTNAME . 'homepage_custom_slide_link_text_' . $i] . '" />';
		$output .= '</div>';
	}
		$output .= '<input type="hidden" id="number_of_slides" value="' . $count . '" />';
	
	return $output;
}

/**
* text module
*
******************************************************************/
function sp_text( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<input type="text" value="';
	// if nothing was set, get standard value
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{ 
		$output .= $spdata[THEME_SHORTNAME . $module['_attr']['id']]; 
	} 
	else 
	{
		$output .= $module['_attr']['std'];	
	}
	$output .= '" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="text-' . sp_a_clean( $module['_attr']['title'] ) . '" class="text-input" />' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* colorpicker module
*
******************************************************************/
function sp_colorpicker( $module = array() ) 
{
	global $spdata;
	
	$spdata_module = ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '';
			
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<span class="selectColor" style="background-color:' . $spdata_module . '"><span></span></span><input type="text" value="' . $spdata_module . '" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="colorpicker-' . sp_a_clean($module['_attr']['title']) . '" class="colorInput" />' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';
	
	return $output;	
}

/**
* all pages module WIDGETS
*
******************************************************************/
function sp_all_pages( $module = array() ) 
{
	global $spdata;
	
	//$layouts = array();
	$layouts = isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '';
	$output = '';
	$output .= '<p>' . __( 'You can add new custom widgets here per page.  This will allow you to have different widgets depending on the page you\'re on.  Once you have generated all the widgets, you will see new widget containers', 'sp' ) . ' <a href="' . admin_url() . '/widgets.php" title="Widgets">' . __( 'here', 'sp' ) . '</a></p>' . "\r\n";
	if ( isset( $layouts ) && is_array( $layouts ) ) 
	{
		foreach( $layouts as $layout ) 
		{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '_' . $layout . '" class="chzn-select">' . "\r\n";
			// get all pages
			$pages = get_pages();
			$output .= '<option value="0">' . __( 'Select a Page', 'sp' ) . '</option>';
			foreach ( $pages as $page ) 
			{
				$output .= '<option value="' . $page->ID . '" ' . ( ( $layout == $page->ID ) ? 'selected="selected"' : '' ) . '>' . $page->post_title . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
		}
	} else {
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '" class="chzn-select">' . "\r\n";
			// get all pages
			$pages = get_pages();
			$output .= '<option value="0">' . __( 'Select a Page', 'sp' ) . '</option>';
			foreach ( $pages as $page ) 
			{
				$output .= '<option value="' . $page->ID . '" ' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && ( $spdata[THEME_SHORTNAME . $module['_attr']['id']]  == $page->ID ) ? 'selected="selected"' : '' ) . '>' . $page->post_title . '</option>';
			}  
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">Delete</a>';
			$output .= '</p>' . "\r\n";
	}
	return $output;	
	
}

/**
* all blog categories module WIDGETS
*
******************************************************************/
function sp_all_blog_categories( $module = array() ) 
{
	global $spdata;
	
	$args = array(
		'type' => 'post',
		'hide_empty' => 0
	);	
	$layouts = isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '';
	$output = '';
	$output .= '<p>' . __( 'You can add new custom widgets here per blog category.  This will allow you to have different widgets depending on the category you\'re on.  Once you have generated all the widgets, you will see new widget containers', 'sp' ) . ' <a href="' . admin_url() . '/widgets.php" title="Widgets">' . __( 'here', 'sp' ) . '</a></p>' . "\r\n";
	if ( isset( $layouts ) && is_array( $layouts ) ) 
	{
		foreach( $layouts as $layout ) 
		{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '_' . $layout . '" class="chzn-select">' . "\r\n";
			// get all categories
			$categories = get_categories( $args );
			$output .= '<option value="0">' . __( 'Select a Category', 'sp' ) . '</option>';
			foreach ( $categories as $category ) 
			{
				$output .= '<option value="' . $category->cat_ID . '" ' . ( ( $layout == $category->cat_ID ) ? 'selected="selected"' : '' ) . '>' . $category->name . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">Delete</a>';
			$output .= '</p>' . "\r\n";
		}
	} else {
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '" class="chzn-select">' . "\r\n";
			// get all categories
			$categories = get_categories( $args );
			$output .= '<option value="0">' . __( 'Select a Category', 'sp' ) . '</option>';
			foreach ( $categories as $category ) 
			{
				$output .= '<option value="' . $category->cat_ID . '" ' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && ( $spdata[THEME_SHORTNAME . $module['_attr']['id']] == $category->cat_ID ) ? 'selected="selected"' : '' ) . '>' . $category->name . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
	}
	return $output;	
	
}

/**
* all product categories module WIDGETS WPEC
*
******************************************************************/
function sp_all_product_categories( $module = array() ) 
{
	global $spdata, $wpdb;
	
	// get all product category ids
	$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'wpsc_product_category'";
	$term_id = $wpdb->get_results( $sql, ARRAY_A );
	$categories = array();
	foreach ( $term_id as $term ) 
	{
		$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
		$categories[$term['term_id']] = $wpdb->get_var( $sql );
	}
	
	$layouts = isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '';
	$output = '';
	$output .= '<p>' . __( 'You can add new custom widgets here per product category page.  This will allow you to have different widgets depending on the product category page you\'re on.  Once you have generated all the widgets, you will see new widget containers', 'sp' ) . ' <a href="' . admin_url() . '/widgets.php" title="Widgets">' . __( 'here', 'sp' ) . '</a></p>' . "\r\n";
	if ( isset( $layouts ) && is_array( $layouts ) ) 
	{
		foreach( $layouts as $layout ) 
		{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '_' . $layout . '" class="chzn-select">' . "\r\n";
			// get all product categories
			
			$output .= '<option value="0">' . __('Select a Category', 'sp') . '</option>';
			foreach ( $categories as $k => $v ) 
			{
				$output .= '<option value="' . $k . '" ' . ( ( $layout == $k ) ? 'selected="selected"' : '' ) . '>' . $v . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
		}
	} 
	else 
	{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '" class="chzn-select">' . "\r\n";
			// get all product categories
			$output .= '<option value="0">' . __( 'Select a Category', 'sp' ) . '</option>';
			foreach ($categories as $k => $v) {
				$output .= '<option value="' . $k . '" ' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && ( $spdata[THEME_SHORTNAME . $module['_attr']['id']] == $k ) ? 'selected="selected"' : '' ) . '>' . $v . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
	}
	return $output;	
	
}

/**
* all product categories module WIDGETS WOO
*
******************************************************************/
function sp_woo_all_product_categories( $module = array() ) 
{
	global $spdata, $wpdb;
	
	// get all product category ids
	$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'product_cat'";
	$term_id = $wpdb->get_results( $sql, ARRAY_A );
	$categories = array();
	foreach ( $term_id as $term ) 
	{
		$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
		$categories[$term['term_id']] = $wpdb->get_var( $sql );
	}
	
	$layouts = isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '';
	$output = '';
	$output .= '<p>' . __( 'You can add new custom widgets here per product category page.  This will allow you to have different widgets depending on the product category page you\'re on.  Once you have generated all the widgets, you will see new widget containers', 'sp' ) . ' <a href="' . admin_url() . '/widgets.php" title="Widgets">' . __( 'here', 'sp' ) . '</a></p>' . "\r\n";
	if ( isset( $layouts ) && is_array( $layouts ) ) 
	{
		foreach( $layouts as $layout ) 
		{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '_' . $layout . '" class="chzn-select">' . "\r\n";
			// get all product categories
			
			$output .= '<option value="0">' . __('Select a Category', 'sp') . '</option>';
			foreach ( $categories as $k => $v ) 
			{
				$output .= '<option value="' . $k . '" ' . ( ( $layout == $k ) ? 'selected="selected"' : '' ) . '>' . $v . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
		}
	} 
	else 
	{
			$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . ' ' . $module['_attr']['id'] . '">';
			$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
			$output .= '<select name="' . THEME_SHORTNAME . $module['_attr']['id'] . '[]" id="' . $module['_attr']['id'] . '" class="chzn-select">' . "\r\n";
			// get all product categories
			$output .= '<option value="0">' . __( 'Select a Category', 'sp' ) . '</option>';
			foreach ($categories as $k => $v) {
				$output .= '<option value="' . $k . '" ' . ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && ( $spdata[THEME_SHORTNAME . $module['_attr']['id']] == $k ) ? 'selected="selected"' : '' ) . '>' . $v . '</option>';
			} 
		
			$output .= '</select>' . "\r\n";
			$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
			$output .= '<a href="#" title="add" class="add">Add</a><a href="#" title="delete" class="delete">' . __( 'Delete', 'sp' ) . '</a>';
			$output .= '</p>' . "\r\n";
	}
	return $output;	
	
}

/**
* select module
*
******************************************************************/
function sp_select( $module = array() ) 
{
	global $spdata;
	
	$option = array();

	$multiple = '';
	$chosen_class = 'chzn-select';
	$arr_element = '';
	
	if ( (isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "count") && (isset( $module['_attr']['option_atr1'] ) && isset( $module['_attr']['option_atr2'] ) ) ) 
	{
		if ( isset( $module['_attr']['option_atr3'] ) && $module['_attr']['option_atr3'] == '--' ) 
		{
			$option[0] = '--';
		}
		for ( $i = $module['_attr']['option_atr1']; $i <= $module['_attr']['option_atr2']; $i++ ) 
		{
			$option[$i] = $i;	
		}		
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "custom" ) 
	{
		$options = explode( ",", $module['_attr']['option_atr'] );
		if ( isset( $module['_attr']['option_first_select'] ) && $module['_attr']['option_first_select'] == '--' ) 
		{
			$option[0] = '--';
		}
		foreach ( $options as $item ) 
		{
			$option[$item] = $item;	
		}		
	}

	// WPEC all products
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "all_products" ) 
	{
		// generate post type array list
		$posts_obj = get_posts( array( 'post_type' => 'wpsc-product', 'numberposts' => '1000' ) ); 
		$option[0] = '--';
		foreach ( $posts_obj as $post ) 
		{ 
			$option[$post->ID] = $post->post_title; 
		}
	}

	// WOO all products
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "woo_all_products" ) 
	{
		// generate post type array list
		$posts_obj = get_posts( array( 'post_type' => 'product', 'numberposts' => '1000' ) ); 
		$option[0] = '--';
		foreach ( $posts_obj as $post ) 
		{ 
			$option[$post->ID] = $post->post_title; 
		}
	}
	
	// WPEC product category
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "product_category" ) 
	{
		// generate list of product categories to choose from
		global $wpdb;
		
		$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'wpsc_product_category'";
		$term_id = $wpdb->get_results( $sql, ARRAY_A );
		$option[0] = '--';
		foreach ( $term_id as $term ) 
		{
			$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
			$option[$term['term_id']] = $wpdb->get_var( $sql );
		}
	}				

	// WOO product category
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "woo_product_category" ) 
	{
		// generate list of product categories to choose from
		global $wpdb;
		
		$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'product_cat'";
		$term_id = $wpdb->get_results( $sql, ARRAY_A );
		$option[0] = '--';
		foreach ( $term_id as $term ) 
		{
			$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
			$option[$term['term_id']] = $wpdb->get_var( $sql );
		}
	}				
	
	// WPEC product categories
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "product_categories" ) 
	{
		// generate list of product categories to choose from with multi-select
		global $wpdb;
		
		$multiple = 'multiple="multiple" size="10"';
		$chosen_class = 'no-chzn';
		$arr_element = '[]';
		$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'wpsc_product_category'";
		$term_id = $wpdb->get_results( $sql, ARRAY_A );
		$option[0] = '--';
		foreach ( $term_id as $term ) 
		{
			$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
			$option[$term['term_id']] = $wpdb->get_var( $sql );
		}
	}				

	// WOO product categories
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "woo_product_categories" ) 
	{
		// generate list of product categories to choose from with multi-select
		global $wpdb;
		
		$multiple = 'multiple="multiple" size="10"';
		$chosen_class = 'no-chzn';
		$arr_element = '[]';
		$sql = "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'product_cat'";
		$term_id = $wpdb->get_results( $sql, ARRAY_A );
		$option[0] = '--';
		foreach ( $term_id as $term ) 
		{
			$sql = "SELECT name FROM {$wpdb->prefix}terms WHERE term_id = '{$term['term_id']}'";
			$option[$term['term_id']] = $wpdb->get_var( $sql );
		}
	}				

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "lightbox_theme" ) 
	{
		$option = array( 'pp_default' => 'default', 'facebook' => 'facebook', 'dark_rounded' => 'dark_rounded', 'dark_square' => 'dark_square', 'light_square' => 'light_square', 'light_rounded' => 'light_rounded' );	
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "product_view" ) 
	{
		$option = array( "default" => "default", "list" => "list", "grid" => "grid" );				
	}
	
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "timthumb_crop" ) 
	{
		$option = array( 'c' => 'center', 't' => 'top', 'tr' => 'topright', 'tl' => 'topleft', 'b' => 'bottom', 'br' => 'bottomright', 'bl' => 'bottomleft', 'l' => 'left', 'r' => 'right' );
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "timthumb_zoomcrop" ) 
	{
		$option = array( '0' => '0', '1' => '1', '2' => '2', '3' => '3' );
	}
	
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "timthumb_compression" ) 
	{
		$option = array( '10' => '10', '20' => '20', '30' => '30', '40' => '40', '50' => '50', '60' => '60', '70' => '70', '80' => '80', '90' => '90', '100' => '100' );
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "timthumb_filters" ) 
	{
		$option = array( '' => 'None', '1' => 'Invert Colors', '2' => 'Grayscale', '10' => 'Sketchy' );
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "easing" ) 
	{
		$option = array( 'linear' => '--','easeInQuad' => 'easeInQuad', 'easeOutQuad' => 'easeOutQuad','easeInOutQuad' => 'easeInOutQuad', 'easeInCubic' => 'easeInCubic','easeOutCubic' => 'easeOutCubic','eastInOutCubic' => 'easeInOutCubic','easeInQuart' => 'easeInQuart','easeOutQuart' => 'easeOutQuart','easeInOutQuart' => 'easeInOutQuart','easeInQuint' => 'easeInQuint','easeInOutQuint' => 'easeInOutQuint','easeInSine' => 'easeInSine', 'easeOutSine' => 'easeOutSine', 'easeInOutSine' => 'easeInOutSine', 'easeInExpo' => 'easeInExpo', 'easeOutExpo' => 'easeOutExpo', 'easeInOutExpo' => 'easeInOutExpo','easeInCirc' => 'easeInCirc', 'easeOutCirc' => 'easeOutCirc', 'easeInOutCirc' => 'easeInOutCirc', 'easeInElastic' => 'easeInElastic', 'easeOutElastic' => 'easeOutElastic', 'easeInOutElastic' => 'easeInOutElastic','easeInBack' => 'easeInBack', 'easeOutBack' => 'easeOutBack', 'easeInOutBack' => 'easeInOutBack','easeInBounce' => 'easeInBounce', 'easeOutBounce' => 'easeOutBounce', 'easeInOutBounce' => 'easeInOutBounce' );	
	}
	
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "font_family" ) 
	{
		// generate a list of fonts
		$option = sp_google_fonts_array();
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "font_weight" ) 
	{
		// generate a list of font weight
		$option = array( '0' => '--', 'bold' => 'bold', 'normal' => 'normal' );								
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "font_style" ) 
	{
		// generate a list of font style
		$option = array( '0' => '--', 'normal' => 'normal', 'italic' => 'italic', 'oblique' => 'oblique' );								
	}

	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "text_decoration" ) 
	{
		// generate a list of font style
		$option = array( '0' => '--', 'none' => 'none', 'underline' => 'underline', 'overline' => 'overline', 'line-through' => 'line-through' );								
	}

	if ( isset( $module['_attr']['property'] ) && $module['_attr']['property'] == "background-position" ) 
	{
		// generate a list of background positions
		$option = array( '0' => '--', 'left top' => 'left top', 'left center' => 'left center', 'left bottom' => 'left bottom', 'right top' => 'right top', 'right center' => 'right center', 'right bottom' => 'right bottom', 'center top' => 'center top', 'center center' => 'center center', 'center bottom' => 'center bottom' );								
	}
	if ( isset( $module['_attr']['property'] ) && $module['_attr']['property'] == "background-repeat" ) 
	{
		// generate a list of background repeats
		$option = array( '0' => '--', 'repeat' => 'repeat', 'repeat-x' => 'repeat-x', 'repeat-y' => 'repeat-y', 'no-repeat' => 'no-repeat' );								
	}
	if ( isset( $module['_attr']['property'] ) && $module['_attr']['property'] == "background-attachment" ) 
	{
		// generate a list of background attachments
		$option = array( '0' => '--', 'fixed' => 'fixed', 'scroll' => 'scroll' );								
	}
	
	if ( isset( $module['_attr']['option'] ) && $module['_attr']['option'] == "custom_background" ) 
	{
		// generate a list of custom backgrounds from the backgrounds folder
		$dir = get_template_directory() . '/images/backgrounds';
		
		if ( is_dir( $dir ) ) 
			$files = array_diff( scandir( $dir ), array( '.', '..' ) );
		
		$option_defaults = array( '' => __( 'No Custom Background', 'sp' ), 'custom' => __( 'Upload Custom Background', 'sp' ) );
		
		// rebuild the files array to set value same as key
		$rebuilt_files = array();
		foreach ( $files as $file )
		{
			$rebuilt_files[get_template_directory_uri().'/images/backgrounds/' . $file] = $file;	
		}
		$option = @array_merge( $option_defaults, $rebuilt_files );
	}
	
	// checks to see if gold cart is active, if so don't show product view options
	$gold_shpcrt_active = get_option( 'activation_state' );
	$gc_active = is_plugin_active( 'gold_cart_files_plugin/gold_shopping_cart.php' );
	if ( get_option( 'show_search' ) == '1' && get_option( 'show_advanced_search' ) == '1' ) 
	{
		$view_option = true;
	} 
	else 
	{
		$view_option = false;	
	}
	$output = '';
	if ( ( $module['_attr']['id'] == "product_view" || $module['_attr']['id'] == "grid_items") && $view_option == true ) {  } else {
		$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
		$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
		$output .= '<select class="'.$chosen_class.'" name="' . THEME_SHORTNAME . $module['_attr']['id'] . $arr_element . '" id="' . $module['_attr']['id'] . '" ' . $multiple . '>' . "\r\n";
		if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && is_array( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ) 
		{
			if ( is_array( $option ) ) 
			{
				foreach ( $option as $k => $v ) : 
						if ( $k == in_array( $k, $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ) :
							if ( $k == 0 ) 
							{
								$selected = '';	
							} 
							else 
							{
								$selected = 'selected="selected"';
							}
						else :	  
							$selected = '';
						endif;                     
					  $output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>' . "\r\n";
				endforeach;
			}
		} 
		else 
		{
			if ( is_array( $option ) ) 
			{
				foreach ( $option as $k => $v ) :
					  if ( $k == ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) ) :
						  $selected = 'selected="selected"';
					  else :	  
						  $selected = '';
					  endif;                     
					$output .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>' . "\r\n";
				endforeach; 
			}
		}
		$output .= '</select>' . "\r\n";
		$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
		$output .= '</p>' . "\r\n";
	}
	return $output;	
}

/**
* textarea module
*
******************************************************************/
function sp_textarea( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<textarea name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="text-' . sp_a_clean( $module['_attr']['title'] ) . '" rows="8" />' . "\r\n";
	// if nothing was set, get standard value
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{ 
		$output .= stripslashes( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ); 
	} 
	elseif ( stripslashes( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) == stripslashes($module['_attr']['std'] ) )
	{
		$output .= stripslashes($module['_attr']['std']);	
	}
	$output .= '</textarea>' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* radio module
*
******************************************************************/
function sp_radio( $module = array() ) 
{
	global $spdata;
		
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$options = explode(',', $module['_attr']['option'] );
	
	foreach ( $options as $option ) 
	{
		$check_state = ( ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) ? $spdata[THEME_SHORTNAME . $module['_attr']['id']] : '' ) == strtolower( $option ) ) ? 'checked="checked"' : null;
		$output .= '<input type="radio" value="' . strtolower( $option ) . '" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="radio-' . sp_a_clean( $module['_attr']['title'] . '-' . $option ) . '" ' . $check_state . ' />' . "\r\n";
		$output .= '<span class="radio">' . $option . '</span>';
	}

	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* datepicker module
*
******************************************************************/
function sp_datepicker( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	$output .= '<input type="text" value="';
	// if nothing was set, get standard value
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{ 
		$output .= $spdata[THEME_SHORTNAME . $module['_attr']['id']]; 
	} 
	else 
	{
		$output .= $module['_attr']['std'];	
	}
	$output .= '" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="text-' . sp_a_clean( $module['_attr']['title'] ) . '" class="datepicker" />' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* checkbox module
*
******************************************************************/
function sp_checkbox( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']] ) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] == 'true' ) 
	{
		$selected = 'checked=checked';
	} 
	else 
	{
		$selected = '';
	}
	$output .= '<input type="checkbox" value="true" ' . $selected . ' name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" id="checkbox-' . sp_a_clean( $module['_attr']['title'] ) . '" />' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* info text module
*
******************************************************************/
function sp_info( $module = array() ) 
{
	$output = '<p class="info-text">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</p>' . "\r\n";
	 
	return $output;
}

/**
* link module
*
******************************************************************/
function sp_link( $module = array() ) 
{
	$page_id = sp_get_page_id( 'maintenance' );
	$page_link = admin_url( 'post.php?post=' . $page_id . '&action=edit' );
	$output = '<p class="info-text">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . "\r\n";
	$output .= '<a href="' . esc_url( $page_link ) . '" title="' . esc_attr_e( $module['_attr']['title'] ) . '" >' . __( 'Edit Maintenance Page', 'sp' ) . '</a></p>' . "\r\n";
	 
	return $output;
}
/**
* site style preview module
*
******************************************************************/
function sp_site_preview( $module = array() ) 
{
	global $spdata;
	
	$output = '<div class="site-preview">' . "\r\n";
	$output .= '<p>' . __( 'Here is a rough preview of what your styles look like.', 'sp' ) . '</p>' . "\r\n";
	$output .= '<div class="container preview-bg">' . "\r\n";
	$output .= '<div class="inner-container">' . "\r\n";
	$output .= '<h1 class="preview-heading">' . __( 'This is a heading', 'sp' ) . '</h1>' . "\r\n";
	$output .= '<h1 class="preview-heading-link">' . __( 'This is a heading with link', 'sp' ) . '</h1>' . "\r\n";
	$output .= '<h1 class="preview-heading-link-hover">' . __( 'This is a heading with link on hover', 'sp' ) . '</h1>' . "\r\n";
	$output .= '<p class="preview-text">This is what your text style will look like. This is what your text style will look like. This is what your text style will look like. This is what your text style will look like. This is what your text style will look like. This is what your text style will look like.</p>' . "\r\n";
	$output .= '<p class="preview-link">' . __( 'This is the link color', 'sp' ) . '</p>' . "\r\n";
	$output .= '<p class="preview-link-hover">' . __( 'This is the link color on hover', 'sp' ) . '</p>' . "\r\n";
	$output .= '</div>' . "\r\n";
	$output .= '</div>' . "\r\n";
	$output .= '</div>';

	return $output;	
}

/**
* reset module
*
******************************************************************/
function sp_reset( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";						
	$output .= '<a href="' . admin_url() . '?page=sp" id="reset-' . sp_a_clean( $module['_attr']['title'] ) . '" class="button-primary reset">' . __( 'Reset All Settings', 'sp' ) . '</a>' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* check sp created page module
*
******************************************************************/
function sp_created_page( $module = array() ) 
{
	global $spdata;
	
	$page_title = $module['_attr']['page_title'];

	$page_check = get_page_by_title( sprintf( __( '%s', 'sp' ), $page_title ) );

	if ( ! isset( $page_check->ID ) )
	{
		$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
		$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";						
		$output .= '<span style="color:red;background-color:#fff;padding:2px;">' . $module['_attr']['msg'] . '</span>' . ' <a href="#" class="button-primary create-page">' . __( 'Create Page', 'sp' ) . '</a>' . "\r\n";
		$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
		$output .= '<input type="hidden" name="page_title" value="' . $module['_attr']['page_title'] . '" class="page-title" />' . "\r\n";
		$output .= '<input type="hidden" name="page_template" value="' . $module['_attr']['template'] . '" class="page-template" />' . "\r\n";
		$output .= '</p>';
		
		return $output;
	}
}

/**
* export theme settings module
*
******************************************************************/
function sp_export_theme_settings( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";						
	$output .= '<a href="#" class="button-primary export-theme-settings">' . __( 'Export Settings', 'sp' ) . '</a>' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* import theme settings module
*
******************************************************************/
function sp_import_theme_settings( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";						
	$output .= '<a href="#" class="button-primary import-theme-settings">' . __( 'Import Settings', 'sp' ) . '</a>' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';

	return $output;	
}

/**
* clear all product star ratings
*
******************************************************************/
function sp_clear_star_ratings( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<label>' . sprintf( __( '%s', 'sp' ), $module['_attr']['title'] ) . '</label>' . "\r\n";						
	$output .= '<a href="#" id="clear-' . sp_a_clean( $module['_attr']['title'] ) . '" class="button-primary clear_star_ratings">' . __( 'Clear Star Ratings', 'sp' ) . '</a>' . "\r\n";
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';
	
	return $output;	
}

/**
* skins module
*
******************************************************************/
function sp_skins( $module = array() ) 
{
	global $spdata, $themeinfo;
	
	$output = '';
	$count = $module['_attr']['skin_count'];
	for ( $i = 1; $i <= $count; $i++ ) 
	{
		$output .= '<p class="' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
		if ( isset( $spdata[THEME_SHORTNAME . 'skins'] ) && $spdata[THEME_SHORTNAME . 'skins'] != '' ) 
		{
			if ( $spdata[THEME_SHORTNAME . 'skins'] == $i ) 
			{
				$selected = 'selected';
			} 
			else 
			{
				$selected = '';	
			}
		}
		$skin_src = $i . '.jpg';
		$output .= '<a href="#" class="skins ' . $selected . '" id="' . $i . '"><img src="' . get_template_directory_uri() . '/skins/images/skin' . $skin_src . '" alt="' . $module['_attr']['title'] . '" /></a>' . "\r\n";
		
		$output .= '</p>';

	}
	if ( isset( $spdata[THEME_SHORTNAME . 'skins'] ) && $spdata[THEME_SHORTNAME . 'skins'] != '' ) 
	{
		$output .= '<input type="hidden" name="' . THEME_SHORTNAME . 'skins" value="' . $spdata[THEME_SHORTNAME . 'skins'] . '" id="hidden_skins_value" />';
	} 
	else 
	{
		$output .= '<input type="hidden" name="' . THEME_SHORTNAME . 'skins" value="1" id="hidden_skins_value" />';
	}
	return $output;
}

/**
* faq module
*
******************************************************************/
function sp_faq( $module = array() ) 
{
	global $spdata;
	
	$output = '<p class="faq">';
	$output .= $module['_attr']['title'] . "<br />\r\n";
	$output .= '<span>' . $module['_attr']['answer'] . '</span>' . "\r\n";
	$output .= '</p>';
	
	return $output;
}

/**
* footer widgets configuration
*
******************************************************************/
function sp_footer_widgets( $module = array() ) 
{
	global $spdata;
	
	$selected1 = '';
	$selected2 = '';
	$selected3 = '';
	$selected4 = '';
	if ( isset( $spdata[THEME_SHORTNAME . $module['_attr']['id']]) && $spdata[THEME_SHORTNAME . $module['_attr']['id']] != '' ) 
	{
		$footer_selected = 	$spdata[THEME_SHORTNAME . $module['_attr']['id']];
		if ( $footer_selected == 0 ) 
		{
			$selected0 = 'selected';	
		} 
		elseif ( $footer_selected == 1 ) 
		{
			$selected1 = 'selected';	
		} 
		elseif ( $footer_selected == 2 ) 
		{
			$selected2 = 'selected';	
		} 
		elseif ( $footer_selected == 3 ) 
		{
			$selected3 = 'selected';
		} 
		else {
			$selected4 = 'selected';	
		}
	} 
	else 
	{
		$footer_selected = 	$module['_attr']['std'];
		if ( $footer_selected == 0 )  
		{
			$selected0 = 'selected';	
		} 
		elseif ( $footer_selected == 1 ) 
		{
			$selected1 = 'selected';	
		} 
		elseif ( $footer_selected == 2 )  
		{
			$selected2 = 'selected';	
		} 
		elseif ( $footer_selected == 3 ) 
		{
			$selected3 = 'selected';
		} 
		else 
		{	
			$selected4 = 'selected';	
		}
		
	}
	$output = '<p class="group ' . THEME_SHORTNAME . $module['_attr']['id'] . '">';
	$output .= '<a href="#" class="footer_widgets" id="0"><img src="' . trailingslashit( FRAMEWORK_URL ) . 'images/footer-widget-0.png" alt="Footer Widget 0" class="' . ( isset( $selected0 ) ? $selected0 : '' ) . '" /></a>' . "\r\n";
	$output .= '<a href="#" class="footer_widgets" id="1"><img src="' . trailingslashit( FRAMEWORK_URL ) . 'images/footer-widget-1.png" alt="Footer Widget 1" class="' . ( isset( $selected1 ) ? $selected1 : '' ) . '" /></a>' . "\r\n";
	$output .= '<a href="#" class="footer_widgets" id="2"><img src="' . trailingslashit( FRAMEWORK_URL ) . 'images/footer-widget-2.png" alt="Footer Widget 2" class="' . ( isset( $selected2 ) ? $selected2 : '' ) . '" /></a>' . "\r\n";
	$output .= '<a href="#" class="footer_widgets" id="3"><img src="' . trailingslashit( FRAMEWORK_URL ) . 'images/footer-widget-3.png" alt="Footer Widget 3" class="' . ( isset( $selected3 ) ? $selected3 : '' ) . '" /></a>' . "\r\n";
	$output .= '<a href="#" class="footer_widgets" id="4"><img src="' . trailingslashit( FRAMEWORK_URL ) . 'images/footer-widget-4.png" alt="Footer Widget 4" class="' . ( isset( $selected4 ) ? $selected4 : '' ) . '" /></a>' . "\r\n";

	$output .= '<input type="hidden" name="' . THEME_SHORTNAME . $module['_attr']['id'] . '" value="' . ( isset( $footer_selected ) ? $footer_selected : '' ) . '" id="footer_widget_hidden_value" />';
	$output .= '<span class="tooltip"><span class="tip">' . sprintf( __( stripslashes( '%s' ), 'sp' ), $module['_attr']['desc'] ) . '</span></span>' . "\r\n";
	$output .= '</p>';
	
	return $output;	
}

?>