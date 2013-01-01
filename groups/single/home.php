<?php 
	
	get_header(); 
?>


<section class="main-col six columns push-three">
	<div class="box">
		<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<article>
			<hgroup class="page-title">
				<h1><?php bp_group_name();?></h1>
				<h2>Type</h2>
			</hgroup>

			<?php 

			if ( bp_is_group_admin_page() && bp_group_is_visible() ) :
				locate_template( array( 'groups/single/admin.php' ), true );

			elseif ( bp_is_group_members() && bp_group_is_visible() ) :
				locate_template( array( 'groups/single/members.php' ), true );

			elseif ( bp_is_group_invites() && bp_group_is_visible() ) :
				locate_template( array( 'groups/single/send-invites.php' ), true );

				elseif ( bp_is_group_forum() && bp_group_is_visible() && bp_is_active( 'forums' ) && bp_forums_is_installed_correctly() ) :
					locate_template( array( 'groups/single/forum.php' ), true );

			elseif ( bp_is_group_membership_request() ) :
				locate_template( array( 'groups/single/request-membership.php' ), true );

			elseif ( bp_group_is_visible() && bp_is_active( 'activity' ) ) :
				locate_template( array( 'groups/single/activity.php' ), true );

			elseif ( bp_group_is_visible() ) :
				locate_template( array( 'groups/single/front.php' ), true );

			elseif ( !bp_group_is_visible() ) :
				// The group is not visible, show the status message

				do_action( 'bp_before_group_status_message' ); ?>

				<div class="message-notice">
					<p><?php bp_group_status_message(); ?></p>
				</div>

				<?php do_action( 'bp_after_group_status_message' );

			else :
				// If nothing sticks, just load a group front template if one exists.
				locate_template( array( 'groups/single/front.php' ), true );
			endif; 
			?>

		</article>

		<?php endwhile; endif; ?>
	</div>
</section>

<div class="sidebar-group box three columns push-three">
	<?php get_template_part("groups/sidebar-group");?>
</div>

<aside class="three columns pull-nine">
	<?php get_sidebar("nav"); ?>	
</aside>

<?php get_footer(); ?>
