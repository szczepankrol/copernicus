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
function smarty_function_the_content($params, $template) {

	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	
    return $content;
}

?>