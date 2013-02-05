<?php

function register_chalmers_taxonomies() {
	register_taxonomy("course_year", "course",
		array(
			"label" => __("Årskurser"),
			"hierarchical" => true,
			"rewrite" => array("slug" => "arskurs")
		)
	);

	register_taxonomy("course_period", "course",
		array(
			"label" => __("Läsperiod"),
			"hierarchical" => true,
			"rewrite" => array("slug" => "period")
		)
	);
}

?>