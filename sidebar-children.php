<?php
	global $post;

	$children = fetch_children($post);
	$top_parent = get_top_parent($post->ID);
?>

<?php if($children): ?>
<nav class="box side-nav">
	<h2><?php echo $top_parent->post_title;?></h2>
	
	<ul>
		<li <?php is_current($top_parent->ID);?>><a href="<?php echo get_permalink($top_parent->ID);?>"><?php echo $top_parent->post_title;?></a></li>
		<?php foreach($children as $child):?>

		<li <?php is_current($child->ID);?>><a href="<?php echo get_permalink($child->ID);?>"><?php echo $child->post_title;?></a></li>

		<?php endforeach;?>
	</ul>
</nav>

<?php endif;?>