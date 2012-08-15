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
function smarty_function_the_title($params, $template) {
	
	if ($params['parent']) {
		global $post;
		if ($post->post_parent) {
			$parent_post = get_post($post->post_parent); 
			$title = $parent_post->post_title;
			return $title;
		}	
	}
	
    $title = get_the_title();
	
    return $title;
}

?>