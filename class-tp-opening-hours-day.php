<?php
/**
 * Opening hours for a day
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Day {

	var $timestamp;
	var $date;
	var $opening_hours;
	var $special;

	function __construct( $time ) {
		$this->timestamp = $time;

		$this->date = array(
			'day'   => date( 'j', $this->timestamp ),
			'month' => date( 'n', $this->timestamp ),
			'year'  => date( 'Y', $this->timestamp ),
		);

		$this->opening_hours = $this->get_opening_hours();
	}

	/**
	 * Determine opening hours for a specific day
	 */
	function get_opening_hours() {
		if( $special = $this->get_special() ) {
			$this->special = $special;
			$opening_hours = TP_Opening_Hours_Special::get_opening_hours( $special );
			return $opening_hours[0];
		} else {
			$period = $this->get_period();
			$opening_hours = TP_Opening_Hours_Periods::get_opening_hours( $period );

			$day_of_week = ( date( 'N', $this->timestamp ) % 7 );

			return $opening_hours[ $day_of_week ];
		}
	}

	/**
	 * Display opening hours
	 */
	function display_opening_hours() {
		$output = '';
		$opening_hours = $this->opening_hours;

		if(
			! isset( $opening_hours ) ||
			! is_array( $opening_hours['from'] ) ||
			(
				'00' == $opening_hours['from']['hours'] &&
				'00' == $opening_hours['from']['minutes'] &&
				'00' == $opening_hours['to']['hours'] &&
				'00' == $opening_hours['to']['minutes']
			)
		) {
			$output = __( 'closed', 'tp-opening-hours' );
		} else {
			$output = implode( ':', $opening_hours['from'] );
			$output .= ' - ';
			$output .= implode( ':', $opening_hours['to'] );
			$output .= ' ' . __( 'o\'clock', 'tp-opening-hours' );
		}

		echo $output;
	}

	/**
	 * Display details
	 */
	function display_details() {
		if( ! isset( $this->special ) )
			return;

		echo get_the_title( $this->special );
	}

	/**
	 * Check if this date has details
	 */
	function has_details() {
		return isset( $this->special );
	}

	/**
	 * Get special day for date
	 */
	function get_special() {
		$special = get_posts( array(
			'post_type'   => TP_OPENING_HOURS_SPECIAL,
			'numberposts' => 1,
			'meta_key'    => 'special-date',
			'meta_value'  => serialize( array( $this->date['month'], $this->date['day'] ) ),
			'fields'      => 'ids',
		) );

		if( 0 < count( $special ) )
			return $special[0];

		return false;
	}

	/**
	 * Get period for today
	 */
	function get_period() {
		$periods = get_posts( array(
			'post_type'   => TP_OPENING_HOURS_PERIODS,
			'numberposts' => -1,
			'fields'      => 'ids',
		) );

		if( 0 < count( $periods ) ) {
			foreach( $periods as $period ) {

				if( $this->in_period( $period ) )
					return $period;

			}
		}

		return false;
	}

	/**
	 * Check if today is in period
	 *
	 * @param int $period_id
	 */
	function in_period( $period_id ) {
		$period_dates = TP_Opening_Hours_Periods::get_period_dates( $period_id );

		if( $this->date['month'] < $period_dates->from[1] )
			return false;

		if( $this->date['month'] > $period_dates->to[1] )
			return false;

		if( $this->date['month'] == $period_dates->from[1] && $this->date['day'] < $period_dates->from[0] )
			return false;

		if( $this->date['month'] == $period_dates->to[1] && $this->date['day'] > $period_dates->to[0] )
			return false;

		return true;

	}

}
