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

	$metaboxes['event_details'] = array(
		'id' => "event-details",
		'title' => "Arrangemangsinformation",
		"context" => "side",
		"pages" => array("post"),
		"priority" => "high",
		"fields" => array(
			array(
				"name" => "Datum",
				"id" => IT_PREFIX."event_date",
				"type" => "text"
			),
			array(
				"name" => "Starttid",
				"id" => IT_PREFIX."event_start_time",
				"type" => "text"
			),
			array(
				"name" => "Sluttid",
				"id" => IT_PREFIX."event_end_time",
				"type" => "text"
			),
			array(
				"name" => "Plats",
				"id" => IT_PREFIX."event_location",
				"type" => "text"
			)
		)
	);

	$metaboxes["kursdetaljer"] = array(
		"id" => "course_details",
		"title" => __("Kursinfo"),
		"context" => "side",
		"priority" => "low",
		"pages" => array("course"),

		"fields" => array(
			array(
				"name" => __("Kurskod"),
				"id" => IT_PREFIX."course_code",
				"type" => "text"
			),
			array(
				"name" => __("Länk till kurshemsida"),
				"id" => IT_PREFIX."course_site",
				"type" => "text",
				"desc" => "Länk till kursens egen hemsida"
			),
			array(
				"name" => __("Länk till kurs"),
				"id" => IT_PREFIX."course_link",
				"type" => "text",
				"desc" => "Länk till kursens sida"	
			),
			array(
				"name" => __("Obligatorisk kurs"),
				"id" => IT_PREFIX."course_is_compulsory",
				"type" => "checkbox"
			)
		)
	);

	foreach($metaboxes as $name => $metabox){
		$mybox = new RW_Meta_Box($metabox);
	}
}

?>