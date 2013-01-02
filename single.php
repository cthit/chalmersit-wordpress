<?php get_header(); ?>

<div class="six columns main-col push-three">	
	<?php the_post(); ?>
	<?php get_template_part("partials/_news_post"); ?>	
</div>

<aside class="sidebar three columns pull-six">
	<?php get_sidebar("single");?>
</aside>

<aside role="complementary" class="three columns">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>