<?php

$image_width = get_post_meta( $post->ID, '_sp_portfolio_single_width', true );
$image_height = get_post_meta( $post->ID, '_sp_portfolio_single_height', true );

if ( ! isset( $image_width ) || empty( $image_width ) )
	$image_width = sp_get_theme_init_setting('portfolio_single_size','width');

if ( ! isset( $image_height) || empty( $image_height ) )
	$image_height = sp_get_theme_init_setting('portfolio_single_size','height');
?>
      <?php 
	  		// get the image
			$post_image_url = sp_get_image( $post->ID );
			  if (has_post_thumbnail() && $post_image_url) { ?>
				  <div class="image-wrap">
				  <a href="<?php echo $post_image_url; ?>" data-rel="prettyPhoto[<?php echo $post->ID; ?>]" class="thickbox preview_link" title="<?php the_title_attribute(); ?>">
                  <?php echo get_the_post_thumbnail( $post->ID, array($image_width,$image_height), array( 'class' => 'wp-post-image portfolio-single-image' ) ); ?>
                  <span class="hover-icon">&nbsp;</span></a>	
				  </div><!--close image-wrap-->   
			  <?php } else { ?>
				  <div class="image-wrap no-image">
				  <a href="<?php echo $post_image_url[0]; ?>" data-rel="prettyPhoto[<?php echo $post->ID; ?>]" class="thickbox preview_link" title="<?php the_title_attribute(); ?>"><img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="wp-post-image portfolio-single-image" alt="<?php the_title_attribute(); ?>" src="<?php echo get_template_directory_uri().'/images/no-product-image.jpg'; ?>" /><span class="hover-icon">&nbsp;</span></a>	
				  </div><!--close image-wrap-->   
			  <?php } ?>
              <h1 class="entry-title"><?php the_title(); ?></h1>
              <div class="entry-content group">
              <?php the_content(); ?>
              </div><!--close entry-content-->