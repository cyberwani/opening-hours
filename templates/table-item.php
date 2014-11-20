<?php
/**
 * Opening hours table item
 *
 * @package Opening_Hours
 */
?>

<tr class="<?php echo implode( ' ', $classes ); ?>">

	<td>
		<?php 
			if( $today )
				_e( 'today', 'tp-opening-hours' );
			else
				echo date_i18n( 'l j F Y', $timestamp ); 
		?>
	</td>

	<td>
		<?php $day->display_opening_hours(); ?>
	</td>

	<td>
		<?php $day->display_details(); ?>
	</td>

</tr>