	<?php //Custom Fields btn_text
		$b_word 		=get_post_meta( 7, 'b_word',true);
		$contact_text 		=get_post_meta( 7, 'contact_text',true);
		$contacta_btn_text 		=get_post_meta( 7, 'contacta_btn_text',true);
	?>
	<!--msgtoadmin-->
	<section id="msgtoadmin">
		<div class="container">
			<div class="row">
				<div class="col-sm-8">
					<p class="lead"><?php echo $contact_text;?></p>
				</div> <!--col-->
				
				<div class="col-sm-4">
					<button class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#myModal"><?php echo $contacta_btn_text;?></button>
				</div> <!--col-->
				
			</div>
		</div> <!--container-->
	</section> <!--msgtoadmin end-->
	
	