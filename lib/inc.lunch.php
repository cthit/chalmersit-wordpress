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
	var_dump($feed->items);
	$week = array();
	foreach ($feed->items as $day) {
		$week[] = format_date($day['pubdate'], "j F");
	}
	unset($day);
	var_dump($week);

	$today = $feed->items[count($feed->items)-1];
	var_dump($today);
	$today = array_slice($feed->items, 0, 1);
	$today = $today[0];

	$data = clean_menu($today);

	return $data;
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
