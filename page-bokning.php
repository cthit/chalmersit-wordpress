<?php 
	get_header(); 
	the_post();

	if(!is_user_logged_in()) auth_redirect();

	global $current_user;
	$subtitle = get_post_meta($post->ID, IT_PREFIX."subtitle", true);
?>

<section class="six columns push-three main-col">
	<article class="box" role="article">
		<?php if($subtitle) : ?>
		<hgroup class="page-title">
			<h1><?php the_title();?></h1>
			<h2><?php echo $subtitle;?></h2>
		</hgroup>
		<?php else : ?>

		<h1 class="huge"><?php the_title();?></h1>
		<?php endif;?>
		
		<div class="article-content">
			<?php the_content();?>
		</div>
	</article>
</section>

<aside class="three columns pull-six side-nav">
	<div class="box">
		<h2>Om bokningssystemet</h2>

		<h3>Hubben</h3>
		<dl>
			<dt>Kan bokas av</dt>
			<dd>Förening</dd>
			<dt>Tid</dt>
			<dd>17+ på vardagar. 00-24 på helger</dd>
		</dl>

		<h3>Grupprummet</h3>
		<dl>
			<dt>Kan bokas av</dt>
			<dd>Privat och förening</dd>
			<dt>Tid</dt>
			<dd>12-13, 17+ på vardagar. 00-24 på helger</dd>
		</dl>

		<p>
			P.R.I.T. reserverar sig för att ta bort eller ändra bokningar.
		</p>
	</div>
</aside>

<aside class="three columns sidebar">
	<div class="box">
		<h3>Tidigare bokningar</h3>

		<?php $bookings = get_bookings_for_user($current_user->ID);?>

		<ul class="simple-list">
		<?php foreach($bookings as $b) : ?>
			<li>
				<h4><?php echo $b->title;?></h4>
				<p class="meta"><?php echo $b->location;?> <?php sep();?>
					<?php echo date("Y-m-d H:i", strtotime($b->start_time));?> – <?php echo date("Y-m-d H:i", strtotime($b->end_time));?>
				</p>
			</li>
		<?php endforeach;?>
		</ul>
	</div>
</aside>

<?php get_footer(); ?>