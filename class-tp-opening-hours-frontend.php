<?php
/**
 * Front-end opening hours
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Frontend {

	function __construct() {
		add_action( 'init', array( $this, 'register_urls' ) );
		add_shortcode( 'opening-hours', array( $this, 'shortcode' ) );
	}

	/**
	 * Register date URLs
	 */
	function register_urls() {
		$opening_hours_slug = untrailingslashit( str_replace( trailingslashit( home_url() ), '', get_permalink( get_option( 'tp-opening-hours-page' ) ) ) );

		add_rewrite_rule( '^' . $opening_hours_slug . '/([0-9]{4})/([0-9]{2})/?$', 'index.php?pagename=' . $opening_hours_slug . '&opening-year=$matches[1]&opening-month=$matches[2]', 'top' );

		add_rewrite_tag( '%opening-year%', '([0-9-]{4})' );
		add_rewrite_tag( '%opening-month%', '([0-9-]{2})' );
	}

	/**
	 * Shortcode
	 */
	function shortcode() {
		/**
		 * Define variables
		 */
		global $wp_locale;

		$year = date( 'Y' );
		$month = date( 'm' );

		if( get_query_var( 'opening-year' ) && get_query_var( 'opening-month' ) ) {
			$year = get_query_var( 'opening-year' );
			$month = (int) get_query_var( 'opening-month' );
		}

		/**
		 * Near months
		 */
		$near_months = array();
		$_year = $year - 1;
		$_month = $month;

		for( $i = -12; $i < 12; $i++ ) {
			if( $_month > 12 ) {
				$_year++;
				$_month -= 12;
			}

			$near_months[ $i ] = (object) array(
				'month' => $_month,
				'year'  => $_year,
				'url'   => self::get_url( $_year, $_month ),
			);

			$_month++;
		}

		/**
		 * Show template
		 */
		$template = self::get_template( 'archive.php' );

		include( $template );
	}

	/**
	 * Display table item
	 */
	function display_table_item( $day, $month, $year ) {
		/**
		 * Define variables
		 */
		$this_day = $day . '-' . $month . '-' . $year;
		$timestamp = strtotime( $this_day );
		$today = false;

		if( $this_day === date( 'd-m-Y' ) )
			$today = true;

		$classes = array( 'tp-date-row' );

		if( 0 === (int) date( 'w', $timestamp ) )
			$classes[] = 'tp-date-row-sunday';

		if( $today )
			$classes[] = 'tp-date-row-today';

		$day = new TP_Opening_Hours_Day( $timestamp );

		/**
		 * Show template
		 */
		include( self::get_template( 'table-item.php' ) );
	}

	/**
	 * Get archive URL
	 *
	 * @param int $year
	 * @param int $month
	 *
	 * @abstract
	 */
	static function get_url( $year = 0, $month = 0 ) {
		$url = get_permalink( get_option( 'tp-opening-hours-page' ) );

		if( 0 < $year && 0 < $month ) {
			if( $year != date( 'Y' ) || $month != date( 'm' ) ) {
				$url .= trailingslashit( $year );
				$url .= trailingslashit( sprintf( '%02d' , $month ) );
			}
		}

		return $url;
	}

	/**
	 * Get template
	 *
	 * @param string $name
	 *
	 * @abstract
	 */
	static function get_template( $name ) {
		/**
		 * Check theme
		 */
		if( $template = locate_template( 'opening-hours/' . $name ) )
			return $template;

		/**
		 * Use plugin version
		 */
		$dir = trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' );

		return $dir . $name;

	}

} new TP_Opening_Hours_Frontend;
