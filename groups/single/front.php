<div class="article-content">
	<?php bp_group_description();?>
</div>

<?php if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) : ?>

	<ul class="listing">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>
			<?php global $members_template;?>

			<li>
				<a href="<?php bp_group_member_domain(); ?>">

					<div class="media-image">
					<?php echo bp_core_fetch_avatar( array( 
						'item_id' => $members_template->member->user_id, 
						'type' => 'full',
						'width' => 64,
						'height' => 64
					)); ?>
					</div>
				</a>

				<h3><?php bp_group_member_link(); ?></h3>
			</li>

		<?php endwhile; ?>

	</ul>

<?php else: ?>

	<div class="message-notice">
		<p>Denna kommittée har inga medlemmar</p>
	</div>

<?php endif; ?>
