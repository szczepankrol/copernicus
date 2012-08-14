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
function smarty_function_post_meta($params, $template) {
	
    $the_id = get_the_ID();
	
    return get_post_meta($the_id, $params['key'], 1);
}

?>