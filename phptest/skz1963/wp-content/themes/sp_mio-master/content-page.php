<article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>
    <header class="page-header">
    <?php if ( is_front_page() ) { ?>
        <h2 class="entry-title"><?php the_title(); ?></h2>
    <?php } else { ?>
        <?php if ((class_exists('WP_eCommerce') && get_post_type() == 'wpsc-product' )) {
			if ( !is_single() ) {
            	$category_name = wpsc_category_name(); ?>
            	<h1 class="entry-title"><?php echo $category_name; ?></h1>
            <?php  } ?>
        <?php } else { ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php
            }
			?>
    <?php } ?>
    </header>
    <div class="entry-content">
        <?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'sp' ), 'after' => '</div>' ) ); ?>
        <footer class="entry-meta">
            <?php edit_post_link( __( 'Edit', 'sp' ), '<span class="edit-link">', '</span>' ); ?>
        </footer><!-- .entry-meta -->
    </div><!-- .entry-content -->
</article><!-- #post-## -->

