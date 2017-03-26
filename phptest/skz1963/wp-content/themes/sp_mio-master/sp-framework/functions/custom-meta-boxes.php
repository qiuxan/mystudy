<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* custom meta boxes
******************************************************************/

add_action( 'add_meta_boxes', 'sp_custom_meta_boxes' );
add_action( 'save_post', 'sp_custom_meta_boxes_save' );

function sp_custom_meta_boxes() {
	$post_id = ( isset( $_GET['post'] ) ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '');
	
    add_meta_box( 
        'sp_auto_formatting_post',
        __( 'SP WP Auto Formating', 'sp' ),
        'sp_custom_meta_box_wpautop',
        'post',
		'side',
		'low' 
    );
    add_meta_box(
        'sp_auto_formatting_page',
        __( 'SP WP Auto Formating', 'sp' ), 
        'sp_custom_meta_box_wpautop',
        'page',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_content_truncate',
        __( 'SP Excerpt Truncate', 'sp' ), 
        'sp_custom_meta_box_truncate',
        'post',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_variation_image_swap',
        __( 'SP Variation Image Swap', 'sp' ), 
        'sp_custom_meta_box_swap',
        'wpsc-product',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_page_layout',
        __( 'SP Page Layout', 'sp' ), 
        'sp_custom_page_layout',
        'page',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_post_layout',
        __( 'SP Post Layout', 'sp' ), 
        'sp_custom_post_layout',
        'post',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_portfolio_layout',
        __( 'SP Portfolio Layout', 'sp' ), 
        'sp_custom_portfolio_layout',
        'portfolio-entries',
		'side',
		'low'
    );	
    add_meta_box(
        'sp_portfolio_size',
        __( 'SP Portfolio Image Sizes', 'sp' ), 
        'sp_custom_portfolio_image_size',
        'portfolio-entries',
		'side',
		'low'
    );

	// first check if page is portfolio
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( $template_file == 'content-portfolio.php' ) 
	{
		add_meta_box(
			'sp_portfolio_options',
			__( 'SP Portfolio Options', 'sp' ), 
			'sp_custom_portfolio_options',
			'page',
			'side',
			'low'
		);	
	}

	// first check if page is using homepage v2 template
	$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
	
	if ( $template_file == 'sp_home_v2.php' ) 
	{
		add_meta_box(
			'sp_homepage_alternate_textarea',
			__( 'Homepage Alternate Textarea', 'sp' ), 
			'sp_homepage_alternate_textarea',
			'page',
			'normal',
			'high'
		);

		// add a layout graphic to show what the template looks like
		add_meta_box(
			'sp_homepage_v2_layout_graphic',
			__( 'Homepage Layout v2', 'sp' ), 
			'sp_homepage_v2_layout',
			'page',
			'side',
			'low'
		);
			
	}

}

function sp_custom_meta_box_wpautop( $post ) 
{
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	

	$checked = false;
	if ( $post->ID ) 
	{
		$checked = get_post_meta( $post->ID, '_sp_wpautop', true );
	}
	echo '<div class="settings-container">' . "\r\n";	
	echo '<label for="sp_wpautop">' . __( 'Disable WP Auto Content Formatting:', 'sp' ) . ' <input type="checkbox" value="1" name="sp_wpautop" id="sp_wpautop"' . checked( $checked, 1, false ) . ' /><span class="sptooltip"><span class="tip">' . __( 'When disabled, it will prevent Wordpress from automatically injecting break and paragraph tags which can cause some layout issues.  Mostly used when you are using HTML mode and want fine control.', 'sp' ) . '</span></span></label>';
	echo '</div>' . "\r\n";
}

