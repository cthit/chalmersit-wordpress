<?php
/*
	Dagens Lunch
	- Parse the RSS feed from the restaurant and show today's lunch
*/

include_once ABSPATH . WPINC.'/rss.php';

define("KAR_URL", "http://cm.lskitchen.se/johanneberg/karrestaurangen/sv.rss");
define("LIN_URL", "http://cm.lskitchen.se/johanneberg/linsen/sv.rss");

function get_todays_meals() {
	return parse_feed();
}

function parse_feed() {
	$karfeed = fetch_rss(KAR_URL);
	$linfeed = fetch_rss(LIN_URL);
	$kartoday = get_today($karfeed->items);
	$lintoday = get_today($linfeed->items);
	$date = format_date($kartoday['pubdate'], "j F");

	return array(
		"date" => $date,
		"places" => array(
			clean_menu($lintoday, "Linsen"),
			clean_menu($kartoday, "Kårrestaurangen"),
		)

	);
}

function get_today($data) {
	$date = date("j");
	$week = array();
	foreach ($data as $day) {
		$week[format_date($day['pubdate'], "j")] = $day;
	}
	$today = null;
	if (isset($week[$date])) {
		$today = $week[$date];
	} else if (isset($week[$date + 1])) {
		$today = $week[$date + 1];
	} else if (isset($week[$date + 2])) {
		$today = $week[$date + 2];
	}
	return $today;
}

function clean_menu($menu, $resname) {

	// Clean description
	$dishes = strip_tags($menu['description'], "<tr>");
	$dishes = str_replace("</tr>", "", $dishes);

	$dishes_array = explode("<tr>", $dishes);

	// Trick to remove empty elements
	$dishes_array = array_filter($dishes_array);
	// Remove 'dagen lunch', etc.
	$dishes_array = array_map("_remove_strings", $dishes_array);

	return format_places($dishes_array, $resname);
}


function format_date($datestring, $format) {
	$datetime = DateTime::createFromFormat("D, j M Y H:i:s O", $datestring);
	return $datetime->format($format);
}


function format_places($dishes, $name) {
	return array(
		"name" => $name,
		"dishes" => $dishes
	);
}

function _remove_strings($input) {

	$strings_to_remove = array("Dagens Lunch", "Veckans soppa", "Classic Kött", "Classic Fisk", "Xpress", "Gröna väggen");
	$func = function($elem) {
		return "<strong>".$elem."</strong>";
	};

	$map = array_map($func, $strings_to_remove);
	return str_ireplace($strings_to_remove, $map, $input);
}

?>
