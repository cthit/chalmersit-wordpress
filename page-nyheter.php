<?php 
	global $post;
	$news = get_posts();

	get_header(); 
?>

<aside class="module col3 side-nav">
	<?php get_sidebar("news");?>
</aside>

<section class="module col6 main-col box">
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

<aside class="module col3 sidebar">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>