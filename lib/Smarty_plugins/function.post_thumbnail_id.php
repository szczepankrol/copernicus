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
function smarty_function_post_thumbnail_id($params, $template) {
    
	$post_id = get_the_ID();
	
	if ($post_id) {
		$post_thumbnail_id = get_post_thumbnail_id($post_id);
		
		if ($post_thumbnail_id)
			return $post_thumbnail_id;
	}
	
    return null;
    
}