<?php
/**
 * WooCommerce Dynamic Gallery Widget
 *
 * Table Of Contents
 *
 * get_items_search()
 * __construct()
 * widget()
 * woops_results_search_form()
 * update()
 * form()
 */

namespace A3Rev\WCDynamicGallery;

class Widgets extends \WP_Widget 
{

	function __construct() {
		$widget_ops = array(
			'classname'   => 'wc_dynamic_gallery_widget',
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'wc_dynamic_gallery_widget', __('WC Product Dynamic Gallery', 'woocommerce-dynamic-gallery' ), $widget_ops );
	}

	function widget($args, $instance) {
		extract($args);
		
		$title      = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		$product_id = empty($instance['product_id']) ? '' : $instance['product_id'];

		echo $before_widget;

		if ( $title != '') {
			echo $before_title . $title . $after_title;
		}

		echo do_shortcode( '[wc_product_dynamic_gallery product_id="'.$product_id.'"]' );

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance               = $old_instance;
		$instance['title']      = strip_tags( $new_instance['title'] );
		$instance['product_id'] = $new_instance['product_id'];
		return $instance;

	}

	function form($instance) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'      => '',
				'product_id' => ''
			)
		);

		$title      = strip_tags( $instance['title'] );
		$product_id = $instance['product_id'];
?>

        <p>
          	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'woocommerce-dynamic-gallery' ); ?> :
            	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          	</label>
        </p>
		<p>
          	<label for="<?php echo $this->get_field_id('product_id'); ?>"><?php _e( 'Product ID', 'woocommerce-dynamic-gallery' ); ?> :
            	<input style="width: 100px;" id="<?php echo $this->get_field_id('product_id'); ?>" name="<?php echo $this->get_field_name('product_id'); ?>" type="number" value="<?php echo $product_id; ?>" />
          	</label>
          	<br>
          	<span class="description"><?php echo __( 'Leave empty for use ID of current product is showing on frontend', 'woocommerce-dynamic-gallery' ); ?></span>
        </p>
<?php
	}
}
