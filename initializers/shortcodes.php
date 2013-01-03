<?php

/*
	SHORTCODES
*/

function show_member_info($atts, $content = null) {
	extract($atts);
	if(empty($namn) && empty($id)) return;

	if(empty($id)) {
		$id = get_user_by("slug", $namn)->data->ID;
	}

	if( $ar = get_user_meta($id) ){
		$meta = array_map( function( $a ){ 
			return $a[0];
		}, $ar);
	}

	ob_start();
	include THEME_ABSPATH."partials/_member.php";
	return ob_get_clean();
}

function show_members_info($atts) {
	extract($atts);
	if(empty($namn) && empty($ids)) return;

	if(empty($ids)){
		$vals = explode(",", $namn);
		$arg = "namn";
	}
	else {
		$vals = explode(",", $ids);
		$arg = "id";
	}

	foreach($vals as $val) {
		show_member_info(array($arg => $val));
	}
}
	
?>