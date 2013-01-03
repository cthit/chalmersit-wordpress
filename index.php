<?php get_header(); ?>

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

<section class="row">
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
	
	<div class="three columns">
		
	</div>
	
	<div class="four columns">
		<section class="today box">
			<header class="panel-header">
				<h1>Idag</h1>
			</header>
			
			<h4>Lunch på Xpress</h4>
			<ul class="list todays-lunch">
				<li>
					<h2>Våfflor</h2>
					<small class="meta">
						<a href="#">Kårrestaurangen</a>
					</small>
				</li>
			</ul>
			
			<h4>Lunchföreläsningar</h4>
			<ul class="list todays-lunch-lectures">
				<li>
					<h2>Om ett ämne</h2>
					<small class="meta">
						<a href="#">ArmIT</a><span class="sep">∙</span><time datetime="2012-06-24T12.00">12.00</time>, HC2
						<span class="sep">∙</span><a href="#">Läs mer</a>
					</small>
				</li>
				<li>
					<h2>Om ett ämne</h2>
					<small class="meta">
						<a href="#">ArmIT</a><span class="sep">∙</span><time datetime="2012-06-24T12.00">12.00</time>, HC2
						<span class="sep">∙</span><a href="#">Läs mer</a>
					</small>
				</li>
			</ul>
			
			<h4>Evenemang</h4>
			<ul class="list todays-events">
				<li>
					<h2>Smurfsittning</h2>
					<small class="meta">
						<a href="#">sexIT</a><span class="sep">∙</span><time datetime="2012-06-24T18.00">18.00</time>, Gasquen
						<span class="sep">∙</span><a href="#">Läs mer</a>
					</small>
				</li>
			</ul>
		</section>
		
		<section class="upcoming-events box">
			<header class="panel-header">
				<h1>Kommande</h1>
			</header>
			
			
		</section>
	</div>
</section>

<?php get_footer(); ?>