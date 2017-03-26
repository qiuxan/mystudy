<?php
global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons, $wpsc_registration_error_messages;
$wpsc_checkout = new wpsc_checkout();
$wpsc_gateway = new wpsc_gateways();
$alt = 0;
if ( function_exists( 'wpsc_get_customer_meta' ) ) {
  $coupon_num = wpsc_get_customer_meta( 'coupon' );
  
  if( $coupon_num )
    $wpsc_coupons = new wpsc_coupons( $coupon_num );

} else {
  if(isset($_SESSION['coupon_numbers']))
    $wpsc_coupons = new wpsc_coupons($_SESSION['coupon_numbers']);
}

if(wpsc_cart_item_count() < 1) :
   _e('Oops, there is nothing in your cart.', 'sp') . "<a href=" . esc_url( get_option( "product_list_url" ) ) . ">" . __('Please visit our shop', 'sp') . "</a>";
   return;
endif;
if (!empty($_SESSION['wpsc_checkout_error_messages']) || !empty($_SESSION['wpsc_checkout_misc_error_messages']) || !empty($_SESSION['wpsc_checkout_user_error_messages']) || isset($_POST['wpsc_submit_zipcode']) || isset($_POST['wpsc_gateway_error_messages']) || isset($_POST['wpsc_update_location']) || (isset($_GET['login']) && $_GET['login'] == 1)) : ?>
	<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery(".slide1").hide();
		jQuery(".slide2").show();
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-119px 0");
			jQuery(".progress_wrapper .cart").addClass('act');
			jQuery(".progress_wrapper .info").addClass('act');
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: "-119px"},600, function() {
				jQuery(".progress_wrapper .cart").addClass('act');
				jQuery(".progress_wrapper .info").addClass('act');	
			});
		}
	});
	</script>
<?php else : ?>
	<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery(".slide1").show();
		if (navigator.appName == "Microsoft Internet Explorer") {
			jQuery(".progress_wrapper .lines").css("background-position", "-263px 0");
			jQuery(".progress_wrapper .cart").addClass('act');
		} else {
			jQuery(".progress_wrapper .lines").animate({ backgroundPosition: "-263px"},600, function() {
				jQuery(".progress_wrapper .cart").addClass('act');	
			});
		}
		
	});
	</script>
<?php endif; ?>
<div class="progress_wrapper top">

 <span class="lines"></span>
 <div class="progress_bar"></div><!--close progress_bar-->	
    <ul>
        <li class="cart"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->
<div id="checkout_page_container" class="group">
<div class="slide1">
  <span class="yourtotal"><?php _e('Sub-Total:', 'sp'); ?><?php echo wpsc_cart_total_widget(false,false,false); ?></span>
