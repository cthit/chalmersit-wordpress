<?php
	global $post;

	$header_image = null;
	
	if(has_post_thumbnail($post->ID)) {
		$header_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'banner');
		$header_image = $header_image[0];
	}
?>

<!doctype html>
<html>
<head>
	<title><?php wp_title("|", true, "right"); bloginfo("name");?></title>
	<meta charset="utf-8" />
	<!-- Load up the Ubuntu fonts -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,400italic" type="text/css">
	
	<!-- Main stylesheet -->
	<link rel="stylesheet" href="<?php bloginfo("stylesheet_url");?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

	<header role="banner" <?php if($header_image) echo 'style="background-image: url('.$header_image.');"'; ?>>
		
		<div class="top-bar">
			<div class="inner-bar">
				<div class="wrapper group">
					<h1>
						<a class="logo" href="/">Informationsteknik</a>
					</h1>

					<nav role="navigation">
						

						<?php wp_nav_menu(array(
							"theme_location" => "main_navigation",
							"container_class" => "",
							"menu_class" => "",
							"container" => false
						)); ?>
					</nav>
				</div>
			</div>
			
			<form role="search" action="" method="GET" class="wrapper">
				<input type="search" placeholder="Sök på Chalmers.it" results="0" name="s" />
			</form>
		</div>
		
		
	</header>

	<section role="main" class="wrapper">