<?php 
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* custom menu
******************************************************************/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// list pages for menu if dynamic primary menu function does not exist
if (!function_exists('header_menu')) :
	function header_menu(){ ?>
                    <p style="color:blue;margin:20px 0;font-size:16px;display:block;">Please setup your menu items by going to your Wordpress backend "appearance/menus".</p>
	<?php }
endif;
// list pages for menu if dynamic secondary menu function does not exist
if (!function_exists('footer_menu')) :
	function footer_menu(){ ?>
					<p style="color:blue;margin:20px 0;font-size:16px;display:block;">Please setup your menu items by going to your Wordpress backend "appearance/menus".</p>
	<?php }
endif;

?>