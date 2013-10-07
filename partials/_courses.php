<?php
	global $IS_AJAX, $courses, $years, $periods;

	# If this partial *is not* called via XHR fetch the 
	# courses from the $period and $year variables	

?>

<?php if($years) : foreach($years as $year) : ?>

	<h2><?php echo $year->name;?></h2>

	<?php if($periods) : ?>

	<?php foreach($periods as $period) : ?>

		<?php
			$courses = get_posts(array(
				"nopaging" => true,
				"post_type" => "course",
				"tax_query" => array(
					"relation" => "AND",
					array(
						"taxonomy" => "course_period",
						"field" => "id",
						"terms" => $period->term_id
					),

					array(
						"taxonomy" => "course_year",
						"field" => "id",
						"terms" => $year->term_id
					)
				)
			));

		?>

		<?php if($courses) : ?>
		<h3><?php echo $period->name;?></h3>
		<ul class="simple-list course-listing">
		<?php foreach($courses as $course) : ?>
			<?php
				$course_code = get_post_meta($course->ID, IT_PREFIX."course_code", true);
				$mandatory = get_post_meta($course->ID, IT_PREFIX."course_is_compulsory", true);
			?>
			<li>
				<?php if($course_code):?><small><?php echo $course_code;?></small><?php endif;?>

				<?php $courseLink = get_post_meta($course->ID, it_course_site, true); ?>
				<a href="<?php
					echo empty($courseLink) ? get_permalink($course->ID) : $courseLink;
				?>"><?php echo get_the_title($course->ID);?></a>

				<?php if($mandatory == "on") : ?>	
				<span class="mandatory-course" rel="tooltip" title="Obligatorisk kurs">⚑</span>
				<?php endif;?>
			</li>

		<?php endforeach; ?>
		</ul>
		<?php endif; ?>

	<?php endforeach; endif;?>

<?php endforeach; ?>

<?php else : ?>
	
	<p class="no-content">Kunde inte hämta kurser</p>

<?php endif;?>
