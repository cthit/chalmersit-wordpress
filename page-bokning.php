<?php 
	get_header(); 
	the_post();

	$subtitle = get_post_meta($post->ID, IT_PREFIX."subtitle", true);
?>

<section class="six columns push-three main-col">
	<article class="box" role="article">
		<?php if($subtitle) : ?>
		<hgroup class="page-title">
			<h1><?php the_title();?></h1>
			<h2><?php echo $subtitle;?></h2>
		</hgroup>
		<?php else : ?>

		<h1 class="huge"><?php the_title();?></h1>
		<?php endif;?>
		
		<div class="article-content">
			<?php the_content();?>
		</div>
	</article>
</section>

<aside class="three columns pull-six side-nav">
	<div class="box">
		<h3>Om bokningssystemet</h3>

		<p>Randomfakta om bokningar</p>
	</div>
</aside>

<aside class="three columns sidebar">
	<?php if(get_post_meta($post->ID, IT_PREFIX."is_committee", true)) : ?>
	<?php get_sidebar("committee");?>
	<?php endif;?>
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>