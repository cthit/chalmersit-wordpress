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

	$metaboxes['lunchlecture'] = array(
		'id' => "lecture-details",
		'title' => "Data för lunchföreläsning",
		"context" => "side",
		"pages" => array("post"),
		"priority" => "high",
		"fields" => array(
			array(
				"name" => "Datum",
				"id" => IT_PREFIX."lunch_lecture_date",
				"type" => "date"
			),
			array(
				"name" => "Starttid",
				"id" => IT_PREFIX."lunch_start_time",
				"format" => "HH:mm",
				"type" => "time"
			),
			array(
				"name" => "Sluttid",
				"id" => IT_PREFIX."lunch_end_time",
				"format" => "HH:mm",
				"type" => "time"
			),
			array(
				"name" => "Sal",
				"id" => IT_PREFIX."lunch_lecture_location",
				"type" => "text"
			),
			array(
				"name" => "Föreläsare",
				"id" => IT_PREFIX."lunch_lecturer",
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