<?php 
get_header(); 
// get the current page layout settings (array)
$layout = sp_page_layout();

if ( class_exists( 'WP_eCommerce' ) && $_GET['post_type'] == 'wpsc-product' ) {
  $posts_per_page = sp_isset_option( 'wpec_search_ppp', value );
  if ( empty( $posts_per_page ) ) $posts_per_page = -1;
  $posts=query_posts($query_string . '&posts_per_page=' . $posts_per_page );
}
?>
		<div id="container" class="group <?php echo $layout['orientation']; ?>">
			<?php
			if ($layout['sidebar_left']) {
				get_sidebar('left');	
			} ?>
        
			<div id="content" role="main">
<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'sp' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                </header>
				  <?php
                  if ( $_GET['post_type'] == 'wpsc-product' && is_search() ) {       
                      sp_pagination();      
                      get_template_part( 'wpsc', 'search' );
                      sp_pagination();                  } else { 
                      $pagination = sp_isset_option( 'blog_pagination', 'value' );
                      if (isset($pagination) && $pagination == 'next-previous') {
                          sp_content_nav('nav-above');
                      } elseif (isset($pagination) && $pagination == 'numeration') {
                          sp_pagination();
                      } elseif (isset($pagination) && $pagination == 'no-pagination') {
                          
                      } else {
                          sp_content_nav('nav-above');
                      } ?>             
                    <div class="article-wrap">
                    <?php
                    while ( have_posts() ) : the_post();
                      get_template_part( 'content', get_post_format() );
                    endwhile; 
                      if (isset($pagination) && $pagination == 'next-previous') {
                          sp_content_nav('nav-below');
                      } elseif (isset($pagination) && $pagination == 'numeration') {
                          sp_pagination();
                      } elseif (isset($pagination) && $pagination == 'no-pagination') {
                                                  
                      } else {
                          sp_content_nav('nav-below');
                      }
                    ?>
                    </div><!--close article-wrap-->
                    <?php
                  } ?>
<?php else : ?>
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'sp' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'sp' ); ?></p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->
<?php endif; ?>
			</div><!-- #content -->
            
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
        </div><!-- #container -->

<?php get_footer(); ?>
