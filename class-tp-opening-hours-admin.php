<?php
/**
 * Manage opening hours
 *
 * @package Opening_Hours
 */

class TP_Opening_Hours_Admin {

	var $periodic_page = 'tp-opening-hours';
	var $special_page = 'tp-opening-hours#special';
	var $settings_page = 'tp-opening-hours#settings';

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'actions' ) );
		add_filter( 'redirect_post_location', array( $this, 'after_save_redirect' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add to admin menu
	 */
	function admin_menu() {
		add_options_page( __( 'Opening hours', 'tp-opening-hours' ), __( 'Opening hours', 'tp-opening-hours' ), 'publish_pages', $this->periodic_page, array( $this, 'admin_page' ) );
	}

	/**
	 * Show opening hours page
	 */
	function admin_page() {
		?>

		<div class="wrap">
			
			<h2>
				<?php _e( 'Opening hours', 'tp-opening-hours' ); ?>
			</h2>

			<h3 class="nav-tab-wrapper">

				<a class="nav-tab nav-tab-active" href="#periodic">
					<?php _e( 'Periodic', 'tp-opening-hours' ); ?>
				</a>

				<a class="nav-tab" href="#special">
					<?php _e( 'Special dates', 'tp-opening-hours' ); ?>
				</a>

				<a class="nav-tab" href="#settings">
					<?php _e( 'Settings', 'tp-opening-hours' ); ?>
				</a>

			</h3>

			<div class="tab-wrapper">
				
				<div id="periodic" class="tab-content active">
					
					<?php $this->page_periodic(); ?>

				</div>

				<div id="special" class="tab-content">
					
					<?php $this->page_special(); ?>

				</div>

				<div id="settings" class="tab-content">
					
					<?php $this->page_settings(); ?>

				</div>

			</div>

		</div>

		<?php
	}

	/**
	 * Periodic page
	 */
	function page_periodic() {
		global $wp_locale;
		?>

		<form method="POST" class="tp-periods">

			<h3>
				<?php _e( 'Periods', 'tp-opening-hours' ); ?>
			</h3>
			
			<?php 
				$periods = TP_Opening_Hours_Periods::get();

				if( 0 < count( $periods ) ) {
					?>

					<table class="wp-list-table widefat">

						<thead>

							<tr>

								<th>
									<?php _e( 'Name', 'tp-opening-hours' ); ?>
								</th>

								<th>
									<?php _e( 'Period', 'tp-opening-hours' ); ?>
								</th>

								<th class="actions"></th>

							</tr>

						</thead>

						<tbody>

							<?php foreach( $periods as $period ) { ?>

									<tr>

										<td>

											<a href="<?php echo get_edit_post_link( $period->ID ); ?>">
												<?php echo get_the_title( $period->ID ); ?>
											</a>

										</td>

										<td>
											<?php
												$period_dates = TP_Opening_Hours_Periods::get_period_dates( $period->ID );

												printf( 
													__( '%1$s until %2$s', 'tp-opening-hours' ), 
													'<strong>' . $period_dates->from[0] . ' ' . $wp_locale->get_month( $period_dates->from[1] ) . '</strong>', 
													'<strong>' . $period_dates->to[0] . ' ' . $wp_locale->get_month( $period_dates->to[1] ) . '</strong>'
												);
											?>
										</td>

										<td>
											
											<a class="dashicons dashicons-edit" href="<?php echo get_edit_post_link( $period->ID ); ?>"></a>
											<a class="dashicons dashicons-trash" href="<?php echo get_delete_post_link( $period->ID ); ?>"></a>

										</td>

									</tr>

							<?php } ?>

						</tbody>

					</table>

					<?php
				}
			?>
			
			<p>

				<a class="button-primary" href="<?php echo admin_url( 'post-new.php?post_type=' . TP_OPENING_HOURS_PERIODS ); ?>">
					+ <?php _e( 'Add period', 'tp-opening-hours' ); ?>
				</a>

			</p>

		</form>

		<?php
	}

	/**
	 * Special dates page
	 */
	function page_special() {
		global $wp_locale;
		?>

		<form method="POST" class="tp-special">

			<h3>
				<?php _e( 'Special dates', 'tp-opening-hours' ); ?>
			</h3>
			
			<?php 
				$special_dates = TP_Opening_Hours_Special::get();

				if( 0 < count( $special_dates ) ) {
					?>

					<table class="wp-list-table widefat">

						<thead>

							<tr>

								<th>
									<?php _e( 'Name', 'tp-opening-hours' ); ?>
								</th>

								<th>
									<?php _e( 'Date', 'tp-opening-hours' ); ?>
								</th>

								<th>
									<?php _e( 'Opening hours', 'tp-opening-hours' ); ?>
								</th>

								<th class="actions"></th>

							</tr>

						</thead>

						<tbody>

							<?php foreach( $special_dates as $special ) { ?>

									<tr>

										<td>

											<a href="<?php echo get_edit_post_link( $special->ID ); ?>">
												<?php echo get_the_title( $special->ID ); ?>
											</a>

										</td>

										<td>
											<?php
												$special_date = TP_Opening_Hours_Special::get_special_date( $special->ID );
												echo '<strong>' . $special_date[0] . ' ' . $wp_locale->get_month( $special_date[1] ) . '</strong>';
											?>
										</td>

										<td>
											<?php
												$day = new TP_Opening_Hours_Day( strtotime( implode( '-', $special_date ) . '-' . date( 'Y' ) ) );
												$day->display_opening_hours();
											?>
										</td>

										<td>
											
											<a class="dashicons dashicons-edit" href="<?php echo get_edit_post_link( $special->ID ); ?>"></a>
											<a class="dashicons dashicons-trash" href="<?php echo get_delete_post_link( $special->ID ); ?>"></a>

										</td>

									</tr>

							<?php } ?>

						</tbody>

					</table>

					<?php
				}
			?>
			
			<p>

				<a class="button-primary" href="<?php echo admin_url( 'post-new.php?post_type=' . TP_OPENING_HOURS_SPECIAL ); ?>">
					+ <?php _e( 'Add special date', 'tp-opening-hours' ); ?>
				</a>

			</p>

		</form>

		<?php
	}

	/**
	 * Settings page
	 */
	function page_settings() {
		?>

		<form action="options.php" method="POST">

			<?php 
				settings_fields( 'tp-opening-hours' );
				do_settings_sections( 'tp-opening-hours' );
				submit_button(); 
			?>

		</form>

		<?php
	}

	/**
	 * Register settings
	 */
	function register_settings() {
		add_settings_section( 'tp-opening-hours-settings', __( 'Settings', 'tp-opening-hours' ), '', $this->periodic_page );

		add_settings_field( 'tp-opening-hours-page', __( 'Page', 'tp-opening-hours' ), array( $this, 'show_pages_dropdown' ), 'tp-opening-hours', 'tp-opening-hours-settings', array(
			'label_for'   => 'tp-opening-hours-page',
			'description' => __( 'Use the shortcode <code>[opening-hours]</code> in this page\'s content to show opening hours.', 'tp-opening-hours' ),
		) );
		register_setting( 'tp-opening-hours', 'tp-opening-hours-page' );
	}

	/**
	 * Show WP Pages dropdown
	 *
	 * @param array $args Some additional arguments
	 */
	function show_pages_dropdown( $args ) {
		$args['option_key'] = isset( $args['option_key'] ) ? $args['option_key'] : $args['label_for'];
		$value = get_option( $args['option_key'] );
		
		wp_dropdown_pages( array(
			'name'     => $args['label_for'],
			'id'       => $args['label_for'],
			'selected' => $value,
		) );

		if( isset( $args['description'] ) )
			echo '<p class="description">' . $args['description'] . '</p>';
	}

	/**
	 * User actions
	 */
	function actions() {
		/**
		 * Redirect user after trashing special date
		 */
		if( isset( $_GET['trashed'] ) && isset( $_GET['ids'] ) ) {
			$id = absint( $_GET['ids'] ); //Always singular in this case

			if( TP_OPENING_HOURS_SPECIAL == get_post_type( $id ) ) {
				wp_redirect( admin_url( 'options-general.php?page=' . $this->special_page ) );
				exit();
			}
		}

		/**
		 * Redirect user after saving settings
		 */
		if( isset( $_GET['settings-updated'] ) && isset( $_GET['page'] ) && $this->periodic_page == $_GET['page'] ) {
			wp_redirect( admin_url( 'options-general.php?page=' . $this->settings_page ) );
			exit();
		}
	}

	/**
	 * Redirect after saving a period post
	 */
	function after_save_redirect( $location, $post_id ) {
		$post_type = get_post_type( $post_id );

		if( TP_OPENING_HOURS_PERIODS == $post_type )
			return admin_url( 'options-general.php?page=' . $this->periodic_page );

		if( TP_OPENING_HOURS_SPECIAL == $post_type )
			return admin_url( 'options-general.php?page=' . $this->special_page );

		return $location;
	}

	/**
	 * Enqueue scripts
	 */
	function enqueue_scripts() {
		wp_enqueue_script( 'tp-opening-hours', plugins_url( 'assets/coffee/admin.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'tp-opening-hours', 'TP_Opening_Hours', array(
			'periods_trash_confirm'      => __( 'Are you sure you want to remove this period?', 'tp-opening-hours' ),
			'special_date_trash_confirm' => __( 'Are you sure you want to remove this special date?', 'tp-opening-hours' ),
		) );

		wp_enqueue_style( 'tp-opening-hours', plugins_url( 'assets/sass/admin.css', __FILE__ ) );
	}

} new TP_Opening_Hours_Admin;

/** 
 * Period
 *
 * @subpackage Meta
 */
class Period_Meta {
	var $post_type;

	function __construct( $post_type ) {
		$this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'register' ), 9 );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	function register() {
		add_meta_box( 'period-meta', __( 'Period', 'tp-opening-hours' ), array( $this, 'display' ), $this->post_type, 'normal', 'high' );
	}
	
	function display( $post ) {
		$period_dates = TP_Opening_Hours_Periods::get_period_dates( $post->ID );
		?>

		<table class="form-table">

			<tbody>
				
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'From', 'tp-opening-hours' ); ?></label>
					</th>

					<td>

						<?php $this->date_dropdowns( 'period-from', $period_dates->from ); ?>

					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">
						<label for="period-to"><?php _e( 'To', 'tp-opening-hours' ); ?></label>
					</th>

					<td>
						
						<?php $this->date_dropdowns( 'period-to', $period_dates->to ); ?>

					</td>
				</tr>
				
			</tbody>

		</table>

		<?php
	}
	
	function save( $post_id ) {
		/**
		 * Perform checks
		 */
		if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) )
			return;

		if( isset( $_REQUEST['doing_wp_cron'] ) )
			return;
			
		if( isset( $_REQUEST['post_view'] ) && $_REQUEST['post_view'] == 'list' )
		    return;

		if( ! isset( $_POST['post_type'] ) || $this->post_type != $_POST['post_type'] )
			return;

		/**
		 * Save data
		 */
		TP_Opening_Hours_Periods::save_period_dates( $post_id, $_POST['period-from'], $_POST['period-to'] );
	}

	/**
	 * Display period dropdowns
	 */
	function date_dropdowns( $name, $current ) {
		global $wp_locale;

		if( 0 === count( array_filter( $current ) ) )
			$current = array( 0, 0 );
		?>

		<select name="<?php echo $name; ?>[]">

			<?php for( $i = 1; $i <= 31; $i++ ) { ?>

				<option <?php selected( $i, $current[0] ); ?>>
					<?php echo $i; ?>
				</option>

			<?php } ?>

		</select>

		<select name="<?php echo $name; ?>[]">

			<?php for( $i = 1; $i <= 12; $i++ ) { ?>

				<option value="<?php echo $i; ?>" <?php selected( $i, $current[1] ); ?>>
					<?php echo $wp_locale->get_month( $i ); ?>
				</option>

			<?php } ?>

		</select>

		<?php
	}
} new Period_Meta( TP_OPENING_HOURS_PERIODS );

