<?php

function register_chalmers_metaboxes() {
	$metaboxes = array();

	$event_hosts = array(
		"-1" => "Ingen kommitté arrangerar"
	);
	$committees = get_posts(array(
		'post_type' => "page",
		"posts_per_page" => -1,
		'meta_key' => IT_PREFIX."is_committee"
	));

	foreach($committees as $c) {
		$event_hosts[$c->ID] = $c->post_title;
	}


	$metaboxes["details"] = array(
		"id" => "details",
		"title" => __("Detaljer"),
		"context" => "normal",
		"pages" => array("page"),
		"priority" => "high",

		"fields" => array(
			array(
				"name" => "Undertitel",
				"desc" => "Sidans undertitel",
				"id" => IT_PREFIX."subtitle",
				"type" => "text"
			),
			array(
				"name" => "Extern länk",
				"desc" => "Visas som knapp i höger sidebar",
				"id" => IT_PREFIX."external_link",
				"type" => "text"
			),
			array(
				"name" => "Sidan är för en kommitté",
				"id" => IT_PREFIX."is_committee",
				"type" => "checkbox"
			),
			array(
				"name" => "Kontakt (e-mail)",
				"id" => IT_PREFIX."contact_email",
				"type" => "text",
				"desc" => "Om sidan är för en kommitté, fyll i e-mailkontakt här"
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
				"desc" => "Anges i format y-m-d",
				"type" => "date"
			),
			array(
				"name" => "Heldag",
				"id" => IT_PREFIX."full_day_event",
				"type" => "checkbox",
				"desc" => "Arrangemanget sträcker sig över hela dagen. Om valt ignoreras tiderna nedan."
			),
			array(
				"name" => "Starttid",
				"id" => IT_PREFIX."event_start_time",
				"type" => "time",
				"format" => "HH:mm"
			),
			array(
				"name" => "Sluttid",
				"id" => IT_PREFIX."event_end_time",
				"type" => "time",
				"format" => "HH:mm"
			),
			array(
				"name" => "Plats",
				"id" => IT_PREFIX."event_location",
				"type" => "text"
			),
			array(
				"name" => "Arrangör",
				"id" => IT_PREFIX."event_host",
				"type" => "select",
				"desc" => "Om en kommitté arrangerar, välj den från listan",
				"options" => $event_hosts
			),
			array(
				"name" => "Annan arrangör",
				"id" => IT_PREFIX."event_host_other",
				"type" => "text",
				"desc" => "Annan arrangör (kommer att användas om inte lämnas tomt)"
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
