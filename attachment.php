<?php
	partial("head");

	the_post();
	$meta = wp_get_attachment_metadata();
	$filename = end(explode("/", $meta['file']));
?>

<body <?php body_class();?>

	<section role="main">
		<section class="attachment-info two">
			<h2>Bifogad fil</h2>
			
			<?php if($post->post_parent) : ?>
			<p>
				<a class="btn back small" href="<?php echo get_permalink($post->post_parent);?>">Till inlägget</a>
			</p>
			<?php endif;?>

			<dl class="attachment-meta">
				<dt>Direktlänk</dt>
				<dd><a href="<?php echo wp_get_attachment_url();?>"><?php echo $filename;?></a></dd>

				<dt>Uppladdad av</dt>
				<dd><?php the_author();?></dd>

				<dt>Bredd × höjd</dt>
				<dd><?php echo $meta['width'] . "×" . $meta['height'];?> px</dd>

				<dt>Storlek</dt>
				<dd><?php echo format_size(filesize(UPLOAD_PATH . $meta['file']));?></dd>

				<dt>Filnamn</dt>
				<dd><code><?php echo $filename;?></code></dd>

				<dt>Filtyp</dt>
				<dd><?php echo $post->post_mime_type;?></dd>
			</dl>	
		</section>

		<div class="attachment-area eight">

			<figure>
				<img src="<?php echo wp_get_attachment_url();?>" alt="<?php the_title();?>" />

		</div>

<?php get_footer();?>
