<?php
	global $current_user;
	get_currentuserinfo();
?>
<!doctype html>
<!--

       _____  _             _                                 _  _   
      / ____|| |           | |                               (_)| |  
     | |     | |__    __ _ | | _ __ ___    ___  _ __  ___     _ | |_ 
     | |     | '_ \  / _` || || '_ ` _ \  / _ \| '__|/ __|   | || __|
     | |____ | | | || (_| || || | | | | ||  __/| |   \__ \ _ | || |_ 
      \_____||_| |_| \__,_||_||_| |_| |_| \___||_|   |___/(_)|_| \__|

      								by                

						     _ _       _____ _______
						    | (_)     |_   _|__   __|
						  __| |_  __ _  | |    | |   
						 / _` | |/ _` | | |    | |   
						| (_| | | (_| |_| |_   | |   
						 \__,_|_|\__, |_____|  |_|   
						          __/ |              
						         |___/
						         

Information Technology at Chalmers University of Technology

# Questions, bug reports or suggestions: 

* digit@chalmers.it
* #digit@irc.chalmers.it

See more in /humans.txt

-->
<html>
<head>
	<title><?= it_wp_title() ?></title>
	<meta charset="utf-8" />
	
	<!-- Load up the Ubuntu fonts -->
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,400italic" type="text/css">

	<!-- HTML5 shim -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php javascript_path("html5"); ?>"></script>
	<![endif]-->
	
	<!-- Main stylesheet -->
	<link rel="stylesheet" href="<?php bloginfo("stylesheet_url");?>" />
	<link rel="stylesheet" href="<?php css_path("jquery-ui-1.10.3.custom.min");?>" />

	<!-- Favicons -->
	<link rel="icon" type="image/png" href="<?php img_url("favicon.png");?>" />
	<link rel="apple-touch-icon-precomposed" href="<?php img_url("apple-touch-icon.png");?>" />

	<!-- iOS Homescreen specials -->
	<meta name="apple-mobile-web-app-title" content="Chalmers.it">
	
	<!-- Viewport setting -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<!-- Humanstxt.org -->
	<link type="text/plain" rel="author" href="/humans.txt" />
	
	<?php if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ):?>
	<?php wp_enqueue_script( 'comment-reply' ); ?>
	<?php endif;?>

	<?php wp_head(); ?>
</head>
