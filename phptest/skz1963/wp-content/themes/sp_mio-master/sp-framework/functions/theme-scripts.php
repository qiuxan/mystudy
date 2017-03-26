<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* theme scripts
******************************************************************/

/**
 * remove contact form 7 style/scripts
 *
 */
if ( function_exists( 'wpcf7_contact_form' ) ) 
{
	if ( ! is_admin() && WPCF7_LOAD_JS ) 
		remove_action( 'wp_enqueue_scripts', 'wpcf7_enqueue_styles' );
}

// enqueue theme scripts
function sp_scripts()
{
	global $spthemeinfo, $spdata;
	
	// comment out the next two lines to load the local copy of jQuery
	//wp_deregister_script( 'jquery' );
	//wp_register_script( 'jquery', sp_ssl_http() . 'ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', '', '1.8.3', false );
	wp_enqueue_script( 'jquery' );
	// enqueue modernizr script
	wp_register_script( apply_filters( 'sp_modernizr', 'modernizr' ), get_template_directory_uri() . '/js/modernizr.min.js', '', '2.6.2', false );
	wp_enqueue_script( apply_filters( 'sp_modernizr', 'modernizr' ) );
	// enqueue respond.js script
	wp_register_script( apply_filters( 'sp_respond_js', 'respond-js' ), get_template_directory_uri() . '/js/respond.min.js', '', '1.1.0', false );
	wp_enqueue_script( apply_filters( 'sp_respond_js', 'respond-js' ) );
	// checks for theme specific js then load them
	if ( file_exists( get_template_directory() . '/sp-theme-js.php' ) ) 
	{
		require_once( get_template_directory() . '/sp-theme-js.php' );	
	}
	// enqueue pretty photo
	wp_register_script( apply_filters( 'sp_pretty_photo', 'pretty-photo' ), get_template_directory_uri() . '/js/jquery.prettyPhoto.js', '', '3.1.4', true );
	wp_enqueue_script( apply_filters( 'sp_pretty_photo', 'pretty-photo' ) );	
	// enqueue touch swipe
	wp_register_script( apply_filters( 'sp_touch_swipe', 'touch-swipe' ), get_template_directory_uri() . '/js/jquery.touchSwipe.js', '', '1.5.1', true );
	wp_enqueue_script( apply_filters( 'sp_touch_swipe', 'touch-swipe' ) );
	// enqueue caroufredsel
	wp_register_script( apply_filters( 'sp_caroufredsel', 'caroufredsel' ), get_template_directory_uri() . '/js/jquery.carouFredSel-packed.js', '', '6.1.0', true );
	wp_enqueue_script( apply_filters( 'sp_caroufredsel', 'caroufredsel' ) );
	// enqueue jquery ui
	wp_register_script( apply_filters( 'sp_jquery_ui', 'sp_jquery_ui' ), sp_ssl_http() . 'ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js', '', '1.9.2', true );
	wp_enqueue_script( apply_filters( 'sp_jquery_ui', 'sp_jquery_ui' ) );		
	// enqueue video js
	wp_register_script( apply_filters( 'sp_video_js', 'video-js' ), get_template_directory_uri() . '/js/video.min.js', '', '3.2.0', true );
	wp_enqueue_script( apply_filters( 'sp_video_js', 'video-js' ) );
	// enqueue audio js
	wp_register_script( apply_filters( 'sp_audio_js', 'audio-js' ), get_template_directory_uri() . '/js/audio.min.js', '', $spthemeinfo['Version'], false );
	wp_enqueue_script( apply_filters( 'sp_audio_js', 'audio-js' ) );		
	// jquery isotope
	wp_register_script( apply_filters( 'sp_isotope', 'isotope' ), get_template_directory_uri() . '/js/jquery.isotope.min.js', '', '1.5.25', true );
	wp_enqueue_script( apply_filters( 'sp_isotope', 'isotope' ) );										
	// enqueue theme JS
	wp_register_script( 'theme-js', get_template_directory_uri() . '/js/theme.min.js', '', $spthemeinfo['Version'], true );
	wp_enqueue_script( 'theme-js' );	
	// localize a namespace for ajax functions
	// check if admin login is set to SSL
	$ssl = 'http';
	if ( is_ssl() ) {
		$ssl = 'https';	
	}
	// set variables if WPEC or WOO plugin is active
	( class_exists( 'woocommerce' ) ) ? $woo_active = true : $woo_active = false; 
	( class_exists( 'WP_eCommerce' ) ) ? $wpec_active = true : $wpec_active = false;
	
	// set mobile ready variable
	$mobile_ready = false;
	if ( sp_isset_option( 'mobile_ready', 'boolean', 'true' ) )
		$mobile_ready = true;
	
	$localized_vars = array(
		'ajaxurl' => admin_url( 'admin-ajax.php', $ssl ),
		'ajaxCustomNonce' => wp_create_nonce( 'ajax_custom_nonce' ),
		'framework_url' => FRAMEWORK_URL,
		'theme_url' => get_template_directory_uri() . '/',
		'site_url' => home_url() . '/',
		'WPSC_CORE_IMAGES_URL' => ( defined( 'WPSC_CORE_IMAGES_URL' ) ? WPSC_CORE_IMAGES_URL : '' ), 
		'woo_active' => $woo_active,
		'wpec_active' => $wpec_active,
		'mobile_ready' => $mobile_ready,
		
		// localized text
		'reset_email_sent' => __( 'Reset e-mail sent!', 'sp' ),
		'mobile_go_to_text' => __( 'Go to...', 'sp' ),
		'core_theme_version' => $spthemeinfo['Version'],
		'core_framework_version' => SP_FRAMEWORK_VERSION
	);
	
	wp_localize_script( 'theme-js', 'sp_custom', $localized_vars );		
}

