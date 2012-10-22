<?php

function remove_head_links() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
}


/**
*	Use the actual short URL in shortlinks.
*
*/
function my_shortlink( $link, $shortlink, $text, $title ){
	return $shortlink;
}




/**
*	Adds the current browser as a class to the body tag. Handy for styling.
*
*/
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_safari, $is_chrome, $is_iphone, $post;

	$is_win = stripos($_SERVER["HTTP_USER_AGENT"], "windows");
	
	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	
	// Adds 'windows' to the body class
	if($is_win !== false) $classes[] = 'windows';

	$classes[] = "page-".$post->post_name;
	
	return $classes;
}


/**
*	Sets the excerpt length. Stored in the EXCERPT_LENGTH constant.
*/
function jb_excerpt_length( $length ) {
	return EXCERPT_LENGTH;
}



/**
 * Adds the "Read More" link to custom post excerpts.
 *
 * @return string Excerpt with "Read More" link in the end.
 */
function custom_excerpt( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= read_more_link();
	}
	return $output;
}



/**
* 	Returns a "Read more" link in excerpts
*
*	@args String $text: The text inside the link. Defaults to 'Read more'.
*	@args String $class: The class attribute. Defaults to 'read-more'.
*
*	@return String: The formatted link with an ellipsis in the front.
*/
function read_more_link() {
	$link = ' &hellip; <p class="read-more-container"><a class="read-more" href="%2$s">%1$s →</a></p>';
	
	return sprintf($link, __("Read more"), get_permalink());
}
