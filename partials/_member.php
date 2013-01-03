<?php
	# Member template for the 'medlem' shortcode function
	
	# The $meta includes member info
	# Uses a $content variable if available, otherwise the meta description of the actual member.

	$year = $meta['it_year'];
	$description = ($content != null) ? $content : $meta['description'];
?>

<section class="member row">
	<figure class="three columns">
		<?php echo get_avatar($id, 120); ?>
	</figure>

	<div class="member-details nine columns">
		<?php if($role) : ?>
		<hgroup>
			<h2><?php user_fullname(get_userdata($id));?></h2>
			<h3 class="sub"><?php echo $role;?></h3>
		</hgroup>
		<?php else : ?>

		<h2><?php user_fullname(get_userdata($id));?></h2>
		
		<?php endif;?>
		
		<p class="description">
			<?php echo strip_tags($description);?>
		</p>
	</div>
</section>
