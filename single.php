<?php get_header(); ?>

<div class="six columns main-col push-three">	
	<?php the_post(); ?>
	<?php get_template_part("partials/_news_post"); ?>	
</div>

<aside class="three columns pull-six">
	<?php $cats = get_the_category($post->ID);?>

	<?php if($cats) : foreach($cats as $c) : ?>
	<?php $cat_posts = get_posts(array("cat" => $c->cat_ID, "exclude" => $post->ID)); ?>

	<?php if($cat_posts) : ?>
	<section class="box">
		<header class="panel-header">
			<h1>Mer i '<?php echo $c->cat_name;?>'</h1>
		</header>

		<ul class="list">
		<?php foreach($cat_posts as $p) : ?>
			<li><a href="<?php echo get_permalink($p->ID);?>"><?php echo $p->post_title;?></a></li>
		<?php endforeach;?>
		</ul>

	</section>
	<?php endif;?>

	<?php endforeach; endif;?>

	<?php get_sidebar("news");?>
</aside>

<aside role="complementary" class="three columns">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>