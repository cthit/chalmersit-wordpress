<?php
/**
 * Groups loop
 */
?>


<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<ul class="listing">

	<?php while ( bp_groups() ) : bp_the_group(); ?>
		<li>
			<figure class="media-image">
				<a class="block" href="<?php bp_group_permalink(); ?>">
					<?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?>
				</a>
			</figure>

			<div class="media-content">
				<h2 class="media-headline"><?php bp_group_name();?></h2>

				<?php bp_group_description_excerpt(); ?>
				<p class="alignright">
					<a class="btn" href="<?php bp_group_permalink();?>">Läs mer</a>
				</p>
			</div>
		</li>

	<?php endwhile; ?>

	</ul>

<?php else: ?>

	<div class="message-notice">
		<p>Inga kommittéer är inlagda</p>
	</div>

<?php endif; ?>
