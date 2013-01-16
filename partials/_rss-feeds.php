<?php

/*
	Partial for showing RSS feeds
*/	

	$feeds = array(
		"Nyheter" => get_bloginfo('rss2_url'),
		"Kommentarer" => get_bloginfo('comments_rss2_url')
	);

?>

<ul class="list">
	<?php foreach($feeds as $title => $url) : ?>
	<li>
		<a href="<?php echo $url;?>"><?php echo $title;?></a>
		<?php echo $url;?>
	</li>
	<?php endforeach;?>
</ul>