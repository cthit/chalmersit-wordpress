<?php get_header(); ?>

<div class="module col7">	
	<?php the_post(); ?>
	<?php get_template_part("partials/_news_post"); ?>	
</div>

<aside role="complementary" class="module col5">
	<section class="module col6">	
		<?php get_sidebar("news");?>
	</section>
	
	<section class="module col6">
		<?php get_sidebar();?>
	</section>
</aside>

<?php get_footer(); ?>