function sp_custom_meta_box_truncate( $post ) 
{
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	

	$checked = false;
	if ( $post->ID ) 
	{
		$count = get_post_meta( $post->ID, '_sp_truncate_count', true );
		$denote = get_post_meta( $post->ID, '_sp_truncate_denote', true );
		$precision = get_post_meta( $post->ID, '_sp_truncate_precision', true );
		$truncate_disabled = get_post_meta( $post->ID, '_sp_truncate_disabled', true );
	}
	echo '<p><label>' . __( 'Disable Truncate Function:', 'sp' ) . ' <input type="checkbox" value="1" name="sp_truncate_disabled" id="sp_truncate_enable"' . checked( $truncate_disabled, 1, false ) . ' /></label><span class="sptooltip"><span class="tip">' . __( 'Checkbox to disable the auto text truncate function for this post.', 'sp' ) . '</span></span></p>' . "\r\n";
	echo '<div class="settings-container" ' . ( ( $truncate_disabled ) ? 'style="display:none;"' : 'style="display:block";"' ) .'>' . "\r\n";	
	echo '<p>' . __( 'This allows you to set the number of characters you want to display in the blog post listings.', 'sp' ) . '</p>' . "\r\n";
	echo '<label>' . __( 'Characters to Show:', 'sp' ) . ' <input type="text" value="' . esc_attr( $count ) . '" name="sp_truncate_count" id="sp_truncate_count" size="5" /><span class="sptooltip"><span class="tip">' . __( 'This is used so that in the blog listing view, your blog post content will not overflow and be too long. This serves as an excerpt.', 'sp' ) . '</span></span></label><br /><br />' . "\r\n";
	echo '<label>' . __( 'The Denote Character to Use:', 'sp' ) . ' <input type="text" value="' . esc_attr( $denote ) . '" name="sp_truncate_denote" id="sp_truncate_denote" size="5" /><span class="sptooltip"><span class="tip">' . __( 'This character denotation is used to signify there are more text to be displayed.', 'sp' ) . '</span></span></label><br /><br />' . "\r\n";
	echo '<label>' . __( 'Precision Truncate:', 'sp' ) . ' <input type="checkbox" value="1" name="sp_truncate_precision" id="sp_truncate_precision"' . checked( $precision, 1, false ) . ' /><span class="sptooltip"><span class="tip">' . __( 'Turning this on means it will cut off the text exactly at the characters you set above.  If you leave it off, it will automatically estimate where the word should be cut off to prevent cutting a word in half.', 'sp' ) . '</span></span></label>' . "\r\n";
	echo '</div>' . "\r\n";
}

function sp_custom_meta_box_swap( $post ) 
{
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	

	$checked = false;
	if ( $post->ID ) 
	{
		$checked = get_post_meta( $post->ID, '_sp_image_swap', true );
	}
	echo '<div class="settings-container">' . "\r\n";	
	echo '<label for="sp_image_swap">' . __( 'Disable Variation Image Swap:', 'sp' ) . ' <input type="checkbox" value="1" name="sp_image_swap" id="sp_image_swap"' . checked( $checked, 1, false ) . ' /><span class="sptooltip"><span class="tip">' . __( 'Check box to disable image swap for this product only.  This is to override the global image swap settings in your theme options.', 'sp' ) . '</span></span></label>';
	echo '</div>' . "\r\n";
}

