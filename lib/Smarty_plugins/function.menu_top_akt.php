<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * WP get_the_content function
 *
 * Type:     function
 * Name:     get_the_content
 * Purpose:  print out a bloginfo information
 *
 */
function smarty_function_menu_top_akt($params, $template) {
	
   $url = $_SERVER['HTTP_HOST'];
   $sub = $params['akt'];

$parsedUrl = parse_url($url);
$host = explode('.', $parsedUrl['path']);

$subdomain = $host[0];
//new dBug($url);
$class="";
	if($subdomain) {
		if ($sub=="uczelnia") {

			if($url=="chat.wiredot.com") {$class.="class=\"active\"";}
		}
		if ($sub=="teologia") {

			if($subdomain=="teologia") {$class.="class=\"active\"";}
		}
		if ($sub=="pedagogika") {

			if($subdomain=="pedagogika") {$class.="class=\"active\"";}
		}
		if ($sub=="biblioteka") {

			if($subdomain=="biblioteka") {$class.="class=\"active\"";}
		}
		if ($sub=="wydawnictwo") {

			if($subdomain=="wydawnictwo") {$class.="class=\"active\"";}
		}
	}
	else {

			$class="class=\"active\"";
	}

    return $class;
}

?>