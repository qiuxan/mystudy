<?php 
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* custom post functions
******************************************************************/

// custom excerpt length
function sp_excerpt($excerpt = '', $excerpt_length = 40, $readmore = "Read More &gt;",$tags = '<a>') {
	global $post;
	$string_check = explode(' ', $excerpt);
	
	if (count($string_check, COUNT_RECURSIVE) > $excerpt_length) :
	$new_excerpt_words = explode(' ', $excerpt, $excerpt_length+1);
	array_pop($new_excerpt_words);
	$excerpt_text = implode(' ', $new_excerpt_words);
	$temp_content = strip_tags($excerpt_text, $tags);
	$short_content = preg_replace('`\[[^\]]*\]`','',$temp_content);
	$short_content .= ' ... <br /><a href="'.$post->guid .'" title="'.$post->post_title.'">'.$readmore.'</a>';
	endif;
	return $short_content;
}

// sets the continue reading more link
if (!function_exists('sp_continue_reading_link')) {
	function sp_continue_reading_link() {
		return ' <a href="'. get_permalink() . '">' . __( 'Read More &gt;', 'sp' ) . '</a>';
	}
}

if (!function_exists('sp_auto_excerpt_more')) {
	function sp_auto_excerpt_more( $more ) {
		return ' &hellip;' . sp_continue_reading_link();
	}
	add_filter( 'excerpt_more', 'sp_auto_excerpt_more' );
}

// shortens the previous and next post titles
if (!function_exists('sp_shorten_title_post_link')) {
	function sp_shorten_title_post_link($linkstring,$link) {
		$characters = 35;
		preg_match('/<a.*?>(.*?)<\/a>/is',$linkstring,$matches);
		$displayedTitle = preg_replace('/<span.*<\/span>/is','',$matches[1]);
		$newTitle = sp_shorten_with_ellipsis($displayedTitle,$characters);
		return str_replace('>'.$displayedTitle.'<','>'.$newTitle.'<',$linkstring);
	}
	add_filter('previous_post_link','sp_shorten_title_post_link',10,2);
	add_filter('next_post_link','sp_shorten_title_post_link',10,2);	
}

if (!function_exists('sp_shorten_with_ellipsis')) {
	function sp_shorten_with_ellipsis($inputstring,$characters) {
	  return (strlen($inputstring) >= $characters) ? substr($inputstring,0,($characters-3)) . '...' : $inputstring;
	}
}

if (!function_exists('sp_comment')) :

// function to manipulate the comments/pingbacks
function sp_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ($comment->comment_type) :
		case '' :
	?>
	<li <?php comment_class('group'); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment_container group">
		<div class="comment-author vcard">
			<?php echo get_avatar($comment, 60); ?><br />
			<?php printf( __('%s', 'sp'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'sp'); ?></em>
			<br />
		<?php endif; ?>
		<div class="comment_wrap group">
            <div class="comment-meta commentmetadata"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                <?php
                    /* translators: 1: date, 2: time */
                    printf( __('%1$s at %2$s', 'sp'), get_comment_date(), get_comment_time()); ?></a><?php edit_comment_link( __('(Edit)', 'sp'), ' ' );
                ?>
            </div><!-- .comment-meta .commentmetadata -->
    
            <div class="comment-body"><?php comment_text(); ?></div>
    
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </div><!--close comment_wrap-->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'sp' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'sp'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
} //end sp_comment function
endif;

if (!function_exists('sp_comment_products')) :

