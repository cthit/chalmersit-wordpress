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
				<dd>styrit@chalmers.it</dd>
				
				<dt>Postadress</dt>
				<dd>Teknologsektionen Informationsteknik<br />
				Teknologgården 2<br />
				412 58 Göteborg</dd>
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

<!-- Created by digIT 12/13. Designed by Johan on a Mac -->

</body>
</html>