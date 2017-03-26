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
            <?php if ( is_search() && $_GET['post_type'] === 'wpsc-product' && class_exists( 'WP_eCommerce' ) ) {
            	if ( !wpsc_have_products() ) { ?>
            		<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'sp' ); ?></p>
            	<?php
            	} else {
            		get_template_part( 'wpsc', 'search' );
            	}
            } else {
            ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
                
                <?php 
					if (!sp_is_store_page()) {
                        comments_template( '', true ); 	
					}
                ?>
            <?php endwhile; // end of the loop. 
        	}
            ?>
			</div><!-- #content -->
            
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
        </div><!-- #container -->
		<?php dynamic_sidebar( 'page-bottom-widget' ); ?>
<?php get_footer(); ?>
