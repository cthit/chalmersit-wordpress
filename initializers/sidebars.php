<?php
	
function it_register_sidebars() {
	$global = array(
		'before_widget' => '<section id="%1$s" class="box widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<header><h3>',
		'after_title' => '</h3></header>'
	);

	$zones = array(
		array(
			"name" => "Förstasida till höger",
			"id" => "index-right"
		) + $global ,

		array(
			"name" => "Sidfot",
			"id" => "footer",
			"before_widget" => '<li id="%1$s" class="widget">',
			"after_widget" => "</li>"
		)
	);

	foreach($zones as $zone){
		register_sidebar($zone);
	}
}

?>