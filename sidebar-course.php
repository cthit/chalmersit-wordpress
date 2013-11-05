<?php
	$years = get_terms("course_year");
	$periods = get_terms("course_period");
?>

<nav class="box side-nav">

	<form action="" method="get" id="courses-form">
		<h2 class="form-title">Filter</h2>
		
		<img src="<?php img_url("loading.gif");?>" class="loading hidden" alt="Laddar" />

		<?php if($periods) : ?>
		<fieldset id="periods-field">
			<ul class="list">
				<li>
					<label><input value="-1" name="course_period" type="radio" checked /> Alla perioder</label>
				</li>
				<?php foreach($periods as $period) : ?>
				<li>
					<label><input 
							value="<?php echo $period->slug;?>"
							name="course_period" 
							type="radio" /> <?php echo $period->name;?></label>
				</li>
				<?php endforeach;?>
			</ul>
		</fieldset>

		<?php else : ?>

		<p class="no-content">Inga läsperioder inlagda</p>

		<?php endif;?>


		<?php if($years) : ?>

		<fieldset id="years-field">
			<ul class="list">
				<li>
					<label><input value="-1" name="course_year" type="radio" checked /> Alla årskurser</label>
				</li>
				<?php foreach($years as $year) : ?>
				<li>
					<label><input 
							value="<?php echo $year->slug;?>"
							name="course_year" 
							type="radio" /> <?php echo $year->name;?></label>
				</li>
				<?php endforeach;?>
			</ul>
		</fieldset>

		<?php else : ?>

		<p class="no-content">Inga årskurser inlagda</p>

		<?php endif;?>

		<button class="small" disabled id="save-config">Spara val</button>
		<button class="btn-boring small" disabled id="clear-config">Glöm bort val</button>

	</form>
</nav>