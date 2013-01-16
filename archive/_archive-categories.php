<?php
	/*
		List all categories
	*/

	$categories = get_categories(array(
		'orderby' => 'count',
		'order' => 'desc'
	));

	$categories_count = count($categories);
?>

<?php if($categories) : ?>

<header>	
	<h3>Kategorier</h3>
	<strong class="data-count"><?php echo $categories_count;?> kategorier</strong>
</header>

<ol class="list term-list">
	<?php foreach($categories as $cat) : ?>

	<?php $percentage = round(($cat->count / $categories_count)*100);?>

	<li>
		<em><?php echo $cat->count;?></em>
		<a rel="tag" href="<?php echo get_category_link($cat->cat_ID);?>">
			<strong><?php echo $cat->cat_name;?></strong>
			<span style="width:<?php echo $percentage;?>%" class="percentage"><?php echo $percentage;?>%</span>
		</a>
	</li>

	<?php endforeach;?>
</ol>

<?php else : ?>

<p class="no-content">Inga kategorier</p>

<?php endif;?>