function sp_custom_page_layout( $post ) 
{
	global $spdata;
	
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	if ( $post->ID ) 
	{
		$layout = get_post_meta( $post->ID, '_sp_page_layout', true );	
		$sitewide_widgets = get_post_meta( $post->ID, '_sp_page_layout_sitewide_widgets', true );
	}
	echo '<div class="settings-container">' . "\r\n";	
	echo '<p>' . __( 'Use the page layout settings to override the global settings', 'sp' ) . '</p>' . "\r\n";
	echo '<select name="sp_page_layout">' . "\r\n";
	echo '<option value="default" ' . selected( $layout, 'default', false ) . '>' . __( 'Use Default', 'sp' ) . '</option>';
	echo '<option value="sidebar-left" ' . selected( $layout, 'sidebar-left', false ) . '>' . __( 'Sidebar Left', 'sp' ) . '</option>';
	echo '<option value="sidebar-right" ' . selected( $layout, 'sidebar-right', false ) . '>' . __( 'Sidebar Right', 'sp' ). '</option>';
	echo '<option value="both-sidebars" ' . selected( $layout, 'both-sidebars', false ) . '>' . __( 'Both Sidebars', 'sp' ) . '</option>';
	echo '<option value="no-sidebars" ' . selected( $layout, 'no-sidebars', false ) . '>' . __( 'No Sidebars', 'sp' ) . '</option>';
	echo '</select>' . "\r\n";
	echo '<p><label>' . __( 'Disable Sitewide Sidebar Widgets for this Page: ', 'sp' ) . '</label>' . "\r\n";
	echo '<input type="checkbox" name="sitewide_widgets" value="1" ' . checked( $sitewide_widgets, 1, false ) . ' /></p>' . "\r\n";
	echo '</div>' . "\r\n";
}

function sp_custom_post_layout( $post ) 
{
	global $spdata;
	
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	if ( $post->ID ) 
	{
		$layout = get_post_meta( $post->ID, '_sp_post_layout', true );	
		$sitewide_widgets = get_post_meta( $post->ID, '_sp_page_layout_sitewide_widgets', true );
	}
	echo '<div class="settings-container">' . "\r\n";		
	echo '<p>' . __( 'Use the post layout settings to override the global settings', 'sp' ) . '</p>' . "\r\n";
	echo '<select name="sp_post_layout">' . "\r\n";
	echo '<option value="default" ' . selected( $layout, 'default', false ) . '>Use Default</option>';
	echo '<option value="sidebar-left" ' . selected( $layout, 'sidebar-left', false ) . '>Sidebar Left</option>';
	echo '<option value="sidebar-right" ' . selected( $layout, 'sidebar-right', false ) . '>Sidebar Right</option>';
	echo '<option value="both-sidebars" ' . selected( $layout, 'both-sidebars', false ) . '>Both Sidebars</option>';
	echo '<option value="no-sidebars" ' . selected( $layout, 'no-sidebars', false ) . '>No Sidebars</option>';
	echo '</select>' . "\r\n";
	echo '<p><label>' . __( 'Disable Sitewide Sidebar Widgets for this Post: ', 'sp' ) . '</label>' . "\r\n";
	echo '<input type="checkbox" name="sitewide_widgets" value="1" ' . checked( $sitewide_widgets, 1, false ) . ' /></p>' . "\r\n";	
	echo '</div>' . "\r\n";
}

function sp_custom_portfolio_layout( $post ) 
{
	global $spdata;
	
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	if ( $post->ID ) 
	{
		$layout = get_post_meta( $post->ID, '_sp_portfolio_layout', true );	
		$sitewide_widgets = get_post_meta( $post->ID, '_sp_page_layout_sitewide_widgets', true );
	}
	echo '<div class="settings-container">' . "\r\n";		
	echo '<p>' . __( 'Use the portfolio layout settings to override the global settings. For single view.', 'sp' ) . '</p>' . "\r\n";
	echo '<select name="sp_portfolio_layout">' . "\r\n";
	echo '<option value="default" ' . selected( $layout, 'default', false ) . '>Use Default</option>';
	echo '<option value="sidebar-left" ' . selected( $layout, 'sidebar-left', false ) . '>Sidebar Left</option>';
	echo '<option value="sidebar-right" ' . selected( $layout, 'sidebar-right', false ) . '>Sidebar Right</option>';
	echo '<option value="both-sidebars" ' . selected( $layout, 'both-sidebars', false ) . '>Both Sidebars</option>';
	echo '<option value="no-sidebars" ' . selected( $layout, 'no-sidebars', false ) . '>No Sidebars</option>';
	echo '</select>' . "\r\n";
	echo '<p><label>' . __( 'Disable Sitewide Sidebar Widgets for this Portfolio: ', 'sp' ) . '</label>' . "\r\n";
	echo '<input type="checkbox" name="sitewide_widgets" value="1" ' . checked( $sitewide_widgets, 1, false ) . ' /></p>' . "\r\n";	
	echo '</div>' . "\r\n";
}

