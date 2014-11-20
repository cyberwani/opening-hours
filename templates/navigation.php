<?php
/**
 * Opening hours navigation
 *
 * @package Opening_Hours
 */

$previous = $near_months[-1];
$next = $near_months[1];
?>

<nav id="tp-opening-hours-navigation">
	
	<a class="back button alignleft" href="<?php echo $previous->url; ?>">
		<?php echo $wp_locale->get_month( $previous->month ) . ' ' . $previous->year; ?>	
	</a>

	<select onchange="window.location.href = this.value" class="alignleft">
		
		<?php foreach( $near_months as $index => $near_month ) { ?>
			
			<option <?php selected( $near_month->url, $near_months[0]->url ); ?> value="<?php echo $near_month->url; ?>">
				<?php echo $wp_locale->get_month( $near_month->month ) . ' ' . $near_month->year; ?>
			</option>

		<?php } ?>

	</select>

	<a class="more-link button" href="<?php echo $next->url; ?>">
		<?php echo $wp_locale->get_month( $next->month ) . ' ' . $next->year; ?>	
	</a>

</nav>
