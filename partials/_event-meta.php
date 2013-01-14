<?php 
	global $post;
	$host = get_post(get_post_meta($post->ID, IT_PREFIX."event_host", true));
	$location = get_post_meta($post->ID, IT_PREFIX."event_location", true);
?>

<ul class="meta event-meta">
	<li class="icon-clock">
		<?php echo date("j F", strtotime(get_post_meta($post->ID, IT_PREFIX."event_date", true)));?>,
		<?php echo get_post_meta($post->ID, IT_PREFIX."event_start_time", true);?>
	-<?php echo get_post_meta($post->ID, IT_PREFIX."event_end_time", true);?></li>
	
	<?php if($location) : ?>
	<li class="icon-map-pin-fill"><?php echo $location;?></li>
	<?php endif;?>

	<?php if($host):?>
	<li rel="tooltip" title="Kommittée som anordnar detta arrangemang" class="icon-user"><a href="<?php echo get_permalink($host->ID);?>"><?php echo $host->post_title;?></a></li>
	<?php endif;?>
</ul>