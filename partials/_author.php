<?php
/*
	Author byline.

	For use in sidebars, footers, etc.
*/

	global $post;
	$author_meta = get_user_meta($post->post_author);
?>
<section class="author-info row media-block">
	<figure class="media-image">
		<?php echo get_avatar($post->post_author, 64);?>
	</figure>

	<hgroup>
		<h3><?php echo $author_meta['first_name'][0] . " '". $author_meta['nickname'][0] . "' " . $author_meta['last_name'][0];?></h3>
		<h4><?php echo $author_meta['it_post'][0];?></h4>
	</hgroup>

	<a href="<?php echo get_author_posts_url($post->post_author);?>" class="read-more">Se alla inlägg av <?php echo $author_meta['first_name'][0];?></a>
</section>