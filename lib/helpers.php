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

function sep($symbol = "|") {
	echo get_sep($symbol);
}

function get_sep($symbol = "|") {
	return '<span class="sep">'.$symbol.'</span>';
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

function author_info($post) {
	$meta = get_user_meta($post->post_author);
	Debug($meta);
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
*	@param String $classname. The classname to be printed. Defaults to 'current'.
*	@param Bool $echo. Print the class string? Defaults to 'true'.
*/
function is_current($id, $classname = "current", $echo = true){
	global $post;
	if($post->ID == $id){
		if($echo)	printf('class="%s"', $classname);
		else 		return true;
	}
	
	return false;
		
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