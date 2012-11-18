<?php 
	global $post;

	$course_code = get_post_meta($post->ID, IT_PREFIX."course_code", true);

	get_header(); 
?>

<aside class="module col3">
	<?php get_sidebar("course");?>
</aside>

<div class="module col7">	
	<?php the_post(); ?>

	<article role="article">
		<hgroup>	
			<h1><?php the_title();?></h1>
			<?php if($course_code):?>
			<h2 class="sub"><?php echo $course_code;?></h2>
			<?php endif;?>
		</hgroup>

		<div class="article-content">
			<?php the_content();?>
		</div>
	</article>
</div>

<?php get_footer(); ?>