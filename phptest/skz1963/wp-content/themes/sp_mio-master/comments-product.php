			<div id="comments" class="products">
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
<h2><?php _e('Product Review', 'sp'); ?></h2>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Reviews', 'sp' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Reviews &rarr;', 'sp' ) ); ?></div>
		</nav>
<?php endif; // check for comment navigation ?>

			<ol class="commentlist">
				<?php
					wp_list_comments( array( 'callback' => 'sp_comment_products' ) );
				?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Reviews', 'sp' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Reviews &rarr;', 'sp' ) ); ?></div>
		</nav>
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments: ?>

<?php endif; // end have_comments() ?>
<?php if ( have_comments() ) : ?>
<div class="btt group"><a href="#top" title="<?php _e( 'Back to Top', 'sp' ); ?>"><?php _e( 'Back to Top', 'sp' ); ?> &uarr;</a></div>
<?php endif; ?>
<?php
if (esc_attr( $commenter['comment_author'] ) == null) { $name = 'Name'; } else { $name = esc_attr( $commenter['comment_author'] ); }
if (esc_attr(  $commenter['comment_author_email'] ) == null) { $email = 'Email'; } else { $email = esc_attr(  $commenter['comment_author_email'] ); }
if (esc_attr( $commenter['comment_author_url'] ) == null) { $site = 'Url'; } else { $site = esc_attr( $commenter['comment_author_url'] ); }

?>
<?php
(isset($aria_req)) ? $aria_req : $aria_req = '';
$fields =  array(
	'author' => '<div class="left"><p class="comment-form-author">'.
	            '<input id="author" class="required" name="author" type="text" value="' .$name. '" size="30"' . $aria_req . ' /><span class="required">*</span></p>',
	'email'  => '<p class="comment-form-email">'.
	            '<input id="email" class="required email" name="email" type="text" value="' .$email. '" size="30"' . $aria_req . ' /><span class="required">*</span></p>',
	'url'    => '<p class="comment-form-url">'.
	            '<input id="url" name="url" type="text" value="' .$site. '" size="30" /></p></div>',
); ?>
<?php comment_form(array('title_reply' => __('Leave a Review', 'sp'),'comment_notes_after' => '','fields' => apply_filters( 'comment_form_default_fields', $fields ),'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="7" aria-required="true" class="required"></textarea><span class="required">*</span></p><div class="group"></div>', 'label_submit' => __('Post Review', 'sp'))); ?> 
<div class="group"></div>
</div><!-- #comments -->
