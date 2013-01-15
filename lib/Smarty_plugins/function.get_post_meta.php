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
function smarty_function_get_post_meta($params, $template) {
	
	$post_id = get_the_ID();
	
	$key = $params['key'];
	
    $post_meta = get_post_meta($post_id, $key, true);
	
    return $post_meta;
}

?>