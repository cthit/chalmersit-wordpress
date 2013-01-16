<section>
	<p class="sidebar-nav">
		<?php if(is_single()) : ?>
		<a class="btn" href="<?php link_to("nyheter");?>">Alla nyheter</a>
		<?php endif;?>

		<?php if(!is_page("nyheter")) : ?>	
			<a class="btn-boring" href="<?php link_to("arkiv");?>">Arkiv</a>
		<?php endif;?>
	</p>
</section>

<section class="box">
	<header class="panel-header">
		<h1>Ämnen</h1>
	</header>
	
	<ul class="columned-list">
		<?php show_categories(0, function($c) { ?>
			<li><?php build_link($c->name, get_category_link($c->term_id));?></li>
		<?php }); ?>
	</ul>
</section>
