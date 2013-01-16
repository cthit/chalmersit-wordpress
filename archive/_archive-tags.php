<?php
	/*
		List all tags
	*/

	$tags = get_tags(array(
		'orderby' => 'count',
		'order' => 'desc'
	));

	$tags_count = count($tags);
?>

<?php if($tags) : ?>

<header>	
	<h3>Taggad?</h3>
	<strong class="data-count"><?php echo $tags_count;?> taggar</strong>
</header>

<ol class="list term-list">
	<?php foreach($tags as $tag) : ?>

	<?php $percentage = round(($tag->count / $tags_count)*100);?>

	<li>
		<em><?php echo $tag->count;?></em>
		<a rel="tag" href="<?php echo get_term_link($tag);?>">
			<strong><?php echo $tag->name;?></strong>
			<span style="width:<?php echo $percentage;?>%" class="percentage"><?php echo $percentage;?>%</span>
		</a>
	</li>

	<?php endforeach;?>
</ol>

<?php else : ?>

<p class="no-content">Inga taggar</p>

<?php endif;?>

