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
                    <?php
					sp_content_nav('nav-above');
    				while ( have_posts() ) : the_post();
                    	get_template_part( 'content', get_post_format() );
					endwhile;
					sp_content_nav('nav-below');
                    ?>
			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'sp' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'sp' ); ?></p>
						<?php get_search_form(); ?>
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
