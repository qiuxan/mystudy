<?php
/**
* SP FRAMEWORK FILE - DO NOT EDIT!
* 
* class widgets
******************************************************************/

add_action( 'widgets_init', 'sp_widgets' );

/* Function that registers our widget. */
function sp_widgets() 
{
	if ( class_exists('WP_eCommerce') )
	{
		register_widget( 'SP_WPEC_Category_Accordion' );
		register_widget( 'SP_WPEC_Price_Range_Slider' );
	}
	
	register_widget( 'SP_Promotion_Widget' );
}

class SP_WPEC_Category_Accordion extends WP_Widget 
{

	function SP_WPEC_Category_Accordion() 
	{
		// Instantiate the parent object
		parent::__construct( false, __( 'SP Product Category', 'sp' ) );

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sp-wpec-category-widget', 'description' => __( 'Custom SP widget to display WPEC product categories in an accordion fashion', 'sp' ) );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'sp-wpec-category-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sp-wpec-category-widget', __( 'SP Product Category', 'sp' ), $widget_ops, $control_ops );	

	}	

	function widget( $args, $instance ) 
	{
		global $wpdb;
		
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		$count = $instance['count'];
		
		/* Before widget (defined by theme). */
		echo $before_widget;

		/* Title of widget (before and after defined by theme). */
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( !isset( $instance['categories'] ) ){
			$instance_categories = get_terms( 'wpsc_product_category', 'hide_empty=0&parent=0');
			if(!empty($instance_categories)){
				foreach($instance_categories as $categories){

					$instance['categories'][$categories->term_id] = 'on';
				}
			}
		}
		foreach ( array_keys( (array)$instance['categories'] ) as $category_id ) {

			if ( ! get_term( $category_id, "wpsc_product_category" ) ) 
				continue;

			$curr_cat = get_term( $category_id, 'wpsc_product_category', ARRAY_A );
				
			?>

			<ul class="wpsc_categories wpsc_top_level_categories sp-cat-accordion">
				<li class="wpsc_category_<?php echo $curr_cat['term_id']; wpsc_print_category_classes($curr_cat);  ?>">
					<a href="#" class="header"><?php echo esc_html( $curr_cat['name'] ); ?></a>

					<ul class="wpsc_categories wpsc_second_level_categories">
	
							<?php wpsc_start_category_query( array( 'parent_category_id' => $category_id, 'show_thumbnails' => false, 'show_name' => true) ); ?>
								<li class="wpsc_category_<?php wpsc_print_category_id(); wpsc_print_category_classes_section();?>">
									<a href="<?php wpsc_print_category_url(); ?>" class="wpsc_category_link">
										<?php wpsc_print_category_name(); ?>
										<?php if ( 1 == $count ) wpsc_print_category_products_count( "(",")" ); ?>
									</a>
	
									<?php wpsc_print_subcategory( '<ul>', '</ul>' ); ?>
	
								</li>
							<?php wpsc_end_category_query(); ?>
				   </ul>
				</li>
			</ul>
		<?php
		}
			
		/* After widget (defined by themes). */
		echo $after_widget;
	}	


	function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
		
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['count'] = $new_instance['count'] ? 1 : 0;
		$instance['categories'] = $new_instance['categories'];

		return $instance;
	}

	function form( $instance ) 
	{
		$defaults = array( 'title' => 'Product Categories', 'count' => false );
		$instance = wp_parse_args( ( array ) $instance, $defaults ); 
		$count    = (bool)$instance['count'];
		?>
		<p>
			<?php _e( 'Show Categories', 'sp' ); ?>: <br />
			<?php wpsc_list_categories( 'wpsc_category_widget_admin_category_list', array( "id" => $this->get_field_id( 'categories' ), "name" => $this->get_field_name( 'categories' ), "instance" => $instance ), 0 ); ?>
			<?php _e( '(leave all unchecked if you want to display all)', 'sp' ); ?>
		</p>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'sp' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" <?php checked( $count ); ?>/>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show Product Count in Category', 'sp' ); ?></label>
		</p>

		<?php
    }	
}

class SP_WPEC_Price_Range_Slider extends WP_Widget 
{

