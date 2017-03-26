<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="viewport" content="width=device-width" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
















	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="topmain">	<div class="topmaincenter">	<div class="row"><?php if ( is_active_sidebar( 'sidebar-top1' ) ) : ?><div class="sidebar-top1 span2"><?php dynamic_sidebar('sidebar-top1'); ?></div><?php endif; ?></div>	
						<div class="row"><?php if ( is_active_sidebar( 'sidebar-top2' ) ) : ?><div class="sidebar-top2 span2"><?php dynamic_sidebar('sidebar-top2'); ?></div><?php endif; ?></div>	</div></div><div class="greyt"> </div>
	<div class="main">
		<div class="hdr1">

			<div class="head">
			<?php if ( function_exists( 'jetpack_the_site_logo' ) ) jetpack_the_site_logo(); ?>
			<h5 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h5>
			<h6 class="site-description"><?php bloginfo( 'description' ); ?></h6>

			</div>
			<?php if ( is_active_sidebar( 'sidebar-head' ) ) : ?>
				<div class="sidebar-head3 span2">
					<?php dynamic_sidebar('sidebar-head'); ?>
				</div>
			<?php endif; ?>


		</div>
	</div>

<div class="greyt"> </div>
		<div class="main3">
			<div class="main4">
					
						<ul><li>
							 <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?> </li>
						</ul>
						 
					
			</div>
		</div>
<div class="greyt"> </div>
<div class="main">
	<div class="content-main">