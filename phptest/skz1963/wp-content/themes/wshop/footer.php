<div class="afooter">
	<div class="container">
		<div class="sidebar-box">
		        <!--widgets-->
	        <div class="row">
	            <?php if ( is_active_sidebar( 'sidebar-footer1' ) ) : ?><div class="sidebar-footer1 span2">
	                 <?php dynamic_sidebar( 'sidebar-footer1' ); ?>
	            </div><?php endif; ?>

	            <?php if ( is_active_sidebar( 'sidebar-footer2' ) ) : ?><div class="sidebar-footer2 span2">
	                <?php dynamic_sidebar( 'sidebar-footer2' ); ?>
	            </div><?php endif; ?>

	            <?php if ( is_active_sidebar( 'sidebar-footer3' ) ) : ?><div class="sidebar-footer3 span2">
	                <?php dynamic_sidebar( 'sidebar-footer3' ); ?>
	            </div><?php endif; ?>
	            <?php if ( is_active_sidebar( 'sidebar-footer4' ) ) : ?><div class="sidebar-footer4 span2">
	                <?php dynamic_sidebar( 'sidebar-footer4' ); ?>
	            </div><?php endif; ?>
	        </div>
		</div>
	</div>
</div>

<div class="afooter2">
	<div class="footer">


		<div class="row">
			<?php if ( is_active_sidebar( 'sidebar-footer5' ) ) : ?><div class="sidebar-footer5 span2">
				<?php dynamic_sidebar( 'sidebar-footer5' ); ?>
			</div><?php endif; ?>
		</div>

		<div class="mlogo">
			<div class="sidebar-user2 span2"><?php _e( 'Powered by', 'wshop' ); ?> <a href="http://wordpress.org" target="_blank">WordPress</a>. <?php _e( 'Theme', 'wshop' ); ?> <a href="http://justpx.com/" target="_blank">WShop</a></div>


		</div>		
	</div>
</div>


	<?php wp_footer(); ?>
</body>
</html>