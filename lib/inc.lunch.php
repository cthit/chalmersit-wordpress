<?php
/*
	Dagens Lunch
	- Parse the RSS feed from the restaurant and show today's lunch
*/

include_once ABSPATH . WPINC.'/rss.php';

define("KAR_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/4");
define("LIN_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/7");

function get_todays_meals() {
	return parse_feed();
}

function parse_feed() {
	$kartoday = get_feed(KAR_URL);
	$lintoday = get_feed(LIN_URL);

	return array(
		"date" => title_date($kartoday[0]['pubdate']),
		"places" => array(
			clean_menu($lintoday, "Linsen"),
			clean_menu($kartoday, "Kårrestaurangen"),
		)
	);
}


function get_feed($url) {
	$items = fetch_rss($url)->items;
	return $items;
}


function clean_menu($list, $resname) {
	$dishes_array = array();

	// Iterate through every dish that day
	foreach ($list as $item) {
		$dishes_array[] = clean_item($item);
	}
	return format_places($dishes_array, $resname);
}

function clean_item($item) {
	// Remove price that is after an @-sign
	$desc = explode("@", $item["description"]);
	return "<strong>" . $item["title"] . "</strong>" . $desc[0];
}

function title_date($datestring) {
	$formatted_date = date_parse($datestring);
	$months = array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september", "november", "december");
	return $formatted_date["day"] . " " . $months[$formatted_date["month"]-1];
}


function format_places($dishes, $name) {
	return array(
		"name" => $name,
		"dishes" => $dishes
	);
}

?>