function sp_custom_portfolio_image_size( $post ) 
{
	global $spdata;
	
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	if ( $post->ID ) 
	{
		$list_width = get_post_meta( $post->ID, '_sp_portfolio_list_width', true );	
		$list_height = get_post_meta( $post->ID, '_sp_portfolio_list_height', true );
		$single_width = get_post_meta( $post->ID, '_sp_portfolio_single_width', true );
		$single_height = get_post_meta( $post->ID, '_sp_portfolio_single_height', true );

		// set default XML sizes if nothing is set
		if ( ! isset( $list_width ) || empty( $list_width ) )
			$list_width = sp_get_theme_init_setting('portfolio_list_size','width');

		if ( ! isset( $list_height ) || empty( $list_height ) )
			$list_height = sp_get_theme_init_setting('portfolio_list_size','height');

		if ( ! isset( $single_width ) || empty( $single_width ) )
			$single_width = sp_get_theme_init_setting('portfolio_single_size','width');

		if ( ! isset( $single_height ) || empty( $single_height ) )
			$single_height = sp_get_theme_init_setting('portfolio_single_size','height');								

	}
	
	echo '<div class="settings-container">' . "\r\n";	
	echo '<p>' . __( 'Set the portfolio image sizes for list view and single view.', 'sp' ) . '</p>' . "\r\n";

	echo '<p><label>' . __( 'Listing View Image Width (format: 300)', 'sp' ) . '</label><br />' . "\r\n";
	echo '<input type="text" name="portfolio_list_width" value="' . $list_width . '"  class="widefat" /></p>' . "\r\n";

	echo '<p><label>' . __( 'Listing View Image Height (format: 300)', 'sp' ) . '</label><br />' . "\r\n";	
	echo '<input type="text" name="portfolio_list_height" value="' . $list_height . '" class="widefat" /></p>' . "\r\n";

	echo '<p><label>' . __( 'Single View Image Width (format: 300)', 'sp' ) . '</label><br />' . "\r\n";	
	echo '<input type="text" name="portfolio_single_width" value="' . $single_width . '" class="widefat" /></p>' . "\r\n";

	echo '<p><label>' . __( 'Single View Image Height (format: 300)', 'sp' ) . '</label><br />' . "\r\n";	
	echo '<input type="text" name="portfolio_single_height" value="' . $single_height . '" class="widefat" /></p>' . "\r\n";
	echo '</div>' . "\r\n";
}

