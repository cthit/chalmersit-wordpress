<?php
	global $post;

	$header_image = null;
	
	if(has_post_thumbnail($post->ID)) {
		$header_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'banner');
		$header_image = $header_image[0];
	}

	partial("head");
?>

<body <?php body_class();?>>

	<header role="banner" <?php if($header_image) echo 'style="background-image: url('.$header_image.');"'; ?>>
		
		<div class="top-bar">
			<div class="inner-bar">
				<div class="wrapper group">
					<h1>
						<a class="logo" href="<?php bloginfo("url");?>"><?php bloginfo("name");?></a>
					</h1>

					<nav role="navigation" class="main-nav">
						

						<?php wp_nav_menu(array(
							"theme_location" => "main_navigation",
							"container_class" => "",
							"menu_class" => "",
							"container" => false,
							"walker" => new Main_Nav_Walker
						)); ?>
					</nav>
				</div>
			</div>
			
			<?php get_template_part("searchform");?>
		</div>
		
		
	</header>

	<section role="main" class="wrapper">