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

					<?php get_template_part("searchform");?>
				</div>
			</div>

			<div class="header-toolbar wrapper">
				<?php if(!is_front_page()) : ?>
				<a class="mobile-home-link show-for-small" href="<?php bloginfo("home");?>">← Till startsidan</a>
				<?php endif;?>
				<a id="main-nav-toggle" class="show-for-small">≡</a>

				<ul class="header-controls horizontal-list">
				<?php if(is_user_logged_in()) : ?>
					<li class="user-details">
						<?php echo get_avatar(wp_get_current_user()->ID, 32); ?>
						<strong><?php user_fullname(wp_get_current_user());?></strong>
					</li>
					<li class="dropdown">
						<span class="dropdown-trigger icon-cog">Verktyg</span>

						<ul class="dropdown-sub">
							<li><a href="<?php link_to("skrivut");?>">Skriv ut</a></li>
							<li><a href="<?php link_to("bokning");?>">Boka rum</a></li>
							<li><a target="_blank" href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&ved=0CDQQFjAA&url=https%3A%2F%2Fweb.timeedit.se%2Fchalmers_se%2Fdb1%2Ftimeedit%2Fp%2Fpublic%2Fr.html%3Fsid%3D3%26h%3Dt&ei=gQcQUaniD8XStQbS2oGYBQ&usg=AFQjCNHcE_uBpOwpal6Bm0dqzmt1c48-0Q&sig2=64mqoqh8_Nf2dwIzyjmJ4A&bvm=bv.41867550,d.Yms">Schema</a></li>
							<li><a href="<?php link_to("profil");?>">Redigera profil</a></li>
							<?php if(current_user_can("publish_posts")) : ?>
							<li><a href="<?php echo admin_url();?>" target="_blank">Admin</a></li>
							<?php endif;?>
						</ul>
					</li>
					<li>
						<a class="btn-round small" href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">Logga ut</a>
					</li>

				<?php else : ?>

					<li><a class="btn-round small" id="login-btn" href="<?php echo wp_login_url();?>">Logga in</a></li>

				<?php endif;?>
					<li>
						<?php get_template_part("searchform");?>
					</li>
				</ul>
			</div>
		</div>
		
		
	</header>

	<section role="main" class="wrapper">