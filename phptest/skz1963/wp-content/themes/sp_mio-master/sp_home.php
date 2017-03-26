<?php 
/* Template Name: SP Home */
get_header(); 
?>
<?php
global $post;

	$image_width = '500';
	$image_height = '500';
	
?>
			<div id="content" role="main" class="home group">
            	<div id="slides">
<?php if (class_exists( 'WP_eCommerce' )) : ?>
		<?php if (sp_isset_option( 'homepage_product1', 'isset' ) || sp_isset_option( 'homepage_product2', 'isset' ) || sp_isset_option( 'homepage_product3', 'isset' )) { 
				$count = 0;
				for ( $i = 1; $i <= 3; $i++ ) {
					if ( sp_isset_option( 'homepage_product' . $i, 'value' ) == '0' ) 
						continue;

					$product_id = sp_isset_option( 'homepage_product' . $i, 'value' );	
					$product_obj = get_post( $product_id );
					$product_description = sp_truncate(do_shortcode($product_obj->post_content), ( sp_isset_option( 'homepage_slider_description_characters', 'isset' ) ? sp_isset_option( 'homepage_slider_description_characters', 'value' ) : '' ), ( sp_isset_option( 'homepage_slider_description_denote', 'isset' ) ? sp_isset_option( 'homepage_slider_description_denote', 'value' ) : ''), true, true);
          $product_description = strip_tags( $product_description, '<p><a><ul><li><strong><br><em><small><div>' );
					$product_title = $product_obj->post_title;
		?>
                	<div class="slide">
                        <div class="product_description">
                            <h2><?php _e($product_title,'sp'); ?></h2>
                            <p><?php echo $product_description; ?></p>
                            <p><a href="<?php echo get_permalink($product_id); ?>" title="<?php esc_attr_e("More Info",'sp'); ?>"><?php _e("More Info",'sp'); ?> &gt;</a></p>
                        </div><!--close product_description-->
                        
                        <div class="featured_image">
                        <?php
							$post_image_url = sp_get_image( $product_id );
							if ( $post_image_url ) {
						?>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>">
                                 <?php echo get_the_post_thumbnail( $product_id, array($image_width,$image_height), array( 'class' => '' ) ); ?>
                                </a>
                        <?php } else { ?>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>"><img src="<?php echo get_template_directory_uri() . '/images/no-product-image.jpg'; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" alt="<?php _e($product_title,'sp'); ?>" /></a>
                        <?php } ?>
                        </div><!--close featured_image-->
                        
                        <div class="product_meta">
                            <div class="price">
                                <p><?php echo sp_wpsc_the_product_price(false,$product_id); ?></p>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>" class="buynow"><span><?php _e("Buy Now",'sp'); ?></span></a>
                            </div><!--close price-->	
                        </div><!--close product_meta-->
                	</div><!--close slide-->
                    <?php $count++; ?>
              <?php } // end for loop ?>
              </div><!--close slides-->
              <ul id="slide_menu" class="group">
              	  <?php $product1_id = sp_isset_option( 'homepage_product1', 'value' );
				  		$product2_id = sp_isset_option( 'homepage_product2', 'value' );
						$product3_id = sp_isset_option( 'homepage_product3', 'value' );
				
                  if ($product1_id != '' && $product1_id != 0) : ?>
                  <li class="menuItem act"><a href="#" title="1">1</a></li>
                  <?php endif; ?>
                  <?php if ($product2_id != '' && $product2_id != 0) : ?>
                  <li class="menuItem"><a href="#" title="2">2</a></li>
                  <?php endif; ?>
                  <?php if ($product3_id != '' && $product3_id != 0) : ?>
                  <li class="menuItem"><a href="#" title="3">3</a></li>
                  <?php endif; ?>
              </ul>
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_interval', 'value' ); ?>" class="homepage_slider_interval" />                           
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_effects', 'value' ); ?>" class="homepage_slider_effects" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_transition', 'value' ); ?>" class="homepage_slider_transition" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_easing', 'value' ); ?>" class="homepage_slider_easing" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_pause', 'value' ); ?>" class="homepage_slider_pause" />   
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_direction', 'value' ); ?>" class="homepage_slider_direction" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_touchswipe', 'value' ); ?>" class="homepage_slider_touchswipe" />
                    
		<?php } // end product is set ?>
<?php endif; // end WPEC check ?>

