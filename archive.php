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

<section class="six columns push-three main-col box">
	<header>	
		<h2><?php echo $title;?></h2>
		<p class="meta"><?php pluralize($wp_query->post_count, "nyhet", "er");?></p>

		<?php if(is_author()) : ?>

		<?php 
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			show_person($userdata->ID);
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
</section>

<aside class="sidebar three columns pull-six">
	<?php get_sidebar("news");?>
</aside>

<aside role="complementary" class="sidebar three columns">
	<?php get_sidebar();?>
</aside>

<?php get_footer(); ?>