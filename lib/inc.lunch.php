<?php
/*
	Dagens Lunch
	- Parse the RSS feed from the restaurant and show today's lunch
*/

require_once ABSPATH . WPINC.'/class-simplepie.php';

// define("KAR_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/4");
// define("LIN_URL", "http://cm.lskitchen.se/lskitchen/rss/sv/7");

define("TODAY", date("Y-m-d"));

function get_todays_meals() {
	$resturants = array(
		array(
			"name" => "Linsen",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/linsen/RSS%20Feed.rss?today=true"
		),
		array(
			"name" => "Kårrestaurangen",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/karrestaurangen/Veckomeny.rss?today=true"
		),
		array(
			"name" => "Express",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/express/V%C3%A4nster.rss?today=true"
		),
		array(
			"name" => "Restaurang Hyllan",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/hyllan/RSS%20Feed.rss?today=true"
 		),
		array(
			"name" => "L's Kitchen",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/l-s-kitchen/Projektor.rss?today=true"
		),
		array(
			"name" => "L's Resto",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/l-s-resto/RSS%20Feed.rss?today=true"
		),
		array(
			"name" => "Kokboken",
			"url" => "http://intern.chalmerskonferens.se/view/restaurant/kokboken/RSS%20Feed.rss?today=true"
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
	$pie = new SimplePie();
	$pie->set_feed_url($url);
	$pie->set_cache_location('/tmp');
	$pie->init();
	return $pie->get_items();
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
	$desc = explode("@", $item->get_description());
	return "<strong>" . $item->get_title() . "</strong>" . $desc[0];
}

function title_date() {
	$months = array("januari", "februari", "mars", "april", "maj", "juni", "juli", "augusti", "september", "oktober", "november", "december");
	return date('j') . " " . $months[date('m')-1];
}

?>