<?php if (class_exists( 'woocommerce' )) : ?>
		<?php if (sp_isset_option( 'homepage_product1', 'isset' ) || sp_isset_option( 'homepage_product2', 'isset' ) || sp_isset_option( 'homepage_product3', 'isset' )) { 
				$count = 0;
				for ( $i = 1; $i <= 3; $i++ ) {
					if ( sp_isset_option( 'homepage_product' . $i, 'value' ) == '0' ) 
						continue;

					$product_id = sp_isset_option( 'homepage_product' . $i, 'value' );	
					$product_obj = get_post( $product_id );
					$product_description = sp_truncate(do_shortcode($product_obj->post_content), ( sp_isset_option( 'homepage_slider_description_characters', 'isset' ) ? sp_isset_option( 'homepage_slider_description_characters', 'value' ) : '' ), ( sp_isset_option( 'homepage_slider_description_denote', 'isset' ) ? sp_isset_option( 'homepage_slider_description_denote', 'value' ) : ''), true, true);
					$product_title = $product_obj->post_title;
          // if 2.0+
          if ( function_exists( 'get_product' ) ) 
              $product = get_product( $product_id );
          else
              $product = new WC_Product( $product_id );	
		?>
                	<div class="slide">
                        <div class="product_description">
                            <h2><?php _e($product_title,'sp'); ?></h2>
                            <p><?php echo $product_description; ?></p>
                            <p><a href="<?php echo get_permalink($product_id); ?>" title="<?php esc_attr_e("More Info",'sp'); ?>"><?php _e("More Info",'sp'); ?> &gt;</a></p>
                        </div><!--close product_description-->
                        
                        <div class="featured_image">
                        <?php
							$post_image_url = sp_get_image( $product_id );
							if ( $post_image_url ) {
						?>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>">
                                
                                <?php echo get_the_post_thumbnail( $product_id, array($image_width,$image_height), array( 'class' => '' ) ); ?>
                                </a>
                        <?php } else { ?>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>"><img src="<?php echo get_template_directory_uri() . '/images/no-product-image.jpg'; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" alt="<?php _e($product_title,'sp'); ?>" /></a>
                        <?php } ?>
                        </div><!--close featured_image-->
                        
                        <div class="product_meta">
                            <div class="price">
                                <p><?php echo $product->get_price_html(); ?></p>
                                <a href="<?php echo get_permalink($product_id); ?>" title="<?php _e("Buy Now",'sp'); ?>" class="buynow"><span><?php _e("Buy Now",'sp'); ?></span></a>
                            </div><!--close price-->	
                        </div><!--close product_meta-->
                	</div><!--close slide-->
                    <?php $count++; ?>
              <?php } // end for loop ?>
              </div><!--close slides-->
              <ul id="slide_menu" class="group">
              	  <?php $product1_id = sp_isset_option( 'homepage_product1', 'value' );
				  		$product2_id = sp_isset_option( 'homepage_product2', 'value' );
						$product3_id = sp_isset_option( 'homepage_product3', 'value' );
				
                  if ($product1_id != '' && $product1_id != 0) : ?>
                  <li class="menuItem act"><a href="#" title="1">1</a></li>
                  <?php endif; ?>
                  <?php if ($product2_id != '' && $product2_id != 0) : ?>
                  <li class="menuItem"><a href="#" title="2">2</a></li>
                  <?php endif; ?>
                  <?php if ($product3_id != '' && $product3_id != 0) : ?>
                  <li class="menuItem"><a href="#" title="3">3</a></li>
                  <?php endif; ?>
              </ul>
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_interval', 'value' ); ?>" class="homepage_slider_interval" />                           
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_effects', 'value' ); ?>" class="homepage_slider_effects" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_transition', 'value' ); ?>" class="homepage_slider_transition" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_easing', 'value' ); ?>" class="homepage_slider_easing" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_pause', 'value' ); ?>" class="homepage_slider_pause" />   
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_direction', 'value' ); ?>" class="homepage_slider_direction" /> 
                    <input type="hidden" value="<?php echo sp_isset_option( 'homepage_slider_touchswipe', 'value' ); ?>" class="homepage_slider_touchswipe" />
                    
		<?php } // end product is set ?>
                                    
<?php endif; //end WOO check ?>                                    

</div><!--close content-->

<?php get_footer(); ?>