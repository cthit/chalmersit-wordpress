<?php
/*
	News archive template
*/


	get_header();
?>

<section class="news-archive nine columns push-three">
	<div class="row box">
		<article class="main nine columns">
			<h1>Arkiv</h1>

			<ul class="tabs">
				<li class="tab-current"><a href="#tab-posts">Nyheter</a></li>
				<li><a href="#tab-categories">Kategorier</a></li>
				<li><a href="#tab-tags">Taggar</a></li>
				<li><a href="#tab-authors">Författare</a></li>
			</ul>

			<div class="tab-container">
				<div id="tab-posts">
					<?php get_template_part("archive/_archive-posts");?>
				</div>
				<div id="tab-categories">
					<?php get_template_part("archive/_archive-categories");?>
				</div>
				<div id="tab-tags">
					<?php get_template_part("archive/_archive-tags");?>
				</div>
				<div id="tab-authors">
					<?php get_template_part("archive/_archive-authors");?>
				</div>
			</div>

		</article>

		<aside class="sidebar three columns">
			hej
		</aside>

	</div>
</section>

<aside class="sidebar three columns pull-nine side-nav">
	<?php get_sidebar("children");?>
</aside>

<?php get_footer();?>