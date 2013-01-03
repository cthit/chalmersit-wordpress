<?php 

if(is_admin()) {
	$options = new Theme_Options("chalmersit", array(
		array("handle" => "contact", "title" => __("Kontaktuppgifter") )
	));

	$_std = array(
		"type" => "text",
		"section" => "contact",
		"desc" => ""
	);

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