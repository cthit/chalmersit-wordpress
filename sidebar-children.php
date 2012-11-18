<?php
	global $post;

	$children = fetch_children($post);

?>

<?php if($chilren): ?>
<nav class="box side-nav">
	<h2>awd</h2>
	
	<ul>
		<?php foreach($children as $child):?>

		<li><?php echo $child->post_title;?></li>

		<?php endforeach;?>
	</ul>
</nav>

<?php endif;?>