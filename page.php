<?php 
	get_header(); 
	the_post();

	$subtitle = get_post_meta($post->ID, IT_PREFIX."subtitle", true);
?>

<aside class="module col3 side-nav">
	<?php get_sidebar("nav"); ?>
</aside>

<section class="module col6 main-col">
	<article class="box" role="article">
		<hgroup class="page-title">
			<h1><?php the_title();?></h1>
			<h2><?php if($subtitle) echo $subtitle;?></h2>
		</hgroup>
		
		<div class="article-content">
			<?php the_content();?>
		</div>
	</article>
</section>

<aside class="module col3 sidebar">
	<?php get_sidebar("about");?>
</aside>

<?php get_footer(); ?>