<?php
	global $post;
?>

<article role="article" <?php post_class();?>>
	<div class="article-inner">

	<header>
		<?php if(is_single()) : ?>
		<h1><?php the_title(); ?></h1>
		<?php else : ?>
		<h1><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h1>
		<?php endif;?>

		<p class="meta">
			<?php if(comments_open()):?>	
				<a href="<?php the_permalink();?>#comments" 
					id="comment-link" 
					class="comments-bubble smooth" 
					rel="tooltip"
					title="<?php comments_number("Inga kommentarer", "1 kommentar", "% kommentarer" );?>">
					<?php comments_number("0", "1", "%" );?></a>
					<?php sep();?>
			<?php endif;?>

			<time title="Publicerad <?php the_time("Y-m-d, H:i");?>" datetime="<?php the_time("c");?>" pubdate><?php the_time_simple("j F");?></time> <?php sep();?>

			<?php if(!is_single()) : ?><a href="<?php the_permalink();?>">Permalänk</a> <?php sep(); endif; ?>
			
			Postat av <?php the_author_posts_link(); ?> i <?php the_category(", ");?>
			
			<?php edit_post_link("Redigera post", get_sep());?>
		</p>

		<?php if(is_event()) : ?>
			<?php partial("event-meta");?>
		<?php endif;?>
	</header>
	
	<div class="article-content">
		<?php the_content(); ?>
	</div>

	<footer>
		<?php edit_post_link("Redigera post", "<p>", "</p>");?>

		<div class="tags">
			<?php the_tags("Taggad ", " ".get_sep("∙")." "); ?>
		</div>

		<?php if(is_single()) : ?>

		<?php partial("post-nav");?>

		<?php endif;?>

	</footer>
	</div>

	<?php if(is_single()) : ?>
	<section id="comments">
		<?php comments_template("", true);?>
	</section>
	<?php endif;?>
</article>