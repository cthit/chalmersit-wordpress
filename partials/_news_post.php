<article role="article">
	<header>
		<h1><?php the_title(); ?></h1>

		<p class="meta">
			<time datetime="<?php the_time("c");?>" pubdate><?php the_time_simple("j F");?></time> <?php sep();?>

			<?php if(!is_single()) : ?><a href="<?php the_permalink();?>">Permalänk</a> <?php sep(); endif; ?>
			
			Postat av <?php the_author_posts_link(); ?> i <?php the_category(", ");?>
			
			<?php edit_post_link("Redigera post", get_sep());?>
		</p>
	</header>
	
	<div class="article-content">
		<?php the_content(); ?>
	</div>

	<footer>
		<?php edit_post_link("Redigera post", "<p>", "</p>");?>

		<section class="author-info">
			<?php author_info($post);?>			
		</section>

		<div class="tags">
			<?php the_tags("Taggad ", " ".get_sep("∙")." "); ?>
		</div>

		<?php if(is_single()):?>
		<nav class="post-nav">
			<?php previous_post_link("%link", "Föregående nyhet"); ?>
			<a class="btn-boring" href="<?php link_to("arkiv");?>">Nyhetsarkiv</a>
			<?php next_post_link("%link", "Nästa nyhet"); ?>
		</nav>
		<?php endif;?>
	</footer>
</article>