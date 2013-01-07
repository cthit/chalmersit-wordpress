<?php 

if(is_admin()) {

	# For choosing categories for the lunch lecture posts
	$categories = get_categories();
	$choices = array();
	foreach($categories as $cat) {
		$choices[$cat->cat_ID] = $cat->cat_name;
	}
	

	$options = new Theme_Options("chalmersit", array(
		array("handle" => "general", "title" => __("Allmänna inställningar") )
	));

	$_std = array(
		"type" => "text",
		"section" => "general",
		"desc" => ""
	);

	$options->add_setting("lunch_lecture_category", array(
		"title" => "Kategori för lunchföreläsningar",
		"section" => "general",
		"desc" => "Välj den kategori som innehåller nyheter om lunchföreläsningar",
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

}

?>