/** 
 * Opening hours for period
 *
 * @subpackage Meta
 */
class Opening_Hours_Period {
	var $post_type;

	function __construct( $post_type ) {
		$this->post_type = $post_type;

		add_action( 'add_meta_boxes', array( $this, 'register' ), 9 );
		add_action( 'save_post', array( $this, 'save' ) );
	}
	
	function register() {
		add_meta_box( 'opening-hours-meta', __( 'Opening hours', 'tp-opening-hours' ), array( $this, 'display' ), $this->post_type, 'normal', 'high' );
	}
	
	function display( $post ) {
		global $wp_locale;

		$opening_hours = TP_Opening_Hours_Periods::get_opening_hours( $post->ID );
		?>

		<table class="form-table">

			<tbody>
				
				<?php 
					for( $i = 1; $i <= 7; $i++ ) {
						$day = $i % 7;
						?>

						<tr valign="top">

							<th scope="row">
								<label><?php echo $wp_locale->get_weekday( $day ); ?></label>
							</th>

							<td>
								<?php $this->time_dropdowns( $day, isset( $opening_hours[ $day ] ) ? $opening_hours[ $day ] : array() ); ?>						
							</td>

						</tr>

						<?php 
					} 
				?>
				
			</tbody>

		</table>

		<p class="description">
			<?php _e( 'Leave empty to show a day as closed.', 'tp-opening-hours' ); ?>
		</p>

		<?php
	}
	
