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
	
	
	return dynamic_sidebar($params['id']);
    
}