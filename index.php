<?php 
	get_header(); 
?>

<header class="row">
	<div class="front-title four columns">
		<div class="box">
			<h1>
				<span class="detail">Teknologsektionen</span>
				Informationsteknik
				<span class="detail">Chalmers Studentkår</span>
			</h1>
		</div>
	</div>
	
	<div class="quick-buttons five columns">
		<ul class="box list">
			<li><a rel="tooltip" data-tooltip-offset="10" title="TimeEdit" href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&ved=0CDQQFjAA&url=https%3A%2F%2Fweb.timeedit.se%2Fchalmers_se%2Fdb1%2Ftimeedit%2Fp%2Fpublic%2Fr.html%3Fsid%3D3%26h%3Dt&ei=gQcQUaniD8XStQbS2oGYBQ&usg=AFQjCNHcE_uBpOwpal6Bm0dqzmt1c48-0Q&sig2=64mqoqh8_Nf2dwIzyjmJ4A&bvm=bv.41867550,d.Yms" class="schedule-icon">Schema</a></li>
			<li><a rel="tooltip" data-tooltip-offset="10" title="Boka Hubben eller grupprummet" href="<?php link_to("bokning");?>" class="booking-icon">Bokning</a></li>
			<li><a rel="tooltip" data-tooltip-offset="10" title="Boka grupprum på Chalmers" href="https://web.timeedit.se/chalmers_se/db1/b1/" target="_blank" class="group-icon">Grupprum</a></li>
			<li><a rel="tooltip" data-tooltip-offset="10" title="Skriv ut på Chalmers skrivare" href="<?php link_to("skrivut");?>" class="print-icon">Skriv ut</a></li>
		</ul>
	</div>
	
	<div class="user-area three columns">
		<?php if(! is_user_logged_in()) : ?>
			<?php partial("signinform");?>

		<?php else : ?>

		<section class="current-event box">
			<h2>Nästa evenemang</h2>
			<?php echo do_shortcode('[google-calendar-events id="1" type="list" max="1"]' ); ?>
		</section>

		<?php endif;?>
	</div>
</header>

<section class="dashboard row">
	<div class="six columns">
		<section class="news box">
			<header class="panel-header">
				<h1>Nyheter</h1>
			</header>
			
			<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php get_template_part("partials/_news_post"); ?>	
			<?php endwhile; endif; ?>

			<footer>
				<a href="<?php link_to("nyheter");?>" class="btn wide read-more">Fler nyheter</a>
			</footer>
		</section>
	</div>

	
	<div class="three columns">
		<?php if(is_active_sidebar("index-mid")) : ?>
			<?php dynamic_sidebar("index-mid"); ?>
		<?php endif;?>
	</div>

	<div class="three columns">
		<?php if(is_active_sidebar("index-right")) : ?>
			<?php dynamic_sidebar("index-right"); ?>
		<?php endif;?>
	</div>
</section>

<?php get_footer(); ?>