<table class="checkout_cart">
   <tr class="header">
      <th colspan="2" ><?php _e('Product:', 'sp'); ?></th>
      <th><?php _e('Quantity:', 'sp'); ?></th>
      <th><?php _e('Price:', 'sp'); ?></th>
      <th><?php _e('Total:', 'sp'); ?></th>
        <th>&nbsp;</th>
   </tr>
   <?php while (wpsc_have_cart_items()) : wpsc_the_cart_item(); ?>
      <?php
       $alt++;
       if ($alt %2 == 1)
         $alt_class = 'alt';
       else
         $alt_class = '';
       ?>
      <?php  //this displays the confirm your order html ?>

    <?php do_action ( "wpsc_before_checkout_cart_row" ); ?>
      <tr class="product_row product_row_<?php echo wpsc_the_cart_item_key(); ?> <?php echo $alt_class;?>">
        <?php
          $image_width = 50;
          $image_height = 50;
        ?>
         <td class="firstcol wpsc_product_image wpsc_product_image_<?php echo wpsc_the_cart_item_key(); ?>">
         <?php if('' != wpsc_cart_item_image()): ?>
      <?php do_action ( "wpsc_before_checkout_cart_item_image" ); ?>
            <img src="<?php echo sp_cart_item_image(wpsc_cart_item_product_id(),$image_width, $image_height); ?>" alt="<?php echo wpsc_cart_item_name(); ?>" title="<?php echo wpsc_cart_item_name(); ?>" class="product_image" />
      <?php do_action ( "wpsc_after_checkout_cart_item_image" ); ?>
         <?php else:
         /* I dont think this gets used anymore,, but left in for backwards compatibility */
         ?>
            
        <?php do_action ( "wpsc_before_checkout_cart_item_image" ); ?>
               <img src="<?php echo get_template_directory_uri(); ?>/images/no-product-image.jpg" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" alt="<?php echo wpsc_cart_item_name(); ?>" title="<?php echo wpsc_cart_item_name(); ?>" class="product_image" />
        <?php do_action ( "wpsc_after_checkout_cart_item_image" ); ?>
           
         <?php endif; ?>
         </td>

         <td class="wpsc_product_name wpsc_product_name_<?php echo wpsc_the_cart_item_key(); ?>">
      <?php do_action ( "wpsc_before_checkout_cart_item_name" ); ?>
            <a href="<?php echo esc_url( wpsc_cart_item_url() );?>"><?php echo wpsc_cart_item_name(); ?></a>
      <?php do_action ( "wpsc_after_checkout_cart_item_name" ); ?>
         </td>

         <td class="wpsc_product_quantity wpsc_product_quantity_<?php echo wpsc_the_cart_item_key(); ?>">
            <form action="<?php echo esc_url( get_option( 'shopping_cart_url' ) ); ?>" method="post" class="adjustform qty">
               <input type="text" name="quantity" size="2" value="<?php echo wpsc_cart_item_quantity(); ?>" />
               <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
               <input type="hidden" name="wpsc_update_quantity" value="true" />
               <input type="submit" value="<?php _e('Update', 'sp'); ?>" name="submit" />
            </form>
         </td>

            <td><?php echo wpsc_cart_single_item_price(); ?></td>
         <td class="wpsc_product_price wpsc_product_price_<?php echo wpsc_the_cart_item_key(); ?>"><span class="pricedisplay"><?php echo wpsc_cart_item_price(); ?></span></td>

         <td class="wpsc_product_remove wpsc_product_remove_<?php echo wpsc_the_cart_item_key(); ?>">
            <form action="<?php echo esc_url( get_option( 'shopping_cart_url' ) ); ?>" method="post" class="adjustform remove">
               <input type="hidden" name="quantity" value="0" />
               <input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
               <input type="hidden" name="wpsc_update_quantity" value="true" />
               <input type="submit" value="<?php _e('Remove', 'sp'); ?>" name="submit" />
            </form>
         </td>
      </tr>
    <?php do_action ( "wpsc_after_checkout_cart_row" ); ?>
   <?php endwhile; ?>
   <?php //this HTML displays coupons if there are any active coupons to use ?>

   <?php

   if(wpsc_uses_coupons()): ?>

      <?php if(wpsc_coupons_error()): ?>
         <tr class="wpsc_coupon_row wpsc_coupon_error_row"><td colspan="6"><?php _e('Coupon is not valid.', 'sp'); ?></td></tr>
      <?php endif; ?>
      <tr class="wpsc_coupon_row">
         <td  colspan="6" class="coupon_code">
            <form  method="post" action="<?php echo esc_url( get_option( 'shopping_cart_url' ) ); ?>">
              <label><?php _e('Enter coupon code :', 'sp'); ?></label>
               <input type="text" name="coupon_num" id="coupon_num" value="<?php echo $wpsc_cart->coupons_name; ?>" />
               <input type="submit" value="<?php _e('Update', 'sp') ?>" />
            </form>
         </td>
      </tr>
   <?php endif; ?>
   </table>
   <!-- cart contents table close -->
  <?php if(wpsc_uses_shipping()): ?>
     <p class="wpsc_cost_before"></p>
   <?php endif; ?>
   <?php  //this HTML dispalys the calculate your order HTML   ?>
   <a href="#" class="step2"><span><?php _e('Continue', 'sp'); ?></span></a>
