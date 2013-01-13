<?php global $post;?>

<ul class="meta event-meta">
	<li class="icon-clock">
		<?php echo date("j F", strtotime(get_post_meta($post->ID, IT_PREFIX."event_date", true)));?>,
		<?php echo get_post_meta($post->ID, IT_PREFIX."event_start_time", true);?>
	-<?php echo get_post_meta($post->ID, IT_PREFIX."event_end_time", true);?></li>
	<li class="icon-map-pin-fill"><?php echo get_post_meta($post->ID, IT_PREFIX."event_location", true);?></li>
</ul>