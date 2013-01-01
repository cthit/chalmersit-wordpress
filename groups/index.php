<?php

	
	the_post();
	get_header();
?>

<section class="main-col six columns push-three">
	<div class="box">
		<hgroup class="page-title">	
			<h1><?php the_title();?></h1>
			<h2>Våra sektionskommittéer och intresseföreningar</h2>
		</hgroup>

		<div class="article-content">
			<?php the_content();?>
		</div>

		<section class="groups-listing">
			<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>
		</section>

	</div>
</section>

<aside class="three columns pull-nine">
	<?php get_sidebar("nav"); ?>	
</aside>

<?php get_footer();?>