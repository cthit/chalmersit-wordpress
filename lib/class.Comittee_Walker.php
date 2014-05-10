<?php
class Comittee_Walker extends Walker_Nav_Menu {

	/* @Override */
	function start_el(&$output, $item, $depth = 0, $args = Array(), $curr_id = 0) {
		global $wp_query;

		$title = get_post_meta($item->object_id, IT_PREFIX."subtitle", true);

		if(!empty($title)) {
			$output .= '<dt>' . $title . '</dt>';
		}
		else {
		$output .= '<dt>Förening</dt>';
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = '<dd>';
		$item_output .= $args["before"];
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args["link_before"] . apply_filters( 'the_title', $item->title, $item->ID ) . $args["link_after"];
		$item_output .= $args["after"];

		$output .= $item_output;
	}

	function end_el(&$output, $item, $depth = 0, $args = Array()) {
		$end = "</a></dd>";
		$output .= apply_filters('walker_nav_menu_end_el', $end, $item, $depth, $args);
	}
}

?>
