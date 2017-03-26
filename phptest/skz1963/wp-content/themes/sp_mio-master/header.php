<?php 
global $page, $paged, $woocommerce, $current_user;
get_currentuserinfo();
$fb_html = '';
if ( sp_isset_option( 'facebook_opengraph', 'boolean', 'true' ) && ( ( class_exists( 'WP_eCommerce' ) && wpsc_is_single_product() ) ||  ( class_exists( 'woocommerce' ) && is_product() ) ) ) 
{ 
	$fb_html = 'xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml"';
} ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js <?php echo sp_get_browser_agent();?>" <?php echo $fb_html; ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'sp' ), max( $paged, $page ) );

	?></title>
<?php 
if ( sp_isset_option( 'facebook_opengraph', 'boolean', 'true' ) && ( class_exists( 'WP_eCommerce' ) || class_exists( 'woocommerce' ) ) ) 
{ 
	if ( class_exists( 'WP_eCommerce' ) && wpsc_is_single_product() ) {
		while ( wpsc_have_products() ) : wpsc_the_product();
			$product_title = wpsc_the_product_title();
			$product_url = wpsc_the_product_permalink();
			$product_image_link = wpsc_the_product_image();
			$product_description = wpsc_the_product_description();
		endwhile;
		wp_reset_postdata(); ?>
<meta property="og:title" content="<?php echo $product_title; ?>" />
<meta property="og:type" content="<?php echo sp_isset_option( 'facebook_opengraph_type', 'value' ); ?>" />
<meta property="og:url" content="<?php echo $product_url; ?>" />
<meta property="og:image" content="<?php echo $product_image_link; ?>" />
<meta property="og:site_name" content="<?php echo bloginfo( 'name' ); ?>" />
<meta property="fb:admins" content="<?php echo sp_isset_option( 'facebook_opengraph_admin_id', 'value' ); ?>" />
<meta property="fb:app_id" content="<?php echo sp_isset_option( 'facebook_opengraph_app_id', 'value' ); ?>" /> 
<meta property="og:description" content="<?php echo strip_tags( $product_description ); ?>" />            
	<?php        
	}
	
	if ( class_exists( 'woocommerce' ) && is_product() ) {
		while ( have_posts() ) : the_post();
			$product_title = get_the_title();
			$product_url = get_permalink();
			$product_image_link = sp_get_image( get_the_ID() );
			$product_description = get_the_content();
		endwhile;
		wp_reset_postdata(); ?>
<meta property="og:title" content="<?php echo $product_title; ?>" />
<meta property="og:type" content="<?php echo sp_isset_option( 'facebook_opengraph_type', 'value' ); ?>" />
<meta property="og:url" content="<?php echo $product_url; ?>" />
<meta property="og:image" content="<?php echo $product_image_link; ?>" />
<meta property="og:site_name" content="<?php echo bloginfo( 'name' ); ?>" />
<meta property="fb:admins" content="<?php echo sp_isset_option( 'facebook_opengraph_admin_id', 'value' ); ?>" />
<meta property="fb:app_id" content="<?php echo sp_isset_option( 'facebook_opengraph_app_id', 'value' ); ?>" /> 
<meta property="og:description" content="<?php echo strip_tags( $product_description ); ?>" />            
	<?php        
	}
} // end check for opengraph and cart ?>  
<?php if (sp_isset_option( 'mobile_zoom', 'boolean', 'true' ) ) { ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php } else { ?>
<meta name="viewport" content="width=device-width, intital-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php } ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script src="<?php bloginfo('template_url'); ?>/js/1.11.3.jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/custom.js"></script>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' );?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php echo sp_facebook_script(); ?>
<?php dynamic_sidebar( 'site-top-widget' ); ?>
<div id="wrap-all">
<div class="container">
<div id="wrapper" class="hfeed">
	<header class="group" id="header">
            <!--LOGO--> 
            <?php $alt_text = sprintf( __( '%s', 'sp' ), sp_isset_option( 'logo_alt_text', 'value' ) ); ?>
            <a href="<?php echo home_url(); ?>" title="<?php echo $alt_text; ?>" id="logo">
            <?php if (sp_isset_option( 'site_logo_image_text', 'boolean', 'image' ) ) :
            			if (sp_isset_option( 'logo_image', 'isset' ) ) {
							$logo_url = sp_isset_option( 'logo_image', 'value' );
							if (is_ssl())
								$logo_url = str_replace('http', 'https', $logo_url); 
							echo '<img src="'.$logo_url.'" alt="'.sp_isset_option( 'logo_alt_text', 'value' ).'" width="80" />';
						} else {
							if (sp_isset_option( 'skins', 'boolean', '1')) {
								echo '<img src="'.get_template_directory_uri().'/images/logo.png" alt="'.sp_isset_option( 'logo_alt_text', 'value' ).'" />';
							} else {
								echo '<img src="'.get_template_directory_uri().'/skins/images/skin'.sp_isset_option( 'skins', 'value' ).'/logo.png" alt="'.sp_isset_option( 'logo_alt_text', 'value' ).'" />';
							}
						}
            	 elseif (sp_isset_option( 'site_logo_image_text', 'boolean', 'text' )) : 
                		if (sp_isset_option( 'site_logo_text_title', 'isset')) {
							echo stripslashes(sp_isset_option( 'site_logo_text_title', 'value' ));
						} else {
							_e('Your Logo Here','sp');	
						}
				endif; ?>
            </a>
            <!--END LOGO-->
        <!--TAGLINE-->
                <?php if (sp_isset_option( 'tagline', 'boolean', 'true' )) : ?>
                <h1 id="tagline">
                    <?php _e(bloginfo( 'description' ),'sp'); ?>
                </h1>
                <?php endif; ?>        
        <!--END TAGLINE-->
        <?php if ( class_exists( 'WP_eCommerce' ) || class_exists( 'woocommerce' ) ) { ?>
			<?php if(is_user_logged_in()) : ?>
            <div id="account_logout">
                <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Logout', 'sp'); ?>"><?php _e('(Logout)', 'sp'); ?></a>
                <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" class="header-logout-loading" />
            </div><!--close account_logout-->
            <?php endif; ?>
			<?php if ( class_exists( 'woocommerce' ) ) {
                $checkout_url = get_permalink( get_option( 'woocommerce_cart_page_id', true ) );
            } else {
                $checkout_url = get_option('shopping_cart_url');
            } ?>            
        <div id="header_cart"><a href="<?php echo $checkout_url; ?>" title="<?php _e('Checkout','sp'); ?>" class="cart_icon">
                <em class="count">
                	<?php $item = ''; ?>
					<?php if (class_exists('WP_eCommerce')) { 
							  if (wpsc_cart_item_count() < 2) {
								  $item = __( 'item', 'sp' );
							  } else {
								  $item = __( 'items', 'sp' );	
							  }
							  if (wpsc_cart_item_count() == 0 || isset($_GET['sessionid'])) { 
								  echo "0";
							  } else { 
								  echo wpsc_cart_item_count(); 
							  } 
						} ?>
					<?php if (class_exists('woocommerce')) {
							if ($woocommerce->cart->cart_contents_count < 2) {
								$item = __( 'item', 'sp' );
							} else {
								$item = __( 'items', 'sp' );
							}
							if ($woocommerce->cart->cart_contents_count == 0) {
								echo "0";
							} else {
								echo $woocommerce->cart->cart_contents_count;
							}
                    } ?>
                          
                        </em> <em class="item"><?php echo __($item,'sp'); ?></em> | <span class="icon"><?php _e( 'Cart', 'sp' ); ?></span><?php _e("Checkout",'sp'); ?><span class="shadow"></span></a>
    	</div><!--close header_cart-->
		<?php if ( class_exists( 'woocommerce' ) ) {
            $account_url = get_permalink( get_option( 'woocommerce_myaccount_page_id', true ) );
        } else {
            $account_url = get_option('user_account_url');
        } ?>
        
		<div id="account">
            <a href="<?php echo $account_url; ?>" title="<?php _e('My Account','sp'); ?>" class="account_icon"><span class="icon"><?php _e( 'Account', 'sp' ); ?></span><?php _e("My Account",'sp'); ?><span class="shadow"></span></a>
        </div><!--close account-->
        <?php } ?>
			<?php /* if dynamic menu is not set, it fallbacks to wp_list_pages function */ ?>
            <nav class="group" id="main-nav">
            <?php wp_nav_menu( array( 'container' => 'false', 'fallback_cb' => 'header_menu', 'theme_location' => 'header', 'before' => '<span class="before">&nbsp;</span>', 'link_before' => '<span></span>') ); ?> 
        	<?php get_search_form(); ?>
            </nav>
        <input type="hidden" value="<?php echo sp_isset_option( 'skins', 'value' ); ?>" id="skins" />        
	</header>

	<div id="content_container" class="group">
