<?php
global $wp_query;

$image_width = get_option('product_image_width');
$image_height = get_option('product_image_height');
$cat_image_width = sp_get_theme_init_setting('wpec_product_category_size', 'width');
$cat_image_height = sp_get_theme_init_setting('wpec_product_category_size', 'height');

?>
<div id="grid_view_products_page_container">
<?php 
$args = array( 
	'crumb-separator' => ''
);
wpsc_output_breadcrumbs($args); ?>
<?php 
if (sp_isset_option( 'product_view_buttons', 'boolean', 'true' )) {
	if (get_option('show_search') != '1') {
		echo sp_product_view(); 
	} else {
		if (get_option('show_advanced_search') != '1') {
			echo sp_product_view();	
		}
	}
}
?>
	<?php do_action('wpsc_top_of_products_page'); // Plugin hook for adding things to the top of the products page, like the live search ?>
	
	<?php if(wpsc_display_categories()): ?>
	  <?php if(get_option('wpsc_category_grid_view') == 1) :?>
			<div class="wpsc_categories wpsc_category_grid group">
				<?php wpsc_start_category_query(array('category_group'=> 1, 'show_thumbnails'=> 1)); ?>
					<a href="<?php wpsc_print_category_url();?>" class="wpsc_category_grid_item  <?php wpsc_print_category_classes_section(); ?>" title="<?php wpsc_print_category_name();?>">
						<?php wpsc_print_category_image(45, 45); ?>
					</a>
					<?php wpsc_print_subcategory("", ""); ?>
				<?php wpsc_end_category_query(); ?>
				
			</div><!--close wpsc_categories-->
	  <?php else:?>
			<ul class="wpsc_categories">
				<?php wpsc_start_category_query(array('category_group'=> 1, 'show_thumbnails'=> get_option('show_category_thumbnails'))); ?>
						<li>
							<?php wpsc_print_category_image(32, 32); ?>
							
							<a href="<?php wpsc_print_category_url();?>" class="wpsc_category_link  <?php wpsc_print_category_classes_section(); ?>"><?php wpsc_print_category_name();?></a>
							<?php if(get_option('wpsc_category_description')) :?>
								<?php wpsc_print_category_description("<div class='wpsc_subcategory'>", "</div>"); ?>				
							<?php endif;?>
							
							<?php wpsc_print_subcategory("<ul>", "</ul>"); ?>
						</li>
				<?php wpsc_end_category_query(); ?>
			</ul>
		<?php endif; ?>
	<?php endif; ?>

	<?php if(wpsc_display_products()): ?>
	<?php if(wpsc_is_in_category()) : ?>
            <?php if ((get_option('wpsc_category_description') || get_option('show_category_thumbnails')) && (sp_check_ms_image(wpsc_category_image()) || wpsc_category_description()) ) { ?>
            <div class="wpsc_category_details group">
                    <?php if(get_option('show_category_thumbnails') && sp_check_ms_image(wpsc_category_image())) : ?>
                                       <?php echo get_the_post_thumbnail( $post->ID, array($cat_image_width,$cat_image_height), array( 'class' => '' ) ); ?>
                <?php endif; ?>
                
                <?php if(get_option('wpsc_category_description') &&  wpsc_category_description()) : ?>
                    <p class="description"><?php echo wpsc_category_description(); ?></p>
                <?php endif; ?>
            </div><!--close wpsc_category_details-->
            <?php } ?>
	<?php endif; ?>
	
	
	<?php if(wpsc_has_pages_top()) : ?>
			<div class="wpsc_page_numbers_top group">
				<?php wpsc_pagination(); ?>
			</div><!--close wpsc_page_numbers_top-->
	<?php endif; ?>		
		

	<div class="product_grid_display group">
		<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>
			<div class="product_grid_item product_view_<?php echo wpsc_the_product_id(); ?>">
					<div class="item_image">
				<?php if(wpsc_the_product_thumbnail()) :?> 	   
						<a title="<?php echo wpsc_the_product_title(); ?>" href="<?php echo wpsc_the_product_permalink(); ?>">
						<?php echo get_the_post_thumbnail( $post->ID, array($image_width,$image_height), array( 'class' => 'product_image' ) ); ?>
						</a>
                            <?php if (wpsc_product_on_special() && sp_isset_option( 'saletag', 'boolean', 'true' )) : ?>
                            <span class="saletag"></span>
                            <?php endif; ?>
                        
				<?php else: ?> 
						<a title="<?php echo wpsc_the_product_title(); ?>" href="<?php echo wpsc_the_product_permalink(); ?>">
						<img class="no-image" alt="<?php echo wpsc_the_product_title(); ?>" title="<?php echo wpsc_the_product_title(); ?>" src="<?php echo get_template_directory_uri() . '/images/no-product-image.jpg'; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" />
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
			<?php  /*
            if (get_option('show_search') != '1') {
                if(($spdata[THEME_SHORTNAME.'grid_items'] > 0) && ((($wp_query->current_post + 1) % $spdata[THEME_SHORTNAME.'grid_items']) == 0)) {
						echo '<div class="group"></div>';	
				}

            } else {
                if (get_option('show_advanced_search') != '1') {
					if(($spdata[THEME_SHORTNAME.'grid_items'] > 0) && ((($wp_query->current_post + 1) % $spdata[THEME_SHORTNAME.'grid_items']) == 0)) {
							echo '<div class="group"></div>';	
					}
                } else {
					if((get_option('grid_number_per_row') > 0) && ((($wp_query->current_post +1) % get_option('grid_number_per_row')) == 0)) {
						echo '<div class="group"></div>';	
					}
				}
            } */
			?>
			
			
		<?php endwhile; ?>
		
		<?php if(wpsc_product_count() == 0):?>
			<p><?php  _e('There are no products in this group.', 'sp'); ?></p>
		<?php endif ; ?>
		
		
	</div><!--close product_grid_display-->
	
		<?php if(wpsc_has_pages_bottom()) : ?>
			<div class="wpsc_page_numbers_bottom group">
				<?php wpsc_pagination(); ?>
			</div><!--close wpsc_page_numbers_bottom-->
		<?php endif; ?>
	<?php endif; ?>
	<?php echo sp_fancy_notifications(); ?>
    <?php do_action( 'wpsc_theme_footer' ); ?> 	

</div><!--close grid_view_products_page_container-->