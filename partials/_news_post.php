<article role="article">
	<header>
		<h1><?php the_title(); ?></h1>

		<p class="meta">
			<?php if(!is_single()) : ?><a href="<?php the_permalink();?>">Permalänk</a> <?php sep(); endif; ?>
			Postat av <?php the_author_posts_link(); ?> i <?php the_category(", ");?>
		</p>
	</header>
	
	<div class="article-content">
		<?php the_content(); ?>
	</div>

	<footer>
		<section class="author-info">
			<?php author_info($post);?>			
		</section>

		<div class="tags">
			<?php the_tags("Taggad ", " ".get_sep("∙")." "); ?>
		</div>

		<?php if(is_single()):?>
		<nav class="post-nav">
			<a rel="prev" href="/" class="btn">Föregående nyhet</a>
			<a class="btn-boring" href="<?php link_to("arkiv");?>">Nyhetsarkiv</a>
			<a rel="next" href="/" class="btn">Nästa nyhet</a>
		</nav>
		<?php endif;?>
	</footer>
</article>