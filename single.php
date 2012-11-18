<?php get_header(); ?>

<aside class="module col3">
	<?php get_sidebar("news");?>
</aside>

<div class="module col6">	
	<?php the_post(); ?>
	<?php get_template_part("partials/_news_post"); ?>	
</div>

<aside role="complementary" class="module col3">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>