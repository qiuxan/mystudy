<?php global $post; ?>
<form action="<?php echo home_url( '/' ); ?>" method="get" class="searchform">
    <fieldset>
        <?php
		$type = '';
		$text = __( 'Search All', 'sp' );
		if ( class_exists( 'WP_eCommerce' ) ) { 
			if ( get_post_type( $post ) == 'post' ) {
				$type = 'post';
				$text = __( 'Search Posts', 'sp' );
			} else {
				$type = 'wpsc-product';
				$text = __( 'Search Products', 'sp' );
			}
		}
		if ( class_exists( 'woocommerce' ) ) { 
			if ( get_post_type( $post ) == 'post' ) {
				$type = 'post';
				$text = __( 'Search Posts', 'sp' );
			} else {
				$type = 'product';
				$text = __( 'Search Products', 'sp' );
			}
		}		
		?>

        <input type="text" name="s" class="search" value="<?php echo $text; ?>" />
        <input type="hidden" value="<?php echo $type; ?>" name="post_type" />
    </fieldset>
</form>
