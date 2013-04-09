<?php
/*
	Dagens Lunch
	- Parse the RSS feed from the restaurant and show today's lunch
*/

include_once ABSPATH . WPINC.'/rss.php';

define("RSS_URL", "http://cm.lskitchen.se/johanneberg/rss");

function get_todays_meals() {
	return parse_feed();
}

function parse_feed() {
	$feed = fetch_rss(RSS_URL);
	$week = cache_week($feed);

	$index = 0;
	if (isset($week[date("j")])) {
		$index = date("j");
	} else if (isset($week[date("j")+1])) {
		$index = date("j")+1;
	} else if (isset($week[date("j")+2])) {
		$index = date("j")+2;
	}
	return clean_menu($week[$index]);;
}

function cache_week($data) {
	$week = array();
	foreach ($data as $day) {
		$week[format_date($day['pubdate'], "j")] = clean_menu($day);
	}
	return $week;
}


function clean_menu($menu) {

	$date = format_date($menu['pubdate'], "j F");

	// Clean description
	$dishes = strip_tags($menu['description'], "<tr>");
	$dishes = str_replace("</tr>", "", $dishes);

	$dishes_array = explode("<tr>", $dishes);

	// Trick to remove empty elements
	$dishes_array = array_filter($dishes_array);
	// Remove 'dagen lunch', etc.
	$dishes_array = array_map("_remove_strings", $dishes_array);
	$places = format_places($dishes_array);

	$ret = array(
		"date" => $date,
		"places" => $places
	);

	return $ret;
}


function format_date($datestring, $format) {
	$datetime = DateTime::createFromFormat("D, j M Y H:i:s O", $datestring);
	return $datetime->format($format);
}


function format_places($dishes) {
	$map = array(
		array(
			"name" => "Linsen",
			"dishes" => array(
				$dishes[1], $dishes[2]
			)
		),
		array(
			"name" => "Kårrestaurangen",
			"dishes" => array(
				$dishes[3],$dishes[4], $dishes[5]
			)
		)
	);

	return $map;
}

function _remove_strings($input) {

	$strings_to_remove = array("Dagens Lunch", "Veckans soppa", "Classic Kött", "Classic Fisk", "Xpress");
	$func = function($elem) {
		return "<strong>".$elem."</strong>";
	};

	$map = array_map($func, $strings_to_remove);
	return str_ireplace($strings_to_remove, $map, $input);
}

?>
