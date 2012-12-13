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
function smarty_function_bloginfo($params, $template) {
    
	if (!isset ($params['show']))
		$params['show'] = '';
	
	if (!isset ($params['filter']))
		$params['filter'] = '';
	
    $retval = bloginfo($params['show'], $params['filter']);
	
    return $retval;
    
}

?>