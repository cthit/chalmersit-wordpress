<?php
	/* Search template */

	get_header();
?>

<section class="main-col six columns">
	<div class="box">
	<header>
		<h1>Sökresultat</h1>
		<p class="annotation">Sökord: "<?php the_search_query();?>"</p>

		<?php get_search_form();?>
	</header>

	<?php if(have_posts()) : ?>
	
	<ol class="list">

	<?php while(have_posts()) : the_post(); ?>

		<li>
			<article role="article">
				<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
				<div class="article-content">
					<?php the_excerpt();?>
				</div>
			</article>
		</li>

	<?php endwhile;?>
	</ol>

	<?php else : ?>

	<p class="no-content">Inga träffar</p>

	<?php endif;?>
	</div>
</section>

<aside class="three columns sidebar">
	<section class="box">
		<h2>Mer</h2>
	</section>
</aside>

<aside role="complementary" class="sidebar three columns">
	<?php get_sidebar();?>
</aside>

<?php get_footer();?>