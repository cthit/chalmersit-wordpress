<!doctype html>
<html>
<head>
	<title><?php wp_title("|", true, "right"); bloginfo("name");?></title>
	<meta charset="utf-8" />
	<!-- Load up the Ubuntu fonts -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,400italic" type="text/css">
	
	<!-- Main stylesheet -->
	<link rel="stylesheet" href="<?php bloginfo("stylesheet_url");?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>