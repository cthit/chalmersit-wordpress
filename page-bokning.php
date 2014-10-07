<?php 
	if(!is_user_logged_in()) auth_redirect();

	get_header(); 
	the_post();

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
			P.R.I.T. förbehåller sig rätten att ta bort eller ändra bokningar.
		</p>
	</div>
</aside>

<aside class="three columns sidebar bookings-sidebar">
	<div class="box">
	<section>
		<h3>Kommande bokningar</h3>

		<?php $future_bookings = get_future_bookings_for_user($current_user->ID);?>

		<?php if($future_bookings) : ?>
		<ul class="booking-listing simple-list">
		<?php foreach($future_bookings as $b) : ?>
			<?php partial("booking");?>
		<?php endforeach;?>
		</ul>

		<?php else : ?>
		<p class="no-content">Inga kommande bokningar</p>
		<?php endif;?>
	</section>

	<section>
		<h3>Tidigare bokningar</h3>

		<?php $past_bookings = get_past_bookings_for_user($current_user->ID);?>

		<?php if($past_bookings) : ?>
		<ul class="booking-listing simple-list">
		<?php foreach($past_bookings as $b) : ?>
			<?php partial("booking");?>
		<?php endforeach;?>
		</ul>
		<?php else : ?>
		<p class="no-content">Inga tidigare bokningar</p>
		<?php endif;?>
	</section>

	<?php
	# Only show for users members in a committée (apart from the default one).
	if(is_user_committee_member($current_user->ID)) :
		$user_groups = get_group_ids_for_user($current_user->ID);
		$future_group_bookings = get_future_bookings_for_group($user_groups);
		$past_group_bookings = get_past_bookings_for_group($user_groups);
	?>

	<section>
		<h3>Bokningar av dina föreningar</h3>

		<?php if($future_group_bookings) : ?>
		<h4 class="section-heading">Kommande</h4>
		<ul class="booking-listing simple-list">
		<?php foreach($future_group_bookings as $b) : ?>
			<?php partial("booking");?>
		<?php endforeach; endif;?>
		</ul>

		<?php if($past_group_bookings) : ?>
		<h4 class="section-heading">Tidigare</h4>
		<ul class="booking-listing simple-list">
		<?php foreach($past_group_bookings as $b) : ?>
			<?php partial("booking");?>
		<?php endforeach; endif;?>
		</ul>
	</section>

	<?php endif;?>
	</div>
</aside>

<?php get_footer(); ?>
