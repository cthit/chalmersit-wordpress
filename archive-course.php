<?php 	
	
	$years = get_terms("course_year");
	$periods = get_terms("course_period");

	get_header(); 
?>

<section class="main-col six columns push-three">
	<div class="box">
		<h1 class="huge">Kurser</h1>

		<div id="courses-container">
		<?php partial("courses");?>
		</div>

	</div>

</section>

<aside class="three columns pull-six">
	<?php get_sidebar("course");?>
</aside>

<?php get_footer();?>
