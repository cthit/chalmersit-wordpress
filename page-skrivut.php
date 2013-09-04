<?php
	require_once "lib/inc.print.php";

	global $errors, $notice;

	get_header();
	the_post();
?>

<section class="main-col six columns push-three">
	<article class="box">
		<h1 class="huge"><?php the_title();?></h1>

		<form name="print_form" id="print-form" enctype="multipart/form-data" action="" method="post">
			<input type="hidden" name="print" />
			<?php if($errors) : ?>
			<div class="message-warning">
				<ul>
					<?php foreach($errors as $err) : ?>
					<li><?php echo $err;?></li>
					<?php endforeach;?>
				</ul>
			</div>
			<?php endif;?>

			<?php if($notice) : ?>
			<div class="message-positive">
				<p><?php echo $notice;?></p>
			</div>
			<?php endif;?>

			<p>
				<label for="upload">Fil</label>
				<input type="file" name="upload" id="upload" />
				<small>(godkända format är bilder, ren text och PDF)</small>
			</p>

			<p>
				<label for="printer">Skrivare</label>
				<select name="printer" id="printer">
					<option value="a-2234-color2">a-2234-color2</option>
					<option value="a-2234-laser1">a-2234-laser1</option>
					<option value="a-2234-plot1">a-2234-plot1</option>
					<option value="a-2234-plot2">a-2234-plot2</option>
					<option value="ed-2338-laser1" selected alt="">ed-2338-laser1 Studion</option>
					<option value="ed-3349a-laser1">ed-3349a-laser1</option>
					<option value="ed-3507-laser1">ed-3507-laser1</option>
					<option value="ed-5229-laser1">ed-5229-laser1</option>
					<option value="ed-5232-color1">ed-5232-color1</option>
					<option value="m-0158a-laser1">m-0158a-laser1</option>
					<option value="m-0164a-color1">m-0164a-color1</option>
					<option value="nc-2504-color1">nc-2504-color1</option>
					<option value="ituniv-pa-324-color1">ituniv-pa-324-color1 Lindholmen Ituniversitetet</option>
					<option value="ituniv-pa-338b-laser1">ituniv-pa-338b-laser1 Lindholmen Ituniversetetet </option>
				</select>
			</p>

			<p>
				<label for="copies">Antal kopior</label>
				<input type="number" min="1" name="copies" id="copies" value="1" size="2" />
			</p>

			<p>
				<label>
					<input type="checkbox" name="oneSided" id="oneSided" value="1" checked />
					Dubbelsidigt
				</label>
			</p>

			<p>
				<label for="ranges">Intervall (ex. "3,6,8,11-54", lämna tom för att skriva ut alla sidor)</label>
				<input type="text" name="ranges" size="10" id="ranges" />
			</p>

			<hr />

			<section class="cid-area">
				<p>
					<label for="cid-name">CID</label>
					<input type="text" id="cid-name" name="user" autocomplete="off" />
				</p>

				<p>
					<label for="cid-pw">CID-lösenord</label>
					<input type="password" id="cid-pw" name="pass" autocomplete="off" />
				</p>

				<p class="description center">
					Du måste skriva in ditt CID-namn och lösenord för att skriva ut genom
					Chalmers skrivare. De utskrivna sidorna kommer att dras från din printerkvota.
				</p>

				<p class="center">
					<input type="submit" id="print-document-submit" value="Skriv ut" class="large" />
				</p>
			</section>

		</form>
	</article>
</section>

<?php get_footer();?>