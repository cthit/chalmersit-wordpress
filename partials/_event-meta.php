<?php 
	global $post;
	$host_id = get_post_meta($post->ID, IT_PREFIX."event_host", true);
	$is_full_day = get_post_meta($post->ID, IT_PREFIX."full_day_event", true);
	$fb_event_url = get_post_meta($post->ID, IT_PREFIX."fb_event_url", true);
	$location = get_post_meta($post->ID, IT_PREFIX."event_location", true);

	if($host_id && $host_id != -1) {
		$host = get_post($host_id);
	}
	else {
		$other_host = get_post_meta($post->ID, IT_PREFIX."event_host_other", true);
	}
	
	
?>

<ul class="meta event-meta">
	<li class="icon-clock">
		<?php echo date("j M", strtotime(get_post_meta($post->ID, IT_PREFIX."event_date", true)));?>
	<?php if($is_full_day != "on") : ?>,
		<?php echo get_post_meta($post->ID, IT_PREFIX."event_start_time", true);?>
	-<?php echo get_post_meta($post->ID, IT_PREFIX."event_end_time", true);?>
	<?php endif;?>
	</li>
	
	<?php if($location) : ?>
	<li class="icon-map-pin-fill"><?php echo $location;?></li>
	<?php endif;?>

	<?php if($fb_event_url) : ?>
	<li class="icon-facebook-sign" rel="tooltip" title="Arrangemangets sida på Facebook"><a target="_blank" href="<?php echo $fb_event_url;?>">Facebook</a></li>
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