</div><!--close slide1-->
<div class="slide2">
   <?php if(wpsc_has_category_and_country_conflict()): 
    if ( function_exists( 'wpsc_get_customer_meta' ) ) {
   ?>
      <p class='validation-error'><?php echo esc_html( wpsc_get_customer_meta( 'category_shipping_conflict' ) ); ?></p>
      <?php } else { ?>
      <p class='validation-error'><?php echo $_SESSION['categoryAndShippingCountryConflict']; ?></p>
      <?php unset($_SESSION['categoryAndShippingCountryConflict']);
      } ?>
   <?php endif; ?>

   <?php if(isset($_SESSION['WpscGatewayErrorMessage']) && $_SESSION['WpscGatewayErrorMessage'] != '') :?>
      <p class="validation-error"><?php echo $_SESSION['WpscGatewayErrorMessage']; ?></p>
   <?php
   endif;
   ?>

   <?php do_action('wpsc_before_shipping_of_shopping_cart'); ?>

   <div id="wpsc_shopping_cart_container" class="group">
   <?php if(wpsc_uses_shipping()) : ?>
      <h2><?php _e('Calculate Shipping Price', 'sp'); ?></h2>
      <table class="productcart">
         <tr class="wpsc_shipping_info">
            <td colspan="5">
               <?php _e('Please specify shipping location below to calculate your shipping costs', 'sp'); ?>
            </td>
         </tr>

         <?php if (!wpsc_have_shipping_quote()) : // No valid shipping quotes ?>
            <?php if (wpsc_have_valid_shipping_zipcode()) : ?>
                  <tr class='wpsc_update_location'>
                     <td colspan='5' class='shipping_error' >
                        <?php _e('Please provide a Zipcode and click Calculate in order to continue.', 'sp'); ?>
                     </td>
                  </tr>
            <?php else: ?>
               <tr class='wpsc_update_location_error'>
                  <td colspan='5' class='shipping_error' >
                     <?php _e('Sorry, online ordering is unavailable to this destination and/or weight. Please double check your destination details.', 'sp'); ?>
                  </td>
               </tr>
            <?php endif; ?>
         <?php endif; ?>
         <tr class='wpsc_change_country'>
            <td colspan='5'>
               <form name='change_country' id='change_country' action='' method='post'>
                  <?php echo wpsc_shipping_country_list();?>
                  <input type='hidden' name='wpsc_update_location' value='true' />
                  <input type='submit' name='wpsc_submit_zipcode' value='<?php esc_attr_e( 'Calculate', 'sp' ); ?>' />
               </form>
            </td>
         </tr>

         <?php if (wpsc_have_morethanone_shipping_quote()) :?>
            <?php while (wpsc_have_shipping_methods()) : wpsc_the_shipping_method(); ?>
                  <?php    if (!wpsc_have_shipping_quotes()) { continue; } // Don't display shipping method if it doesn't have at least one quote ?>
                  <tr class='wpsc_shipping_header'><td class='shipping_header' colspan='5'><?php echo wpsc_shipping_method_name().__(' - Choose a Shipping Rate', 'sp'); ?> </td></tr>
                  <?php while (wpsc_have_shipping_quotes()) : wpsc_the_shipping_quote();  ?>
                     <tr class='<?php echo wpsc_shipping_quote_html_id(); ?>'>
                        <td class='wpsc_shipping_quote_name wpsc_shipping_quote_name_<?php echo wpsc_shipping_quote_html_id(); ?>' colspan='3'>
                           <label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_name(); ?></label>
                        </td>
                        <td class='wpsc_shipping_quote_price wpsc_shipping_quote_price_<?php echo wpsc_shipping_quote_html_id(); ?>' style='text-align:center;'>
                           <label for='<?php echo wpsc_shipping_quote_html_id(); ?>'><?php echo wpsc_shipping_quote_value(); ?></label>
                        </td>
                        <td class='wpsc_shipping_quote_radio wpsc_shipping_quote_radio_<?php echo wpsc_shipping_quote_html_id(); ?>' style='text-align:center;'>
                           <?php if(wpsc_have_morethanone_shipping_methods_and_quotes()): ?>
                              <input type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>' <?php echo wpsc_shipping_quote_selected_state(); ?>  onclick='switchmethod("<?php echo wpsc_shipping_quote_name(); ?>", "<?php echo wpsc_shipping_method_internal_name(); ?>")' value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
                           <?php else: ?>
                              <input <?php echo wpsc_shipping_quote_selected_state(); ?> disabled='disabled' type='radio' id='<?php echo wpsc_shipping_quote_html_id(); ?>'  value='<?php echo wpsc_shipping_quote_value(true); ?>' name='shipping_method' />
                                 <?php wpsc_update_shipping_single_method(); ?>
                           <?php endif; ?>
                        </td>
                     </tr>
                  <?php endwhile; ?>
            <?php endwhile; ?>
         <?php endif; ?>

         <?php wpsc_update_shipping_multiple_methods(); ?>

         <?php if (!wpsc_have_shipping_quote()) : // No valid shipping quotes ?>
               </table>
               </div>
      </div>
            <?php return; ?>
         <?php endif; ?>
      </table>
   <?php endif;  ?>

   <?php do_action('wpsc_before_form_of_shopping_cart'); ?>

  <?php if( ! empty( $wpsc_registration_error_messages ) ): ?>
    <p class="validation-error">
    <?php
    foreach( $wpsc_registration_error_messages as $user_error )
     echo $user_error."<br />\n";
    ?>
  <?php endif; ?>

  <?php if ( !is_user_logged_in() && sp_isset_option( 'checkout_account', 'boolean', 'true' )) : ?>
      <div class="wpsc_registration_form group">
        <fieldset class='wpsc_left_registration'>
          <?php if (wpsc_show_user_login_form()) : ?>
                    <p><?php _e('You must sign in or register with us to continue with your purchase.','sp');?></p>
                    <?php else : ?>
                    <p><?php _e('If you have bought from us before please sign in for a speedy checkout.','sp');?></p>
                    <?php endif; ?>
                
          <h2><?php _e( 'Sign in','sp' ); ?></h2>
          <?php echo sp_display_login(); ?>         
        </fieldset>
      </div><!--close wpsc_registration_form-->
  <?php endif; ?> 
  <form class='wpsc_checkout_forms' action='<?php echo esc_url( get_option( 'shopping_cart_url' ) ); ?>' method='post' enctype="multipart/form-data">
      <?php
      /**
       * Both the registration forms and the checkout details forms must be in the same form element as they are submitted together, you cannot have two form elements submit together without the use of JavaScript.
      */
      ?>

    <?php if(!is_user_logged_in() && wpsc_show_user_login_form()):
          global $current_user;
          get_currentuserinfo();   ?>

    <div class="wpsc_registration_form">
        <fieldset class='wpsc_registration_form wpsc_right_registration'>
        <p class="wpsc_signup_text"><?php _e('Signing up is free and easy!', 'sp');?></p>            
            <h2><?php _e('Join up now', 'sp');?></h2>

        <label><?php _e('Username:', 'sp'); ?></label>
        <input type="text" name="log" value="" size="20"/><br/>

        <label><?php _e('Password:', 'sp'); ?></label>
        <input type="password" name="pwd" value="" size="20" /><br />

        <label><?php _e('E-mail', 'sp'); ?>:</label>
              <input type="text" name="user_email" id="user_email" value="" size="20" /><br />
              
          </fieldset>

        </div>
        <div class="clear"></div>
   <?php endif; // closes user login form
   if ( function_exists( 'wpsc_get_customer_meta' ) ) {
      $misc_error_messages = wpsc_get_customer_meta( 'checkout_misc_error_messages' );
      if( ! empty( $misc_error_messages ) ): ?>
         <div class='login_error'>
            <?php foreach( $misc_error_messages as $user_error ){?>
               <p class='validation-error'><?php echo $user_error; ?></p>
               <?php } ?>
         </div>

      <?php
      endif;
    } else {
      if(!empty($_SESSION['wpsc_checkout_misc_error_messages'])): ?>
          <div class="login_error">
            <?php foreach((array)$_SESSION['wpsc_checkout_misc_error_messages'] as $user_error ){?>
               <p class='validation-error'><?php echo $user_error; ?></p>
               <?php } ?>
          </div>
      <?php
      endif;
       $_SESSION['wpsc_checkout_misc_error_messages'] = array();
    }
      ?>
