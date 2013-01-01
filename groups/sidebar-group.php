<?php
	//TODO
	$external = "http://google.com";
?>

<aside role="complementary">
	<section class="node">
		<?php if($external):?>
		<a href="<?php echo $external;?>" class="btn-alt wide">
			Till <?php echo str_replace(array("http://", "/"), array(""), $external);?>
		</a>
		<?php endif;?>
	</section>

</aside>