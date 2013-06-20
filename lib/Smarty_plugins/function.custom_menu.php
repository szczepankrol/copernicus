<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * WP bloginfo function
 *
 * Type:     function<br>
 * Name:     bloginfo<br>
 * Purpose:  print out a bloginfo information
 *
 */
function smarty_function_custom_menu($params, $template) {

	$defaults = array(
	'menu'            => $params['name']
	
); 

return wp_nav_menu( $defaults ); 

}

?>