<?php get_header(); ?>   
		<div class="content">
			<div class="post-main">
				<?php woocommerce_content(); ?>
			</div>
		</div>

<div class="row">
	<?php if ( is_active_sidebar( 'sidebar-left' ) ) : ?>
		<div class="sidebar-right1 span2">
			<ul><?php dynamic_sidebar( 'sidebar-left' ); ?></ul>
		</div>
	<?php endif; ?>
</div>




</div>
</div> <div class="greyt"> </div><div class="grey"> </div>
<?php get_footer(); ?>