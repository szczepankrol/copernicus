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
function smarty_function_form_send($form, $template) {
    
	if (!isset ($form['id']))
		return null;
	
	global $CP_Image;
	
	new dBug ($form);
	
	//return $CP_Image->image($params);
	
    
}

?>