<?php
/*
	Booking partial (used page-bokning.php)
 */

	global $b;
?>

<li>
	<?php
		# If the event is spanning over just one day, don't show the full date
		if(date("Y-m-d", strtotime($b->start_time)) == date("Y-m-d", strtotime($b->end_time))) {
			$date = date("Y-m-d", strtotime($b->start_time)) . ", ".
					date("H:i", strtotime($b->start_time)) . " – ". date("H:i", strtotime($b->end_time));
		}
		else {
			$date = date("Y-m-d, H:i", strtotime($b->start_time)) . " – " .date("Y-m-d, H:i", strtotime($b->end_time));
		}

		$tooltip = $b->location . ", " . $date;
	?>
	<h4 data-tooltip-gravity="e" data-tooltip-offset="10" rel="tooltip" title="<?php echo $tooltip;?>"><?php echo $b->title;?></h4>
</li>