function sp_custom_portfolio_options( $post ) 
{
	global $spdata;
	
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	echo '<div class="settings-container">' . "\r\n";	
	echo '<p>' . __( 'Use below settings to customize this portfolio page.', 'sp' ) . '</p>' . "\r\n";
	echo '<p><label>' . __( 'Select the Portfolio Category', 'sp' ) . '</label><br />' . "\r\n";
	
	if ( $post->ID ) 
	{
		$selected_cat_id = get_post_meta( $post->ID, '_sp_portfolio_cats', true );
		$selected_columns = get_post_meta( $post->ID, '_sp_portfolio_columns', true );
		$selected_postperpage = get_post_meta( $post->ID, '_sp_portfolio_postperpage', true );
		$selected_sortable = get_post_meta( $post->ID, '_sp_portfolio_sortable', true );
		$selected_show_title = get_post_meta( $post->ID, '_sp_portfolio_show_title', true );
		$selected_show_excerpt = get_post_meta( $post->ID, '_sp_portfolio_show_excerpt', true );
		$selected_gallery_only = get_post_meta( $post->ID, '_sp_portfolio_gallery_only', true );
	}
	
	$args = array(
		'show_option_none' => __( 'Please Select', 'sp' ),
		'show_count' => true,
		'name' => 'sp_portfolio_cats',
		'taxonomy' => 'portfolio_categories',
		'selected' => $selected_cat_id
	);
	wp_dropdown_categories( $args );

	echo '</p>' . "\r\n";
	echo '<p><label>' . __( 'Select the number of columns to display', 'sp' ) . '</label><br />' . "\r\n";
	echo '<select name="sp_portfolio_columns">' . "\r\n";
	echo '<option value="0" ' . selected( $selected_columns, '0', false ) . '>' . __( 'Please Select', 'sp' ) . '</option>';
	echo '<option value="1" ' . selected( $selected_columns, '1', false ) . '>' . __( '1 Column', 'sp' ) . '</option>';
	echo '<option value="2" ' . selected( $selected_columns, '2', false ) . '>' . __( '2 Columns', 'sp' ) . '</option>';
	echo '<option value="3" ' . selected( $selected_columns, '3', false ) . '>' . __( '3 Columns', 'sp' ) . '</option>';
	echo '<option value="4" ' . selected( $selected_columns, '4', false ) . '>' . __( '4 Columns', 'sp' ) . '</option>';
	echo '</select></p>' . "\r\n";
	echo '<p><label>' . __( 'Enter the number of entries per page to display', 'sp' ) . '</label><br />' . "\r\n";
	echo '<input type="text" name="sp_portfolio_postperpage" value="' . ( ( isset( $selected_postperpage ) && $selected_postperpage != '0' ) ? $selected_postperpage : "10" ) . '" /></p>' . "\r\n";

	echo '<p>' . "\r\n";
	echo '<input type="checkbox" name="sp_portfolio_sortable" value="1" ' . checked( $selected_sortable, 1, false ) . ' /><label>' . __( 'Turn on sortable feature. ', 'sp' ) . '</label><span class="sptooltip"><span class="tip">' . __( 'This will take your entry tags and list them as sortable items.', 'sp' ) . '</span></span></p>' . "\r\n";

	echo '<p>' . "\r\n";
	echo '<input type="checkbox" name="sp_portfolio_show_title" value="1" ' . checked( $selected_show_title, 1, false ) . ' /><label>' . __( 'Turn on show title.', 'sp' ) . '</label><span class="sptooltip"><span class="tip">' . __( 'This will display the title of your portfolio entry.', 'sp' ) . '</span></span></p>' . "\r\n";

	echo '<p>' . "\r\n";
	echo '<input type="checkbox" name="sp_portfolio_show_excerpt" value="1" ' . checked( $selected_show_excerpt, 1, false ) . ' /><label>' . __( 'Turn on show excerpt.', 'sp' ) . '</label><span class="sptooltip"><span class="tip">' . __( 'This will display a text excerpt of your portfolio entry if set.', 'sp' ) . '</span></span></p>' . "\r\n";

	echo '<p>' . "\r\n";
	echo '<input type="checkbox" name="sp_portfolio_gallery_only" value="1" ' . checked( $selected_gallery_only, 1, false ) . ' /><label>' . __( 'Turn on gallery only.', 'sp' ) . '</label><span class="sptooltip"><span class="tip">' . __( 'This will display your portfolio in a gallery only fashion where detailed portfolio entry is disabled.  Clicking on the images in the gallery will open a lightbox instead of going to the detailed entry view.', 'sp' ) . '</span></span></p>' . "\r\n";
	
	echo '</div>' . "\r\n";
}

function sp_homepage_alternate_textarea( $post )
{
	wp_nonce_field( __FILE__ , 'sp_custom_meta_boxes_noncename' );	
	
	$field_value = get_post_meta( $post->ID, '_sp_homepage_alt_textarea', true );
	if ( empty( $field_value ) )
		$field_value = __( 'Type your alternate text here', 'sp' );
		
	wp_editor( $field_value, 'homepagealt' );			
}