	function save( $post_id ) {
		/**
		 * Perform checks
		 */
		if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) )
			return;

		if( isset( $_REQUEST['doing_wp_cron'] ) )
			return;
			
		if( isset( $_REQUEST['post_view'] ) && $_REQUEST['post_view'] == 'list' )
		    return;

		if( ! isset( $_POST['post_type'] ) || $this->post_type != $_POST['post_type'] )
			return;

		/**
		 * Save data
		 */
		TP_Opening_Hours_Periods::save_opening_hours( $post_id, $_POST['opening-hours'] );
	}

	/**
	 * Output dropdowns for time
	 * 
	 * @param  int $day     
	 * @param  array $current Current settings
	 */
	function time_dropdowns( $day, $current ) {
		if( 0 === count( array_filter( (array) $current ) ) )
			$current = array(
				'from'        => array(
					'hours'   => '00',
					'minutes' => '00',
				),
				'to'        => array(
					'hours'   => '00',
					'minutes' => '00',
				),
			);
		?>

		<select name="opening-hours[<?php echo $day; ?>][from][hours]">
			<?php $this->hours_options( $current['from']['hours'] ); ?>
		</select>

		<select name="opening-hours[<?php echo $day; ?>][from][minutes]">
			<?php $this->minutes_options( $current['from']['minutes'] ); ?>
		</select>

		<?php _e( 'until', 'tp-opening-hours' ); ?>

		<select name="opening-hours[<?php echo $day; ?>][to][hours]">
			<?php $this->hours_options( $current['to']['hours'] ); ?>
		</select>

		<select name="opening-hours[<?php echo $day; ?>][to][minutes]">
			<?php $this->minutes_options( $current['to']['minutes'] ); ?>
		</select>

		<?php
	}

	/**
	 * Ouput options for hours
	 * 
	 * @param string $current 
	 */
	function hours_options( $current ) {
		for( $i = 0; $i < 24; $i++ ) {
			$val = sprintf( '%02d' , $i );
			?>

			<option <?php selected( $val, $current ); ?>>
				<?php echo $val; ?>
			</option>

			<?php
		}
	}

	/**
	 * Ouput options for minutes
	 * 
	 * @param string $current 
	 */
	function minutes_options( $current ) {
		$options = array( '00', '30' );

		foreach( $options as $option ) {
			?>

			<option <?php selected( $option, $current ); ?>>
				<?php echo $option; ?>
			</option>

			<?php
		}
	}
} new Opening_Hours_Period( TP_OPENING_HOURS_PERIODS );

