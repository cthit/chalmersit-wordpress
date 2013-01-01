<?php
	//TODO listings
?>	

<section class="box">
	<header class="panel-header">
		<h1>Ämnen</h1>
	</header>
	
	<ul class="columned-list">
		<?php show_categories(10, function($c) { ?>
			<li><?php build_link($c->name, get_category_link($c->term_id));?></li>
		<?php }); ?>
	</ul>
</section>
