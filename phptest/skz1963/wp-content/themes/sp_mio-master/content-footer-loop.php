<?php
	if (sp_isset_option( 'footer_post_rotator_count', 'isset' ) && sp_isset_option( 'footer_post_rotator_count', 'value' ) > '1') {
		$count = sp_isset_option( 'footer_post_rotator_count', 'value' );
	} else {
		$count = 1;	
	}
	$delay = sp_isset_option( 'footer_post_rotator_interval', 'value' );
?>
<?php query_posts('posts_per_page='.$count); ?>
<?php while ( have_posts() ) : the_post(); ?>
<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sp' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
<?php endwhile; ?>
<input type="hidden" value="<?php echo $delay; ?>" class="footer_rotator_delay" />