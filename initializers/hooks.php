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

function chalmers_redirect_login() {
	global $pagenow;
	if( 'wp-login.php' == $pagenow ) {
		wp_redirect(link_to("login", false));
		exit();
	}
}

/**
*	Use the actual short URL in shortlinks.
*
*/
function my_shortlink( $link, $shortlink, $text, $title ){
	return $shortlink;
}

/**
*	Highlight the news page in main nav if user is viewing
*	a single post or an archive page.
*/
function add_current_class_to_single($classes, $item) {
	global $post;

	if(!is_front_page()) {
		$nav_object = get_page($item->object_id);

		if($nav_object->post_name == "nyheter" && 
			(get_post_type($post) == "post" || is_post_type_archive("post") ) ) {

			$classes[] = "current_page_item";
		}
		else if($nav_object->post_name == "kurser" && 
			(is_post_type_archive("course") )) {

			$classes[] = "current_page_item";	
		}
	}
	return $classes;
}

/**
*	Add attributes to the HTML generated by next_post_link/previous_post_link
*/
function post_link_attributes($link) {
	return preg_replace('/(<a\b[^><]*)>/i', '$1 class="btn">', $link);
}

function posts_link_attributes($url){
	return 'class="btn"';
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
	$link = ' &hellip; <p class="read-more-container"><a class="read-more" href="%2$s">%1$s</a></p>';
	
	return sprintf($link, __("Read more"), get_permalink());
}

/**
 * Alter the allowed file types to upload.
 */
function custom_upload_mimes ( $existing_mimes=array() ) {
	$existing_mimes['svg'] = 'mime/type';

	return $existing_mimes;
}


/*
	Attach WP data to injected Javascript object
*/
function attach_page_variables() {
	global $wp_query;

	$max = $wp_query->max_num_pages;
	$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1; ?>

	<script type='text/javascript'>
	/* <![CDATA[ */
	var pageOptions = {
		startPage: <?php echo $paged; ?>,
		maxPages: <?php echo $max;?>,
		nextLink: "<?php echo next_posts($max, false); ?>",
		ajaxURL: "<?php echo admin_url('admin-ajax.php');?>",
		assetURL: "<?php echo ASSET_PATH;?>"
	};
	/* ]]> */
	</script>

	<?php
}

function init_epic_sexit_hack() { 
	if(!is_front_page()) return;
	?>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function() {
			document.querySelector(".sexitaegerfett .close").addEventListener("click", function(evt) {
				evt.preventDefault();
				var el = document.querySelector(".sexitaegerfett");

				el.parentElement.removeChild(el);

			}, false);
		}, false);
	</script>

	<div class="sexitaegerfett">
		<a href="#" class="close">Till Chalmers.it →</a>
		<h1>V for Vendetta-sittning</h1>
		<a href="http://sexit.chalmers.it">
			<img src="http://sexit.chalmers.it/wp-core/wp-content/uploads/2013/08/v.jpg" alt="V for Vendetta" />
		</a>	
	</div>

	<style type="text/css">

		.sexitaegerfett {
			-webkit-animation: fadeIn 1s 1s backwards;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: #0d0f0e;
			text-align: center;
			z-index: 9999;
		}
		.sexitaegerfett h1 {
			text-indent: -999em;
		}
		.sexitaegerfett .close {
			position: absolute;
			display: inline-block;
			top: 10px;
			right: 20px;
			color: #fff;
			font-weight: bold;
			text-decoration: none;
			font-size: 1.5em;
		}
	</style>
<?php
}
