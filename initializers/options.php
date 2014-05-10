<?php

if(is_admin()) {

	# For choosing categories for the lunch lecture posts
	$categories = get_categories("hide_empty=0");
	$choices = array();
	foreach($categories as $cat) {
		$choices[$cat->cat_ID] = $cat->cat_name;
	}

	$groups = array();
	if(defined("SCOPER_VERSION")) {
		global $wpdb;
		$sql = "SELECT ID AS group_id, group_name AS name FROM it_groups_rs WHERE group_name NOT LIKE '\[%\]'";
		$all_groups = $wpdb->get_results($sql);

		foreach($all_groups as $g) {
			$groups[$g->group_id] = $g->name;
		}
	}

	$options = new Theme_Options("chalmersit", array(
		array("handle" => "general", "title" => __("Allmänna inställningar")),
		array("handle" => "it_booking", "title" => __("Inställningar för bokningssystemet"))
	));

	$_std = array(
		"type" => "text",
		"section" => "general",
		"desc" => ""
	);

	$options->add_setting("event_category", array(
		"title" => "Kategori för arrangemang",
		"section" => "general",
		"desc" => "Välj den kategori som innehåller nyheter om arrangemang och lunchföreläsningar. Tips:
					ha en allmän 'Arrangemangs'-kategori och lägg kategorier såsom 'Lunchföreläsningar' som
					underkategorier",
		"type" => "select",
		"choices" => $choices
	));

	$options->add_setting("lunch_category", array(
		"title" => "Kategori för lunchföreläsningar",
		"section" => "general",
		"desc" => "Välj den kategori som innehåller nyheter om lunchföreläsningar.",
		"type" => "select",
		"choices" => $choices
	));

	$options->add_setting("main_contact_email", array(
		"title" => "Mail till huvudkontakt",
		"std" => "styrit@chalmers.it",
		"desc" => "Visas bl.a. i sidfoten"
	) + $_std);

	$options->add_setting("contact_official_name", array(
		"title" => "Kontaktnamn",
		"std" => "Teknologsektionen Informationsteknik"
	) + $_std);

	$options->add_setting("contact_address", array(
		"title" => "Postadress",
		"std" => "Teknologgården 2"
	) + $_std);

	$options->add_setting("postal_code", array(
		"title" => "Postkod",
		"std" => "412 58"
	) + $_std);

	$options->add_setting("locality", array(
		"title" => "Ort",
		"std" => "Göteborg"
	) + $_std);

	/* Booking settings */

	$options->add_setting("booking_supergroup", array(
		"title" => "Admingrupp för bokningar",
		"desc" => "Den grupp som ska kunna overrida bokningar",
		"section" => "it_booking",
		"type" => "select",
		"choices" => $groups
	));

	$options->add_setting("booking_hubben_groups", array(
		"title" => "Grupper för Hubben",
		"desc" => "Välj de grupper som kan boka hubben (håll inne CMD/CTRL för att välja flera)",
		"section" => "it_booking",
		"type" => "select",
		"multiple" => true,
		"choices" => $groups
	));

	$options->add_setting("booking_hubben_email", array(
		"title" => "Mail för bokningar av Hubben",
		"std" => "prit@chalmers.it",
		"desc" => "Dit alla e-mail från bokningar ska gå (separera flera e-mails med kommatecken)",
		"section" => "it_booking"
	) + $_std);

	$options->add_setting("booking_grouproom_email", array(
		"title" => "Mail för bokningar av grupprummet",
		"std" => "prit@chalmers.it",
		"desc" => "Dit alla e-mail från bokningar ska gå (separera flera e-mails med kommatecken)",
		"section" => "it_booking"
	) + $_std);

	$options->add_setting("booking_party_email", array(
		"title" => "Mail för festanmälningar",
		"std" => "vo@chalmers.it",
		"desc" => "Dit alla e-mail för festanmälningar ska gå (separera flera e-mails med kommatecken)",
		"section" => "it_booking"
	) + $_std);

}

?>