// function to manipulate the comments of products
function sp_comment_products($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class('group'); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment_container group">
		<div class="comment_wrap group">
            <div class="comment-body group">
                <div class="comment-author vcard">
                    <?php printf( __('%s', 'sp'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
                </div><!-- .comment-author .vcard -->
            
				<?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment_wait"><?php _e('Your comment is awaiting moderation.', 'sp'); ?></em>
                <?php endif; ?>
                
                <?php comment_text(); ?>
                <div class="comment-meta commentmetadata">
                    <?php
                        /* translators: 1: date, 2: time */
                        printf( __('%1$s | %2$s', 'sp'), get_comment_date(), get_comment_time()); ?><?php edit_comment_link( __('(Edit)', 'sp'), ' ' );
                    ?>
                </div><!-- .comment-meta .commentmetadata -->                
                
            </div><!--close comment-body-->
    
        </div><!--close comment_wrap-->
	</div><!-- #comment-##  -->

<?php } //end sp_comment_products function
endif;

// removes the default recent comments style
function sp_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'sp_remove_recent_comments_style' );

if ( ! function_exists( 'sp_posted_on' ) ) :
// function to manipulate the posted_on HTML
function sp_posted_on() {
	printf( __( '<span class="calendar-icon">&nbsp;</span>%1$s <span class="meta-sep">by</span> %2$s', 'sp' ),
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time pubdate datetime="%2$s" class="entry-date">%3$s</time></a>',
			get_permalink(),
			get_the_date('c'),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'sp' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'sp_posted_in' ) ) :
// function to manipulate the posted_in HTML
function sp_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( '<span class="article-icon">&nbsp;</span>Posted in %1$s <span class="tag-icon">&nbsp;</span>Tags %2$s. <span class="bookmark-icon">&nbsp;</span>Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'sp' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( '<span class="article-icon">&nbsp;</span>Posted in %1$s. <span class="bookmark-icon">&nbsp;</span>Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'sp' );
	} else {
		$posted_in = __( '<span class="bookmark-icon">&nbsp;</span>Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>', 'sp' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

if ( ! function_exists( 'sp_posted_in_list' ) ) :
// function to manipulate the posted_in HTML
function sp_posted_in_list() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( '<p><span class="article-icon">&nbsp;</span>%1$s</p><p><span class="tag-icon">&nbsp;</span>%2$s.</p>', 'sp' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( '<p><span class="article-icon">&nbsp;</span>%1$s.</p>', 'sp' );
	} else {
		$posted_in = __( '<p><span class="bookmark-icon">&nbsp;</span>Bookmark the <a href="%3$s" title="Permalink to %4$s" data-rel="bookmark">permalink</a></p>', 'sp' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


if (!function_exists('sp_related_post')) :
function sp_related_post() {
	global $post;
	$image_width = sp_get_theme_init_setting('post_related_image_size','width'); 
	$image_height = sp_get_theme_init_setting('post_related_image_size','height');
	
	$tags = wp_get_post_tags($post->ID);
	$tagIDs = array();
	if ($tags) {
		$tagcount = count($tags);
    	for ($i = 0; $i < $tagcount; $i++) {
      	$tagIDs[$i] = $tags[$i]->term_id;
    	}		
		$args = array(
			'tag__in' => $tagIDs,
			'post__not_in' => array($post->ID),
			'showposts' => 4,
			'ignore_sticky_posts' => 1
		);
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {
			if (sp_isset_option( 'related_post_text', 'isset' )) {
				$custom_text  = sp_isset_option( 'related_post_text', 'value' );
			} else {
				$custom_text = __( 'Related Posts', 'sp' );		
			}
			echo '<div class="related-post group"><h2>'.$custom_text.'</h2><ul>';
			while ($my_query->have_posts()) : $my_query->the_post(); 
				$post_image_url = sp_get_image( $post->ID );
				if (has_post_thumbnail() && $post_image_url) {
			?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="post-image-link"> <?php echo get_the_post_thumbnail( $post->ID, array($image_width,$image_height), array( 'class' => 'wp-post-image' ) ); ?><?php the_title(); ?></a></li>
			<?php
				} else { ?>
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>" class="post-image-link"><img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo get_template_directory_uri().'/images/no-product-image.jpg'; ?>" /><?php the_title(); ?></a></li>
				<?php
                }
			endwhile;
			echo '</ul></div>';
			wp_reset_postdata();
		}
	}
}
endif;

// function that displays the top next/previous nav links
if (!function_exists('sp_content_nav')) {
	function sp_content_nav( $nav_id ) {
		global $wp_query;
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo $nav_id; ?>" class="navigation group">
                <div class="nav-previous"><?php next_posts_link( __( '<span class="arrow">&lt;</span> Older posts', 'sp' ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="arrow">&gt;</span>', 'sp' ) ); ?></div>    
			</nav><!-- #nav -->
	<?php	
		endif;
	}
}

?>