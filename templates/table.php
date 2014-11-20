<?php
/**
 * Opening hours table
 *
 * @package Opening_Hours
 */
?>

<table id="tp-opening-hours-table">
	
	<thead>

		<tr>

			<th>
				<?php _e( 'Date', 'tp-opening-hours' ); ?>
			</th>

			<th>
				<?php _e( 'Opening hours', 'tp-opening-hours' ); ?>
			</th>

			<th>
				<?php _e( 'Details', 'tp-opening-hours' ); ?>
			</th>

		</tr>

	</thead>

	<tbody>
		
		<?php 
			$days_this_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
			
			for( $day = 1; $day <= $days_this_month; $day++ )
				$this->display_table_item( $day, $month, $year );
		?>

	</tbody>

</table>

