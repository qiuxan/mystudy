<?php 
/* Template Name: Portfolio Template */
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
			<?php while ( have_posts() ) : the_post(); ?>
                <div class="entry-content">
                	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sp' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h2>
                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sp' ) ); ?>
            		<?php
					// get page meta
					$portfolio_cats = get_post_meta($post->ID, '_sp_portfolio_cats', true);
					$portfolio_columns = get_post_meta($post->ID, '_sp_portfolio_columns', true);
					$portfolio_postperpage = get_post_meta($post->ID, '_sp_portfolio_postperpage', true);
					$portfolio_sortable = get_post_meta($post->ID, '_sp_portfolio_sortable', true);
					$portfolio_show_title = get_post_meta($post->ID, '_sp_portfolio_show_title', true);
					$portfolio_show_excerpt = get_post_meta($post->ID, '_sp_portfolio_show_excerpt', true);
					$portfolio_gallery_only = get_post_meta($post->ID, '_sp_portfolio_gallery_only', true);

					// set a default 4 columns if no columns were set
					if (isset($portfolio_columns) && $portfolio_columns == '0') { 
						$portfolio_columns = 'one_fourth';
					} elseif (isset($portfolio_columns) && $portfolio_columns == '1') { 
						$portfolio_columns = 'onecolumn';
					} elseif (isset($portfolio_columns) && $portfolio_columns == '2') {
						$portfolio_columns = 'one_half';
					} elseif (isset($portfolio_columns) && $portfolio_columns == '3') {
						$portfolio_columns = 'one_third';
					} elseif (isset($portfolio_columns) && $portfolio_columns == '4') {
						$portfolio_columns = 'one_fourth';
					} else {
						$portfolio_columns = 'one_fourth';	
					}
					
					// build the portfolio entry argument
					$args = array (
						'post_type' => 'portfolio-entries',
						//'cat' => $portfolio_cats,
						'post_status' => 'publish',
						'numberposts' => '-1',
						'paged' => (get_query_var('paged')) ? get_query_var('paged') : 1,
						'posts_per_page' => $portfolio_postperpage,
						'tax_query' => array(
							array(
								'taxonomy' => 'portfolio_categories',
								'field' => 'id',
								'terms' => $portfolio_cats,
								'operator' => 'IN'
							)
						)
						
					);	
					$entries = new WP_Query($args); 
					
					if (isset($portfolio_sortable) && $portfolio_sortable == true) {
						// gather the unqiue tags from entries
						$tags = array();
						foreach ($entries->posts as $post) { $terms = wp_get_post_terms($post->ID, 'portfolio_tags'); foreach ($terms as $term) { $tags[] = $term->name; }}
						// remove any duplicates
						$filtered_tags = array_unique($tags);
						if (is_array($filtered_tags) && strlen((string)$filtered_tags)) {
							echo '<ul class="portfolio-sort group">'."\r\n";
								echo '<li><span class="divider">&nbsp;</span><a href="#" title="'.__('All', 'sp').'" data-filter="*" class="active">'.__('All', 'sp').'</a></li>'."\r\n";
							foreach ($filtered_tags as $tag) {
								echo '<li><span class="divider">&nbsp;</span><a href="#" title="'.$tag.'" data-filter=".'.$tag.'-sort">'.$tag.'</a></li>'."\r\n";	
							}
							echo '</ul>'."\r\n";
						}
					}
					?>
					<div id="portfolio-container">
					<?php while ( $entries->have_posts() ) : $entries->the_post(); 
						$terms = wp_get_post_terms($post->ID, 'portfolio_tags');
						$tags = '';
						foreach ($terms as $term) { $tags .= ' '.$term->name.'-sort'; }
						$post_image_url = sp_get_image($post->ID);
					?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('group portfolio-item '. $portfolio_columns . $tags); ?>>
                            <?php  
                            $image_width = get_post_meta( $post->ID, '_sp_portfolio_list_width', true );
                            $image_height = get_post_meta( $post->ID, '_sp_portfolio_list_height', true );

                            if ( ! isset( $image_width ) || empty( $image_width ) )
                            	$image_width = sp_get_theme_init_setting('portfolio_list_size','width');

                            if ( ! isset( $image_height ) || empty( $image_height ) )
                            	$image_height = sp_get_theme_init_setting('portfolio_list_size','height');
                       
						if (has_post_thumbnail() && $post_image_url) {
                            if ($portfolio_gallery_only) { ?>
                            
                            <div class="image-wrap gallery-only">
                            <a href="<?php echo $post_image_url; ?>" title="<?php the_title_attribute(); ?>" class="post-image-link thickbox preview_link" data-rel="prettyPhoto[<?php echo $post->ID; ?>]">
                            <?php echo get_the_post_thumbnail( $post->ID, array($image_width,$image_height), array( 'class' => 'wp-post-image' ) ); ?>	
                            <span class="hover-icon">&nbsp;</span></a>
                            </div><!--close image-wrap-->  
                             
                      <?php } else { ?>
                            
                            <div class="image-wrap">                            
                            <a href="<?php the_permalink(); ?>" title="<?php _e('Read More', 'sp'); ?>" class="post-image-link">
                            <?php echo get_the_post_thumbnail( $post->ID, array($image_width,$image_height), array( 'class' => 'wp-post-image' ) ); ?>	
                            <span class="hover-icon">&nbsp;</span></a>
                            </div><!--close image-wrap-->   
					  <?php } // end gallery only check ?> 
                                                       
                    <?php } else { ?>
                            <?php if ($portfolio_gallery_only) { ?>
                            
                            <div class="image-wrap no-image gallery-only">
                            <a href="<?php echo get_template_directory_uri(); ?>/images/no-product-image.jpg" title="<?php the_title_attribute(); ?>" class="post-image-link thickbox preview_link" data-rel="prettyPhoto[<?php echo $post->ID; ?>]">
                            <img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo get_template_directory_uri().'/images/no-product-image.jpg'; ?>" />	
                            <span class="hover-icon">&nbsp;</span></a>
                            </div><!--close image-wrap-->   
                            
                            <?php } else { ?>
                            
                            <div class="image-wrap no-image">
                            <a href="<?php the_permalink(); ?>" title="<?php _e('Read More', 'sp'); ?>" class="post-image-link">
                            <img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo get_template_directory_uri().'/images/no-product-image.jpg'; ?>" />	
                            <span class="hover-icon">&nbsp;</span></a>
                            </div><!--close image-wrap-->   
                            
                           <?php } // end gallery only check ?>  
                 <?php } ?>
                        	<?php if (isset($portfolio_show_title) && $portfolio_show_title) { ?>
                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sp' ), the_title_attribute( 'echo=0' ) ); ?>" data-rel="bookmark"><?php the_title(); ?></a></h2>
                            <?php } ?>
                        	<?php if (isset($portfolio_show_excerpt) && $portfolio_show_excerpt) { ?>
                            <p><?php the_excerpt(); ?></p>
                            <?php } ?>
                            
						</article>
					<?php endwhile;	?>
                    </div><!--close portfolio-->		
					<?php
					sp_pagination($entries->max_num_pages, 2, true);	
					?>
					<?php comments_template( '', true ); ?><?php  ?>
                </div><!-- .entry-content -->
				<?php 
                    wp_reset_query();
					wp_reset_postdata();
             	?>
            <?php endwhile; // end of the loop.  ?>
			</div><!-- #content -->
            
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
        </div><!-- #container -->
		<?php dynamic_sidebar( 'page-bottom-widget' ); ?>

<?php get_footer(); ?>
