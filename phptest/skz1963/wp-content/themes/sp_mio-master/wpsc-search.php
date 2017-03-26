<?php
$image_width = get_option('product_image_width');
$image_height = get_option('product_image_height');
$cat_image_width = sp_get_theme_init_setting('wpec_product_category_size', 'width');
$cat_image_height = sp_get_theme_init_setting('wpec_product_category_size', 'height');

?>
<div id="grid_view_products_page_container">

	<div class="product_grid_display group">
		<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
			<div class="product_grid_item product_view_<?php echo wpsc_the_product_id(); ?>">
					<div class="item_image">
				<?php if(wpsc_the_product_thumbnail()) :?> 	   
						<a title="<?php echo wpsc_the_product_title(); ?>" href="<?php echo wpsc_the_product_permalink(); ?>">
						 <?php echo get_the_post_thumbnail( wpsc_the_product_id(), array($image_width,$image_height), array( 'class' => 'product_image' ) ); ?>
						</a>
                            <?php if (wpsc_product_on_special() && sp_isset_option( 'saletag', 'boolean', 'true' )) : ?>
                            <span class="saletag"></span>
                            <?php endif; ?>
                        
				<?php else: ?> 
						<a title="<?php echo wpsc_the_product_title(); ?>" href="<?php echo wpsc_the_product_permalink(); ?>">
						<img class="no-image" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo  get_template_directory_uri() . '/images/no-product-image.jpg'; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
						</a>
                            <?php if (wpsc_product_on_special() && sp_isset_option( 'saletag', 'boolean', 'true' )) : ?>
                            <span class="saletag"></span>
                            <?php endif; ?>
				<?php endif; ?> 
					</div><!--close item_image-->
				<?php if(get_option('show_images_only') != 1): ?>
					<div class="grid_product_info">
							<h2 class="prodtitle"><a href="<?php echo wpsc_the_product_permalink(); ?>" title="<?php echo wpsc_the_product_title(); ?>"><?php echo wpsc_the_product_title(); ?></a></h2>
						<?php if((wpsc_the_product_description() != '') && (get_option('display_description') == 1)): ?>
							<div class="grid_description"><?php echo do_shortcode(wpsc_the_product_description()); ?></div>
						<?php endif; ?>
                        	<div class="price_container">
							<?php if(wpsc_product_on_special()) : ?>
										<p class="pricedisplay <?php echo wpsc_the_product_id(); ?>"><span class="oldprice"><?php echo wpsc_product_normal_price(); ?></span></p>
									<?php endif; ?>
									<p class="pricedisplay <?php echo wpsc_the_product_id(); ?>"><span class="currentprice"><?php echo wpsc_the_product_price(); ?></span></p>
							</div><!--close price_container-->
						<?php if(get_option('display_moredetails') == 1) : ?>
							<a href="<?php echo wpsc_the_product_permalink(); ?>" class="more_details"><?php _e('More Details', 'sp'); ?></a>
						<?php endif; ?> 
					</div><!--close grid_product_info-->
					<div class="grid_more_info">
						<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
							<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
						<?php else: ?>
						<?php $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' ); ?>					
						<?php endif; ?>					                    
						<form class="product_form_ajax"  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" >
                        <?php do_action ( 'wpsc_product_form_fields_begin' ); ?>
							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action" />
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id" />
							
								<?php if((get_option('display_addtocart') == 1) && (get_option('addtocart_or_buynow') !='1')) : ?> 	   
								<?php if(wpsc_product_has_stock()) : ?>
									<div class="wpsc_buy_button_container group">                                
                                            <?php if (wpsc_have_variation_groups()) { ?>
                                            	<?php if(get_option('display_variations') != '1') : ?>
                                            	<a href="<?php echo wpsc_the_product_permalink(); ?>" title="<?php _e('More Info', 'sp'); ?>" class="more"><span><?php _e('More Info', 'sp'); ?></span></a>
                                                <?php endif; ?>
											<?php } else { ?>
												<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
                                                <?php $action = wpsc_product_external_link( wpsc_the_product_id() ); ?>
                                                <div class="input-button-buy"><span><input class="wpsc_buy_button external-purchase" type="submit" value="<?php echo wpsc_product_external_link_text( wpsc_the_product_id(), __( 'Buy Now', 'sp' ) ); ?>" data-external-link="<?php echo $action; ?>" data-link-target="<?php echo wpsc_product_external_link_target( wpsc_the_product_id() ); ?>" /></span></div>
                                                <?php else: ?>
                                            <div class="input-button-buy"><span><input type="submit" value="<?php _e('Add To Cart', 'sp'); ?>" name="Buy" class="wpsc_buy_button" /></span>							
                                            <div class="alert error"><p><?php _e('Please select product options before adding to cart','sp'); ?></p><span>&nbsp;</span></div>
                                            <div class="alert addtocart"><p><?php _e('Item has been added to your cart!','sp'); ?></p><span>&nbsp;</span></div>
                                            </div><!--close input-button-buy-->
                                            <?php endif; ?>
                                            <div class="wpsc_loading_animation">
                                                <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
                                            </div><!--close wpsc_loading_animation-->                                        
                                            
                                    	<?php } // close have variation ?>
                                        
									</div><!--close wpsc_buy_button_container-->                                                                                                                                                             
								<?php else : ?>
									<p class="soldout"><?php _e('Sorry, sold out!', 'sp'); ?></p>
								<?php endif; ?>
							<?php endif; ?>
						<?php do_action ( 'wpsc_product_form_fields_end' ); ?>
                    </form>                    
					</div><!--close grid_more_info-->
					
					<?php if((get_option('display_addtocart') == 1) && (get_option('addtocart_or_buynow') == '1')) :?> 	  
						<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
					<?php endif ; ?>
					
				<?php endif; ?> 				
			</div><!--close product_grid_item-->
		<?php endwhile; ?>
	</div><!--close product_grid_display-->
	<?php echo sp_fancy_notifications(); ?>	
</div><!--close grid_view_products_page_container-->