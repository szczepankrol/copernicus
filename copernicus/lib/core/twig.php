<?php 

function menu($id) {
	global $cp;
	
	print_r($cp->menus);
	wp_page_menu($cp->menus[$id]);
}

?>