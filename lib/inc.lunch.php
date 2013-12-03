<?php
/*
	Dagens Lunch
	- Parse the RSS feed from the restaurant and show today's lunch
*/

include_once ABSPATH . WPINC.'/rss.php';

// define("KAR_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/4");
// define("LIN_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/7");

define("TODAY", date("Y-m-d"));

function get_todays_meals() {
	$resturants = array(
		array(
			"name" => "Linsen",
			"url" => "http://cm.lskitchen.se/johanneberg/linsen/sv/%date.rss"
		),
		array(
			"name" => "Kårrestaurangen",
			"url" => "http://cm.lskitchen.se/johanneberg/karrestaurangen/sv/%date.rss"
		),
		array(
			"name" => "L's Kitchen",
			"url" => "http://cm.lskitchen.se/lindholmen/foodcourt/sv/%date.rss"
		)
	);
	return parse_feed($resturants);
}

function parse_feed($resturants) {
	$menus = array();
	foreach ($resturants as $res) {
		$menus[] = clean_menu(get_items(str_replace('%date', TODAY, $res['url'])), $res['name']);
	}
	return array(
		"date" => title_date(),
		"places" => $menus
	);
}


function get_items($url) {
	$items = fetch_rss($url)->items;
	return $items;
}


function clean_menu($list, $resname) {
	$dishes_array = array();

	// Iterate through every dish that day
	foreach ($list as $item) {
		$dishes_array[] = clean_item($item);
	}
	return array(
		"name" => $resname,
		"dishes" => $dishes_array
	);
}

function clean_item($item) {
	// Remove price that is after an @-sign
	$desc = explode("@", $item["description"]);
	return "<strong>" . $item["title"] . "</strong>" . $desc[0];
}

function title_date() {
	$months = array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september", "oktober", "november", "december");
	return date('j') . " " . $months[date('m')-1];
}

?>
