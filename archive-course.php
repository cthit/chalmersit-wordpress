<?php 	
	
	$years = get_terms("course_year");

	get_header(); 
?>

<aside class="module col3">
	<?php get_sidebar("course");?>
</aside>

<section class="module col6 main-col">

	<div class="box">
		<h1 class="huge">Kurser</h1>

		<?php if($years) : foreach($years as $year) : ?>
			<?php $courses = get_posts(array(
				"post_type" => "course", 
				"tax_query" => array(
					array(
						"taxonomy" => "course_year",
						"field" => "id",
						"terms" => $year->term_id
					)
				)
			));?>

			<h2><?php echo $year->name;?></h2>

			<?php if($courses) : ?>
			<ul class="course-listing">
			<?php foreach($courses as $course) : ?>
				<?php
					$course_code = get_post_meta($post->ID, IT_PREFIX."course_code", true);
				?>
				<li>
					<strong>
						<a href="<?php echo get_permalink($course->ID);?>">
							<?php echo get_the_title($course->ID);?></a>
						<?php if($course_code):?><small>(<?php echo $course_code;?>)</small><?php endif;?>
					</strong>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		<?php endforeach; endif; ?>

	</div>

</section>

<?php get_footer();?>
