<?php

function load_all($dir) {
	foreach(glob(get_template_directory()."/{$dir}/*.php") as $filename) {
		include $filename;
	}
}

function asset_path($file) {
	echo ASSET_PATH . $file;
}

function img_url($img) {
	echo asset_path("/images/".$img);
}

function javascript_path($file) {
	if(substr($file, -3) != ".js") {
		$file = $file . ".js";
	}

	echo asset_path("/javascripts/".$file);
}


function sep($symbol = "|") {
	echo get_sep($symbol);
}

function get_sep($symbol = "|") {
	return '<span class="sep">'.$symbol.'</span>';
}

function pluralize($count, $word, $ending) {
	echo $count ." ". (($count > 1) ? $word.$ending : $word);
}

function the_time_simple($format) {
	global $post;

	$format .= (date("Y") == get_the_time("Y", $post->ID) ) ? "" : ", Y";
	echo get_the_time($format, $post->ID);
}

/**
*	Returns the ID of a page from the slug.
*
*	@param String $page_slug: The slug.
*/
function get_id_by_slug($page_slug) {
	# Try grab page by title.
    $obj = get_page_by_title($page_slug);

    if ($obj) {
        return $obj->ID;
	}else{
		# If no-go, try by full page path:
		$obj = get_page_by_path($page_slug);		

		return ($obj) ? $obj->ID : false;
	}
}

function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      
      if ($size == 0)
      	return "N/A";
      else
      	return (round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]);
}

function show_categories($count = 0, $template) {
	$cats = get_categories(array("number" => $count, "orderby" => "count"));
	if($cats) {
		foreach ($cats as $category) {
			if(is_callable($template)) {
				$template($category);
			}
		}
	}
}


/**
*	Call in the top of a template with a string to
*	set the main page heading.
*/
function title_for_page($title) {
	$print = function() use($title) {
		echo $title;
	};

	add_action("main_heading", $print);
}


function formatted_tags($class_name, $wrap = "li") {
	$tags = get_the_tags();
	if($tags){
		foreach($tags as $tag) {
			echo "<".$wrap.">";
			printf('<a class="%1$s" href="%3$s">%2$s</a>', $class_name, $tag->name, get_tag_link($tag->term_id));
			echo "</".$wrap.">";
		}
	}
}


/**
*	Echoes 'class="current"' if the provided post/page ID matches the current one.
*
*	@param Int $id. The id to check for.
*	@param String $classname. The classname to be printed. Defaults to 'current_page_item'.
*	@param Bool $echo. Print the class string? Defaults to 'true'.
*/
function is_current($id, $classname = "current_page_item", $echo = true){
	global $post;
	if($post->ID == $id){
		if($echo)	printf('class="%s"', $classname);
		else 		return true;
	}
	
	return false;
		
}


/**
*	Shorthand function for rendering a partial.
*
*	Will automatically prepend an underscore to parameter
*	names without an underscore in the front.
*
*	@param $partial. The partial name (without .php).
*/
function partial($partial) {
	if($partial[0] != "_") {
		$partial = "_" . $partial;
	}

	get_template_part("partials/".$partial);
}


/**
*	Builds a HTML link element and prints it.
*
*	@param String $text: The text inside the link
*	@param String/int $page: The slug or ID of the page you're linking to.
*/
function build_link($text, $page){
	$format = '<a href="%2$s">%1$s</a>';

	printf($format, $text, link_to($page, false));
}


/**
*	Wrapper function for linking to pages.
*
*	@param String/int $page: The slug or ID of the page you're linking to
*	@param bool $echo: Prints (true) or returns (false) the permalink.
*
*	Usage: <a href="<?php link_to('about');?>">About me</a>
*/
function link_to($page, $echo = true){


	if(is_string($page) && intval($page) == 0){
		$id = get_ID_by_slug($page);

		if(!$id){

			$link = (strrpos($page, "http://") === true) ? get_bloginfo("url")."/".$page : $page;
			
		}else{
			$link = get_permalink($id);
		}
	}else{
		if(get_permalink($page)) {
			$link = get_permalink($page);
		}
		else if(get_category_link($page)) {
			$link = get_category_link($page);
		}
	}
	
	if($echo)
		echo $link;
	else
		return $link;
}



/**
*	Returns the children of a page
*
*	@param Object $obj. The page object to check from. Defaults to the current parent.
*	@return Array $pages. An array of all child pages.
*/
function fetch_children($obj = null){
	$parent = ($obj == null) ? get_parent() : $obj;
	$id = ($parent->post_parent) ? $parent->post_parent : $parent->ID;

	// Fetch all child pages of the parent OR siblings:
	return get_pages("hierarchical=0&parent=".$id."&child_of=".$id);
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

function get_top_parent($id) {
	return get_post(get_top_parent_ID($id));
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


/* CUSTOM CONDITIONAL TAGS 
----------------------- */

/**
*	Check if the current (or provided parameter) post's category
*	is an event category (set from Theme Options in wp-admin).
*
*	Also traverses and check applicable parent categories.
*/
function is_event($p = null) {
	global $post;
	$p = ($p == null) ? $post : $p;
	$cat = get_the_category($p->ID);
	$option_category = get_it_option('event_category');

	return $cat[0]->cat_ID == $option_category ||
			get_top_parent_cat_ID($cat[0]) == $option_category;
}

function get_top_parent_cat_ID($cat) {
	if(is_numeric($cat))
		$cat = get_category($cat->cat_ID);
	
	return ($cat->parent == 0) ? $cat->cat_ID : get_top_parent_cat_ID($cat->parent);
}



/* OPTIONS 
----------------------- */

/**
*	Get options from the database
*	(ties with a custom class, see lib/class.Options.php)
*
*	@param String $option. The option name to fetch.
*/
function get_it_option( $option ) {
	$options = get_option( 'chalmersit_options' );
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return false;
}

/* Shorthand echo of the above: */
function it_option($option){
	echo get_it_option($option);
}



/*	USER FUNCTIONS
----------------------- */

/**
*	Prints the $user's name on the form
*	"First Last"
*/
function user_fullname($user) {
	$nick = (!empty($user->nickname)) ? " '".$user->nickname."' " : "";
	echo $user->user_firstname . $nick . $user->user_lastname;
}


/**
*	Show a registered user.
*
*	Using the "partials/members" template.
*
*	@param $user_id The user ID
*	@param $args 	Options
*		'avatar_size' => The size of the avatar
*/
function show_person($user_id, $args) {
	if( $ar = get_user_meta($user_id) ){
		$meta = array_map( function( $a ){ 
			return $a[0];
		}, $ar);
	}

	$defaults = array("avatar_size" => 96);
	$args = wp_parse_args($args, $defaults);

	# $id must me present for use in _members.php
	$id = $user_id;

	ob_start();
	include THEME_ABSPATH."partials/_member.php";
	echo ob_get_clean();
}


function get_user_role($user) {
	if(is_numeric($user)) {
		$user = get_userdata($user);
	}

	return ($user) ? $user->roles[0] : false;
}

/**
*	Helper debug function. Prints the value of '$debug' in a
*	code block.
*
*	@param var $debug. The value to show.
*	@param String $msg. An optional heading.
*/
function Debug($debug, $msg = "Debugging"){
	if(ENV == PRODUCTION && (!isset($_GET['debug'])) ){
		return;
	}

	echo '<pre class="debug">';
	echo '<h2>'.$msg.'</h2>';
	print_r($debug);
	echo '</pre>';
}

?>