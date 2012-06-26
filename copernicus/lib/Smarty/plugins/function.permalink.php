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
function smarty_function_permalink($params, $template) {
    
	if (isset ($params['id']))
		return get_permalink($params['id']);
	
	return get_permalink();
    
}

?>