<?php

define("THEME_PATH", get_template_directory_uri());
define("THEME_ABSPATH", ABSPATH . "wp-content/themes/chalmersit/");
define("ASSET_PATH", THEME_PATH . "/assets");

require_once "lib/constants.php";
require_once "lib/helpers.php";
require_once "lib/class.Comittee_Walker.php";
require_once "lib/class.Metabox.php";
require_once "lib/class.Options.php";

load_all("initializers");


$host = $_SERVER['HTTP_HOST'];
$allowed_sites = array(
	"chalmers.dev",
	"localhost"
);
$env = (in_array($host, $allowed_sites) || (isset($_GET['dev']) && $_GET['dev'] == 1)) ? DEVELOPMENT : PRODUCTION;
define("ENV", $env);


/* SETUP */

add_action("after_setup_theme", "setup_chalmers");

function setup_chalmers() {
	add_theme_support("menus");
	add_theme_support("post-formats");
	add_theme_support("post-thumbnails", array("post", "page", "course"));
	add_theme_support("automatic-feed-links");

	add_action("init", "register_chalmers_metaboxes");
	add_action("init", "register_chalmers_menus");
	add_action("init", "register_chalmers_posttypes");
	add_action("init", "register_chalmers_taxonomies");
	add_action('init', 'remove_head_links');

	add_filter( 'excerpt_length', 'jb_excerpt_length' );
	add_filter( 'get_the_excerpt', 'custom_excerpt' );
	add_filter( 'excerpt_more', 'read_more_link' );	

	add_filter('the_shortlink', 'my_shortlink', 10, 4 );
	add_filter('body_class','browser_body_class');
	add_filter('show_admin_bar', '__return_false');

	add_shortcode('medlem', 'show_member_info');
	add_shortcode('medlemmar', 'show_members_info');

	#add_filter('post_type_link', 'filter_post_type_link', 10, 2);

	/* Thumbnails */

	add_image_size("banner", 9999, 525, true);
}


/**
*	Build correct URL for course post type and taxonomy
*	course_year.
*/
function filter_post_type_link($link, $post) {
    if ($post->post_type != 'course')
        return $link;

    if ($cats = get_the_terms($post->ID, 'course_year'))
        $link = str_replace('%course_year%', array_pop($cats)->slug, $link);

    return $link;
}

/**
*	Returns the ID of a page from the slug.
*
*	@param String $page_slug: The slug.
*/
function get_id_by_slug($page_slug) {
    $obj = get_page_by_path($page_slug);
    if ($obj)
        return $obj->ID;
	else
        return false;
}

/**
*	Works like include(), but returns the content of the file
*
*	@param String $filename The path to the file
*	@return String $content The file contents
*/
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}


/**
*	Returns the children of a page
*
*	@param Object $obj. The page object to check from. Defaults to the current parent.
*	@return Array $pages. An array of all child pages.
*/
function fetch_children($obj = null){
	if(!$obj){
		$parent = get_parent();
	}else{
		if(is_int($obj))
			$parent = $obj;
		else
			$parent = $obj->ID;
	}

	// Fetch all child pages of the parent OR siblings:
	return get_pages("hierarchical=0&parent=".$parent."&child_of=".$parent);
}


/**
*	Returns the ID of the parent page of the current page, or a page provided as $obj.
*
*	@param Object $obj. The page object.
*	@return Int $id. The id of the parent page, or if there's no parent: the id of the current page.
*/
function get_parent($obj = null){
	global $wp_query;
	if(!$obj){
		$obj = $wp_query->post;
	}
	
	// Checks to see if we've got a parent or not:
	if(empty($obj->post_parent))
		return $obj->ID;
	else
		return $obj->post_parent;
}



/**
*	Returns the ID of the top parent page of the page with the ID $id.
*
*	@param Int $id. The ID of the current page.
*	@return Int $id. The ID of the top parent page.
*/
function get_top_parent_ID($id){
	$page = get_page($id);
	$id = $page->post_parent;
	$parent = get_page($id);
	
	if ($parent->post_parent == 0){
		return $id;
	}
	else{
		return get_top_parent_ID($id);
	}
}

/**
*	Get the the menu object from a specific location.	
*
*	@param $location. The location of the menu. See menus.php
*/
function get_menu_by_location( $location ) {
    if( empty($location) ) return false;

    $locations = get_nav_menu_locations();
    if( ! isset( $locations[$location] ) ) return false;

    $menu_obj = get_term( $locations[$location], 'nav_menu' );

    return $menu_obj;
}

?>