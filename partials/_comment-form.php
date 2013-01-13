<?php
	global $current_user, $comment;
	get_currentuserinfo();
	$req = get_option( 'require_name_email' );
	$commenter = wp_get_current_commenter();
?>



<?php if ('open' == $post->comment_status) : ?>

<section id="respond">

	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p class="comments-closed-msg">
			Du måste vara inloggad för att skriva kommentarer. 
			<a class="read-more" href="<?php echo wp_login_url();?>">Logga in</a>
		</p>

	<?php else : ?>
		
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" 
			method="post" id="commentform">

		<?php if(!$user_ID) : ?>
		<p>
			<label for="author"><?php _e("Ditt namn");?></label>
			<input class="text" type="text" name="author" id="author" required aria-required="true" value="<?php echo esc_attr($commenter['comment_author']); ?>" />
		</p>

		<p>
			<label for="email"><?php _e("Din e-postadress <small class='light'>(publiceras ej)</small>");?></label>
			<input type="email" class="text" required aria-required="true" id="email" name="email" value="<?php echo esc_attr($commenter['comment_author_email']);?>" />

		</p>

		<?php endif; ?>

		<div class="comment-area">	
			<textarea name="comment" class="autosize" placeholder="Skriv en kommentar" id="comment"></textarea>

			<p class="comment-submit">
				<span 
					class="commenter" 
					rel="tooltip" 
					title="Du är inloggad som <?php user_fullname(wp_get_current_user()); ?>">
					
					<?php echo get_avatar(wp_get_current_user()->ID, 32);?>
					<?php user_fullname(wp_get_current_user());?>
				</span>
				<input name="submit" class="accent" formnovalidate type="submit" id="submit" 
					value="Skicka kommentar" />
			</p>
		</div>

		<p class="cancel-reply">
		<?php 	
			$style = isset($_GET['replytocom']) ? '' : ' style="display:none;"';
			$link = esc_html( remove_query_arg('replytocom') ) . '#respond';
			$text = "Avbryt svar";
			
			echo apply_filters('cancel_comment_reply_link', '<a rel="nofollow" id="cancel-comment-reply-link" href="' . $link . '"' . $style . '>'. $text .'</a>', $link, $text);
		?>
		</p>
		
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>

		</form>

	<?php endif; // If registration required and not logged in ?>
</section>

<?php endif; // if you delete this the sky will fall on your head ?>
