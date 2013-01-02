<?php
	global $post;
?>

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