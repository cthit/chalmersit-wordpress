<?php get_header("404"); ?>

<section role="main">
	<hgroup class="page-title">
		<h1>Sidan kunde inte hittas</h1>
		<h2>404-fel</h2>
	</hgroup>

	<div class="article-content">
		<p>
			<strong>Vi hittade inte sidan du kanske sökte.</strong> Det kan bero på
		</p>

		<ul>
			<li>att du följde en bruten länk</li>
			<li>att du skrev in fel adress i adressfältet</li>
			<li>att vi har klantat oss med något</li>
		</ul>

		<p>När du dubbelkollat adressen, och det ändå inte fungerar, får du jättegärna kontakta oss om felet:
			<a href="mailto:digit@chalmers.it">digit@chalmers.it</a>. Du kan även prova att söka efter innehållet
			nedan.</p>

		<?php get_template_part("searchform");?>

	</div>

</div>

<? get_footer();?>