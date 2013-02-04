	
	<?php if(is_active_sidebar("footer")) : ?>
	<section class="footer-widgets">
		<ul>
		<?php dynamic_sidebar("footer"); ?>
		</ul>
	</section>
	<?php endif;?>

</section> <!-- role main end -->

<footer role="contentinfo">
	<div class="wrapper group">
		<section class="four columns">
			<hgroup>
				<h1>
					Informationsteknik
				</h1>
				<h2>
					Chalmers Tekniska Högskola
				</h2>
			</hgroup>
			
			<nav>
				<?php wp_nav_menu(array(
					"theme_location" => "footer_navigation",
					"container_class" => "",
					"menu_class" => "",
					"container" => false
				)); ?>
			</nav>
		</section>
		
		<section class="four columns">
			<dl>
				<dt>Kontakt</dt>
				<dd><?php it_option("main_contact_email");?></dd>
				
				<dt>Postadress</dt>
				<dd><?php it_option("contact_official_name");?><br />
				<?php it_option("contact_address");?><br />
				<?php it_option("postal_code");?> <?php it_option("locality");?></dd>
			</dl>
		</section>
		
		<section class="four columns">
			<?php
				$walker = new Comittee_Walker;
			?>

			<dl>
			<?php wp_nav_menu(array(
				"theme_location" => "comittees",
				"container_class" => "",
				"menu_class" => "",
				"container" => false,
				"items_wrap" => '%3$s',
				"walker" => $walker
			)); ?>
			</dl>
		</section>
	</div>
</footer>

<?php wp_footer(); ?>

<script src="<?php javascript_path("jquery.smoothscroll");?>"></script>
<script src="<?php javascript_path("jquery.autosize");?>"></script>
<script src="<?php javascript_path("jquery.tipsy");?>"></script>
<script src="<?php javascript_path("chalmersit");?>"></script>

<?php if(is_home()):?>
<script type="text/javascript">
	$(function() {
		// "Load more posts"
		$.loadMorePosts(".news");
	});
</script>
<?php endif;?>

<!-- Created by digIT 12/13. Designed by Johan on a Mac -->

</body>
</html>