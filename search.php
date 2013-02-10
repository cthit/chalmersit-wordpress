<?php
	/* Search template */

	$user_query = new WP_User_Query(array(
		"search_columns" => array('user_login', 'user_email', 'user_nicename' ),
		"search" => "*". $_GET['s'] ."*"
	));

	get_header();
?>

<section class="main-col eight columns push-two">
	<div class="box row">
		<header>
			<h1>Sökresultat</h1>
			<?php get_search_form();?>
		</header>

		<section class="six columns">

			<h2>Nyheter och sidor</h2>

			<?php if(have_posts()) : ?>
			
			<ol class="simple-list">

			<?php while(have_posts()) : the_post(); ?>

				<li>
					<a href="<?php the_permalink();?>"><?php the_title();?></a>
				</li>

			<?php endwhile;?>
			</ol>

			<?php else : ?>

			<p class="no-content">Inget hittades</p>

			<?php endif;?>
		</section>

		<section class="six columns">

			<h2>Personer</h2>

			<ul class="list user-search-results">
			<?php if($user_query->results) : ?>
				<?php foreach($user_query->results as $user) : ?>
				<?php $meta = get_user_meta($user->ID);?>

				<li>
					<?php echo get_avatar($user->ID, 64);?>

					<dl>
						<dt>Namn</dt>
						<dd><?php user_fullname($user);?></dd>

						<dt>E-post</dt>
						<dd><a href="mailto:<?php echo $user->user_email;?>"><?php echo $user->user_email;?></a></dd>

						<?php if($meta['it_year'][0]) : ?>
						<dt>Antagningsår</dt>
						<dd><?php echo $meta['it_year'][0];?></dd>
						<?php endif;?>
					</dl>
				</li>

			<?php endforeach; ?>

			<?php else : ?>

			<p class="no-content">Inga personer hittades</p>

			<?php endif;?>
			</ul>
		</section>

	</div>
</section>

<?php get_footer();?>