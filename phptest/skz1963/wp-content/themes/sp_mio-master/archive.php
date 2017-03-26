<?php 
get_header(); 

// get the current page layout settings (array)
$layout = sp_page_layout();
?>
		<?php dynamic_sidebar( 'page-top-widget' ); ?>
		<div id="container" class="group <?php echo $layout['orientation']; ?>">
			<?php
			if ($layout['sidebar_left']) {
				get_sidebar('left');	
			} ?>
			<?php
			if ($sidebar_left) {
				get_sidebar('left');	
			} ?>
        
			<div id="content" role="main">

<?php
	if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title">
<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', 'sp' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', 'sp' ), get_the_date( 'F Y' ) ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', 'sp' ), get_the_date( 'Y' ) ); ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'sp' ); ?>
<?php endif; ?>
			</h1>
		</header>
		<?php
		$pagination = sp_isset_option( 'blog_pagination', 'value' );
		if (isset($pagination) && $pagination == 'next-previous') {
			sp_content_nav('nav-above');
		} elseif (isset($pagination) && $pagination == 'numeration') {
			sp_pagination();
		} elseif (isset($pagination) && $pagination == 'no-pagination') {
			
		} else {
			sp_content_nav('nav-above');
		}
        while ( have_posts() ) : the_post();
            get_template_part( 'content', get_post_format() );
        endwhile;
		if (isset($pagination) && $pagination == 'next-previous') {
			sp_content_nav('nav-below');
		} elseif (isset($pagination) && $pagination == 'numeration') {
			sp_pagination();
		} elseif (isset($pagination) && $pagination == 'no-pagination') {
									
		} else {
			sp_content_nav('nav-below');
		}
        ?>
	<?php else : ?>

        <article id="post-0" class="post no-results not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e( 'Nothing Found', 'sp' ); ?></h1>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'sp' ); ?></p>
                <?php get_search_form(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-0 -->

    <?php endif; ?>
        
			</div><!-- #content -->
            <?php 
			if ($layout['sidebar_right']) {
				get_sidebar('right'); 
			} ?>
		</div><!-- #container -->
		<?php dynamic_sidebar( 'page-bottom-widget' ); ?>
<?php get_footer(); ?>
