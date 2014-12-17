<?php
/**
 * Opening hours
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Widget extends WP_Widget {

	function __construct() {
		$this->WP_Widget( 'TP_Opening_Hours_Widget', __( 'Opening hours', 'tp-opening-hours' ), array( 'description' => __( 'Shows opening hours of the next couple of days.', 'tp-opening-hours' ) ) );
	}
	
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : $this->name;
		?>
		
		<p>
			<label>
				<strong><?php _e( 'Title' ); ?></strong><br />
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>

		<?php do_action( 'tp-opening-hours_widget-after-options' ); ?>
		
		<?php
	}
	
	function widget( $args, $instance ) {
		global $wp_locale;

		extract( $args );
		
		echo $before_widget;
		
			if( $instance['title'] )
				echo $before_title . $instance['title'] . $after_title; 

			/**
			 * Display widget template
			 */
			do_action( 'tp-opening-hours_widget-before-content' );

			include( TP_Opening_Hours_Frontend::get_template( 'widget.php' ) );

			do_action( 'tp-opening-hours_widget-after-content' );

		echo $after_widget;
	}
	
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "TP_Opening_Hours_Widget" );' ) );
