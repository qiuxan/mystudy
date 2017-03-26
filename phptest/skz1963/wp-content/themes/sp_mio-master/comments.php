			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'sp' ); ?></p>
			</div><!-- #comments -->
<?php
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
			<h3 id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'sp' ),
			number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
			?></h3>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'sp' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'sp' ) ); ?></div>
			</div> <!-- .navigation -->
<?php endif; // check for comment navigation ?>

			<ol class="commentlist">
				<?php
					wp_list_comments( array( 'callback' => 'sp_comment' ) );
				?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'sp' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'sp' ) ); ?></div>
			</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	if ( ! comments_open() ) :
		if (get_post_type() == 'post') {
?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'sp' ); ?></p>
<?php 
		}
	endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>
<?php if ( have_comments() ) : ?>
<div class="btt group"><a href="#top" title="<?php _e('Back to Top', 'sp'); ?>"><?php _e('Back to Top', 'sp'); ?> &uarr;</a></div>
<?php endif; ?>

<?php
(isset($aria_req)) ? $aria_req : $aria_req = '';

$fields =  array(
	'author' => '<div class="left"><p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'sp' ) . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) .
	            '<input id="author" class="required" name="author" type="text" value="" size="30"' . $aria_req . ' /></p>',
	'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'sp' ) . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) .
	            '<input id="email" class="required email" name="email" type="text" value="" size="30"' . $aria_req . ' /></p>',
	'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'sp' ) . '</label><br />' .
	            '<input id="url" name="url" type="text" value="" size="30" /></p></div>',
); ?>

<?php comment_form(array('title_reply' => 'Leave a Comment','comment_notes_after' => '','fields' => apply_filters( 'comment_form_default_fields', $fields ),'comment_field' => '<div class="right"><p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'sp' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<br /><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true" class="required"></textarea></p></div><div class="group"></div>')); ?>
<div class="group"></div>
</div><!-- #comments -->
