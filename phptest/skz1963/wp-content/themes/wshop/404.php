<?php get_header(); ?>   
		<div class="content">
			<div class="post-main">
				<center><h1><?php _e( 'Error 404 - Page Not Found', 'wshop' ); ?></h1></center><br>
			</div> 			
		</div>
<div class="row">
	<?php if ( is_active_sidebar( 'sidebar-left' ) ) : ?>
		<div class="sidebar-right1 span2">
			<?php dynamic_sidebar( 'sidebar-left' ); ?>
		</div>
	<?php endif; ?>
</div>




</div>
</div> <div class="greyt"> </div><div class="grey"> </div>
<?php get_footer(); ?>