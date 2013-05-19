<?php 

/*- CUSTOM COMMENT TEMPLATE
-------------------------------------------------*/

function chalmers_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="comment pingback">
		<article class="group">
 			<footer>
 				<h3><?php _e("Inkommande länk");?></h3>
 			</footer>
 		
 			<div class="comment-text">
 				<?php comment_author_link(); ?>
 			</div>
		</article>
	</li>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<article class="row">
			<header class="row vcard">
				<figure class="two columns">	
					<?php 
						$avatar_size = 64;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 48;

						echo get_avatar( $comment, $avatar_size );?>
				</figure>
						
				<div class="ten columns">
					<h3 class="fn"><?php comment_author();?></h3>
					<p class="comment-meta"><?php printf( '<time title="%2$s" datetime="%1$s" pubdate>%3$s</time>',
						get_comment_time('c'),
						get_comment_time('j F Y, H:i'),
						sprintf('%s ago', human_time_diff(strtotime($comment->comment_date_gmt))));?>
						
						<?php comment_reply_link(array_merge( $args, array( 
								'reply_text' => get_sep().' Skriv ett svar', 
								'depth' => $depth, 
								'max_depth' => $args['max_depth'] 
								)),
								get_comment_ID(),
								$post->ID
							);?>
					</p>
				</div>

			</header>

			<div class="comment-text ten columns push-two">
				<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="no-content">
					Första gången du kommenterar måste din kommentar godkännas först
				</p>
				<?php endif; ?>
				
				<?php echo strip_tags(get_comment_text(), "<p><br><strong><em>"); ?>
			</div>
			
		</article>

	<?php
			break;
	endswitch;
}
