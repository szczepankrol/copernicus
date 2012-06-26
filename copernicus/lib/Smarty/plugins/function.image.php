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
function smarty_function_image($params, $template) {
    
	if (!isset ($params['id']))
		return null;
	
	global $CP_Image;
	
	return $CP_Image->image($params);
	
    
}

?>