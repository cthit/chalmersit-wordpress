<div class="sidebar-news box">
	<section>
		<header class="panel-header">
			<h1>Ämnen</h1>
		</header>
		<ul class="listing">
			<?php show_categories(10, function($c) { ?>
				<li><?php build_link($c->name, get_category_link($c->term_id));?></li>
			<?php }); ?>

			<li class="read-more"><a href="#">Fler kategorier</a></li>
		</ul>
	</section>
</div>