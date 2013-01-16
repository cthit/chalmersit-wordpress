<?php
	/*
		List all recent posts
	*/

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$posts = new WP_Query(array(
		"posts_per_page" => 50,
		"paged" => $paged,
		"ignore_sticky_posts" => true
	));

	$count = wp_count_posts();
?>


<?php if($posts->have_posts()) : ?>

<header>
	<h3>Nyheter</h3>
	<strong class="data-count"><?php echo $count->publish;?> publicerade nyheter</strong>
</header>

<table class="post-list">
<?php while($posts->have_posts()) : $posts->the_post(); ?>
	<tr>
		<td><a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a></td>
		<td class="meta">
			<time rel="tooltip" data-tooltip-offset="10" data-tooltip-gravity="e" title="<?php echo human_time_diff(strtotime($post->post_date));?> <?php _e("ago");?>" datetime="<?php the_time("c");?>" pubdate><?php the_time("Y-m-d");?>
		</td>
		<td class="meta"><?php the_author_posts_link();?></td>
	</tr>

<?php endwhile;?>
</table>

<nav class="pagination">
	<?php echo paginate_links(array(
		"total" => $posts->max_num_pages,
		"current" => max(1, $paged),
		"format" => '/page/%#%',
		"base" => str_replace( 9999, '%#%', esc_url( get_pagenum_link(9999) ) ),
		"prev_text" => "Föregående sida",
		"next_text" => "Nästa sida",
		"type" => "list"
	));?>
</nav>

<?php else : ?>
<p class="no-content">Inga nyheter ännu</p>
<?php endif;?>

<?php
	# Cleanup:
	wp_reset_query();
	wp_reset_postdata();
?>