<div class="form-table-wrapper group">      
<?php ob_start(); ?>
   <table class='wpsc_checkout_table table-1'>
      <?php $i = 0;
      while (wpsc_have_checkout_items()) : wpsc_the_checkout_item(); ?>

        <?php if(wpsc_checkout_form_is_header() == true){
               $i++;
               //display headers for form fields ?>
               <?php if($i > 1):?>
                  </table>
                  <table class='wpsc_checkout_table table-<?php echo $i; ?>'>
               <?php endif; ?>

               <tr <?php echo wpsc_the_checkout_item_error_class();?>>
                  <td <?php wpsc_the_checkout_details_class(); ?> colspan='2'>
                     <h4><?php echo wpsc_checkout_form_name();?></h4>
                  </td>
               </tr>
               <?php if(wpsc_is_shipping_details()):?>
               <tr class='same_as_shipping_row'>
                  <td colspan ='2'>
                  <?php $checked = '';
                  if ( function_exists( 'wpsc_get_customer_meta' ) ) {
                    $shipping_same_as_billing = wpsc_get_customer_meta( 'shipping_same_as_billing' );
                    if(isset($_POST['shippingSameBilling']) && $_POST['shippingSameBilling'])
                       $shipping_same_as_billing = true;
                    elseif(isset($_POST['submit']) && !isset($_POST['shippingSameBilling']))
                      $shipping_same_as_billing = false;
                    wpsc_update_customer_meta( 'shipping_same_as_billing', $shipping_same_as_billing );
                      if( $shipping_same_as_billing )
                        $checked = 'checked="checked"';
                  } else {
                    if(isset($_POST['shippingSameBilling']) && $_POST['shippingSameBilling'])
                      $_SESSION['shippingSameBilling'] = true;
                    elseif(isset($_POST['submit']) && !isset($_POST['shippingSameBilling']))
                      $_SESSION['shippingSameBilling'] = false;

                      if(isset($_SESSION['shippingSameBilling']) &&  $_SESSION['shippingSameBilling'] == 'true')
                        $checked = 'checked="checked"';
                  }
                   ?>
          <label for='shippingSameBilling'><?php _e('Same as billing address:','sp'); ?></label>
          <input type='checkbox' value='true' name='shippingSameBilling' id='shippingSameBilling' <?php echo $checked; ?> />
          <br/><span id="shippingsameasbillingmessage"><?php _e('Your order will be shipped to the billing address', 'sp'); ?></span>
                  </td>
               </tr>
               <?php endif;

            // Not a header so start display form fields
            }elseif(wpsc_disregard_shipping_state_fields()){
            ?>
               <tr class='wpsc_hidden'>
                  <td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
                     <label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
                     <?php echo wpsc_checkout_form_name();?>
                     </label>
                  </td>
                  <td>
                     <?php echo wpsc_checkout_form_field();?>
                      <?php if(wpsc_the_checkout_item_error() != ''): ?>
                             <p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
                     <?php endif; ?>
                  </td>
               </tr>
            <?php
            }elseif(wpsc_disregard_billing_state_fields()){
            ?>
               <tr class='wpsc_hidden'>
                  <td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
                     <label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
                     <?php echo wpsc_checkout_form_name();?>
                     </label>
                  </td>
                  <td>
                     <?php echo wpsc_checkout_form_field();?>
                      <?php if(wpsc_the_checkout_item_error() != ''): ?>
                             <p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
                     <?php endif; ?>
                  </td>
               </tr>
            <?php
            }elseif( $wpsc_checkout->checkout_item->unique_name == 'billingemail'){ ?>
               <?php $email_markup =
               "<div class='wpsc_email_address group'>
                  <p class='".wpsc_checkout_form_element_id()." wpsc_email_address_p'>
          <img src='https://secure.gravatar.com/avatar/empty?s=60&amp;d=mm' id='wpsc_checkout_gravatar' alt='Gravatar' />
                     <label class='wpsc_email_address_label' for='" . wpsc_checkout_form_element_id() . "'>
                     " . __('Enter your email address', 'sp') . "<br />
                  
                  " . wpsc_checkout_form_field().'</label>';
                  
                   if(wpsc_the_checkout_item_error() != '')
                      $email_markup .= "<p class='validation-error'>" . wpsc_the_checkout_item_error() . "</p>";
               $email_markup .= "</div>";
             }else{ ?>
      <tr>
               <td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
                  <label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
                  <?php echo wpsc_checkout_form_name();?>
                  </label>
               </td>
               <td>
                  <?php echo wpsc_checkout_form_field();?>
                   <?php if(wpsc_the_checkout_item_error() != ''): ?>
                          <p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
                  <?php endif; ?>
               </td>
            </tr>

         <?php }//endif; ?>

      <?php endwhile; ?>

