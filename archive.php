<?php
	global $wp_query;

	if(is_category()) {
		$object = single_cat_title("", false);
		$title = "Nyheter om <strong>". $object ."</strong>";
	}
	else if(is_tag()) {
		$object = single_tag_title("", false);
		$title = "Nyheter taggade <strong>" . $object ."</strong>";
	}
	else if(is_author()) {
		$object = $wp_query->query['author_name'];
		$title = "Nyheter av <strong>". $object . "</strong>";
	}
	else {
		$title = "Nyhetsarkiv";
	}

	get_header();
?>

<section class="six columns push-three main-col">
	<div class="box">
	<header>	
		<h2><?php echo $title;?></h2>
		<p class="annotation"><?php pluralize($wp_query->post_count, "nyhet", "er");?></p>

		<?php if(is_author()) : ?>

		<?php 
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			show_person($userdata->ID, array("avatar_size" => 120));
		?>

		<?php endif;?>
	</header>

<?php if(have_posts()) : ?>

<?php while(have_posts()): the_post(); ?>

	<?php get_template_part("partials/_news_post");?>

<?php endwhile;?>

<?php else : ?>

	<p class="no-content">Inga inlägg</p>

<?php endif; ?>
	</div>
</section>

<aside class="sidebar three columns pull-six">
	<?php get_sidebar("news");?>
</aside>

<aside role="complementary" class="sidebar three columns">

	<?php if($object) : ?>
	<section class="archive-rss box">
		<h2>RSS</h2>

		<ul class="list">
			<li><a class="rss-link" href="feed">RSS-flöde för '<?php echo $object;?>'</a></li>
		</ul>

		<footer class="center">
			<a class="read-more" href="<?php link_to("nyheter/rss-floden");?>">Fler RSS-flöden</a>
		</footer>

	</section>
	<?php endif;?>	

	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>