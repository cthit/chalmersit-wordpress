<?php 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$news = new WP_Query(array(
		"posts_per_page" => 10,
		"paged" => $paged
	));

	get_header(); 
?>

<section class="six columns main-col push-three">
	<?php if($news->have_posts()): while($news->have_posts()) : $news->the_post(); ?>
		<?php get_template_part("partials/_news_post"); ?>	

	<?php endwhile; ?>

	<nav class="post-nav">
		<?php previous_posts_link("Tidigare nyheter", $news->max_num_pages);?>
		<a class="btn-boring" href="<?php link_to("arkiv");?>">Nyhetsarkiv</a>
		<?php next_posts_link("Äldre nyheter", $news->max_num_pages);?>
	</nav>

	<?php else: ?>

	<p class="no-content">Inga nyheter</p>

	<?php endif;?>

	<?php
		# Cleanup:
		wp_reset_query();
		wp_reset_postdata();
	?>
</section>

<aside class="three columns pull-six sidebar">
	<?php get_sidebar("news");?>
</aside>

<aside class="three columns sidebar">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>