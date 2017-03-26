<?php 
$image_width = get_option('thumbnail_size_w');
$image_height = get_option('thumbnail_size_h');
?>
    
		<article id="post-<?php the_ID(); ?>" <?php post_class('group list'); ?>>
			<div class="entry-meta">
                <div class="date_label">
                    <span class="month"><?php the_time('M'); ?></span>
                    <span class="date"><?php the_time('d'); ?></span>
                    <span class="year"><?php the_time('Y'); ?></span>
                </div><!--close date-->
                
			</div><!-- .entry-meta -->
        
			<?php  
			$post_image_url = sp_get_image( $post->ID );
			if (has_post_thumbnail() && $post_image_url ) { ?>
            <div class="header-meta group">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				 <?php // context was blog_list for timthumb 310x80
					echo get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => "wp-post-image $size", 'alt' => trim( strip_tags($attachment->post_title)), 'title' => trim( strip_tags($attachment->post_title)))); ?>
			<?php } else { ?>
            <div class="header-meta no-image group">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php } ?>
        	</a>
            <div class="title-container">
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                <p class="byauthor">by <?php the_author(); ?></p>
            </div><!--close title-container-->

			</div><!--close header-meta-->
        <?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
                <div class="entry-summary"><p>
                	<?php
					$count = get_post_meta($post->ID, '_sp_truncate_count', true);
					$denote = get_post_meta($post->ID, '_sp_truncate_denote', true);
					$disabled = get_post_meta($post->ID, '_sp_truncate_disabled', true);
					?>
                    <?php 
                    if ( $disabled != '1' )
					{                    					                    
                    echo sp_truncate(get_the_excerpt(), (!isset($count) || $count == null) ? 200 : $count, (!isset($denote) || $denote == null) ? '...' : $denote, get_post_meta($post->ID, '_sp_truncate_precision', true), true);
                    } else {
						the_excerpt();	
					}
                    ?>
                </p></div><!-- .entry-summary -->
        <?php else : ?>
                <div class="entry-content">
                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sp' ) ); ?>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sp' ), 'after' => '</div>' ) ); ?>
                </div><!-- .entry-content -->
        <?php endif; ?>
    
                <div class="entry-utility">
                    <?php if ( count( get_the_category() ) ) : ?>
                        <span class="cat-links">
                            <?php printf( __( '<span class="article-icon">&nbsp;</span><span class="%1$s">Posted in</span> %2$s', 'sp' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
                        </span>
                        <span class="meta-sep">|</span>
                    <?php endif; ?>
                    <?php
                        $tags_list = get_the_tag_list( '', ', ' );
                        if ( $tags_list ):
                    ?>
                        <span class="tag-links">
                            <?php printf( __( '<span class="tag-icon">&nbsp;</span><span class="%1$s">Tagged</span> %2$s', 'sp' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
                        </span>
                        <span class="meta-sep">|</span>
                    <?php endif; ?>
                    <span class="comments-link"><span class="comment-icon">&nbsp;</span><?php comments_popup_link( __( 'Leave a comment', 'sp' ), __( '1 Comment', 'sp' ), __( '% Comments', 'sp' ) ); ?></span>
                    <?php edit_post_link( __( 'Edit', 'sp' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
                </div><!-- .entry-utility -->
		</article><!-- #post-## -->        