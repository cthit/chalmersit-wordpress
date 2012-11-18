<?php
	global $wp_query;

	if(is_category()) {
		$title = "Nyheter om <strong>". single_cat_title("", false) ."</strong>";
	}
	else if(is_tag()) {
		$title = "Nyheter taggade <strong>" . single_tag_title("", false) ."</strong>";
	}
	else if(is_author()) {
		$title = "Nyheter av <strong>". $wp_query->query['author_name'] . "</strong>";
	}
	else {
		$title = "Nyhetsarkiv";
	}

	get_header();
?>

<aside class="module col3">
	<?php get_sidebar("news");?>
</aside>

<section class="module col6 main-col">
	<header>	
		<h1 class="subtle"><?php echo $title;?></h1>
		<p class="meta"><?php pluralize($wp_query->post_count, "nyhet", "er");?></p>
	</header>

	<div class="box">
<?php if(have_posts()) : ?>

<?php while(have_posts()): the_post(); ?>

	<?php get_template_part("partials/_news_post");?>

<?php endwhile;?>

<?php else : ?>

	<p class="no-content">Inga inlägg</p>

<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>