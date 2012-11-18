<?php
	# Member template for the 'medlem' shortcode function
	#
	# The $meta includes member info

	$name = sprintf('%1$s \'%2$s\' %3$s', $meta['first_name'], $meta['nickname'], $meta['last_name']);
	$role = $meta['it_post'];
	$description = $meta['description'];
?>

<section class="member">
	<?php echo get_avatar($id, 120); ?>
	<div class="member-details">
		<hgroup>
			<h2><?php echo $name;?></h2>
			<?php if($role):?><h3 class="sub"><?php echo $role;?></h3><?php endif;?>
		</hgroup>
		<p class="description"><?php echo $description;?></p>
	</div>
</section>