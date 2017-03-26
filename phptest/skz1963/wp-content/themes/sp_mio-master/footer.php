<?php 
	$image_width = '205';
	$image_height = '120';
?>
	</div><!--close content_container-->

	<footer role="contentinfo" id="footer">
    	<section class="footer_blog">
        	<p><?php _e("Latest Blog Post:",'sp'); ?> <?php get_template_part( 'content', 'footer-loop' ); ?></p>
        </section>
        
        <section class="footer_featured">
        	<?php 
if (class_exists('WP_eCommerce')) {
			  $featured_products = get_posts( array(
				  'post_type'   => 'wpsc-product',
				  'numberposts' => 4, 
				  'orderby'       => 'rand',
			  ) );
			  $output = '';
				
			  if ( count( $featured_products ) > 0 ) {
				  $output .= '<ul class="group">';		
				  foreach ( $featured_products as $product ) {
					  $dots = '';
					  $output .= '<li>';
					  if (strlen(stripslashes( $product->post_title )) >= 26) { $dots = '...'; } 
					  $output .= '<a href="' . wpsc_product_url( $product->ID, null ) . '" title="'.stripslashes( $product->post_title ).'">'.substr(stripslashes( $product->post_title ),0,25).$dots.'</a>';
					  
					  // Thumbnails, if required
						  $output .= '<a href="' . wpsc_product_url( $product->ID, null ) . '">';

						  // context was footer_menu for timthumb
						  $output .= '<img src="'. sp_get_image( $product->ID) .'" title="' . $product->post_title . '" alt="' . $product->post_title . '" width="'.$image_width.'" " height="'.$image_height.'" />';
						  $output .= '</a>/';

					  $output .= '<a href="' . wpsc_product_url( $product->ID, null ) . '" class="more" title="'.__("More Details",'sp').'">'.__("More Details",'sp').' &gt;</a>';
					  $output .= '</li>';
				  }
				  $output .= "</ul>";
			  }
			  echo $output;
} // END WPEC check ?>

<?php if (class_exists('woocommerce')) {
			  $featured_products = get_posts( array(
				  'post_type'   => 'product',
				  'numberposts' => 4, 
				  'orderby'       => 'rand',
				  'post_status'  => 'publish'
			  ) );
			  $output = '';
				
			  if ( count( $featured_products ) > 0 ) {
				  $output .= '<ul class="group">';		
				  foreach ( $featured_products as $product ) {
					  $dots = '';
					  $output .= '<li>';
						  if (strlen(stripslashes( $product->post_title )) >= 26) { $dots = '...'; } 
						  $output .= '<a href="' . get_permalink( $product->ID ) . '" title="'.stripslashes( $product->post_title ).'">'.substr(stripslashes( $product->post_title ),0,25).$dots.'</a>';
						  
						  // Thumbnails, if required
						  $output .= '<a href="' . get_permalink( $product->ID ) . '">';
	
						  // context was footer_menu for timthumb
						  $output .= '<img src="'. sp_get_image( $product->ID) .'" title="' . $product->post_title . '" alt="' . $product->post_title . '" width="'.$image_width.'" " height="'.$image_height.'" />';		
						  $output .= '</a>';
	
						  $output .= '<a href="' . get_permalink( $product->ID ) . '" class="more" title="'.__("More Details",'sp').'">'.__("More Details",'sp').' &gt;</a>';
					  $output .= '</li>';
				  }
				  $output .= "</ul>";
			  }
			  echo $output;
} // END WOO check ?>
        </section>
        <?php if ( sp_isset_option( 'footer_widget', 'isset' ) && sp_isset_option( 'footer_widget', 'value' ) != '0') { ?>
        <section id="footer-widget" class="group">
					  <?php
					  // sets an array with the number of columns to output
					  $columns = array('4' 	=> array('footer-col col4','footer-col col4','footer-col col4','footer-col col4'),
										 '3'	=> array('footer-col col3','footer-col col3','footer-col col3'),
										 '2' 	=> array('footer-col col2','footer-col col2'),
										 '1' 	=> array('') );
					  $i = 0;
						if (is_array($columns[sp_isset_option( 'footer_widget', 'value' )])) {
						foreach($columns[sp_isset_option( 'footer_widget', 'value' )] as $col): 
						
								 $i++;
								 if($i == 1){ 
									  $class = "first"; 
								 } else {
									  $class = "";	
								 }
							?>
							<div class="<?php echo $col;?> <?php echo $class; ?>">
								 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-widget-'.$i) ) ?>
							</div>
					  <?php endforeach; 
						}
					  ?>
        </section><!--close footer-widget-->
        <?php } ?>
       	<section id="footer_nav" class="group">
            <!--SOCIAL MEDIA-->
            <?php if (sp_isset_option( 'facebook_enable', 'boolean', 'true') || sp_isset_option( 'twitter_enable', 'boolean', 'true') || sp_isset_option( 'flickr_enable', 'boolean', 'true') || sp_isset_option( 'rss_enable', 'boolean', 'true') || sp_isset_option( 'gplus_enable', 'boolean', 'true') || sp_isset_option( 'pinterest_enable', 'boolean', 'true') || sp_isset_option( 'youtube_enable', 'boolean', 'true')) 			
			{ ?>
            <div id="social-media" class="group">
					<?php sp_social_media_script(); ?>
            </div><!--close social-media-->
            <?php } ?>
            <!--END SOCIAL MEDIA-->  
        
            <p>
                    <?php if (sp_isset_option( 'footer_copyright', 'isset' ) && sp_isset_option( 'footer_copyright', 'value' ) != '') : ?>
                        <?php echo sp_isset_option( 'footer_copyright', 'value' ); ?>
                    <?php endif; ?>
        	</p>

        	<?php wp_nav_menu( array('container' => 'nav', 'container_id' => 'footer-nav', 'fallback_cb' => 'footer_menu', 'theme_location' => 'footer', 'depth' => 1 ) ); ?>
        </section>
			<!--start lightbox hidden values-->
            <input type="hidden" value="<?php echo sp_isset_option( 'lightbox_social', 'value' ); ?>" id="lightbox_social" />
            <input type="hidden" value="<?php echo sp_isset_option( 'lightbox_theme', 'value' ); ?>" id="lightbox_theme" />
            <input type="hidden" value="<?php echo sp_isset_option( 'lightbox_slideshow', 'value' ); ?>" id="lightbox_slideshow" />
            <input type="hidden" value="<?php echo sp_isset_option( 'lightbox_show_overlay_gallery', 'value' ); ?>" id="lightbox_show_overlay_gallery" />
            <input type="hidden" value="<?php echo sp_isset_option( 'lightbox_title', 'value' ); ?>" id="lightbox_title" />
            <input type="hidden" value="<?php echo sp_isset_option( 'variation_image_swap', 'value' ); ?>" id="variation_image_swap" />
            <!--end lightbox hidden values-->
            <input type="hidden" value="<?php echo sp_isset_option( 'tabs_start_collapsed', 'value' ); ?>" id="tabs_start_collapsed" />
            
	</footer>

</div><!--close wrapper-->
</div><!--close container-->
</div><!--close wrap_all-->
<?php dynamic_sidebar( 'site-bottom-widget' ); ?>
<?php wp_footer(); ?>

</body>
</html>
