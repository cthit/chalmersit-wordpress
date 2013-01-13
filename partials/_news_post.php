<?php
	global $post;

	$author_meta = get_user_meta($post->post_author);
?>

<article role="article" <?php post_class();?>>
	<div class="article-inner">

	<header>
		<h1><?php the_title(); ?></h1>

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

		<?php if(is_lunch_lecture()) : ?>
			<?php partial("lunch_lecture_meta");?>
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

		<section class="author-info row media-block">
			<figure class="media-image">
				<?php echo get_avatar($post->post_author, 64);?>
			</figure>

			<hgroup class="alignleft">
				<h3><span class="detail">Av</span> <?php echo $author_meta['first_name'][0] . " '". $author_meta['nickname'][0] . "' " . $author_meta['last_name'][0];?></h3>
				<h4><?php echo $author_meta['it_post'][0];?></h4>
			</hgroup>

			<p class="alignright">
				<a href="<?php echo get_author_posts_url($post->post_author);?>" class="read-more">Se alla inlägg av <?php echo $author_meta['first_name'][0];?></a>
			</p>
		</section>

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