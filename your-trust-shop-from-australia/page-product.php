<?php
/*
	Template Name: Product
	

 */
 
 $thumbnail_url= wp_get_attachment_url(get_post_thumbnail_id($post->ID));



 
get_header(); ?>
<?php
	if(has_post_thumbnail()){// check for feature image?>


	


	<section class="feature-image" style="background: url(<?php echo "'".$thumbnail_url."'"; ?>) no-repeat;;  background-size:cover;" data-type="background" data-speed="2">
		
		
		
		
		
		<h1 class="page-title"><?php the_title();?></h1>
	</section>
<?php }else{// fallback image?>
	

<section class="feature-image feature-image-default" data-type="background" data-speed="2">
	<h1 class="page-title"><?php  the_title();?></h1>
</section>
<?php }?>	
	<!--main content-->
	
	<div class="container">
		<div class="row" id="primary">
			
			<div id="content" class="col-sm-12">
				
				<section class="main-content">
					<?php while (have_posts()): the_post(); ?>
					<?php the_content();?>
					<?php endwhile; //end of the loop?>
					
					<?php $loop= new WP_Query(array('post_type'=>'product','orderby'=>'post_id','order'=>'ASC'));
						//print_r($loop);
					?>
					
					
					<hr>
					
					<div class="product-row clearfix">
						
						<?php while($loop->have_posts()): $loop->the_post();?>
						<?php 
							 $product_image=get_field('product_image');
							 $product_url=get_field('product_url');
							 $button_text=get_field('button_text');
						?>
						
							<div class="product">
								<img src="<?php echo $product_image[url];?>" alt="<?php echo $product_image[alt];?>">
							
							
							
							
							
							
							
							
							
							
							
					    	<h3><a href="<?php echo $product_url;?>"> <?php the_title();?></a></h3>
					    	<?php the_content();?>
					    	<?php if(!empty($button_text)):?>
					    	<a href="<?php echo $product_url;?>" class="btn btn-success"> <?php echo $button_text;?></a>
					    	
					    	<?php endif;?>
						</div> <!--product-->
						
						
						
						
						
						
						
						
						
						
						
						
						<?php endwhile;?>
						
						
						
					</div> <!--product row-->
					
				</section> <!--main content-->
				
			</div> <!--content-->
			
		</div> <!--row-->
	</div> <!--container-->


<?php
get_footer();