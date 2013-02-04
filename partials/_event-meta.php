<?php 
	global $post;
	$host_id = get_post_meta($post->ID, IT_PREFIX."event_host", true);

	if($host_id && $host_id != -1) {
		$host = get_post($host_id);
	}
	else {
		$other_host = get_post_meta($post->ID, IT_PREFIX."event_host_other", true);
	}
	
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

	<?php if($host_id) : ?>
	<li rel="tooltip" title="Arrangör av detta arrangemang" class="icon-user">
		<?php if($host_id != -1) : ?>
		<a href="<?php echo get_permalink($host->ID);?>"><?php echo $host->post_title;?></a>
		<?php elseif($other_host) : ?>
		<?php echo $other_host;?>
		<?php endif;?>
	</li>
	<?php endif;?>
</ul>