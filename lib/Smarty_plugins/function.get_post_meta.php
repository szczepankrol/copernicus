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
	
	// default params
	$default_params = array(
		'id' => get_the_ID(),
		'key' => null,
	);
    
    // merge default params with the provided ones
	$params = array_merge($default_params, $params);

    return get_post_meta($params['id'], $params['key'], true);
}
