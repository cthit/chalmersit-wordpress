<?php partial("head");?>

<body <?php body_class();?>>

	<header role="banner">

		<h1><a class="logo" href="<?php bloginfo("url");?>"><?php bloginfo("name");?></a></h1>
		
		<nav role="navigation" class="main-nav">
			<?php wp_nav_menu(array(
				"theme_location" => "main_navigation",
				"container_class" => "",
				"menu_class" => "",
				"container" => false
			)); ?>
		</nav>
		
		
	</header>