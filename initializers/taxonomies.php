<?php

function register_chalmers_taxonomies() {
	register_taxonomy("course_year", "course",
		array(
			"label" => __("Årskurser"),
			"hierarchical" => true,
			"rewrite" => array("slug" => "arskurs")
		));
}

?>