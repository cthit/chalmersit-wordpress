<?php 
	global $post;
	$news = get_posts();

	get_header(); 
?>

<section class="six columns main-col push-three">
	<?php if($news): foreach($news as $post):?>
		<?php setup_postdata($post);?>

		<?php get_template_part("partials/_news_post"); ?>	

	<?php endforeach; else: ?>

	<p class="no-content">Inga nyheter</p>

	<?php endif;?>

	<?php
		# Cleanup:
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