/** 
 * Special date
 *
 * @subpackage Meta
 */
class Special_Meta extends Period_Meta {
	var $post_type;
	
	function register() {
		add_meta_box( 'special-date-meta', __( 'Special date', 'tp-opening-hours' ), array( $this, 'display' ), $this->post_type, 'normal', 'high' );
	}
	
	function display( $post ) {
		$special_date = TP_Opening_Hours_Special::get_special_date( $post->ID );
		?>

		<table class="form-table">

			<tbody>
				
				<tr valign="top">
					<th scope="row">
						<label><?php _e( 'Special date', 'tp-opening-hours' ); ?></label>
					</th>

					<td>

						<?php $this->date_dropdowns( 'special-date', $special_date ); ?>

					</td>
				</tr>
				
			</tbody>

		</table>

		<?php
	}
	
	function save( $post_id ) {
		/**
		 * Perform checks
		 */
		if( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) )
			return;

		if( isset( $_REQUEST['doing_wp_cron'] ) )
			return;
			
		if( isset( $_REQUEST['post_view'] ) && $_REQUEST['post_view'] == 'list' )
		    return;

		if( ! isset( $_POST['post_type'] ) || $this->post_type != $_POST['post_type'] )
			return;

		/**
		 * Save data
		 */
		TP_Opening_Hours_Special::save_special_date( $post_id, $_POST['special-date'] );
	}

} new Special_Meta( TP_OPENING_HOURS_SPECIAL );

/** 
 * Opening hours for single day
 *
 * @subpackage Meta
 */
class Opening_Hours_Single extends Opening_Hours_Period {
	
	function display( $post ) {
		global $wp_locale;

		$opening_hours = TP_Opening_Hours_Special::get_opening_hours( $post->ID );
		?>

		<table class="form-table">

			<tbody>
				
				<tr valign="top">

					<th scope="row">
						<label>
							<?php _e( 'Opening hours', 'tp-opening-hours' ); ?>
						</label>
					</th>

					<td>
						<?php $this->time_dropdowns( 0, $opening_hours[ 0 ] ); ?>						
					</td>

				</tr>
				
			</tbody>

		</table>

		<p class="description">
			<?php _e( 'Leave empty to show a day as closed.', 'tp-opening-hours' ); ?>
		</p>

		<?php
	}

} new Opening_Hours_Single( TP_OPENING_HOURS_SPECIAL );
