<?php
/**
 * Product quantity inputs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="quantity buttons_added"><input type="button" class="minus" value="-"><input type="text" step="<?php echo esc_attr( $step ); ?>" <?php if ( is_numeric( $min_value ) ) : ?>min="<?php echo esc_attr( $min_value ); ?>"<?php endif; ?> <?php if ( is_numeric( $max_value ) ) : ?>max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?> name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" title="<?php _ex( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) ?>" class="input-text qty text" size="6" /><input type="button" class="plus" value="+"></div>

 <script>
jQuery( document ).ready(function($) { 
  $('.plus').live('click',function(){
  var plus=$(this).prev().val();
      plus=parseInt(plus)+1;
      $(this).prev().val(plus);
      //alert(plus);
  }); 
  
  $('.minus').live('click',function(){
  var minus=$(this).next().val();
      if(minus>1) {
      minus=parseInt(minus)-1;
      $(this).next().val(minus);
	  }
      //alert(minus);
  });
  
});

</script>