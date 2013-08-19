<?php
/**
 * Smarty plugin
 *
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
function smarty_function_the_content( $params, $template ) {
	
	// default params
	$default_params = array(
		'more_link_text' => null,
		'stripteaser' => false
	);
    
    // merge default params with the provided ones
	$params = array_merge($default_params, $params);
	
	$content = get_the_content($params['more_link_text'], $params['stripteaser']);
	return apply_filters('the_content', $content);
}
