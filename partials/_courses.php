<?php
	global $courses, $years, $periods;

	# If this partial *is not* called via XHR ($courses is not
	# initialized), fetch the courses from the $period and $year
	# variables.
	
	function callback($item) {
		return $item->term_id;
	}

	$period_ids = array_map("callback", $periods);
	$year_ids = array_map("callback", $years);

	if(!$courses) {
		$courses = get_posts(array(
			"nopaging" => true,
			"post_type" => "course",
			"tax_query" => array(
				"relation" => "AND",
				array(
					"taxonomy" => "course_period",
					"field" => "id",
					"terms" => $period_ids
				),

				array(
					"taxonomy" => "course_year",
					"field" => "id",
					"terms" => $year_ids
				)
			)
		));
	}

?>

<?php if($years) : foreach($years as $year) : ?>

	<h2><?php echo $year->name;?></h2>

	<?php if($periods) : ?>

	<?php foreach($periods as $period) : ?>

		<?php if($courses) : ?>
		<h3><?php echo $period->name;?></h3>
		
		<ul class="course-listing">
		<?php foreach($courses as $course) : ?>
			<?php
				$course_code = get_post_meta($course->ID, IT_PREFIX."course_code", true);
			?>
			<li>
				<strong>
					<a href="<?php echo get_permalink($course->ID);?>">
						<?php echo get_the_title($course->ID);?></a>
					<?php if($course_code):?><small>(<?php echo $course_code;?>)</small><?php endif;?>
				</strong>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

	<?php endforeach; endif;?>

<?php endforeach; endif; ?>