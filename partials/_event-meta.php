<?php 
	global $post;
	$host = get_post(get_post_meta($post->ID, IT_PREFIX."event_host", true));
?>

<ul class="meta event-meta">
	<li class="icon-clock">
		<?php echo date("j F", strtotime(get_post_meta($post->ID, IT_PREFIX."event_date", true)));?>,
		<?php echo get_post_meta($post->ID, IT_PREFIX."event_start_time", true);?>
	-<?php echo get_post_meta($post->ID, IT_PREFIX."event_end_time", true);?></li>
	<li class="icon-map-pin-fill"><?php echo get_post_meta($post->ID, IT_PREFIX."event_location", true);?></li>
	
	<?php if($host):?>
	<li class="icon-user"><a href="<?php echo get_permalink($host->ID);?>"><?php echo $host->post_title;?></a></li>
	<?php endif;?>
</ul>