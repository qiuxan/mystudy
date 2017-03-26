<?php 
/* Template Name: One Column Page */
get_header(); ?>
		<?php dynamic_sidebar( 'page-top-widget' ); ?>
		<div id="container" class="group no-sidebars">
			<div id="content" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
                <?php 
					if (!sp_is_store_page()) {
                        comments_template( '', true ); 	
					}
                ?>
                
			<?php endwhile; ?>
			</div><!-- #content -->
            
        </div><!-- #container -->
		<?php dynamic_sidebar( 'page-bottom-widget' ); ?>
<?php get_footer(); ?>
