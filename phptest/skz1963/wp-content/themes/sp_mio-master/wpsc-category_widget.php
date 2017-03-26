<?php
	$curr_cat       = get_term( $category_id, 'wpsc_product_category', ARRAY_A );
	$category_list  = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=' . $category_id );
	$link = get_term_link((int)$category_id , 'wpsc_product_category');
	$category_image = wpsc_get_categorymeta( $curr_cat['term_id'], 'image' );
	if (!isset($category_image)) {
		$category_image = get_template_directory_uri().'/images/no-product-image.jpg';
	} else {
		$category_image = WPSC_CATEGORY_URL . urlencode($category_image);
	}
	$show_name = $instance['show_name'];
	
	if ( $grid ) : ?>

		<a href="<?php echo $link; ?>" title="<?php echo $curr_cat['name']; ?>" class="wpsc_category_grid_item" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;"><?php
						ob_start();
						sp_check_ms_image(wpsc_parent_category_image( $show_thumbnails, $category_image , $width, $height, false, $show_name ));
						$content = ob_get_clean();
						$image = preg_replace('/<.*/',wp_get_attachment_image( $curr_cat['term_id'], array($width,$height,true), $icon, array( 'alt' => $curr_cat['name'] )), $content);
						echo $image;
		?></a>

<?php else : ?>
		<div class="wpsc_categorisation_group group" id="categorisation_group_<?php echo $category_id; ?>">
			<ul class="wpsc_categories wpsc_top_level_categories">
				<li class="wpsc_category_<?php echo $curr_cat['term_id']; wpsc_print_category_classes($curr_cat);  ?>">
					<?php if(! ($category_image == WPSC_CATEGORY_URL) ){ ?>
						<a href="<?php echo $link; ?>" class="wpsc_category_image_link"><?php 
						ob_start();
						sp_check_ms_image(wpsc_parent_category_image( $show_thumbnails, $category_image , $width, $height, false, $show_name ));
						$content = ob_get_clean();
						$image = preg_replace('/<.*/',wp_get_attachment_image( $curr_cat['term_id'], array($width,$height,true), $icon, array( 'alt' => $curr_cat['name'] )), $content);
						echo $image;
						?></a>
					<?php } ?>
					
					<a href="<?php echo $link; ?>"><?php echo esc_html( $curr_cat['name'] ); ?></a>

				</li>
			</ul>
		</div>

<?php endif; ?>
