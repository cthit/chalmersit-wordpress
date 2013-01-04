<?php

define("UPLOAD_PATH", ABSPATH . "wp-content/uploads/");
define("THEME_PATH", get_template_directory_uri());
define("THEME_ABSPATH", ABSPATH . "wp-content/themes/chalmersit/");
define("ASSET_PATH", THEME_PATH . "/assets");

define("EXCERPT_LENGTH", 75);

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
if(! is_admin()) {
	add_action("wp_enqueue_scripts", "it_custom_scripts", 11);
}

function setup_chalmers() {
	add_theme_support("menus");
	add_theme_support("post-formats");
	add_theme_support("post-thumbnails", array("post", "page", "course"));
	add_theme_support("automatic-feed-links");

	# Buddypress
	# add_theme_support( 'buddypress' );

	add_action("init", "register_chalmers_metaboxes");
	add_action("init", "register_chalmers_menus");
	add_action("init", "register_chalmers_posttypes");
	add_action("init", "register_chalmers_taxonomies");
	add_action("init", "it_register_sidebars");
	add_action('init', 'remove_head_links');

	add_filter( 'excerpt_length', 'jb_excerpt_length' );
	add_filter( 'get_the_excerpt', 'custom_excerpt' );
	add_filter( 'excerpt_more', 'read_more_link' );	
	add_filter('next_post_link', 'posts_link_attributes');
	add_filter('previous_post_link', 'posts_link_attributes');
	add_filter( 'nav_menu_css_class', 'add_current_class_to_single', 10, 2);

	add_filter( 'the_shortlink', 'my_shortlink', 10, 4 );
	add_filter( 'body_class','browser_body_class');
	add_filter( 'show_admin_bar', '__return_false');
	add_filter( 'use_default_gallery_style', '__return_false' );

	add_shortcode('person', 'show_member_info');


	#add_filter('post_type_link', 'filter_post_type_link', 10, 2);

	/* Thumbnails */

	add_image_size("banner", 9999, 525, true);
}


function it_custom_scripts() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? 
		"s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js", false, null, true);

	wp_enqueue_script('jquery');
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


?>