	function SP_WPEC_Price_Range_Slider() 
	{
		// Instantiate the parent object
		parent::__construct( false, 'SP Product Price Range Slider' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sp-wpec-price-range-slider-widget', 'description' => __( 'Custom SP widget to filter WPEC products in a price range slider.', 'sp' ) );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'sp-wpec-price-range-slider-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sp-wpec-price-range-slider-widget', 'SP Product Price Range Slider', $widget_ops, $control_ops );	

	}	

	function widget( $args, $instance ) 
	{
		global $wpdb;
		
		extract( $args );

		/* User-selected settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		/* Before widget (defined by theme). */
		echo $before_widget;

		/* Title of widget (before and after defined by theme). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		$results = $wpdb->get_results( "SELECT DISTINCT CAST(`meta_value` AS DECIMAL) AS `price` FROM " . $wpdb->postmeta . " AS `m` WHERE `meta_key` IN ('_wpsc_price') ORDER BY `price` ASC", ARRAY_A );
		
		if ( $results )
		{
			foreach ( $results as $k => $v ) 
			{
				$price[] = $v['price'];
			}
		}
		
		if ( is_array( $price ) && isset( $price ) )
		{
			?>
			<p>
            	<?php if ( isset( $_GET['range'] ) && $_GET['range'] != '' ) {
					$range = explode( '-', mysql_real_escape_string( $_GET['range'] ) );
					$selected_min = $range[0];
					$selected_max = $range[1];
				} else {
					$selected_min = min( $price );
					$selected_max = max( $price );	
				}
				?>
				<input type="text" id="price" />
                <input type="hidden" id="price-max" value="<?php echo max( $price ); ?>" />
                <input type="hidden" id="price-min" value="<?php echo min( $price ); ?>" />
                <input type="hidden" id="selected-min" value="<?php echo $selected_min; ?>" />
                <input type="hidden" id="selected-max" value="<?php echo $selected_max; ?>" />
                <input type="hidden" id="currsymbol" value="<?php echo wpsc_get_currency_symbol(); ?>" />
			</p>
			
			<div id="slider-range"></div>	
            
            <p>
            	<?php $link = trailingslashit( get_option( 'product_list_url' ) ); ?>
            	<a href="<?php echo esc_attr( $link . '?range=all' ); ?>" title="Price Range Filter" class="price-filter"><span><?php _e( 'Filter', 'sp' ); ?></span></a>
            </p>	
            <?php
		}
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}	


	function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
		
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = esc_attr( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) 
	{
		$defaults = array( 'title' => 'Price Range Filter' );
		$instance = wp_parse_args( ( array ) $instance, $defaults ); 
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'sp' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<?php
    }	
}

class SP_Promotion_Widget extends WP_Widget 
{

	function SP_Promotion_Widget() 
	{
		// Instantiate the parent object
		parent::__construct( false, 'SP Promotion Widget' );

		/* Widget settings. */
		$widget_ops = array( 'classname' => 'sp-promotion-widget', 'description' => __( 'Custom SP widget to display any promotional items.', 'sp' ) );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'sp-promotion-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'sp-promotion-widget', 'SP Promotion Widget', $widget_ops, $control_ops );	

	}	

	function widget( $args, $instance ) 
	{
		global $wpdb;
		
		extract( $args );

		$content = $instance['content'];
		$timezone = $instance['timezone'];
		
		// sets the default timezone based on user selection
		if ( isset( $timezone ) && ( $timezone != '0' ) ) 
			date_default_timezone_set($timezone);

		$start_time = strtotime( $instance['start_time'] );
		$end_time = strtotime( $instance['end_time'] ); 
		
		// current unix timestamp
		$cur_time = time();
		
		if ( ( $cur_time >= $start_time ) && ( $cur_time <= $end_time ) )
		{
			/* Before widget (defined by theme). */
			echo $before_widget;
			echo $content;
			
			/* After widget (defined by themes). */
			echo $after_widget;
		}
	}	


	function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
		$instance['content'] = $new_instance['content'];
		$instance['timezone'] = $new_instance['timezone'];
		$instance['start_time'] = $new_instance['start_time'];
		$instance['end_time'] = $new_instance['end_time'];
		
		return $instance;
	}

	function form( $instance ) 
	{
		$defaults = array( 'content' => '', 'timezone' => '0', 'start_time' => '', 'end_time' => '' );
		$instance = wp_parse_args( ( array ) $instance, $defaults ); 
		$timezone = $instance['timezone'];
		?>
        <p>
        	<label for="<?php echo $this->get_field_id( 'timezone' ); ?>"><?php _e( 'Select Time Zone:', 'sp' ); ?></label><br />
        	<?php 	
			include( get_template_directory() . '/sp-framework/functions/timezones.php' );
			$output = '<select id="' . $this->get_field_id( 'timezone' ) . '" name="' . $this->get_field_name( 'timezone' ) . '">' . "\r\n";
			$output .= '<option value="0">' . __( '--Please Select--', 'sp' ) . '</option>' . "\r\n";
			foreach ( $timezone_list as $k => $v )
			{
				$output .= '<option value="' . $k . '" ' . ( ( $k == $timezone  ) ? 'selected="selected"' : '' ) . '>' . $k . '</option>' . "\r\n";	
			}
			$output .= '</select>';
			
			echo $output;
 			?>
        </p>
        
        <p>
        	<label for="<?php echo $this->get_field_id( 'start_time' ); ?>"><?php _e( 'Select Start Time:', 'sp' ); ?></label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'start_time' ); ?>" name="<?php echo $this->get_field_name( 'start_time' ); ?>" class="datepicker"  value="<?php echo $instance['start_time']; ?>" /><span class="sptooltip"><span class="tip"><?php _e( 'Select the time you want to start displaying this widget', 'sp' ); ?></span></span>
        </p>

        <p>
        	<label for="<?php echo $this->get_field_id( 'end_time' ); ?>"><?php _e( 'Select End Time:', 'sp' ); ?></label><br />
            <input type="text" id="<?php echo $this->get_field_id( 'end_time' ); ?>" name="<?php echo $this->get_field_name( 'end_time' ); ?>" class="datepicker" value="<?php echo $instance['end_time']; ?>" /><span class="sptooltip"><span class="tip"><?php _e( 'Select the time you want to stop displaying this widget', 'sp' ); ?></span></span>
        </p>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:', 'sp' ); ?></label><br />
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" style="width:100%;height:200px;"><?php echo $instance['content']; ?></textarea>
		</p>
		<?php
    }	
}

?>