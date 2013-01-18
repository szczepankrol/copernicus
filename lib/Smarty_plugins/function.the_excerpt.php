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
function smarty_function_the_excerpt($params, $template) {

	global $more;
	$more = 0;

	$the_id = get_the_ID();

	$post = get_post_meta($the_id, $params['key'], 1);
	
	if (!$post) {
		return null;
	}
	
	if (isset($params['lenght'])) {
		$post = strip_tags($post);
		return substr($post, 0, strpos($post, ' ', $params['lenght']));
	}

	return $post;
}

?>