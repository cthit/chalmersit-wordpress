<?php
	# Member template for the 'medlem' shortcode function
	
	# The $meta includes member info
	# Uses a $content variable if available, otherwise the meta description of the actual member.

	$year = $meta['it_year'];
	$userdata = get_userdata($id);
	$description = ($content != null) ? $content : $meta['description'];
	$avatar_size = ($args != null) ? $args['avatar_size'] : 96;
	# Shown email may be overriden in the shortcode attribute 'contact'
	$user_contact = ($contact != null) ? $contact : $userdata->data->user_email;
?>

<section class="member row">
	<figure class="three columns">
		<?php echo get_avatar($id, $avatar_size); ?>
	</figure>

	<div class="member-details nine columns">
		<?php if($role) : ?>
		<hgroup>
			<h2><?php user_fullname($userdata);?></h2>
			<h3 class="sub"><?php echo $role;?></h3>
		</hgroup>
		<?php else : ?>

		<h2><?php user_fullname($userdata);?></h2>
		
		<?php endif;?>
		
		<p class="description">
			<?php echo strip_tags($description);?>
		</p>

		<footer>
			<?php if($user_contact) : ?>
			<strong>Kontakt:</strong> <a href="mailto:<?php echo $user_contact;?>"><?php echo $user_contact;?></a>
			<?php endif;?>
		</footer>
	</div>
</section>
