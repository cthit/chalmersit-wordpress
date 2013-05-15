<?php

# Wordpress logic: add frontend hooks inside a is_admin() clause .. -.-
if(is_admin()) {
	add_action("wp_ajax_nopriv_it_courses_filter", "courses_filter");
	add_action("wp_ajax_it_courses_filter", "courses_filter");
}

	function courses_filter() {
		global $periods, $years;

		$year = intval(addslashes($_GET['year']));
		$period = intval(addslashes($_GET['period']));
		$periods = _get_terms_if("course_period", $period);
		$years = _get_terms_if("course_year", $year);

		partial("courses");

		die();
	}


	function _get_terms_if($term_name, $id) {
		$args = array();

		if($id != -1) {
			$args = array(
				"include" => array($id)
			);
		}

		return get_terms($term_name, $args);
	}

?>