<?php
  $buffer_contents = ob_get_contents();
  ob_end_clean();
  if(isset($email_markup))
    echo $email_markup;
  echo $buffer_contents;
?>

      <?php if (wpsc_show_find_us()) : ?>
      <tr>
         <td><label for='how_find_us'><?php _e('How did you find us' , 'sp'); ?></label></td>
         <td>
            <select name='how_find_us'>
               <option value='Word of Mouth'><?php _e('Word of mouth' , 'sp'); ?></option>
               <option value='Advertisement'><?php _e('Advertising' , 'sp'); ?></option>
               <option value='Internet'><?php _e('Internet' , 'sp'); ?></option>
               <option value='Customer'><?php _e('Existing Customer' , 'sp'); ?></option>
            </select>
         </td>
      </tr>
      <?php endif; ?>
      <?php do_action('wpsc_inside_shopping_cart'); ?>

      <?php  //this HTML displays activated payment gateways   ?>
      <?php if(wpsc_gateway_count() > 1): // if we have more than one gateway enabled, offer the user a choice ?>
         <tr>
         <td colspan='2' class='wpsc_gateway_container'>
            <h3><?php _e('Payment Type', 'sp');?></h3>
            <?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
               <div class="custom_gateway">
                     <label><input type="radio" value="<?php echo wpsc_gateway_internal_name();?>" <?php echo wpsc_gateway_is_checked(); ?> name="custom_gateway" class="custom_gateway"/><?php echo wpsc_gateway_name(); ?>
                      <?php if( wpsc_show_gateway_image() ): ?>
                      <img src="<?php echo wpsc_gateway_image_url(); ?>" alt="<?php echo wpsc_gateway_name(); ?>" style="position:relative; top:5px;" />
                      <?php endif; ?>
                     </label>

                  <?php if(wpsc_gateway_form_fields()): ?>
                     <table class='wpsc_checkout_table <?php echo wpsc_gateway_form_field_style();?>'>
                        <?php echo wpsc_gateway_form_fields();?>
                     </table>
                  <?php endif; ?>
               </div>
            <?php endwhile; ?>
            </td></tr>
         <?php else: // otherwise, there is no choice, stick in a hidden form ?>
            <tr><td colspan="2" class='wpsc_gateway_container'>
            <?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
               <input name='custom_gateway' value='<?php echo wpsc_gateway_internal_name();?>' type='hidden' />

                  <?php if(wpsc_gateway_form_fields()): ?>
                     <table class='wpsc_checkout_table <?php echo wpsc_gateway_form_field_style();?>'>
                        <?php echo wpsc_gateway_form_fields();?>
                     </table>
                  <?php endif; ?>
            <?php endwhile; ?>
         </td>
         </tr>
         <?php endif; ?>

      <?php if(wpsc_has_tnc()) : ?>
         <tr>
            <td colspan='2'>
                <label for="agree"><input id="agree" type='checkbox' value='yes' name='agree' /> <?php printf(__("I agree to the <a class='thickbox terms_lightbox' target='_blank' href='%s'>Terms and Conditions</a>", "sp"), esc_url( site_url( "?termsandconds=true&amp;width=360&amp;height=400" ) ) ); ?> <span class="asterix">*</span></label>
                <div id="term-content"><?php echo stripslashes(get_option('terms_and_conditions')); ?></div>
               </td>
         </tr>
      <?php endif; ?>
      </table>