if ( ! is_admin() ) 
{
	add_action( 'wp_enqueue_scripts', 'sp_scripts' );
}

// deregister all wpec styles and use our own
if ( ! is_admin() && class_exists( 'WP_eCommerce' ) ) 
{
	add_action( 'wp_print_styles', 'sp_deregister_styles', 100 );
}

function sp_deregister_styles() 
{
	wp_deregister_style( 'wpsc-thickbox' );
	wp_deregister_style( 'wpsc-colorbox-css' );
	wp_deregister_style( 'wpsc-theme-css' );
	wp_deregister_style( 'wpsc-theme-css-compatibility' );
	wp_deregister_style( 'wpsc-product-rater' );
	wp_deregister_style( 'wp-e-commerce-dynamic' );
	wp_deregister_style( 'wpsc-gold-cart-grid-view' );
	wp_deregister_style( 'wpsc-gold-cart' );
	remove_action( 'wp_head', 'wpsc_grid_custom_styles', 9 );
}

// remove gold cart styles
remove_action( 'wp_head', 'wpsc_grid_custom_styles', 9 );

// deregister all wpec scripts to move them to the footer where they belong
if ( ! is_admin() && class_exists( 'WP_eCommerce' ) ) 
{
	add_action( 'wp_enqueue_scripts', 'sp_deregister_wpec_js', 100 );
}

function sp_deregister_wpec_js() 
{
	wp_deregister_script( 'wp-e-commerce' );
	wp_deregister_script( 'infieldlabel' );
	wp_deregister_script( 'wp-e-commerce-ajax-legacy' );
	wp_deregister_script( 'wp-e-commerce-dynamic' );
	wp_deregister_script( 'livequery' );
	wp_deregister_script( 'jquery-rating' );
	//wp_deregister_script( 'wp-e-commerce-legacy' );
	wp_deregister_script( 'wpsc-thickbox' );	
	wp_deregister_script( 'colorbox-min' );
	wp_deregister_script( 'wpsc_colorbox' );
	wp_dequeue_script( 'wpsc-gold-cart' );
}

// register and enqueue the wpec scripts and put them in the footer
function sp_wpec_scripts() 
{

	wp_register_script( 'sp_wp-e-commerce', plugins_url() . '/wp-e-commerce/wpsc-core/js/wp-e-commerce.js', '', WPSC_VERSION, true );
	wp_enqueue_script( 'sp_wp-e-commerce' );

	$ssl = 'http';
	if ( is_ssl() ) 
	{
		$ssl = 'https';	
	}		
	wp_localize_script( 'sp_wp-e-commerce', 'wpsc_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php', $ssl ) ) );
}

if ( ! is_admin() && class_exists( 'WP_eCommerce' ) ) 
{
	add_action( 'wp_enqueue_scripts', 'sp_wpec_scripts' );
}

