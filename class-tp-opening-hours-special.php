<?php
/**
 * Special dates basics
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Special extends TP_Opening_Hours_Periods {

	/**
	 * Basic setup
	 */
	function setup() {
		/**
		 * Post type
		 */
		$args = array(
			'labels'            => array(
				'add_new_item'  => __( 'Add special date', 'tp-opening-hours' ),
				'edit_item'     => __( 'Edit special date', 'tp-opening-hours' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'supports'          => array( 'title' ),
		); 
		register_post_type( TP_OPENING_HOURS_SPECIAL, $args );
	}

	/**
	 * Get special dates
	 *
	 * @abstract
	 */
	static function get() {
		$periods = get_posts( array(
			'post_type'      => TP_OPENING_HOURS_SPECIAL,
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'meta_value',
			'meta_key'       => 'special-date',
		) );

		return $periods;
	}

	/**
	 * Get special date
	 *
	 * @param int $period_id Post ID of date
	 *
	 * @abstract
	 */
	static function get_special_date( $period_id ) {
		return array_reverse( (array) get_post_meta( $period_id, 'special-date', true ) );
	}

	/**
	 * Save special date
	 *
	 * @param int $period_id Post ID of date
	 * @param array $date
	 *
	 * @abstract
	 */
	static function save_special_date( $period_id, $date ) {
		update_post_meta( $period_id, 'special-date', array_reverse( $date ) );
	}

} new TP_Opening_Hours_Special;
