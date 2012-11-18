<?php
	$courses = get_posts("post_type=course"); 
?>

<nav class="box side-nav">

	<h2>Kurser</h2>

	<ul>
		<?php foreach($courses as $course): ?>
		<li <?php is_current($course->ID)?>>
			<a href="<?php echo get_permalink($course->ID);?>">
			<?php echo get_the_title($course->ID);?>
		</a></li>
		<?php endforeach;?>
	</ul>
	

</nav>