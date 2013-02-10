<?php
	global $post;

	$external = get_post_meta($post->ID, IT_PREFIX."external_link", true);
	$contact = strip_tags(get_post_meta($post->ID, IT_PREFIX."contact_email", true));

	$attachments = get_posts(array(
		'post_type' => 'attachment', 
		'numberposts' => -1,
		'post_parent' => $post->ID 
	));

	$documents = array();
	foreach($attachments as $a) {
		if($a->post_mime_type == "application/pdf")
			$documents[] = $a;
	}

	$events = get_posts(array(
		"meta_key" => IT_PREFIX."event_host",
		"meta_value" => $post->ID
	));
?>

<div class="sidebar-committee box">
	<section>
		<?php if($external):?>
		<a href="<?php echo $external;?>" class="btn-alt wide read-more	">
			<?php echo str_replace(array("http://", "/"), array(""), $external);?>
		</a>
		<?php endif;?>
	</section>

	<section>
		<?php if($contact) : ?>
		<h2 class="section-heading">Kontakt</h2>
		<a class="committee-contact-email" href="mailto:<?php echo $contact;?>"><?php echo $contact;?></a>
		<?php endif;?>
	</section>

	<?php if($events) : ?>
	<section>
		<h2 class="section-heading">Arrangemang av <?php echo $post->post_title;?></h2>

		<ul class="committee-events simple-list">
		<?php foreach($events as $evt) : ?>
			<?php
				$date = get_post_meta($evt->ID, IT_PREFIX."event_date", true);
				$time = get_post_meta($evt->ID, IT_PREFIX."event_start_time", true);
				$location = get_post_meta($evt->ID, IT_PREFIX."event_location", true);

				# Don't show past events
				if(is_past_date($date)) {
					continue;
				}

				$day = date("j", strtotime($date));
				$month = date("M", strtotime($date));
			?>
			<li>
				<time class="date">
					<span class="day"><?php echo $day;?></span>
					<span class="month"><?php echo $month;?></span>
				</time>

				<p class="event-info">
					<a class="event-title" href="<?php echo get_permalink($evt->ID);?>"><?php echo $evt->post_title;?></a>
					<span class="meta">
						<?php if($time):?><strong>kl.</strong> <?php echo $time;?><?php endif;?>
						<?php if($location):?><strong>Plats:</strong> <?php echo $location;?><?php endif;?>
					</span>
				</p>
			</li>
		<?php endforeach;?>
		</ul>
	</section>
	<?php endif;?>

	<?php if($documents) : ?>
	<section class="committee-documents">
		<h2 class="section-heading">Dokument</h2>

		<ul class="simple-list">
		<?php foreach($documents as $d) : ?>
			<li>
				<figure class="file-type-icon">
					<img src="<?php img_url("icon-pdf.png");?>" />
				</figure>
				<a href="<?php echo get_permalink($d->ID);?>" class="read-more"><?php echo $d->post_title;?></a>
				<p class="meta"><strong>Uppdaterad:</strong> <?php echo date("Y-m-d", strtotime($d->post_modified));?></p>
			</li>
		<?php endforeach;?>
		</ul>
	</section>
	<?php endif;?>

</div>