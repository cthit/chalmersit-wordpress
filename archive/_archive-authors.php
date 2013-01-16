<?php
	/*
		List authors
	*/

	$authors = get_users(array(
		"orderby" => "post_count"
	));
?>

<header>
	<h3>Författare</h3>
</header>

<?php if($authors) : ?>
	<ol class="list authors-list">
		<?php foreach($authors as $author) : ?>
		<?php $numposts = count_user_posts($author->ID);?>

		<?php if($numposts < 1) continue; ?>
		<li>	
			<?php echo get_avatar($author->ID, 48);?>
			<?php echo user_fullname($author);?>

			<a href="<?php echo get_author_posts_url($author->ID);?>" 
				rel="tooltip" data-tooltip-gravity="w" data-tooltip-offset="10"
				title="Se nyheter postade av <?php echo $author->nickname;?>">
				<?php echo $numposts;?> nyheter
			</a>
		</li>
		
		<?php endforeach;?>
	</ol>

<?php else :?>

<p class="no-content">Inga författare</p>

<?php endif;?>

