<?php 
	$lunch_lectures = get_posts(array(
		"category_name" => "lunchforelasningar",
		"posts_per_page" => 3
	));

	get_header(); 
?>

<header class="row">
	<div class="page-title four columns">
		<div class="box">
			<h1 class="huge">IT-sektionen <span class="detail">på Chalmers</span></h1>
		</div>
	</div>
	
	<div class="quick-buttons four columns">
		<ul class="box list">
			<li><a href="#" class="schedule-icon">Schema</a></li>
			<li><a href="#" class="group-icon">Boka grupprum</a></li>
			<li><a href="<?php link_to("skrivut");?>" class="print-icon">Skriv ut</a></li>
		</ul>
	</div>
	
	<div class="login four columns">
		<?php if(! is_user_logged_in()) : ?>
			<?php partial("signinform");?>
		<?php else : ?>

		<section class="current-user-info media-block">
			<figure class="media-image">
				<?php echo get_avatar(wp_get_current_user()->ID, 48); ?>
			</figure>

			<h2><?php user_fullname(wp_get_current_user());?></h2>
		</section>

		<?php endif;?>
	</div>
</header>

<section class="dashboard row">
	<div class="five columns">
		<section class="news box">
			<header class="panel-header">
				<h1>Nyheter</h1>
			</header>
			
			<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php get_template_part("partials/_news_post"); ?>	
			<?php endwhile; endif; ?>
		</section>
	</div>

	
	<div class="activity three columns">

		<section class="lunch box">
			<header>
				<h3>Lunch</h3>
			</header>

			
			
		</section>

		<section class="upcoming box">
			<header class="panel-header">
				<h1>Kommande</h1>
			</header>
			
			<?php if($lunch_lectures) : ?>
			<h2>Lunchföreläsningar</h2>
			<ul class="list lunch-lectures">

				<?php foreach($lunch_lectures as $lecture) : ?>
				<?php $date = get_post_meta($lecture->ID, IT_PREFIX."lunch_lecture_date", true);?>
				<li>
					<h3><?php echo $lecture->post_title;?></h3>
					<ul class="meta">
						<li><time datetime="<?php echo $date;?>">
							<?php echo date("j F", strtotime($date));?>,
							<?php echo get_post_meta($lecture->ID, IT_PREFIX."lunch_start_time", true);?></time>
						</li>
						<li>
							<?php echo get_post_meta($lecture->ID, IT_PREFIX."lunch_lecture_location", true);?>
						</li>
						<li><?php the_author_posts_link(); ?></li>
						<li>
							<a href="<?php echo get_permalink($lecture->ID);?>" class="read-more">Läs mer</a>
						</li>
					</ul>
				</li>
				<?php endforeach;?>

			</ul>
			<?php endif;?>
			
			<h2>Evenemang</h2>
			
			<?php echo do_shortcode('[google-calendar-events id="2" type="list" max="3"]' ); ?>
		</section>
	</div>

	<div class="four columns">
		<?php if(is_active_sidebar("index-right")) : ?>
			<?php dynamic_sidebar("index-right"); ?>
		<?php endif;?>
	</div>
</section>

<?php get_footer(); ?>