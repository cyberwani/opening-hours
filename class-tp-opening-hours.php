<?php
/**
 * Plugin Name: Opening hours
 * Description: Dynamic opening hours per period and special dates.
 *
 * @package Opening_Hours
 */

define( 'TP_OPENING_HOURS_PERIODS', 'tp-periods' );
define( 'TP_OPENING_HOURS_SPECIAL', 'tp-special' );

include_once( 'class-tp-opening-hours-periods.php' );
include_once( 'class-tp-opening-hours-special.php' );
include_once( 'class-tp-opening-hours-admin.php' );
include_once( 'class-tp-opening-hours-day.php' );
include_once( 'class-tp-opening-hours-frontend.php' );
include_once( 'class-tp-opening-hours-widget.php' );

class TP_Opening_Hours {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'localization' ) );	
	}

	/**
	 * Load localization
	 */
	function localization() {
		load_muplugin_textdomain( 'tp-opening-hours', dirname( plugin_basename( __FILE__ ) ) . '/assets/lang/' );
	}

} new TP_Opening_Hours;
