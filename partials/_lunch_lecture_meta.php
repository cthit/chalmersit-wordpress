<?php global $post;?>

<ul class="meta lunch-lecture-meta">
	<li class="icon-clock">
		<?php echo date("j F", strtotime(get_post_meta($post->ID, IT_PREFIX."lunch_lecture_date", true)));?>,
		<?php echo get_post_meta($post->ID, IT_PREFIX."lunch_start_time", true);?>
	-<?php echo get_post_meta($post->ID, IT_PREFIX."lunch_end_time", true);?></li>
	<li class="icon-map-pin-fill"><?php echo get_post_meta($post->ID, IT_PREFIX."lunch_lecture_location", true);?></li>
	<li class="icon-user"><?php echo get_post_meta($post->ID, IT_PREFIX."lunch_lecturer", true);?></li>
</ul>