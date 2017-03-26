<?php 
global $post, $wpdb;

// get current page/post's sitewide widget check
$sitewide_widgets = get_post_meta( $post->ID, '_sp_page_layout_sitewide_widgets', true );
?>

<div id="sidebar-right" class="sidebar">
	<div class="widget-wrapper">
<?php
	if (sp_isset_option( 'sitewide_widget_enable', 'boolean', 'true' ) && $sitewide_widgets != true ) {
		if (!dynamic_sidebar('sidebar-right-sitewide')) : ?>
		<aside id="meta" class="widget widget-container">
			<h3 class="widget-title"><?php _e( 'Meta', 'sp' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</aside>
		<?php endif; ?>
    <?php } ?>

<?php
	if (sp_isset_option( 'custom_page_widget', 'isset' ) && is_array(sp_isset_option( 'custom_page_widget', 'value' ))) {
		$page_ids = sp_isset_option( 'custom_page_widget', 'value' );
		
		foreach($page_ids as $page) {
			if ($post->ID == $page) {
				dynamic_sidebar('sidebar-right-page-'.$page);	
			}
		}
	}
?>    

<?php
	if (sp_isset_option( 'custom_blog_category_widget', 'isset' ) && is_array(sp_isset_option( 'custom_blog_category_widget', 'value' ))) {
		$cat_ids = sp_isset_option( 'custom_blog_category_widget', 'value' );
		
		foreach($cat_ids as $cat) {
			if (in_category($cat)) {
				dynamic_sidebar('sidebar-right-blog-category-'.$cat);	
			}
		}
	}
?>    

<?php
	// check if WPEC is active
	if (class_exists('WP_eCommerce')) {
		if (sp_isset_option( 'custom_product_category_widget', 'isset' ) && is_array(sp_isset_option( 'custom_product_category_widget', 'value' ))) {
			global $post;
			$cat_obj = wp_get_post_terms($post->ID, 'wpsc_product_category', array('fields' => 'ids'));
			$cat_ids = sp_isset_option( 'custom_product_category_widget', 'value' );
			foreach($cat_ids as $cat) {
				if (is_array($cat_obj)) {
					if (in_array($cat, $cat_obj)) {
						dynamic_sidebar('sidebar-right-product-category-'.$cat);	
					}
				}
			}
		}
	}
	// check if WOO is active
	if (class_exists('woocommerce')) {
		if (sp_isset_option( 'custom_product_category_widget', 'isset' ) && is_array(sp_isset_option( 'custom_product_category_widget', 'value' ))) {
			global $post;
			$cat_obj = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'ids'));
			$cat_ids = sp_isset_option( 'custom_product_category_widget', 'value' );
			foreach($cat_ids as $cat) {
				if (is_array($cat_obj)) {
					if (in_array($cat, $cat_obj)) {
						dynamic_sidebar('sidebar-right-product-category-'.$cat);	
					}
				}
			}
		}
	}

?>    
    
	</div><!--close widget-wrapper-->
</div><!--close sidebar-->