<?php

/*
	Partial for showing RSS feeds
*/	

	$feeds = array(
		"Nyheter" => get_bloginfo('rss2_url'),
		"Kommentarer" => get_bloginfo('comments_rss2_url')
	);

?>

<ul class="simple-list">
	<?php foreach($feeds as $title => $url) : ?>
	<li>
		<a href="<?php echo $url;?>"><?php echo $title;?></a>
	</li>
	<?php endforeach;?>
</ul>