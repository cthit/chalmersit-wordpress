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

			<?php get_template_part("archive/_archive-posts");?>

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