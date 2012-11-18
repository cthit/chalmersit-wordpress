<?php 

if(is_admin()) {
	$options = new Theme_Options("chalmersit", array(
		array("handle" => "contact", "title" => __("Kontaktuppgifter") )
	));
}

?>