<?php

	/* Comment template */

?>



	

	
<?php if ( post_password_required() ) : ?>
	<p class="no-content"><?php _e('Denna artikel kräver ett lösenord för att kommentera.'); ?></p>
<?php
		return;
	endif;
?>


<?php if ( comments_open() ) : ?>
	<header>
		<h2><?php comments_number("Inga kommentarer", 'En kommentar', '% kommentarer');?>
			<?php if ( get_option('comment_registration') && $user_ID ) :?>
			– 
			<a class="comment-action" href="#respond">Lämna din egen</a>
			<?php endif;?>
		</h2>
	</header>
	
	<?php if(have_comments()):?>
	<ol class="commentlist">
		<?php
			wp_list_comments( array( 'callback' => 'chalmers_comment' ) );
		?>
	</ol>
	<?php endif;?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Paging ?>
	<nav class="comment-nav">
		<ul>
			<li class="comment-previous"><?php previous_comments_link( __( 'Äldre kommentarer') ); ?></li>
			<li class="comment-next"><?php next_comments_link( __( 'Nyare kommentarer') ); ?></li>
		</ul>
	</nav>
	<?php endif; ?>

<?php
	/* If comments are closed */
	elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
	<p class="comments-closed-msg">Kommentarer är stängda</p>
<?php endif; ?>


<?php partial("comment-form");?>

