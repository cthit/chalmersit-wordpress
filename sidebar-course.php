<?php
	$years = get_terms("course_year");
	$periods = get_terms("course_period");
?>

<nav class="box side-nav">

	<h2>Filter</h2>

	<form action="" method="get" id="courses-form">
		<fieldset id="periods-field">
			<ul class="list">
				<li>
					<label><input value="-1" name="course_period" type="radio" checked /> Alla perioder</label>
				</li>
				<?php foreach($periods as $period) : ?>
				<li>
					<label><input 
							value="<?php echo $period->term_id;?>" 
							name="course_period" 
							type="radio" /> <?php echo $period->name;?></label>
				</li>
				<?php endforeach;?>
			</ul>
		</fieldset>

		<fieldset id="years-field">
			<ul class="list">
				<li>
					<label><input value="-1" name="course_year" type="radio" checked /> Alla årskurser</label>
				</li>
				<?php foreach($years as $year) : ?>
				<li>
					<label><input 
							value="<?php echo $year->term_id;?>" 
							name="course_year" 
							type="radio" /> <?php echo $year->name;?></label>
				</li>
				<?php endforeach;?>
			</ul>
		</fieldset>

	</form>
</nav>