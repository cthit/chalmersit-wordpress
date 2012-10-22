<?php

function register_chalmers_menus() {
	register_nav_menu("main_navigation", "Huvudnavigation");
	register_nav_menu("footer_navigation", "Sidfotsnavigation");
	register_nav_menu("about", "Om sektionen");
	register_nav_menu("comittees", "Kommittéer");
}

?>