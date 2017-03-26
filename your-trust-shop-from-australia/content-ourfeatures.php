	<?php 
		$our_feature_title 		=get_field('our_feature_title');
		$our_features_body 		=get_field('our_features_body');
	?>
	<section id="quick-link">
		<div class="container">
			
			<div class="section-header">
	
				<!--if feature img exist-->
				
				<?php    
					if(!empty($notebook_image)):?>
					<img src="<?php echo $our_feature_image['url'];?>" alt="<?php echo $our_feature_image['alt'];?>">
				<?php endif;?>
								
				
				<h2><?php echo $our_feature_title;?></h2>
				
			
				
				<?php    
					if(!empty($our_features_body)):?>
					<p class="lead"><?php echo $our_features_body;?></p>
				<?php endif;?>


			</div> <!--end section header-->
			<div class="row">
				
				
				<?php
					$loop= new WP_Query(array('post_type'=>'our_features','orderby'=>'post_id','order'=>'ASC'));
					//print_r($loop);
				?>
				
				
				<?php while($loop->have_posts()): $loop->the_post();?>
				
				<div class="col-sm-2">
					<i class="<?php the_field('our_feature_icon');?>"></i> <!--font awesome-->
					<h4> <?php the_title();?></h4>
				</div><!-- end col -->
				
				<?php endwhile; wp_reset_query(); ?>
		
			</div> <!--row-->
			
		</div> <!--container end-->
	</section>	<!--end quicklink-->