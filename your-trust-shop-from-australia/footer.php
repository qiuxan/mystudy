<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SKZ1963shopping
 */

?>

	

<?php wp_footer(); ?>

<footer>
		<div class="container">
			
			<div class="col-sm-3">
				<p> <a href="/"> <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/logo.png" alt="mylogo"></a></p>				
			</div> <!--col end-->
			<div class="col-sm-6">
				<nav>
					
					<ul class="list-unstyled list-inline">
						<li><a href="">主页</a></li>
						<li><a href="">博客</a></li>
						<li><a href="">产品</a></li>
						<li><a href="">联系我们</a></li>
					</ul>	
					
				</nav>	<!--nav end-->			
			</div> <!--col end-->
			<div class="col-sm-3">
						<p class="pull-right">&copy; 2016 Xian Qiu</p>		
			</div> <!--col end-->
		</div> <!--container end-->
		
	</footer> <!--footer end-->


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/js/jquery-2.1.1.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/js/bootstrap.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/assets/js/main.js"></script>
</body>
</html>
