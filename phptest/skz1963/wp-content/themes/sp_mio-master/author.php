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
        
			<div id="content" role="main" class="author">
<?php if ( have_posts() ) : ?>
		<?php the_post(); ?>
			<header class="page-header">
				<h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'sp' ), "<a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' data-rel='me'>" . get_the_author() . "</a>" ); ?></h1>
			</header>
<?php
rewind_posts(); // since we called the_post up top
// If a user has filled out their description, show a bio on their entries.
if ( get_the_author_meta( 'description' ) ) : ?>
        <div id="entry-author-info" class="group">
            <div id="author-avatar">
                <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'sp_author_bio_avatar_size', 100 ) ); ?>
            </div><!-- #author-avatar -->
            <div id="author-description">
                <h2><?php printf( __( 'About %s', 'sp' ), get_the_author() ); ?></h2>
                <p><?php the_author_meta( 'description' ); ?></p>
            </div><!-- #author-description	-->
        </div><!-- #entry-author-info -->
<?php endif; ?>

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
