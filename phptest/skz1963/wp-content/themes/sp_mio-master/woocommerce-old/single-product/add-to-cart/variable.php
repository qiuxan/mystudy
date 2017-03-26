<?php
/**
 * Variable product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $product, $post;
?>

<?php do_action('woocommerce_before_add_to_cart_form'); ?>
<?php if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) { ?>
<script type="text/javascript">
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
</script>
<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart group" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>">
	<table class="variations variations_hook"><?php $i = 1; ?>
		<tbody>
			<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
            <?php if ($i&1) { ?><tr><?php } ?>
					<td class="value"><label for="<?php echo sanitize_title($name); ?>"><?php echo $woocommerce->attribute_label($name); ?></label><br /><select id="<?php echo esc_attr( sanitize_title($name) ); ?>" name="attribute_<?php echo sanitize_title($name); ?>">
						<option value=""><?php echo __('Choose an option', 'sp') ?>&hellip;</option>
						<?php 
							if ( is_array( $options ) ) {
							
								if ( empty( $_POST ) )
									$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
								else
									$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';

								// Get terms if this is a taxonomy - ordered
								if ( taxonomy_exists( sanitize_title( $name ) ) ) {

									$terms = get_terms( sanitize_title($name), array('menu_order' => 'ASC') );
	
									foreach ( $terms as $term ) {
										if ( ! in_array( $term->slug, $options ) ) continue;
										echo '<option value="' . $term->slug . '" ' . selected( $selected_value, $term->slug ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
									}
								} else {
									foreach ( $options as $option )
										echo '<option value="' . $option . '" ' . selected( $selected_value, $option ) . '>' . apply_filters( 'woocommerce_variation_option_name', $option ) . '</option>';
								}
							}
						?>
					</select></td>
                    <?php if (!$i&1) { ?></tr><?php } ?> 
                    <?php $i++; ?>
	        <?php endforeach;?>
		</tbody>
	</table>
	<?php
	if ( sizeof($attributes) == $loop )
		echo '<a class="reset_variations reset_variations_hook" href="#reset">'.__('Clear selection', 'sp').'</a>';
	?>
	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<div class="single_variation_wrap single_variation_wrap_hook">
		<div class="single_variation single_variation_hook group"></div>
		<div class="variations_button variations_button_hook">
			<input type="hidden" name="variation_id" value="" />
			<?php woocommerce_quantity_input(); ?>
            <div class="woo_buy_button_container group">
                <div class="input-button-buy"><span><button type="submit" class="single_add_to_cart_button button alt" data-product_id="<?php echo $product->id; ?>"><?php echo apply_filters('single_add_to_cart_text', __('Add to cart', 'sp'), $product->product_type); ?></button></span>
                </div><!--close input-button-buy-->
                <div class="loading_animation">
                    <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
                </div><!--close wpsc_loading_animation-->                                        
            
            </div><!--close woo_buy_button_container-->
            
		</div>
	</div>
	<div><input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" /></div>

	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

</form>
<?php
} else {
?>
<form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<table class="variations" cellspacing="0">
		<tbody>
			<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
				<tr>
					<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo $woocommerce->attribute_label( $name ); ?></label></td>
					<td class="value"><select id="<?php echo esc_attr( sanitize_title($name) ); ?>" name="attribute_<?php echo sanitize_title($name); ?>">
						<option value=""><?php echo __( 'Choose an option', 'sp-theme' ) ?>&hellip;</option>
						<?php
							if ( is_array( $options ) ) {

								if ( empty( $_POST ) )
									$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
								else
									$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';

								// Get terms if this is a taxonomy - ordered
								if ( taxonomy_exists( sanitize_title( $name ) ) ) {

									$orderby = $woocommerce->attribute_orderby( $name );

									switch ( $orderby ) {
										case 'name' :
											$args = array( 'orderby' => 'name', 'hide_empty' => false, 'menu_order' => false );
										break;
										case 'id' :
											$args = array( 'orderby' => 'id', 'order' => 'ASC', 'menu_order' => false );
										break;
										case 'menu_order' :
											$args = array( 'menu_order' => 'ASC' );
										break;
									}

									$terms = get_terms( sanitize_title( $name ), $args );

									foreach ( $terms as $term ) {
										if ( ! in_array( $term->slug, $options ) )
											continue;

										echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected_value, $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
									}
								} else {

									foreach ( $options as $option ) {
										echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
									}

								}
							}
						?>
					</select></td>
				</tr>
	        <?php endforeach;?>
		</tbody>
	</table>
	<?php
	if ( sizeof($attributes) == $loop )
		echo '<a class="reset_variations reset_variations_hook" href="#reset">'.__('Clear selection', 'sp').'</a>';
	?>
	<?php do_action('woocommerce_before_add_to_cart_button'); ?>

	<div class="single_variation_wrap single_variation_wrap_hook">
		<div class="single_variation single_variation_hook group"></div>
		<div class="variations_button variations_button_hook">
			<input type="hidden" name="variation_id" value="" />
			<?php woocommerce_quantity_input(); ?>
            <div class="woo_buy_button_container group">
                <div class="input-button-buy"><span><button type="submit" class="single_add_to_cart_button button alt" data-product_id="<?php echo $product->id; ?>"><?php echo apply_filters('single_add_to_cart_text', __('Add to cart', 'sp'), $product->product_type); ?></button></span>
                </div><!--close input-button-buy-->
                <div class="loading_animation">
                    <img title="Loading" alt="Loading" src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
                </div><!--close wpsc_loading_animation-->                                        
            
            </div><!--close woo_buy_button_container-->
            
		</div>
	</div>
	<div><input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" /></div>

	<?php do_action('woocommerce_after_add_to_cart_button'); ?>

</form>
<?php
} 
?>
<?php do_action('woocommerce_after_add_to_cart_form'); ?>
