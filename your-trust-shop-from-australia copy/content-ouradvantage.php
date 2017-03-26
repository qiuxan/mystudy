<?php 

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
	
?>

<section id="our-advantage">
		
		<div class="container">
			
			<div class="section-header">
				
				<!--if feature img exist-->
				
				<?php    
					if(!empty($notebook_image)):?>
					<img src="<?php echo $notebook_image['url'];?>" alt="<?php echo $notebook_image['alt'];?>">
				<?php endif;?>
				
				
				
				<h2><?php echo $notebook_section__title;?></h2>
			</div> <!--s headere-->
			
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2"> 
					<h3><?php echo $title1;?></h3>
					<p><?php echo $information_content1;?></p>

					<h3><?php echo $title2;?></h3>
					<p><?php echo $information_content2;?></p>
					

					<h3><?php echo $title3;?></h3>
					<p><?php echo $information_content3;?>/p>
					
					<h3><?php echo $title4;?></h3>
					<p><?php echo $information_content4;?></p>

				</div> <!--col-->
				
			</div> <!--row-->
		
		</div> <!--container-->
				
		
	</section> <!--ouradvantage-->