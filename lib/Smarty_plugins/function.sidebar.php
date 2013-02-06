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

function smarty_function_sidebar($params, $template) {
    
	if (!isset ($params['id']))
		return null;
	
	ob_start();
	
	dynamic_sidebar($params['id']);
	
	$sidebar = ob_get_clean();
	
	return $sidebar;
    
}