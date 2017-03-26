<?php
	/**
	 * The Transaction Results Theme.
	 *
	 * Displays everything within transaction results.  Hopefully much more useable than the previous implementation.
	 *
	 * @package WPSC
	 * @since WPSC 3.8
	 */

	global $purchase_log, $errorcode, $sessionid, $echo_to_screen, $cart, $message_html;
?>
	<script type="text/javascript">
	jQuery(window).load(function() {
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-13px 0");
			jQuery(".progress_wrapper .final").addClass("act");
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: '-13px 0'},600, function() {
				jQuery(".progress_wrapper .final").addClass("act");	
			});
		}
	});
	</script>

<div class="progress_wrapper top">

 <span class="lines"></span>
 <div class="progress_bar"></div><!--close progress_bar-->	
    <ul>
        <li class="cart act"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info act"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->

<div class="wpsc-transaction-results-wrap">
	<?php echo wpsc_transaction_theme(); ?>
</div>

<div class="progress_wrapper below">

 <span class="lines"></span>
 <div class="progress_bar_white"></div><!--close progress_bar-->	
    <ul>
        <li class="cart act"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info act"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->
