<?php
define("PRINTER_TABLE", "it_printers");
function list_printers() {
	global $wpdb;
	$all = $wpdb->prepare("SELECT `Printer` AS `label`, `Description` AS `desc` FROM ".PRINTER_TABLE. " ORDER BY `Weight` DESC");
	return $wpdb->get_results($all);
}

function insert_printer($db, $printer, $desc) {
	$db->insert(PRINTER_TABLE, array(
			"Printer" => $printer,
			"Description" => $desc,
			"Weight" => 0
		), array('%s', '%s', '%d'));
}

function update_tables($json) {
	global $wpdb;
	$new_printers = json_decode($json, true);
	foreach (array_merge($old_printers, $new_printers) as $printer_name => $printer_desc) {
		insert_printer($wpdb, $printer["printer"], $printer["desc"]);
	}
}

function increment_printer($printer_name) {
	global $wpdb;
	$weight_query = $wpdb->prepare("SELECT `Weight` FROM ".PRINTER_TABLE. " WHERE `Printer` = %s", $printer_name);
	$weight = (int)$wpdb->get_var($weight_query);
	var_dump($weight);
	if ($weight < 100) {
		$wpdb->update(
			PRINTER_TABLE, 
			array(
				"Weight" => $weight + 1
			),
			array(
				"Printer" => $printer_name
			),
			'%s',
			'%s'
		);
	}
}

function printers_to_json() {
	$printers = list_printers();
	echo json_encode($printers);
}
?>