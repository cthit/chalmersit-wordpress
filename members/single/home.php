<?php

get_header(); ?>

<section class="six columns box">
	<header>
		<?php bp_displayed_user_avatar("type=full");?>


		<hgroup>
			<h1><?php echo xprofile_get_field_data("Nick"); ?></h1>
			<h2 class="sub"><?php bp_displayed_user_fullname();?></h2>
		</hgroup>
	</header>

	<?php
		if ( bp_is_user_groups() ) :
			locate_template( array( 'members/single/groups.php'    ), true );

		elseif ( bp_is_user_messages() ) :
			locate_template( array( 'members/single/messages.php'  ), true );

		elseif ( bp_is_user_profile() ) :
			locate_template( array( 'members/single/profile.php'   ), true );

		elseif ( bp_is_user_settings() ) :
			locate_template( array( 'members/single/settings.php'  ), true );

		// If nothing sticks, load a generic template
		else :
			locate_template( array( 'members/single/plugins.php'   ), true );

		endif;

		?>
</section>

<aside role="complementary three columns">	
	<?php get_sidebar(); ?>
</aside>

<?php get_footer(); ?>
