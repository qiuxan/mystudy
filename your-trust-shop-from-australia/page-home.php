<?php
/*
	Template Name: Home Page	
	

 */



//advance custom fields

$notebook_image 		=get_field('notebook_image');
$notebook_section__title 		=get_field('notebook_section__title');
$title1 		=get_field('title1');
$information_content1 		=get_field('information_content1');
$title2 		=get_field('title2');
$information_content2 		=get_field('information_content2');
$title3 		=get_field('title3');
$information_content3 		=get_field('information_content3');
$title4 		=get_field('title4');
$information_content4 		=get_field('information_content4');
$our_feature_image 		=get_field('our_feature_image');

$our_feature_title 		=get_field('our_feature_title');
$our_features_body 		=get_field('our_features_body');


//our_feature_image  our_feature_title	
//print_r($notebook_image);our_features_body

get_header(); ?>

	<?php 
		get_template_part('content','hero');
		get_template_part('content','msgtoadmin');
		get_template_part('content', 'ouradvantage');
		
	?>
	
	
	
	

	
	
	
	
	
	
	
	
	
	
	<section id="quick-link">
		<div class="container">
			
			<div class="section-header">
				<!--$our_feature_image 		=get_field('our_feature_image');	$our_feature_title 		=get_field('our_feature_title');
-->
				
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
				
				<?php endwhile; ?>
				
				
				
				


				
			</div> <!--row-->
			
		</div> <!--container end-->
	</section>	<!--end quicklink-->

<?php
get_footer();
