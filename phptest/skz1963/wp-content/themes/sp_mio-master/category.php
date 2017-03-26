<?php 
get_header(); 

// get the current page layout settings (array)
$layout = sp_page_layout();
?>		
<?php dynamic_sidebar( 'page-top-widget' ); ?>
<div id="container" class="group <?php echo $layout['orientation']; ?>">
			<?php
			if ($layout['sidebar_left']) {
				get_sidebar('left');	
			} ?>

			<div id="content" role="main">
				<?php if ( have_posts() ) : ?>
                	<header class="page-header">
                        <h1 class="page-title"><?php
                            printf( __( 'Category: %s', 'sp' ), '<span>' . single_cat_title( '', false ) . '</span>' );
                        ?></h1>
                        <?php
                            $category_description = category_description();
                            if ( ! empty( $category_description ) )
                                echo apply_filters( 'category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>' );
                        ?>
                    </header>
                    <?php
					$pagination = sp_isset_option( 'blog_pagination', 'value' );
					if (isset($pagination) && $pagination == 'next-previous') {
						sp_content_nav('nav-above');
					} elseif (isset($pagination) && $pagination == 'numeration') {
						sp_pagination();
					} elseif (isset($pagination) && $pagination == 'no-pagination') {
						
					} else {
						sp_content_nav('nav-above');
					} ?>
                    <div class="blog-article-wrap">
					<?php
    				while ( have_posts() ) : the_post();
                    	get_template_part( 'content', get_post_format() );
					endwhile; ?>
                    </div><!--close blog-article-wrap-->
					<?php
					if (isset($pagination) && $pagination == 'next-previous') {
						sp_content_nav('nav-below');
					} elseif (isset($pagination) && $pagination == 'numeration') {
						sp_pagination();
					} elseif (isset($pagination) && $pagination == 'no-pagination') {
												
					} else {
						sp_content_nav('nav-below');
					}
                    ?>
                    <div class="btt group"><a href="#top" title="<?php _e( 'Back to Top', 'sp' ); ?>"><?php _e( 'Back to Top', 'sp' ); ?> &uarr;</a></div>
				<?php else : ?>
                    <article id="post-0" class="post no-results not-found">
                        <header class="entry-header">
                            <h1 class="entry-title"><?php _e( 'Nothing Found', 'sp' ); ?></h1>
                        </header><!-- .entry-header -->
    
                        <div class="entry-content">
                            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'sp' ); ?></p>
                        </div><!-- .entry-content -->
                    </article><!-- #post-0 -->
                <?php endif; ?>
            </div><!-- #content -->
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
		</div><!-- #container -->
		<?php dynamic_sidebar( 'page-bottom-widget' ); ?>
<?php get_footer(); ?>
