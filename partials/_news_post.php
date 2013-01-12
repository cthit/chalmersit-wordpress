<?php
	global $post;

	$author_meta = get_user_meta($post->post_author);
?>

<article role="article" <?php post_class();?>>
	<header>
		<h1><?php the_title(); ?></h1>

		<p class="meta">
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

		<section class="author-info media-block">
			<figure class="media-image">
				<?php echo get_avatar($post->post_author, 64);?>
			</figure>

			<hgroup>
				<h3><span class="detail">Av</span> <?php echo $author_meta['first_name'][0] . " '". $author_meta['nickname'][0] . "' " . $author_meta['last_name'][0];?></h3>
				<h4><?php echo $author_meta['it_post'][0];?></h4>
			</hgroup>

			<p class="description">
				<?php echo $author_meta['description'][0];?>
			</p>

			<p class="right">
				<a href="<?php echo get_author_posts_url($post->post_author);?>" class="read-more">Se alla inlägg av <?php echo $author_meta['first_name'][0];?></a>
			</p>
		</section>

		<nav class="pagination post-nav">
			<?php previous_post_link("%link", "Föregående nyhet"); ?>
			<a class="btn-boring" href="<?php link_to("nyheter");?>">Alla nyheter</a>
			<?php next_post_link("%link", "Nästa nyhet"); ?>
		</nav>

		<?php endif;?>

	</footer>
</article>