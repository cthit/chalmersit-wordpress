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
							"container" => false
						)); ?>
					</nav>
				</div>
			</div>

			<div class="wrapper">
				<ul class="header-controls horizontal-list">
				<?php if(is_user_logged_in()) : ?>
					<li class="user-details">
						<strong><?php user_fullname(wp_get_current_user());?></strong>
					</li>
					<li class="dropdown">
						<span class="dropdown-trigger icon-cog">Verktyg</span>

						<ul class="dropdown-sub">
							<li><a href="#">Skriv ut</a></li>
							<li><a href="#">Boka rum</a></li>
							<li><a href="#">Schema</a></li>
							<?php if(current_user_can("publish_posts")) : ?>
							<li><a href="<?php echo admin_url();?>" target="_blank">Admin</a></li>
							<?php endif;?>
						</ul>
					</li>
					<li>
						<a class="btn-round small" href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">Logga ut</a>
					</li>
				<?php endif;?>
					<li>
						<?php get_template_part("searchform");?>
					</li>
				</ul>
			</div>
		</div>
		
		
	</header>

	<section role="main" class="wrapper">