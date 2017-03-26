<?php
	// Setup globals
	// @todo: Get these out of template
	global $wp_query;

	// Setup image width and height variables
	// @todo: Investigate if these are still needed here
	$image_width  = get_option( 'single_view_image_width' );
	$image_height = get_option( 'single_view_image_height' );
	
?>

<div id="single_product_page_container">
<?php 
$args = array( 
	'crumb-separator' => ''
);
wpsc_output_breadcrumbs($args); ?>
	<?php
		// Plugin hook for adding things to the top of the products page, like the live search
		do_action( 'wpsc_top_of_products_page' );
	?>
	
	<div class="single_product_display group">
<?php
		/**
		 * Start the product loop here.
		 * This is single products view, so there should be only one
		 */

		while ( wpsc_have_products() ) : wpsc_the_product(); ?>
					<div class="imagecol group">
                    
						<?php if ( wpsc_the_product_thumbnail() ) : ?>
								<a data-rel="prettyPhoto[<?php echo wpsc_the_product_id(); ?>]" class="product-image <?php echo wpsc_the_product_image_link_classes(); ?>" href="<?php echo wpsc_the_product_image(); ?>" title="<?php echo wpsc_the_product_title(); ?>" data-id="<?php echo wpsc_the_product_id(); ?>">
									<?php echo get_the_post_thumbnail( wpsc_the_product_id(), array($image_width,$image_height), array( 'class' => 'product_image' ) ); ?>
                        	<img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" class="load loading-<?php echo wpsc_the_product_id(); ?>" />                                
                                    
								</a>
								<?php 
						if ( sp_isset_option( 'show_gallery', 'boolean', 'true' ) ) :					
							echo sp_main_display_gallery(wpsc_the_product_id(), $image_height);
						endif;
								?>
						<?php else: ?>
								<a data-rel="prettyPhoto[<?php echo wpsc_the_product_id(); ?>]" class="product-image <?php echo wpsc_the_product_image_link_classes(); ?>" href="<?php echo get_template_directory_uri(); ?>/images/no-product-image.jpg" title="<?php echo wpsc_the_product_title(); ?>" data-id="<?php echo wpsc_the_product_id(); ?>">
									<img class="no-image" alt="No Image" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo get_template_directory_uri().'/images/no-product-image.jpg'; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
                        	<img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" class="load loading-<?php echo wpsc_the_product_id(); ?>" />                                
                                    
								</a>
						<?php endif; ?>
					</div><!--close imagecol-->

					<div class="productcol">			
						<?php							
							do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post);
							do_action('wpsc_product_addons', wpsc_the_product_id());
						?>
                        <h1 class="prodtitle"><?php the_title(); ?></h1>
						<div class="product_description">
							<?php echo sp_wpsc_the_product_description(); ?>
						</div><!--close product_description -->
						<?php do_action( 'wpsc_product_addons', wpsc_the_product_id() ); ?>		
						<?php
						/**
						 * Custom meta HTML and loop
						 */
						?>
                        <?php 
						// not really sure why this needs to be displayed....
						if (wpsc_have_custom_meta() && sp_isset_option( 'custom_meta_fields', 'boolean', 'true' )) : ?>
						<div class="custom_meta">
							<?php while ( wpsc_have_custom_meta() ) : wpsc_the_custom_meta(); ?>
								<strong><?php echo wpsc_custom_meta_name(); ?>: </strong><?php echo wpsc_custom_meta_value(); ?><br />
							<?php endwhile; ?>
						</div><!--close custom_meta-->
                        <?php endif; ?>
						<?php
						/**
						 * Form data
						 */
						?>
						
						<form class="product_form_ajax" enctype="multipart/form-data" action="<?php echo wpsc_this_page_url(); ?>" method="post" name="1">
                        	<?php do_action ( 'wpsc_product_form_fields_begin' ); ?>
							<?php if ( wpsc_product_has_personal_text() ) : ?>
								<fieldset class="custom_text">
									<p><span><?php _e( 'Personalize Your Product', 'sp' ); ?></span><br />
									<?php _e( 'Complete this form to include a personalized message.', 'sp' ); ?></p>
									<textarea cols='40' rows='3' name="custom_text"></textarea>
								</fieldset>
							<?php endif; ?>
						
							<?php if ( wpsc_product_has_supplied_file() ) : ?>

								<fieldset class="custom_file">
									<legend><?php _e( 'Upload a File', 'sp' ); ?></legend>
									<p><?php _e( 'Select a file from your computer to include with this purchase.', 'sp' ); ?></p>
									<input type="file" name="custom_file" />
								</fieldset>
							<?php endif; ?>	
						<?php /** the variation group HTML and loop */?>
                        <?php if (wpsc_have_variation_groups()) { ?>
                        <fieldset>
						<div class="wpsc_variation_forms">
                        	<table><?php $i = 1; ?>
							<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
                            	<?php if ($i&1) { ?><tr class="row<?php echo $i; ?>"><?php } ?>
								<td class="col"><label for="<?php echo wpsc_vargrp_form_id(); ?>"><?php echo wpsc_the_vargrp_name(); ?>:</label><br /><select class="wpsc_select_variation_ajax" name="variation[<?php echo wpsc_vargrp_id(); ?>]" id="<?php echo wpsc_vargrp_form_id(); ?>">
								<?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
									<option value="<?php echo wpsc_the_variation_id(); ?>" <?php echo wpsc_the_variation_out_of_stock(); ?>><?php echo wpsc_the_variation_name(); ?></option>
								<?php endwhile; ?>
								</select></td>
                                <?php if (!$i&1) { ?></tr><?php } ?> 
                                <?php $i++; ?>
							<?php endwhile; ?>
                            </table>
						</div><!--close wpsc_variation_forms-->
                        </fieldset>
						<?php } ?>
						<?php /** the variation group HTML and loop ends here */?>

							<?php
							/**
							 * Quantity options - MUST be enabled in Admin Settings
							 */
							?>
							<?php if(wpsc_has_multi_adding()): ?>
                            	<fieldset class="wpsc_quantity_update">
								<ul>
                                <li>
                                <label><?php _e('Quantity', 'sp'); ?></label>
								<input type="text" id="wpsc_quantity_update_<?php echo wpsc_the_product_id(); ?>" name="wpsc_quantity_update" size="2" value="1" />
								<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>"/>
								<input type="hidden" name="wpsc_update_quantity" value="true" />
                                </li>
                                </ul><!--close wpsc_quantity_update-->
                                </fieldset>
							<?php endif ;?>
							<div class="wpsc_product_price">
								<?php if(!wpsc_product_has_stock()) : ?>
									<div class="out_of_stock stock_display_<?php echo wpsc_the_product_id(); ?>"><?php _e('Product not in stock', 'sp'); ?></div>
								<?php endif; ?>
								<?php if(wpsc_product_is_donation()) : ?>
									<label for="donation_price_<?php echo wpsc_the_product_id(); ?>"><?php _e('Donation', 'sp'); ?>:</label>
									<input type="text" class="donation_price_<?php echo wpsc_the_product_id(); ?>" name="donation_price" value="<?php echo wpsc_calculate_price(wpsc_the_product_id()); ?>" size="6" />
								<?php else : ?>
									<?php if(wpsc_product_on_special()) : ?>
										<p class="pricedisplay specialprice <?php echo wpsc_the_product_id(); ?>"><span class="oldprice old_product_price_<?php echo wpsc_the_product_id(); ?>"><?php echo wpsc_product_normal_price(); ?></span></p>
									<?php endif; ?>
									<p class="pricedisplay normalprice <?php echo wpsc_the_product_id(); ?>"><span class="currentprice pricedisplay product_price_<?php echo wpsc_the_product_id(); ?>"><?php echo wpsc_the_product_price(); ?></span></p>
									 <!-- multi currency code -->
                                    <?php if(wpsc_product_has_multicurrency()) : ?>
	                                    <?php echo wpsc_display_product_multicurrency(); ?>
                                    <?php endif; ?>
								<?php endif; ?>
							</div><!--close wpsc_product_price-->
							<?php
								//if ( function_exists( 'wpsc_akst_share_link' ) && ( get_option( 'wpsc_share_this' ) == 1 ) )
									//echo wpsc_akst_share_link('return');
							?>

							<input type="hidden" value="add_to_cart" name="wpsc_ajax_action" />
							<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id" />					
							<?php if( wpsc_product_is_customisable() ) : ?>
								<input type="hidden" value="true" name="is_customisable"/>
							<?php endif; ?>
					
							<?php
							/**
							 * Cart Options
							 */
							?>

							<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
								<?php if(wpsc_product_has_stock()) : ?>
									<div class="wpsc_buy_button_container group">
											<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
											<?php $action = wpsc_product_external_link( wpsc_the_product_id() ); ?>
											<div class="input-button-buy"><span><input class="wpsc_buy_button external-purchase" type="submit" value="<?php echo wpsc_product_external_link_text( wpsc_the_product_id(), __( 'Buy Now', 'sp' ) ); ?>" data-external-link="<?php echo $action; ?>" data-link-target="<?php echo wpsc_product_external_link_target( wpsc_the_product_id() ); ?>" /></span></div><!--close input-button-buy-->
								<?php else: ?>
										<div class="input-button-buy"><span><input type="submit" value="<?php _e('Add To Cart', 'sp'); ?>" name="Buy" class="wpsc_buy_button" /></span>
                                        <div class="alert error"><p><?php _e('Please select product options before adding to cart','sp'); ?></p><span>&nbsp;</span></div>
                                        <div class="alert addtocart"><p><?php _e('Item has been added to your cart!','sp'); ?></p><span>&nbsp;</span></div>                                                                                                                     
                                        </div><!--close input-button-buy-->
										<div class="wpsc_loading_animation">
											<img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
											
										</div><!--close wpsc_loading_animation-->                                        
								<?php endif; ?>
                                            
									</div><!--close wpsc_buy_button_container-->
								<?php else : ?>
									<p class="soldout"><?php _e('This product has sold out.', 'sp'); ?></p>
								<?php endif ; ?>
							<?php endif ; ?>
                            <?php do_action ( 'wpsc_product_form_fields_end' ); ?>
						</form><!--close product_form-->
						
						<?php
							if ( (get_option( 'hide_addtocart_button' ) == 0 ) && ( get_option( 'addtocart_or_buynow' ) == '1' ) )
								echo wpsc_buy_now_button( wpsc_the_product_id() ); 
						?>
                        <ul class="social group">
						<?php if (sp_isset_option( 'gplusone_button', 'boolean', 'true' )) : ?>
                        <li>
						
                              <?php if (sp_isset_option( 'gplusone_counter', 'value' ) == '' || ! sp_isset_option( 'gplusone_counter', 'isset' )) {
                                    $counter = 'false';	
                                } else {
                                    $counter = 'true';	
                                }
                            echo sp_gplusonebutton_shortcode(array('url' => 'post','size' => sp_isset_option( 'gplusone_size', 'value' ), 'count' => $counter)); ?>
						</li>
                        <?php endif; ?>
					
							<!--sharethis-->
							<?php if ( get_option( 'wpsc_share_this' ) == 1 ): ?>
                        <li>    
							<div class="st_sharethis" displayText="ShareThis"></div>
                        </li>
							<?php endif; ?>
							<!--end sharethis-->
						<?php if(wpsc_show_fb_like()): ?>
                        <li>                                                
	                        <div class="fb-like" data-href="<?php echo wpsc_the_product_permalink(); ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
                        </li>                            
                        <?php endif; ?>
                        </ul>
						<?php 
						if(get_option( 'product_ratings' ) == 1) :
							echo sp_product_rating(get_the_ID()); 
						endif;
						?>
                        
					</div><!--close productcol-->
		
					<form onsubmit="submitform(this);return false;" action="<?php echo wpsc_this_page_url(); ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_extra_<?php echo wpsc_the_product_id(); ?>">
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="prodid"/>
						<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="item"/>
					</form>
			<div class="wpec-tabs">
            	<ul class="tabs group">
                	<?php
					// check if comments enable per post
					$comments = maybe_unserialize(get_post_meta(wpsc_the_product_id(), '_wpsc_product_metadata', true));
					$comments = $comments['enable_comments'];
					?>
				
					<?php if ( wpsc_the_product_additional_description() ) : ?>
                    <li><a href="#additional-tab" title="<?php _e( 'Additional Information', 'sp' ); ?>"><?php _e( 'Additional Information', 'sp' ); ?></a></li>
                    <?php endif; ?>		
					<?php 
                    if (sp_isset_option( 'product_review', 'boolean', 'true' ) && $comments) { 
						if ( $comments == '1' ) {
					?> 
                        <li><a href="#review-tab" title="<?php _e( 'Product Review', 'sp' ); ?>"><?php _e( 'Product Review', 'sp' ); ?></a></li>
                    <?php
						}
                    }
                    ?>
                    
            	</ul>
				<?php if ( wpsc_the_product_additional_description() ) : ?>
                    <div class="additional_description_container" id="additional-tab">
                        <h2><?php _e( 'Additional Information', 'sp' ); ?></h2>
                        <div class="additional_description">
                        <?php echo sp_wpsc_the_product_additional_description(); ?>
                        </div><!--close additional_description-->
                    </div><!--close single_additional_description-->
                <?php endif; ?>		
                <?php do_action( 'wpsc_product_addon_after_descr', wpsc_the_product_id() ); ?>
				<?php 
                if (sp_isset_option( 'product_review', 'boolean', 'true' ) && $comments) { 
					if ( $comments == '1' ) {
				?>
                	<div id="review-tab">
                <?php
        
                    if ( get_option( 'wpsc_enable_comments' ) == 1 ) {
                        echo wpsc_product_comments();
                    } else {
                        comments_template( '/comments-product.php', true );
                    } ?>
                    </div><!--close review-tab-->
                <?php
					}
                }
                ?>
                
            </div><!--close wpec-tabs-->    
                    
		</div><!--close single_product_display-->
        <?php echo sp_wpsc_also_bought( wpsc_the_product_id() ); ?>
<?php endwhile;
	echo sp_fancy_notifications();
    do_action( 'wpsc_theme_footer' ); ?> 
    <input type="hidden" value="<?php echo get_option("fancy_notifications"); ?>" class="fancy-notification" />
	
</div><!--close single_product_page_container-->
