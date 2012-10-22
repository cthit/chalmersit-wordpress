<?php
	global $post;

	$external = get_post_meta($post->ID, IT_PREFIX."external_link", true);
?>

<div class="sidebar-about box">
	<section class="node">
		<?php if($external):?>
		<a href="<?php echo $external;?>" class="btn-alt wide">
			Till <?php echo str_replace(array("http://", "/"), array(""), $external);?>
		</a>
		<?php endif;?>

	</section>
</div>