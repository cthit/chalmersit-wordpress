<?php

function register_chalmers_posttypes() {

	$courses_args = array(
		"public" => true,
		"label" => __("Kurser"),
		"show_in_admin_bar" => false,
		"supports" => array("title", "editor"),
		"has_archive" => true,
		"rewrite" => array("slug" => __("kurser"), "feeds" => false)
	);

	register_post_type("course", $courses_args);
}

?>