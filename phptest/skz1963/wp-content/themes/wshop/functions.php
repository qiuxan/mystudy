<?php
add_action( 'after_setup_theme', 'wshop_setup' );
function wshop_setup() {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(166, 124, TRUE); 
	global $content_width;
	if ( ! isset( $content_width ) )
	$content_width = 960;
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );						// background
	$defaults = array(												// background
		'default-color'          => '',
		'default-image'          => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''	);
	add_action( 'wp_enqueue_scripts', 'wshop_frontend' );
	add_editor_style( 'editor-style.css' );	add_theme_support( 'title-tag' );
	add_theme_support( 'woocommerce' );
	add_image_size( 'wshop-logo-size', 330, 100, true );
    add_theme_support( 'site-logo', array( 'size' => 'wshop-logo-size' ) );
    load_theme_textdomain( 'wshop', get_template_directory() . '/languages' );}
add_action('wp_enqueue_scripts' , 'wshop_enqueue_resources');function wshop_enqueue_resources() { if ( is_singular() ) wp_enqueue_script( "comment-reply" );}
function wshop_register_my_menu() {
  		register_nav_menu('header-menu', __( 'Header Menu', 'wshop' ));
	}
add_action( 'init', 'wshop_register_my_menu' );
if ( ! function_exists( '_wp_render_title_tag' ) ) :
    function wshop_render_title() {
?>
<title><?php wp_title( '-', true, 'right' ); ?></title>
<?php
    }
    add_action( 'wp_head', 'wshop_render_title' );
endif;
function wshop_widgets() {
		register_sidebar(		array(
			'id' => 'sidebar-left', 		 	'name' => __( 'sidebar-left', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-head',      		'name' => __( 'sidebar-head', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-top1', 		 	'name' => __( 'sidebar-top1', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-top2', 		 	'name' => __( 'sidebar-top2', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer1',			'name' => __( 'sidebar-footer1', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer2',			'name' => __( 'sidebar-footer2', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer3',			'name' => __( 'sidebar-footer3', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer4',			'name' => __( 'sidebar-footer4', 'wshop' ),			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer5',			'name' => __( 'sidebar-footer5', 'wshop' ),	
		)		);
}
add_action( 'widgets_init', 'wshop_widgets' );
add_filter('loop_shop_per_page', create_function('$cols', 'return 12;'));
add_filter('loop_shop_columns', 'wshop_loop_columns');
if (!function_exists('wshop_loop_columns')) {
	function wshop_loop_columns() {
		return 3;
	}
}
function wshop_frontend() {
 	wp_enqueue_style( 'wshop-style', get_stylesheet_uri() );}
function woocommerce_output_related_products() {    $args = array('posts_per_page' => 3, 'columns' => 3,'orderby' => 'rand' );    woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );}
function wshop_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	if ( $paged >= 3 || $page >= 3 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'wshop' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'wshop_wp_title', 10, 3 );
add_filter( 'wp_tag_cloud', 'wshop_tag_cloud' );
function wshop_tag_cloud( $tags ){
    return preg_replace(
        "~ style='font-size: (\d+)pt;'~",
        ' class="tag-cloud-size-\10"',
        $tags
    );
}
function wshop_menu() {
	add_theme_page('WShop Setup', 'Free vs PRO', 'edit_theme_options', 'wshop', 'wshop_menu_page');
}
add_action('admin_menu', 'wshop_menu');
function wshop_menu_page() {
echo '
<br>
<center><h1 style="font-size:79px;">' . __( 'Theme WShop free', 'wshop' ) . '</h1></ceter>
<br><br><br>
	<center><h1>' . __( '9 Sidebar for theme WShop', 'wshop' ) . '</h1></ceter>
<br>
<center><img src="' . get_template_directory_uri() . '/images/wshop-sidebar.jpg"></center>
<br><br><br>
<h1><center>' . __( 'Site ', 'wshop' ) . '<a href="http://justpx.com/wshop-free-documentation/">' . __( 'WShop free ', 'wshop' ) . '</a>' . __( ' -  documentation (Logo, favicon, ...).', 'wshop' ) . '</center></h1><br><br>
<br><br>
<center><img src="' . get_template_directory_uri() . '/images/pro-vs-free.png"></center><br><br>
<center><b>' . __( 'Localization Ready:', 'wshop' ) . '</b> ' . __( 'Chinese, Czech, Dutch, English, French, German, Greek, Hungarian, Indonesian, Italian, Japanese, Polish, Romana, Spanish, ... and other.  Add ', 'wshop' ) . '<a href="http://justpx.com/your-language">' . __( 'Your language', 'wshop' ) . '</a>. </center><br/><br/>
<br><br>
<center><h1 style="font-size:79px;">' . __( 'Theme WShop PRO', 'wshop' ) . '</h1></ceter><br><br>
<h1><center>' . __( ' Page ', 'wshop' ) . ' <a href="http://justpx.com/product/wshop-pro/" target="_blank">' . __( ' WShop PRO ', 'wshop' ) . '</a>' . __( ' - theme, demo, documentation.', 'wshop' ) . '</center></h1><br><br>
<h1><center>' . __( 'WShop PRO width: 1280px - ', 'wshop' ) . '<a href="http://wshop-pro.justpx.com/" target="_blank">' . __( 'Demo', 'wshop' ) . '</a></center></h1><br>
<center><h1><font color="#dd3f56">10%</font>' . __( ' Discount - Code: ', 'wshop' ) . '<font color="#dd3f56">justpx10</font></h1></ceter>
<br/><br/><br/><br/>
<center><h1>' . __( 'WShop PRO 38 Sidebar Home page 1', 'wshop' ) . '</h1></ceter>
<br/><br/>
<center><img src="' . get_template_directory_uri() . '/images/wshop-sidebar-home-page-1.jpg"></center>
<br/><br/>
<center><h1>' . __( 'WShop PRO 32 Sidebar Home page 1', 'wshop' ) . '</h1></ceter>
<br/><br/>
<center><img src="' . get_template_directory_uri() . '/images/wshop-sidebar-home-page-2.jpg"></center>
<br/><br/>
<center><h1>' . __( 'WShop PRO - Theme Options', 'wshop' ) . '</h1></ceter><center><img src="' . get_template_directory_uri() . '/images/admin-1.jpg"></center><br/><br/><center><img src="' . get_template_directory_uri() . '/images/admin-2.jpg"></center><br/><br/><center><img src="' . get_template_directory_uri() . '/images/admin-3.jpg"></center><br/><br/>
<h1><center>' . __( 'Buy theme', 'wshop' ) . '  <a href="http://justpx.com/product/wshop-pro/">' . __( 'WShop PRO', 'wshop' ) . '</a></center></h1><br><br>
';
}
?>