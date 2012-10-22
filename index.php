<?php get_header(); ?>

<header class="group">
	<div class="page-title module col4">
		<div class="box">
			<h1 class="huge">IT-sektionen <span class="detail">på Chalmers</span></h1>
		</div>
	</div>
	
	<div class="quick-buttons module col4">
		<ul class="box list">
			<li><a href="#">Schema</a></li>
			<li><a href="#">Boka grupprum</a></li>
			<li><a href="#">Skriv ut</a></li>
		</ul>
	</div>
	
	<div class="login module col4">
		<form class="box" action="" method="POST">
			<p>
				<input type="text" placeholder="Användarnamn" />
				<input type="password" placeholder="Lösenord" />
			</p>
			<p>
				<label><input type="checkbox" /> Håll mig inloggad</label>
				<input type="submit" value="Logga in" />
			</p>
		</form>
	</div>
</header>

<section class="group">
	<div class="module col5">
		<section class="news box">
			<header class="panel-header">
				<h1>Nyheter</h1>
			</header>
			
			<?php if(have_posts()): while(have_posts()) : the_post(); ?>
				<?php get_template_part("partials/_news_post"); ?>	
			<?php endwhile; endif; ?>
		</section>
	</div>
	
	<div class="module col3">
		
	</div>
	
	<div class="module col4">
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