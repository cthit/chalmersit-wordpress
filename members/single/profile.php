<?php
	$profile_link = trailingslashit(bp_loggedin_user_domain() . "profile");
?>

<?php if ( bp_is_my_profile() && !bp_is_current_action("edit") && !bp_is_current_action("change-avatar")) : ?>

	<nav>
		<ul class="list">

			<li><a href="<?php echo trailingslashit($profile_link . "edit");?>">Redigera profil</a></li>

		</ul>
	</nav>

<?php endif; ?>


<?php
	// Profile Edit
	if ( bp_is_current_action( 'edit' ) )
		locate_template( array( 'members/single/profile/edit.php' ), true );

	// Change Avatar
	elseif ( bp_is_current_action( 'change-avatar' ) )
		locate_template( array( 'members/single/profile/change-avatar.php' ), true );

	// Display XProfile
	elseif ( bp_is_active( 'xprofile' ) )
		locate_template( array( 'members/single/profile/profile-loop.php' ), true );

	// Display WordPress profile (fallback)
	else
		locate_template( array( 'members/single/profile/profile-wp.php' ), true );
?>
