<?php
	partial("head");

	the_post();
	$meta = wp_get_attachment_metadata($post->ID);
	$filename = end(explode("/", $meta['file']));
?>

<body <?php body_class();?>

	<section role="main">
		<section class="attachment-info two columns">
			<h2>Bifogad fil</h2>
			
			<?php if($post->post_parent) : ?>
			<p>
				<a class="btn back small" href="<?php echo get_permalink($post->post_parent);?>">Tillbaka</a>
			</p>
			<?php endif;?>

			<dl class="attachment-meta">
				<dt>Direktlänk</dt>
				<dd><a href="<?php echo wp_get_attachment_url();?>"><?php echo ($filename) ? $filename : "Länk";?></a></dd>

				<dt>Uppladdad av</dt>
				<dd><?php the_author();?></dd>

				<?php if($meta) : ?>
				<dt>Bredd × höjd</dt>
				<dd><?php echo $meta['width'] . "×" . $meta['height'];?> px</dd>

				<dt>Storlek</dt>
				<dd><?php echo format_size(filesize(UPLOAD_PATH . $meta['file']));?></dd>

				<dt>Filnamn</dt>
				<dd><code><?php echo $filename;?></code></dd>
				<?php endif;?>

				<dt>Filtyp</dt>
				<dd><?php echo $post->post_mime_type;?></dd>
			</dl>	
		</section>

		<div class="attachment-area eight columns">

			<figure>
				<?php if($post->post_mime_type == "application/pdf") : ?>

				<iframe seamless src="<?php echo wp_get_attachment_url();?>"></iframe>

				<?php else : ?>
				
				<img src="<?php echo wp_get_attachment_url();?>" alt="<?php the_title();?>" />
				
				<?php endif;?>
			</figure>

		</div>

<?php get_footer();?>
