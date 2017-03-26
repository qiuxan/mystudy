<?php 
get_header(); 
// get the current page layout settings (array)
$layout = sp_page_layout();
?>
		<div id="container" class="group <?php echo $layout['orientation']; ?>">
			<?php
			if ($layout['sidebar_left']) {
				get_sidebar('left');	
			} ?>
        
			<div id="content" role="main">
            
			<?php while ( have_posts() ) : the_post(); ?>
				<?php 
					if (get_post_type( $post->ID ) == 'portfolio-entries') { 
						get_template_part( 'content', 'portfolio-single' );	
					} else {
						get_template_part( 'content', 'single' ); 
					}
					?>
                <?php comments_template( '', true ); ?>
			<?php endwhile; ?>
			</div><!-- #content -->
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
        </div><!-- #container -->

<?php get_footer(); ?>
