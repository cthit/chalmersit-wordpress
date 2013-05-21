<?php
	/* Search template */

    $q = $_GET['s'];
	$user_query = new WP_User_Query(array(
		"search_columns" => array('user_login', 'user_email'),
		"search" => "*". $_GET['s'] ."*",
        /*"meta_query"     => array(
            'relation' => 'OR',
            array( 
                "key"     => "first_name",
                "value"   => $q,
                "compare" => "LIKE"
            ),
            array (
                "key"     => "last_name",
                "value"   => $q,
                "compare" => "LIKE"

            ),
            array( 
                "key"     => "nickname",
                "value"   => $q,
                "compare" => "LIKE"
            ),
            array(
                "key"     => "it_year",
                "value"   => $q
            )
        )*/
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
                <?php 
                    $counter = 0;
                    foreach($user_query->results as $user) : 
                ?>
                <?php 
                    $meta = get_user_meta($user->ID);
                    $counter++;
                ?>

				<li>
					<?php echo get_avatar($user->ID, 64);?>

					<dl>
						<dt>Namn</dt>
                        <dd><a href="/author/<?php echo $user->user_login; echo "\">"; user_fullname($user);?></a></dd>

                        <!--<dt>E-post</dt>
						<dd><a href="mailto:<?php // echo $user->user_email;?>"><?php //echo $user->user_email;?></a></dd>-->

						<?php if($meta['it_year'][0]) : ?>
						<dt>Antagningsår</dt>
						<dd><?php echo $meta['it_year'][0];?></dd>
						<?php endif;?>

						<?php if($meta['it_phone'][0]) : ?>
						<dt>Telefon</dt>
						<dd><?php echo $meta['it_phone'][0];?></dd>
						<?php endif;?>
					</dl>
				</li>
            <?php if($counter > 20 ) : ?>
			      <p class="no-content">Hittade för många användare. Vänligen förfina din söksträng</p>
            <?php break; endif; ?>
			<?php endforeach; ?>

			<?php else : ?>

			<p class="no-content">Inga personer hittades</p>

			<?php endif;?>
			</ul>
		</section>

	</div>
</section>

<?php get_footer();?>