function sp_homepage_v2_layout() 
{
	echo '<div class="settings-container">' . "\r\n";	
	echo '<p>' . "\r\n";
	echo '<img src="' . FRAMEWORK_URL . 'images/homepage_v2_layout_graphic.jpg" alt="Homepage v2 Layout Graphic" />' . "\r\n";
	echo '</p>' . "\r\n";
	echo '</div>' . "\r\n";
	
}

function sp_custom_meta_boxes_save( $post_id ) 
{
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;		
	
	if ( ! wp_verify_nonce( ( isset( $_POST['sp_custom_meta_boxes_noncename'] ) ? $_POST['sp_custom_meta_boxes_noncename'] : '' ), __FILE__  ) )
		return;		
	
	// Check permissions
	if ( 'page' == $_POST['post_type'] ) 
	{
	  if ( ! current_user_can( 'edit_page', $post_id ) )
		  return;
	}
	else
	{
	  if ( ! current_user_can( 'edit_post', $post_id ) )
		  return;
	}		
	
	// settings for wpautop	 
	$wpautop = ( ( $_POST['sp_wpautop'] == 1 ) ? true : false );
	if ( $wpautop ) 
	{
		update_post_meta( $post_id, '_sp_wpautop', 1 );
	} else {
		delete_post_meta( $post_id, '_sp_wpautop' );
	}	 
	// settings for truncate
	$count = mysql_real_escape_string( trim( $_POST['sp_truncate_count'] ) );
	$denote = mysql_real_escape_string( trim( $_POST['sp_truncate_denote'] ) );
	$precision = ( ( $_POST['sp_truncate_precision'] == 1 ) ? true : false );
	if ( $precision ) 
	{
		update_post_meta( $post_id, '_sp_truncate_precision', 1 );
	} 
	else 
	{
		delete_post_meta( $post_id, '_sp_truncate_precision' );
	}

	$truncate_disabled = ( ( $_POST['sp_truncate_disabled'] == 1 ) ? true : false );
	if ( $truncate_disabled  ) 
	{
		update_post_meta( $post_id, '_sp_truncate_disabled', 1 );
	} 
	else 
	{
		delete_post_meta( $post_id, '_sp_truncate_disabled' );
	}
	
	update_post_meta( $post_id, '_sp_truncate_count', $count );
	update_post_meta( $post_id, '_sp_truncate_denote', $denote );	 
	
	// settings for image swap
	$swap = ( ( $_POST['sp_image_swap'] == 1 ) ? true : false );
	if ( $swap ) 
	{
		update_post_meta( $post_id, '_sp_image_swap', 1 );
	} 
	else 
	{
		delete_post_meta( $post_id, '_sp_image_swap' );
	}
	
	// settings for page layout
	$layout = sanitize_text_field( $_POST['sp_page_layout'] );	 
	update_post_meta( $post_id, '_sp_page_layout', $layout );

	// settings for post layout
	$layout = sanitize_text_field( $_POST['sp_post_layout'] );	 
	update_post_meta( $post_id, '_sp_post_layout', $layout );

	// settings for portfolio layout
	$layout = sanitize_text_field( $_POST['sp_portfolio_layout'] );	 
	update_post_meta( $post_id, '_sp_portfolio_layout', $layout );

	// settings for portfolio image sizes
	$portfolio_list_width = absint( $_POST['portfolio_list_width'] );
	// if nothing is set
	if ( $portfolio_list_width == 0 )
		$portfolio_list_width = sp_get_theme_init_setting('portfolio_list_size','width');

	update_post_meta( $post_id, '_sp_portfolio_list_width', $portfolio_list_width );
	
	$portfolio_list_height = absint( $_POST['portfolio_list_height'] );
	if ( $portfolio_list_height == 0 )
		$portfolio_list_height = sp_get_theme_init_setting('portfolio_list_size','height');

	update_post_meta( $post_id, '_sp_portfolio_list_height', $portfolio_list_height );
	
	$portfolio_single_width = absint( $_POST['portfolio_single_width'] );
	if ( $portfolio_single_width == 0 )
		$portfolio_single_width = sp_get_theme_init_setting('portfolio_single_size','width');

	update_post_meta( $post_id, '_sp_portfolio_single_width', $portfolio_single_width );
	
	$portfolio_single_height = absint( $_POST['portfolio_single_height'] );
	if ( $portfolio_single_height == 0 )
		$portfolio_single_height = sp_get_theme_init_setting('portfolio_single_size','height');

	update_post_meta( $post_id, '_sp_portfolio_single_height', $portfolio_single_height );		
	
	// settings for post/page/portfolio sitewide widgets 
	$sitewide_widgets = ( ( $_POST['sitewide_widgets'] == 1 ) ? true : false );
	if ( $sitewide_widgets ) 
	{
		update_post_meta( $post_id, '_sp_page_layout_sitewide_widgets', 1 );
	} else {
		delete_post_meta( $post_id, '_sp_page_layout_sitewide_widgets' );
	}	

	// settings for portfolio pages
	$portfolio_cats = sanitize_text_field( $_POST['sp_portfolio_cats'] );
	$portfolio_columns = sanitize_text_field( $_POST['sp_portfolio_columns'] );
	$portfolio_postperpage = sanitize_text_field( $_POST['sp_portfolio_postperpage'] );
	if ( isset( $portfolio_cats ) && isset( $portfolio_columns ) ) 
	{
		update_post_meta( $post_id, '_sp_portfolio_cats', $portfolio_cats );
		update_post_meta( $post_id, '_sp_portfolio_columns', $portfolio_columns );
		update_post_meta( $post_id, '_sp_portfolio_postperpage', $portfolio_postperpage );
		
		$portfolio_sortable = sanitize_text_field( $_POST['sp_portfolio_sortable'] );
		if ( $portfolio_sortable ) 
		{
			update_post_meta( $post_id, '_sp_portfolio_sortable', $portfolio_sortable );
		} 
		else 
		{
			delete_post_meta( $post_id, '_sp_portfolio_sortable', $portfolio_sortable );	
		}
		
		$portfolio_show_title = sanitize_text_field( $_POST['sp_portfolio_show_title'] );
		if ( $portfolio_show_title ) 
		{
			update_post_meta( $post_id, '_sp_portfolio_show_title', $portfolio_show_title );
		} 
		else 
		{
			delete_post_meta( $post_id, '_sp_portfolio_show_title', $portfolio_show_title );	
		}
		
		$portfolio_show_excerpt = sanitize_text_field( $_POST['sp_portfolio_show_excerpt'] );
		if ( $portfolio_show_excerpt ) 
		{
			update_post_meta( $post_id, '_sp_portfolio_show_excerpt', $portfolio_show_excerpt );
		} 
		else 
		{
			delete_post_meta( $post_id, '_sp_portfolio_show_excerpt', $portfolio_show_excerpt );	
		}

		$portfolio_gallery_only = sanitize_text_field( $_POST['sp_portfolio_gallery_only'] );
		if ( $portfolio_gallery_only ) 
		{
			update_post_meta( $post_id, '_sp_portfolio_gallery_only', $portfolio_gallery_only );
		} 
		else 
		{
			delete_post_meta( $post_id, '_sp_portfolio_gallery_only', $portfolio_gallery_only );	
		}

	}
	
	if ( isset( $_POST['homepagealt'] ) )
	{
		$alt_text = sanitize_text_field( $_POST['homepagealt'] );
		update_post_meta( $post_id, '_sp_homepage_alt_textarea', $alt_text );
	}

}


?>