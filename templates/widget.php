<?php
/**
 * Widget content
 *
 * @package Opening_Hours
 */
?>

<ul id="opening-hours-upcoming">
	
	<?php 
		for( $i = 0; $i < 7; $i++ ) {
			$this_day = time() + ( $i * DAY_IN_SECONDS );
			?>
	
			<li>
				
				<span class="day">
					<?php 
						if( 0 === $i )
							_e( 'today', 'tp-opening-hours' );
						else
							echo date_i18n( 'l d-m-Y', $this_day ); 
					?>
				</span>

				<span class="open">
					<?php 
						$day = new TP_Opening_Hours_Day( $this_day );
						$day->display_opening_hours();

						if( $day->has_details() ) {
							?>

							<span class="details">
								(<?php $day->display_details(); ?>)
							</span>

							<?php
						}

					?>
				</span>

			</li>

			<?php 
		}
	?>

</ul>

<a class="more-link" href="<?php echo TP_Opening_Hours_Frontend::get_url(); ?>">
	<?php _e( 'View full calendar', 'tp-opening-hours' ); ?>
</a>
