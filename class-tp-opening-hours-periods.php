<?php
/**
 * Periods basics
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Periods {

	function __construct() {
		add_action( 'init', array( $this, 'setup' ) );
	}

	/**
	 * Basic setup
	 */
	function setup() {
		/**
		 * Post type
		 */
		$args = array(
			'labels'            => array(
				'add_new_item'  => __( 'Add period', 'tp-opening-hours' ),
				'edit_item'     => __( 'Edit period', 'tp-opening-hours' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'supports'          => array( 'title' ),
		); 
		register_post_type( TP_OPENING_HOURS_PERIODS, $args );
	}

	/**
	 * Get periods
	 *
	 * @abstract
	 */
	static function get() {
		$periods = get_posts( array(
			'post_type'      => TP_OPENING_HOURS_PERIODS,
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'meta_value',
			'meta_key'       => 'period-from',
		) );

		return $periods;
	}

	/**
	 * Get period dates
	 *
	 * @param int $period_id Post ID of period
	 *
	 * @abstract
	 */
	static function get_period_dates( $period_id ) {
		$periods = new stdClass;

		$periods->from = array_reverse( (array) get_post_meta( $period_id, 'period-from', true ) );
		$periods->to = array_reverse( (array) get_post_meta( $period_id, 'period-to', true ) );

		return $periods;
	}

	/**
	 * Save period dates
	 *
	 * @param int $period_id Post ID of period
	 * @param array $from From date
	 * @param array $to To date
	 *
	 * @abstract
	 */
	static function save_period_dates( $period_id, $from, $to ) {
		update_post_meta( $period_id, 'period-from', array_reverse( $from ) );
		update_post_meta( $period_id, 'period-to', array_reverse( $to ) );
	}

	/**
	 * Get opening hours
	 *
	 * @param int $period_id
	 *
	 * @abstract
	 */
	static function get_opening_hours( $period_id ) {
		return (array) get_post_meta( $period_id, 'opening-hours', true );
	}

	/**
	 * Save opening hours
	 *
	 * @param int $period_id Post ID of period
	 * @param array $opening_hours
	 *
	 * @abstract
	 */
	static function save_opening_hours( $period_id, $opening_hours ) {
		update_post_meta( $period_id, 'opening-hours', $opening_hours );
	}

} new TP_Opening_Hours_Periods;
