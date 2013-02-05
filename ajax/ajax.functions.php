<?php

add_action("wp_ajax_nopriv_it_courses_filter", "courses_filter");
add_action("wp_ajax_it_courses_filter", "courses_filter");

	function courses_filter() {
		global $periods, $years;

		$year = intval(addslashes($_GET['year']));
		$period = intval(addslashes($_GET['period']));

		$periods = _get_terms_if("course_period", $period);
		$years = _get_terms_if("course_year", $year);

		$args = array(
			"nopaging" => true,
			"post_type" => "course",

			"tax_query" => array(
				"relation" => "AND"
			)
		);

		if($year != -1) {

			$args['tax_query'][] = array(
				"taxonomy" => "course_year",
				"field" => "id",
				"terms" => $year
				);
		}

		if($period != -1) {
			
			$args['tax_query'][] = array(
				"taxonomy" => "course_period",
				"field" => "id",
				"terms" => $period
				);
		}

		global $courses;
		$courses = get_posts($args);

		partial("courses");

		die();
	}


	function _get_terms_if($term_name, $id) {
		$args = null;

		if($id != -1) {
			$args = array(
				"include" => array($id)
			);
		}

		return get_terms($term_name, $args);
	}

?>