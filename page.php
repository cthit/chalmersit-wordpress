<?php 
	get_header(); 
	the_post();

	$subtitle = get_post_meta($post->ID, IT_PREFIX."subtitle", true);
?>

<?php if(is_page("loggain")) : ?>
	
<?php partial("profile-template");?>
<?php else : ?>

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
	<?php if(is_page("sektionen") || get_top_parent($post->ID)->post_name == "sektionen") : ?>
		<?php get_sidebar("nav"); ?>	
	<?php else:?>
		<?php get_sidebar("children"); ?>
	<?php endif;?>
</aside>

<aside class="three columns sidebar">
	<?php if(get_post_meta($post->ID, IT_PREFIX."is_committee", true)) : ?>
	<?php get_sidebar("committee");?>
	<?php endif;?>
	<?php if(is_page("profil")){
		dynamic_sidebar("profile");
	}
	get_sidebar();?>
</aside>

<?php endif; ?>

<?php get_footer(); ?>