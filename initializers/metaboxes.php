<?php

function register_chalmers_metaboxes() {
	$metaboxes = array();

	$metaboxes["details"] = array(
		"id" => "details",
		"title" => __("Detaljer"),
		"context" => "normal",
		"pages" => array("page"),
		"priority" => "high",

		"fields" => array(
			array(
				"name" => __("Undertitel"),
				"desc" => __("Sidans undertitel"),
				"id" => IT_PREFIX."subtitle",
				"type" => "text"
			),
			array(
				"name" => __("Extern länk"),
				"desc" => __("Visas som knapp i höger sidebar"),
				"id" => IT_PREFIX."external_link",
				"type" => "text"
			)
		)

	);

	foreach($metaboxes as $name => $metabox){
		$mybox = new RW_Meta_Box($metabox);
	}
}

?>