// load wpec styles
function sp_wpec_styles() 
{
	global $spthemeinfo;
		
	wp_register_style( 'sp_wpec_styles', get_template_directory_uri() . '/style-wpec.css', '', $spthemeinfo['Version'] );	
	wp_enqueue_style( 'sp_wpec_styles' );
	
}

if ( ! is_admin() && class_exists( 'WP_eCommerce' ) ) :
	add_action( 'wp_enqueue_scripts', 'sp_wpec_styles' );
endif;

// deregister all woo scripts to move them to the footer where they belong
if ( ! is_admin() && class_exists( 'woocommerce' ) ) :
		add_action( 'wp_enqueue_scripts', 'sp_deregister_woo_scripts', 100 );
endif;

function sp_deregister_woo_scripts() 
{
	wp_dequeue_style( 'woocommerce_frontend_styles' );
	wp_dequeue_style( 'woocommerce_fancybox_styles' );
	wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	wp_dequeue_script( 'fancybox' );
	wp_dequeue_script( 'jqueryui' );
	wp_deregister_script( 'jqueryui' );
	wp_dequeue_script( 'prettyPhoto' );
	wp_dequeue_script( 'prettyPhoto-init' );
}

// load woocommerce styles
function sp_woo_styles() 
{
	global $spthemeinfo;
		
	wp_register_style( 'sp_woocommerce_styles', get_template_directory_uri() . '/style-woo.css', '', $spthemeinfo['Version'] );	
	wp_enqueue_style( 'sp_woocommerce_styles' );
	
}

if ( ! is_admin() && class_exists( 'woocommerce' ) ) :
	add_action( 'wp_enqueue_scripts', 'sp_woo_styles' );
endif;

// load skin styles
function sp_skin_styles()
{
	global $spdata, $spthemeinfo;
	
	wp_register_style( 'sp_skin_styles', get_template_directory_uri() . '/skins/skin' . $spdata[THEME_SHORTNAME . 'skins'] . '.css', '', $spthemeinfo['Version'] );	
	wp_enqueue_style( 'sp_skin_styles' );
}

global $spdata;
// load only if skin is active
if ( isset( $spdata[THEME_SHORTNAME . 'skins'] ) && $spdata[THEME_SHORTNAME . 'skins'] != '1' && $spdata[THEME_SHORTNAME . 'skins'] != '' ) 
{
	add_action( 'wp_enqueue_scripts', 'sp_skin_styles' );
}

// load mobile styles
function sp_mobile_styles() 
{
	global $spthemeinfo;
	
	wp_register_style( 'sp_mobile_styles', get_template_directory_uri() . '/mobile-styles.css', '', $spthemeinfo['Version'] );	
	wp_enqueue_style( 'sp_mobile_styles' );
}

if ( ! is_admin() && ( sp_isset_option( 'mobile_ready', 'boolean', 'true' ) ) ) :
	add_action( 'wp_enqueue_scripts', 'sp_mobile_styles' );
endif;


function sp_custom_styles() 
{
	global $spthemeinfo;
	
	$deps = array();
	
	if ( wp_style_is( 'sp_woocommerce_styles', 'queue' ) )
		array_unshift( $deps, 'sp_woocommerce_styles' );
	
	if ( wp_style_is( 'sp_wpec_styles', 'queue' ) )
		array_unshift( $deps, 'sp_wpec_styles' );
		
	if ( wp_style_is( 'sp_mobile_styles', 'queue' ) )
		array_unshift( $deps, 'sp_mobile_styles' );	

	if ( wp_style_is( 'sp_skin_styles', 'queue' ) )
		array_unshift( $deps, 'sp_skin_styles' );
		
	if ( is_child_theme() ) 
	{ 
		if ( file_exists( get_stylesheet_directory() . '/custom_styles.css' ) )
		{ 
			wp_register_style( 'sp_custom_styles', get_stylesheet_directory_uri() . '/custom_styles.css', $deps, $spthemeinfo['Version'] );
			wp_enqueue_style( 'sp_custom_styles' );
		}
	} 
	else 
	{
		if ( file_exists( get_template_directory() . '/custom_styles.css' ) )
		{		
			wp_register_style( 'sp_custom_styles', get_template_directory_uri() . '/custom_styles.css', $deps, $spthemeinfo['Version'] ); 
			wp_enqueue_style( 'sp_custom_styles' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'sp_custom_styles' );
?>