</div><!--close form-table-wrapper-->      
   <div class="secured-wrap">
  <?php if (sp_isset_option( 'checkout_secured_icon', 'boolean', 'true' )) { ?>   
      <span class="secured-icon">&nbsp;</span>
        <p><?php _e('SECURED CHECKOUT','sp'); ?></p>
   <?php } ?>
      <?php if ( sp_isset_option( 'checkout_secured_code', 'isset' ) && strlen( (string)sp_isset_option( 'checkout_secured_code', 'value' ) ) ) { ?>
          <div class="code"><?php echo sp_isset_option( 'checkout_secured_code', 'value' ); ?></div>
        <?php } else { ?>
      <?php if ( sp_isset_option( 'checkout_secured_image', 'isset' ) && strlen( (string)sp_isset_option( 'checkout_secured_image', 'value' ) ) ) { 
        $image_url = sp_isset_option( 'checkout_secured_image', 'value' );
        if (is_ssl()) 
          $image_url = str_replace('http', 'https', $image_url);
  ?>
            <img src="<?php echo $image_url; ?>" alt="Secured" />
          <?php } ?>
      <?php } ?> 
   </div><!--close secured-wrap-->  
  <div class="review group">
   
   <table  class='wpsc_checkout_table table-4'>
        <tr>
           <td class='wpsc_total_price_and_shipping' colspan='2'>
              <h4><?php _e('Review and purchase','sp'); ?></h4>
           </td>
        </tr>

      <?php if(wpsc_uses_shipping()) : ?>
      
        <tr class="total_price total_shipping">
           <td class='wpsc_totals'>
              <?php _e('Total Shipping:', 'sp'); ?>
           </td>
           <td class='wpsc_totals'>
              <span class="pricedisplay checkout-shipping"><?php echo wpsc_cart_shipping(); ?></span>
           </td>
        </tr>
      <?php endif; ?>
        <tr class="total_price total_item">
           <td class='wpsc_totals'>
              <?php _e('Item Cost:', 'sp'); ?>
           </td>
           <td class='wpsc_totals'>
              <span class="pricedisplay checkout-shipping"><?php echo wpsc_cart_total_widget(false,false,false); ?></span>
           </td>
        </tr>
     <?php
          $wpec_taxes_controller = new wpec_taxes_controller();
          if($wpec_taxes_controller->wpec_taxes_isenabled()):
       ?>
        <tr class="total_price total_tax">
           <td class='wpsc_totals'>
              <?php echo wpsc_display_tax_label(true); ?>:
           </td>
           <td class='wpsc_totals'>
              <span class="pricedisplay checkout-shipping"><?php echo wpsc_cart_tax(); ?></span>
           </td>
        </tr>
       <?php endif; ?>
          
     <?php if(wpsc_uses_coupons() && (wpsc_coupon_amount(false) > 0)): ?>
      <tr class="total_price">
         <td class='wpsc_totals'>
            <?php _e('Discount:', 'sp'); ?>
         </td>
         <td class='wpsc_totals'>
            <span id="coupons_amount" class="pricedisplay"><?php echo wpsc_coupon_amount(); ?></span>
          </td>
         </tr>
     <?php endif ?>

   <tr class='total_price'>
      <td class='wpsc_totals'>
      <?php _e('Total Price', 'sp'); ?>:
      </td>
      <td class='wpsc_totals'>
         <span id='checkout_total' class="pricedisplay checkout-total"><?php echo wpsc_cart_total(); ?></span>
      </td>
   </tr>
   </table>
   </div><!--close review-->   
    <a href="#" class="step1"><span><?php _e('Go Back', 'sp'); ?></span></a>   
<!-- div for make purchase button -->
      <div class='wpsc_make_purchase'>
         <div>
            <?php if(!wpsc_has_tnc()) : ?>
               <input type='hidden' value='yes' name='agree' />
            <?php endif; ?>
               <input type='hidden' value='submit_checkout' name='wpsc_action' />
               <div class="input-button-buy"><span><input type='submit' value='<?php _e('Purchase', 'sp');?>' name='submit' class='make_purchase wpsc_buy_button' /></span></div>
         </div>
      </div>

</form>
</div>
</div><!--close slide2-->

</div><!--close checkout_page_container-->
<div class="progress_wrapper below">

 <span class="lines"></span>
 <div class="progress_bar_white"></div><!--close progress_bar-->	
    <ul>
        <li class="cart"><?php _e('Your Cart', 'sp'); ?></li>
        <li class="info"><?php _e('Info', 'sp'); ?></li>
        <li class="final"><?php _e('Final', 'sp'); ?></li>
    </ul>
</div><!--close progress_wrapper-->

<?php
do_action('wpsc_bottom_of_shopping_cart');

?>
