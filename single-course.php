<?php 
	global $post;

	$course_code = get_post_meta($post->ID, IT_PREFIX."course_code", true);
	$external = get_post_meta($post->ID, IT_PREFIX."course_site", true);
	$site = get_post_meta($post->ID, IT_PREFIX."course_link", true);
	$is_mandatory = get_post_meta($post->ID, IT_PREFIX."course_is_compulsory", true);

	get_header(); 
?>

<section class="main-col six columns push-three">	
	<div class="box">
	<?php the_post(); ?>

		<article role="article">
			<hgroup>	
				<h1><?php the_title();?></h1>
				<?php if($course_code):?>
				<h2 class="sub"><?php echo $course_code;?></h2>
				<?php endif;?>
			</hgroup>

			<div class="article-content">
				<?php the_content();?>
			</div>
		</article>
	</div>
</section>

<aside class="three columns pull-six">
	<nav class="side-nav">
		<a class="btn small back" href="<?php link_to("kurser");?>">Se alla kurser</a>
	</nav>
</aside>

<aside class="sidebar courses-sidebar three columns">
	<div class="box">
		<section>
			<?php if($external):?>
			<a href="<?php echo $external;?>" class="btn-alt wide read-more	">
				Kursens hemsida
			</a>
			<?php endif;?>
		</section>

		<section>
			<ul class="list">
				<li>
				<?php if($is_mandatory == "on") : ?>
					<strong><span class="positive">⚑</span> Obligatorisk kurs</strong>
				<?php else : ?>
					<span class="negative">✖</span> Kursen är inte obligatorisk
				<?php endif;?>
				</li>

				<?php if($site) : ?>
				<li>
					<a class="read-more" href="<?php echo $site; ?>">Kursens sida på Studieportalen</a>
				</li>
				<?php endif;?>
			</ul>
		</section>
	</div>
</aside>

<?php get_footer(); ?>